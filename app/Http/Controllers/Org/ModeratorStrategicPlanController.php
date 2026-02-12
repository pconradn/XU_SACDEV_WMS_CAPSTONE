<?php

namespace App\Http\Controllers\Org;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\OrgMembership;
use App\Support\InAppNotifier;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\StrategicPlanSubmission;

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



    public function returnToOrg(Request $request, StrategicPlanSubmission $submission){
        
        ['orgId' => $orgId, 'syId' => $syId, 'userId' => $userId] = $this->ctx($request);

        abort_unless($orgId && $syId, 403, 'No active organization / school year selected.');
        abort_unless((int) $submission->organization_id === (int) $orgId, 403);
        abort_unless((int) $submission->target_school_year_id === (int) $syId, 403);

        $request->validate([
            'moderator_remarks' => ['required', 'string', 'min:5'],
        ]);

        DB::transaction(function () use ($submission, $request, $userId, $orgId, $syId) {
            $submission = StrategicPlanSubmission::query()
                ->whereKey($submission->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            if ($submission->status === StrategicPlanSubmission::STATUS_APPROVED_BY_SACDEV) {
                abort(403, 'Already approved by SACDEV.');
            }

            if (!in_array($submission->status, [
                StrategicPlanSubmission::STATUS_SUBMITTED_TO_MODERATOR,
                StrategicPlanSubmission::STATUS_RETURNED_BY_MODERATOR,
                StrategicPlanSubmission::STATUS_FORWARDED_TO_SACDEV,
            ], true)) {
                abort(403, 'Invalid state for returning.');
            }

            $submission->status = StrategicPlanSubmission::STATUS_RETURNED_BY_MODERATOR;
            $submission->moderator_reviewed_by = $userId;
            $submission->moderator_reviewed_at = now();
            $submission->moderator_remarks = $request->input('moderator_remarks');
            $submission->forwarded_to_sacdev_at = null;
            $submission->save();

       
            DB::afterCommit(function () use ($submission, $orgId, $syId) {
                $president = $this->presidentForSy($orgId, $syId);

                $dedupeKey = implode(':', [
                    'rereg',
                    'strategic_plan',
                    'returned_by_moderator',
                    'org'.$orgId,
                    'sy'.$syId,
                    'sub'.$submission->getKey(),
                    'to_user'.($president?->getKey() ?? 0),
                ]);

                InAppNotifier::notifyOnce($president, [
                    'dedupe_key'   => $dedupeKey,
                    'title'        => 'Strategic Plan returned by Moderator',
                    'message'      => 'Your Strategic Plan submission was returned. Please review the remarks and resubmit.',
                    'org_id'       => $orgId,
                    'target_sy_id' => $syId,
                    'form'         => 'strategic_plan',
                    'status'       => 'returned_by_moderator',
                    
                    'action_url' => route('org.rereg.b1.edit'),
                    'meta'         => [
                        'submission_id' => $submission->getKey(),
                    ],
                ]);
            });
        });

        return redirect()
            ->route('org.moderator.strategic_plans.show', $submission)
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

        DB::transaction(function () use ($submission, $request, $userId, $orgId, $syId) {
            $submission = StrategicPlanSubmission::query()
                ->whereKey($submission->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            if ($submission->status === StrategicPlanSubmission::STATUS_APPROVED_BY_SACDEV) {
                abort(403, 'Already approved by SACDEV.');
            }

            if (!in_array($submission->status, [
                StrategicPlanSubmission::STATUS_SUBMITTED_TO_MODERATOR,
                StrategicPlanSubmission::STATUS_RETURNED_BY_MODERATOR,
            ], true)) {
                abort(403, 'This submission is no longer in moderator review stage.');
            }

            $submission->status = StrategicPlanSubmission::STATUS_FORWARDED_TO_SACDEV;
            $submission->moderator_reviewed_by = $userId;
            $submission->moderator_reviewed_at = now();
            $submission->moderator_remarks = $request->input('moderator_note');
            $submission->forwarded_to_sacdev_at = now();
            $submission->save();

            DB::afterCommit(function () use ($submission, $orgId, $syId) {
                $president = $this->presidentForSy($orgId, $syId);

                $dedupeKey = implode(':', [
                    'rereg',
                    'strategic_plan',
                    'forwarded_to_sacdev',
                    'org'.$orgId,
                    'sy'.$syId,
                    'sub'.$submission->getKey(),
                    'to_user'.($president?->getKey() ?? 0),
                ]);

                InAppNotifier::notifyOnce($president, [
                    'dedupe_key'   => $dedupeKey,
                    'title'        => 'Strategic Plan forwarded to SACDEV',
                    'message'      => 'Your Strategic Plan submission has been forwarded to SACDEV for review.',
                    'org_id'       => $orgId,
                    'target_sy_id' => $syId,
                    'form'         => 'strategic_plan',
                    'status'       => 'forwarded_to_sacdev',
                    'action_url' => route('org.rereg.b1.edit'),
                    'meta'         => [
                        'submission_id' => $submission->getKey(),
                    ],
                ]);
            });
        });

        return redirect()
            ->route('org.moderator.strategic_plans.show', $submission)
            ->with('success', 'Noted and forwarded to SACDEV.');
    }

}
