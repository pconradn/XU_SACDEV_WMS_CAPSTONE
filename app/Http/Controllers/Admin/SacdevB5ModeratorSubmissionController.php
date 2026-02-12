<?php

namespace App\Http\Controllers\Admin;

use App\Models\SchoolYear;
use Illuminate\Http\Request;
use App\Support\InAppNotifier;
use App\Models\OrgModeratorTerm;
use Illuminate\Support\Facades\DB;
use App\Models\ModeratorSubmission;
use App\Http\Controllers\Controller;

class SacdevB5ModeratorSubmissionController extends Controller
{
    public function index(Request $request)
    {
        $targetSyId = (int) ($request->input('target_school_year_id') ?? 0);
        $status = (string) ($request->input('status') ?? '');

        $q = ModeratorSubmission::query()
            ->with(['organization', 'targetSchoolYear', 'moderatorUser'])
            ->orderByDesc('updated_at');

        if ($targetSyId > 0) {
            $q->where('target_school_year_id', $targetSyId);
        }

        if ($status !== '') {
            $q->where('status', $status);
        }

        $submissions = $q->paginate(15)->withQueryString();
        $schoolYears = SchoolYear::query()->orderByDesc('id')->get();

        return view('admin.forms.b5_moderator.index', compact('submissions', 'schoolYears', 'targetSyId', 'status'));
    }


    private function moderatorRecipient(ModeratorSubmission $submission)
    {
        
        return $submission->moderatorUser ?? $submission->moderatorUser()->first();
    }

    public function show(ModeratorSubmission $submission)
    {
        $submission->load(['organization', 'targetSchoolYear', 'moderatorUser', 'leaderships', 'term']);

        return view('admin.forms.b5_moderator.show', compact('submission'));
    }

    public function returnToModerator(Request $request, ModeratorSubmission $submission){
        $data = $request->validate([
            'sacdev_remarks' => ['required', 'string', 'min:3', 'max:5000'],
        ]);

        $moderator = $this->moderatorRecipient($submission);
        if (!$moderator) {
            return back()->with('error', 'No moderator user is linked to this submission.');
        }

        DB::transaction(function () use ($submission, $data, $moderator) {
            $locked = ModeratorSubmission::query()
                ->whereKey($submission->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            if ($locked->status === 'approved_by_sacdev') {
                abort(403, 'This form is already approved. Use a revert flow if you want later.');
            }

            $locked->status = 'returned_by_sacdev';
            $locked->returned_at = now();
            $locked->sacdev_reviewed_by_user_id = auth()->id();
            $locked->sacdev_remarks = $data['sacdev_remarks'];
            $locked->sacdev_reviewed_at = now();
            $locked->save();

            $submissionId = (int) $locked->getKey();
            $orgId = (int) $locked->organization_id;
            $syId  = (int) $locked->target_school_year_id;

            DB::afterCommit(function () use ($moderator, $submissionId, $orgId, $syId) {
                $dedupeKey = "b5:moderator_submission:{$submissionId}:returned_by_sacdev:to:{$moderator->getKey()}";

                InAppNotifier::notifyOnce($moderator, [
                    'dedupe_key'   => $dedupeKey,
                    'title'        => 'B5 Moderator Form returned by SACDEV',
                    'message'      => 'Your B5 Moderator form was returned with remarks. Please revise and resubmit.',
                    'org_id'       => $orgId,
                    'target_sy_id' => $syId,
                    'form'         => 'b5_moderator',
                    'status'       => 'returned_by_sacdev',
                    'action_url'   => route('org.moderator.rereg.b5.edit', $submissionId),
                    'meta'         => ['submission_id' => $submissionId],
                ]);
            });
        });

        return back()->with('success', 'Returned to moderator with remarks.');
    }


    public function approve(Request $request, ModeratorSubmission $submission)
    {
        $moderator = $this->moderatorRecipient($submission);
        if (!$moderator) return back()->with('error', 'No moderator user is linked to this submission.');

        DB::transaction(function () use ($submission, $moderator) {
            $locked = ModeratorSubmission::query()
                ->whereKey($submission->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            if ($locked->status !== 'submitted_to_sacdev') {
                abort(403, 'Only submitted forms can be approved.');
            }

            $locked->status = 'approved_by_sacdev';
            $locked->approved_at = now();
            $locked->sacdev_reviewed_by_user_id = auth()->id();
            $locked->sacdev_reviewed_at = now();
            $locked->save();

            // Activate term (keep inside same tx)
            if ($locked->org_moderator_term_id) {
                $term = OrgModeratorTerm::query()
                    ->whereKey($locked->org_moderator_term_id)
                    ->lockForUpdate()
                    ->first();

                if ($term) {
                    $term->status = 'active';
                    $term->activated_at = now();
                    $term->save();
                }
            }

            $submissionId = (int) $locked->getKey();
            $orgId = (int) $locked->organization_id;
            $syId  = (int) $locked->target_school_year_id;

            DB::afterCommit(function () use ($moderator, $submissionId, $orgId, $syId) {
                $dedupeKey = "b5:moderator_submission:{$submissionId}:approved_by_sacdev:to:{$moderator->getKey()}";

                InAppNotifier::notifyOnce($moderator, [
                    'dedupe_key'   => $dedupeKey,
                    'title'        => 'B5 Moderator Form approved by SACDEV',
                    'message'      => 'Your B5 Moderator form has been approved by SACDEV.',
                    'org_id'       => $orgId,
                    'target_sy_id' => $syId,
                    'form'         => 'b5_moderator',
                    'status'       => 'approved_by_sacdev',
                    'action_url'   => route('org.moderator.rereg.b5.edit', $submissionId),
                    'meta'         => ['submission_id' => $submissionId],
                ]);
            });
        });

        return back()->with('success', 'Moderator form approved.');
    }



    public function allowEdit(Request $request, ModeratorSubmission $submission)
    {
        $moderator = $this->moderatorRecipient($submission);
        if (!$moderator) return back()->with('error', 'No moderator user is linked to this submission.');

        DB::transaction(function () use ($request, $submission, $moderator) {

            $locked = ModeratorSubmission::query()
                ->whereKey($submission->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            if (!$locked->edit_requested) {
                abort(403, 'No edit request is pending for this submission.');
            }

            if (!in_array($locked->status, ['submitted_to_sacdev', 'approved_by_sacdev'], true)) {
                abort(403, 'Allow edit is only valid when the form is submitted or approved.');
            }

            $data = $request->validate([
                'sacdev_remarks' => ['nullable', 'string', 'max:5000'],
            ]);

            $locked->status = 'returned_by_sacdev';
            $locked->returned_at = now();
            $locked->approved_at = null;

            $msg = trim((string)($data['sacdev_remarks'] ?? ''));
            if ($msg === '') $msg = 'Edit request granted. Please update the form and resubmit.';

            $locked->sacdev_reviewed_by_user_id = auth()->id();
            $locked->sacdev_remarks = $msg;
            $locked->sacdev_reviewed_at = now();

            $locked->edit_requested = false;
            $locked->edit_requested_at = null;
            $locked->edit_requested_by_user_id = null;
            $locked->edit_request_message = null;

            $locked->save();

            $submissionId = (int) $locked->getKey();
            $orgId = (int) $locked->organization_id;
            $syId  = (int) $locked->target_school_year_id;

            DB::afterCommit(function () use ($moderator, $submissionId, $orgId, $syId) {
                $dedupeKey = "b5:moderator_submission:{$submissionId}:edit_granted:to:{$moderator->getKey()}";

                InAppNotifier::notifyOnce($moderator, [
                    'dedupe_key'   => $dedupeKey,
                    'title'        => 'Edit request granted for B5 Moderator Form',
                    'message'      => 'SACDEV granted your edit request. Please update the form and resubmit.',
                    'org_id'       => $orgId,
                    'target_sy_id' => $syId,
                    'form'         => 'b5_moderator',
                    'status'       => 'edit_granted',
                    'action_url'   => route('org.moderator.rereg.b5.edit', $submissionId),
                    'meta'         => ['submission_id' => $submissionId],
                ]);
            });
        });

        return back()->with('success', 'Edit access granted. Submission returned for revision.');
    }




    public function revertApproval(Request $request, ModeratorSubmission $submission)
    {
        $moderator = $this->moderatorRecipient($submission);
        if (!$moderator) return back()->with('error', 'No moderator user is linked to this submission.');

        $data = $request->validate([
            'sacdev_remarks' => ['required', 'string', 'min:3', 'max:5000'],
        ]);

        DB::transaction(function () use ($submission, $data, $moderator) {

            $locked = ModeratorSubmission::query()
                ->whereKey($submission->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            if ($locked->status !== 'approved_by_sacdev') {
                abort(403, 'Only approved submissions can be reverted.');
            }

            $locked->status = 'returned_by_sacdev';
            $locked->returned_at = now();
            $locked->approved_at = null;

            $locked->sacdev_reviewed_by_user_id = auth()->id();
            $locked->sacdev_remarks = $data['sacdev_remarks'];
            $locked->sacdev_reviewed_at = now();

            $locked->edit_requested = false;
            $locked->edit_requested_at = null;
            $locked->edit_requested_by_user_id = null;
            $locked->edit_request_message = null;

            $locked->save();

            $submissionId = (int) $locked->getKey();
            $orgId = (int) $locked->organization_id;
            $syId  = (int) $locked->target_school_year_id;

            DB::afterCommit(function () use ($moderator, $submissionId, $orgId, $syId) {
                $dedupeKey = "b5:moderator_submission:{$submissionId}:approval_reverted:to:{$moderator->getKey()}";

                InAppNotifier::notifyOnce($moderator, [
                    'dedupe_key'   => $dedupeKey,
                    'title'        => 'B5 Moderator Form approval reverted',
                    'message'      => 'SACDEV reverted the approval and returned the form for revision. Please update and resubmit.',
                    'org_id'       => $orgId,
                    'target_sy_id' => $syId,
                    'form'         => 'b5_moderator',
                    'status'       => 'approval_reverted',
                    'action_url'   => route('org.moderator.rereg.b5.edit', $submissionId),
                    'meta'         => ['submission_id' => $submissionId],
                ]);
            });
        });

        return back()->with('success', 'Approval reverted and returned for revision.');
    }

   



}
