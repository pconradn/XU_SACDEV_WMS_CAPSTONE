<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OfficerSubmission;
use App\Models\SchoolYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\OrgMembership;
use App\Models\User;
use App\Support\InAppNotifier;
use Illuminate\Support\Facades\DB;


class SacdevB3OfficerSubmissionController extends Controller
{

    private function presidentForSy(int $orgId, int $targetSyId): ?User
    {
        $membership = OrgMembership::query()
            ->with('user')
            ->where('organization_id', $orgId)
            ->where('school_year_id', $targetSyId)
            ->where('role', 'president')
            ->first();

        return $membership?->user;
    }

    public function index(Request $request)
    {
        $targetSyId = (int) ($request->input('target_school_year_id') ?? 0);
        $status = $request->input('status');

        $q = OfficerSubmission::query()
            ->with(['organization', 'targetSchoolYear'])
            ->orderByDesc('submitted_at')
            ->orderByDesc('updated_at');

        if ($targetSyId > 0) {
            $q->where('target_school_year_id', $targetSyId);
        }

        if ($status) {
            $q->where('status', $status);
        } else {
            // Default view: show relevant pipeline statuses
            $q->whereIn('status', ['submitted_to_sacdev', 'returned_by_sacdev', 'approved_by_sacdev']);
        }

        $submissions = $q->paginate(15)->withQueryString();
        $schoolYears = SchoolYear::query()->orderByDesc('id')->get();

        return view('admin.forms.b3_officers.index', compact('submissions', 'schoolYears', 'targetSyId', 'status'));
    }

    public function show(OfficerSubmission $submission)
    {
        $submission->load(['organization', 'targetSchoolYear', 'items']);

        return view('admin.forms.b3_officers.show', compact('submission'));
    }

    public function returnToOrg(Request $request, OfficerSubmission $submission)
    {
        $data = $request->validate([
            'sacdev_remarks' => ['required', 'string', 'min:3'],
        ]);

        $orgId = (int) $submission->organization_id;
        $syId  = (int) $submission->target_school_year_id;

        $president = $this->presidentForSy($orgId, $syId);

        DB::transaction(function () use ($submission, $data, $president, $orgId, $syId) {

            $locked = OfficerSubmission::query()
                ->whereKey($submission->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            if ($locked->status !== 'submitted_to_sacdev') {
                abort(403, 'Only submitted forms can be returned.');
            }

            $locked->status = 'returned_by_sacdev';
            $locked->returned_at = now();
            $locked->sacdev_reviewed_by_user_id = Auth::id();
            $locked->sacdev_remarks = $data['sacdev_remarks'];
            $locked->sacdev_reviewed_at = now();
            $locked->save();

            $submissionId = (int) $locked->getKey();

            DB::afterCommit(function () use ($president, $orgId, $syId, $submissionId) {
                if (!$president) return;

                $dedupeKey = "b3:officer_submission:{$submissionId}:returned_by_sacdev:to:{$president->getKey()}";

                InAppNotifier::notifyOnce($president, [
                    'dedupe_key'   => $dedupeKey,
                    'title'        => 'B3 Officers List returned by SACDEV',
                    'message'      => 'SACDEV returned your Officers List with remarks. Please revise and resubmit.',
                    'org_id'       => $orgId,
                    'target_sy_id' => $syId,
                    'form'         => 'b3_officers_list',
                    'status'       => 'returned_by_sacdev',
                    'action_url'   => route('org.rereg.b3.officers-list.edit'),
                    'meta'         => ['submission_id' => $submissionId],
                ]);
            });
        });

        return redirect()
            ->route('admin.officer_submissions.show', $submission->id)
            ->with('success', 'Returned to organization with remarks.');
    }


    public function approve(Request $request, OfficerSubmission $submission)
    {
        $data = $request->validate([
            'sacdev_remarks' => ['nullable', 'string'],
        ]);

        $orgId = (int) $submission->organization_id;
        $syId  = (int) $submission->target_school_year_id;

        $president = $this->presidentForSy($orgId, $syId);

        DB::transaction(function () use ($submission, $data, $president, $orgId, $syId) {

            $locked = OfficerSubmission::query()
                ->whereKey($submission->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            if ($locked->status !== 'submitted_to_sacdev') {
                abort(403, 'Only submitted forms can be approved.');
            }

            $locked->status = 'approved_by_sacdev';
            $locked->approved_at = now();
            $locked->sacdev_reviewed_by_user_id = Auth::id();
            $locked->sacdev_remarks = $data['sacdev_remarks'] ?? null;
            $locked->sacdev_reviewed_at = now();
            $locked->save();

            $submissionId = (int) $locked->getKey();

            DB::afterCommit(function () use ($president, $orgId, $syId, $submissionId) {
                if (!$president) return;

                $dedupeKey = "b3:officer_submission:{$submissionId}:approved_by_sacdev:to:{$president->getKey()}";

                InAppNotifier::notifyOnce($president, [
                    'dedupe_key'   => $dedupeKey,
                    'title'        => 'B3 Officers List approved by SACDEV',
                    'message'      => 'Your Officers List has been approved by SACDEV.',
                    'org_id'       => $orgId,
                    'target_sy_id' => $syId,
                    'form'         => 'b3_officers_list',
                    'status'       => 'approved_by_sacdev',
                    'action_url'   => route('org.rereg.b3.officers-list.index'),
                    'meta'         => ['submission_id' => $submissionId],
                ]);
            });
        });

        return redirect()
            ->route('admin.officer_submissions.show', $submission->id)
            ->with('success', 'Approved successfully.');
    }


    public function allowEdit(Request $request, OfficerSubmission $submission)
    {
        $data = $request->validate([
            'sacdev_remarks' => ['nullable', 'string', 'max:5000'],
        ]);

        $orgId = (int) $submission->organization_id;
        $syId  = (int) $submission->target_school_year_id;

        $president = $this->presidentForSy($orgId, $syId);

        DB::transaction(function () use ($request, $submission, $data, $president, $orgId, $syId) {

            $locked = OfficerSubmission::query()
                ->whereKey($submission->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            if (!$locked->edit_requested) {
                abort(403, 'No edit request is pending for this submission.');
            }

            if (!in_array($locked->status, ['submitted_to_sacdev', 'approved_by_sacdev'], true)) {
                abort(403, 'Cannot allow edit for this status.');
            }

            $locked->status = 'returned_by_sacdev';
            $locked->returned_at = now();
            $locked->sacdev_reviewed_by_user_id = auth()->id();
            $locked->sacdev_reviewed_at = now();

            $base = "Edit request granted. Please update the form then resubmit.";
            $extra = trim((string)($data['sacdev_remarks'] ?? ''));
            $locked->sacdev_remarks = $extra ? ($base . "\n\nSACDEV Note: " . $extra) : $base;

            $locked->edit_requested = false;
            $locked->edit_request_reason = null;
            $locked->edit_requested_by_user_id = null;
            $locked->edit_requested_at = null;

            $locked->approved_at = null;

            $locked->save();

            $submissionId = (int) $locked->getKey();

            DB::afterCommit(function () use ($president, $orgId, $syId, $submissionId) {
                if (!$president) return;

                $dedupeKey = "b3:officer_submission:{$submissionId}:edit_granted:to:{$president->getKey()}";

                InAppNotifier::notifyOnce($president, [
                    'dedupe_key'   => $dedupeKey,
                    'title'        => 'Edit request granted for B3 Officers List',
                    'message'      => 'SACDEV granted your edit request. Please update the Officers List and resubmit.',
                    'org_id'       => $orgId,
                    'target_sy_id' => $syId,
                    'form'         => 'b3_officers_list',
                    'status'       => 'edit_granted',
                    'action_url'   => route('org.rereg.b3.officers-list.edit'),
                    'meta'         => ['submission_id' => $submissionId],
                ]);
            });
        });

        return back()->with('success', 'Edit request granted. The organization can now edit and resubmit.');
    }



}
