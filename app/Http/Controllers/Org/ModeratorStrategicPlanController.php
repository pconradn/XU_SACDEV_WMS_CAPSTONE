<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use App\Models\OrgMembership;
use App\Models\StrategicPlanSubmission;
use App\Models\User;
use App\Support\InAppNotifier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ModeratorStrategicPlanController extends Controller
{
    private function ctx(Request $request): array
    {
        return [
            'orgId' => (int) $request->session()->get('active_org_id'),
            'syId'  => (int) $request->session()->get('encode_sy_id'),
            'userId' => (int) auth()->id(),
        ];
    }

    private function presidentForSy(int $orgId, int $syId): ?User
    {

        $membership = OrgMembership::query()
            ->with('user')
            ->where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->where('role', 'president') 
            ->where('archived_at', null)  
            ->first();

        return $membership?->user;
    }


    public function index(Request $request)
    {
        ['orgId' => $orgId, 'syId' => $syId] = $this->ctx($request);

        
        abort_unless($orgId && $syId, 403, 'No active organization / school year selected.');

        $submissions = StrategicPlanSubmission::query()
            ->with(['targetSchoolYear', 'submittedBy'])
            ->where('organization_id', $orgId)
            ->where('target_school_year_id', $syId)
            ->whereIn('status', [
                StrategicPlanSubmission::STATUS_SUBMITTED_TO_MODERATOR,
                StrategicPlanSubmission::STATUS_RETURNED_BY_MODERATOR,
                StrategicPlanSubmission::STATUS_FORWARDED_TO_SACDEV,
                StrategicPlanSubmission::STATUS_RETURNED_BY_SACDEV,
                StrategicPlanSubmission::STATUS_APPROVED_BY_SACDEV,
            ])
            ->orderByDesc('submitted_to_moderator_at')
            ->orderByDesc('updated_at')
            ->paginate(15);

        return view('org.moderator.strategic_plans.index', compact('submissions'));
    }

    public function show(Request $request, StrategicPlanSubmission $submission)
    {
        ['orgId' => $orgId, 'syId' => $syId] = $this->ctx($request);

        abort_unless($orgId && $syId, 403, 'No active organization / school year selected.');
        abort_unless((int) $submission->organization_id === (int) $orgId, 403);
        abort_unless((int) $submission->target_school_year_id === (int) $syId, 403);

        $submission->load([
            'targetSchoolYear',
            'submittedBy',
            'projects.objectives',
            'projects.beneficiaries',
            'projects.deliverables',
            'projects.partners',
            'fundSources',
        ]);

        return view('org.moderator.strategic_plans.show', compact('submission'));
    }





    public function returnToOrg(Request $request, StrategicPlanSubmission $submission)
    {
        ['orgId' => $orgId, 'syId' => $syId, 'userId' => $userId] = $this->ctx($request);

        abort_unless($orgId && $syId, 403, 'No active organization / school year selected.');
        abort_unless((int) $submission->organization_id === (int) $orgId, 403);
        abort_unless((int) $submission->target_school_year_id === (int) $syId, 403);

        $request->validate([
            'moderator_remarks' => ['required_without:remarks', 'string', 'min:5'],
            'remarks' => ['nullable', 'string', 'min:5'],
        ]);

        $remarks = $request->input('moderator_remarks') ?? $request->input('remarks');

        $result = DB::transaction(function () use ($remarks, $submission, $request, $userId, $orgId, $syId) {

            $locked = StrategicPlanSubmission::query()
                ->whereKey($submission->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            if ($locked->status === StrategicPlanSubmission::STATUS_APPROVED_BY_SACDEV) {
                return redirect()
                    ->route('org.moderator.strategic_plans.show', $locked->getKey())
                    ->with('error', 'This Strategic Plan has already been approved by SACDEV and can no longer be modified.');
            }

            if (! in_array($locked->status, [
                StrategicPlanSubmission::STATUS_SUBMITTED_TO_MODERATOR,
            ], true)) {
                return redirect()
                    ->route('org.moderator.strategic_plans.show', $locked->getKey())
                    ->with('error', 'This submission cannot be returned in its current state.');
            }

            $oldStatus = $locked->status;

            $locked->status = StrategicPlanSubmission::STATUS_RETURNED_BY_MODERATOR;
            $locked->moderator_reviewed_by = $userId;
            $locked->moderator_reviewed_at = now();
            $locked->moderator_remarks = $remarks;
            $locked->forwarded_to_sacdev_at = null;

            $locked->save();

            $locked->timelines()->create([
                'user_id' => $userId,
                'action' => 'returned_by_moderator',
                'remarks' => $remarks,
                'old_status' => $oldStatus,
                'new_status' => StrategicPlanSubmission::STATUS_RETURNED_BY_MODERATOR,
            ]);
            $submissionId = (int) $locked->getKey();

            DB::afterCommit(function () use ($orgId, $syId, $submissionId) {
                $president = $this->presidentForSy($orgId, $syId);
                if (! $president) return;

                $dedupeKey = implode(':', [
                    'rereg',
                    'strategic_plan',
                    'returned_by_moderator',
                    'org'.$orgId,
                    'sy'.$syId,
                    'sub'.$submissionId,
                    'to_user'.$president->getKey(),
                ]);

                InAppNotifier::notifyOnce($president, [
                    'dedupe_key'   => $dedupeKey,
                    'title'        => 'Strategic Plan Returned by Moderator',
                    'message'      => 'Your Strategic Plan submission was returned. Please review the remarks and resubmit.',
                    'org_id'       => $orgId,
                    'target_sy_id' => $syId,
                    'form'         => 'strategic_plan',
                    'status'       => 'returned_by_moderator',
                    'route'   => route('org.rereg.b1.edit'),
                    'meta'         => ['submission_id' => $submissionId],
                    'send_mail'    => true,
                ]);
            });

            return true;
        });

        
        if ($result instanceof RedirectResponse) {
            return $result;
        }

        return redirect()
            ->route('org.rereg.b1.edit', $submission->getKey())
            ->with('success', 'Returned to organization with remarks.');
    }






    public function forwardToSacdev(Request $request, StrategicPlanSubmission $submission){


        ['orgId' => $orgId, 'syId' => $syId, 'userId' => $userId] = $this->ctx($request);

        abort_unless($orgId && $syId, 403, 'No active organization / school year selected.');
        abort_unless((int) $submission->organization_id === (int) $orgId, 403);
        abort_unless((int) $submission->target_school_year_id === (int) $syId, 403);

        $request->validate([
            'moderator_note' => ['nullable', 'string'],
        ]);

        $result = DB::transaction(function () use ($submission, $request, $userId, $orgId, $syId) {

            $locked = StrategicPlanSubmission::query()
                ->whereKey($submission->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            if ($locked->status === StrategicPlanSubmission::STATUS_APPROVED_BY_SACDEV) {
                return redirect()
                    ->route('org.moderator.strategic_plans.show', $locked->getKey())
                    ->with('error', 'This Strategic Plan has already been approved by SACDEV and can no longer be modified.');
            }

          
            if (! in_array($locked->status, [
                StrategicPlanSubmission::STATUS_SUBMITTED_TO_MODERATOR,
            ], true)) {
                return redirect()
                    ->route('org.moderator.strategic_plans.show', $locked->getKey())
                    ->with('error', 'This submission is no longer in the moderator review stage.');
            }

            $oldStatus = $locked->status;

            $locked->status = StrategicPlanSubmission::STATUS_FORWARDED_TO_SACDEV;
            $locked->moderator_reviewed_by = $userId;
            $locked->moderator_reviewed_at = now();
            $locked->moderator_remarks = $request->input('moderator_note');
            $locked->forwarded_to_sacdev_at = now();

            $locked->save();

            $locked->timelines()->create([
                'user_id' => $userId,
                'action' => 'forwarded_to_sacdev',
                'remarks' => $request->input('moderator_note'),
                'old_status' => $oldStatus,
                'new_status' => StrategicPlanSubmission::STATUS_FORWARDED_TO_SACDEV,
            ]);

            $submissionId = (int) $locked->getKey();

            DB::afterCommit(function () use ($orgId, $syId, $submissionId) {
                $president = $this->presidentForSy($orgId, $syId);
                if (! $president) return;

                $dedupeKey = implode(':', [
                    'rereg',
                    'strategic_plan',
                    'forwarded_to_sacdev',
                    'org'.$orgId,
                    'sy'.$syId,
                    'sub'.$submissionId,
                    'to_user'.$president->getKey(),
                ]);

                InAppNotifier::notifyOnce($president, [
                    'dedupe_key'   => $dedupeKey,
                    'title'        => 'Strategic Plan Forwarded to SACDEV',
                    'message'      => 'Your Strategic Plan submission has been forwarded to SACDEV for review.',
                    'org_id'       => $orgId,
                    'target_sy_id' => $syId,
                    'form'         => 'strategic_plan',
                    'status'       => 'forwarded_to_sacdev',
                    'route'   => route('org.rereg.b1.edit'),
                    'meta'         => ['submission_id' => $submissionId],
                    'send_mail'    => true,
                ]);
            });

            return true;
        });

        
        if ($result instanceof RedirectResponse) {
            return $result;
        }

        return redirect()
            ->route('org.rereg.b1.edit', $submission->getKey())
            ->with('success', 'Noted and forwarded to SACDEV.');
    }


}
