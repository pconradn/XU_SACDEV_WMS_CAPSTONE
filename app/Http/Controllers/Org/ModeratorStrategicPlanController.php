<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use App\Models\SchoolYear;
use App\Models\StrategicPlanSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ModeratorStrategicPlanController extends Controller
{
    private function ctx(Request $request): array
    {
        return [
            'orgId' => (int) $request->session()->get('active_org_id'),
            'activeSyId' => (int) SchoolYear::query()->where('is_active', true)->value('id'),
            'userId' => (int) auth()->id(),
        ];
    }

    public function index(Request $request)
    {
        ['orgId' => $orgId] = $this->ctx($request);

        $submissions = StrategicPlanSubmission::query()
            ->with(['targetSchoolYear'])
            ->where('organization_id', $orgId)
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
        ['orgId' => $orgId] = $this->ctx($request);

        if ((int) $submission->organization_id !== (int) $orgId) {
            abort(403);
        }

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
        ['orgId' => $orgId, 'userId' => $userId] = $this->ctx($request);

        if ((int) $submission->organization_id !== (int) $orgId) {
            abort(403);
        }

        $request->validate([
            'moderator_remarks' => ['required', 'string', 'min:5'],
        ]);

        DB::transaction(function () use ($submission, $request, $userId) {
            $submission->refresh();

            if ($submission->status === StrategicPlanSubmission::STATUS_APPROVED_BY_SACDEV) {
                abort(403, 'Already approved by SACDEV.');
            }

            // Only allow return if it has been submitted to moderator
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

            // If it was forwarded before, pull it back
            $submission->forwarded_to_sacdev_at = null;

            $submission->save();
        });

        return redirect()
            ->route('org.moderator.strategic_plans.show', $submission)
            ->with('success', 'Returned to organization with remarks.');
    }

    public function forwardToSacdev(Request $request, StrategicPlanSubmission $submission)
    {
        ['orgId' => $orgId, 'userId' => $userId] = $this->ctx($request);

        if ((int) $submission->organization_id !== (int) $orgId) {
            abort(403);
        }

        $request->validate([
            'moderator_note' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($submission, $request, $userId) {
            $submission->refresh();

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
        });

        return redirect()
            ->route('org.moderator.strategic_plans.show', $submission)
            ->with('success', 'Noted and forwarded to SACDEV.');
    }
}
