<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use App\Models\StrategicPlanSubmission;
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

    public function index(Request $request)
    {
        ['orgId' => $orgId, 'syId' => $syId] = $this->ctx($request);

        // If require.context middleware is solid, this is optional
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
            'moderator_remarks' => ['required', 'string', 'min:5'],
        ]);

        DB::transaction(function () use ($submission, $request, $userId) {
            $submission->refresh();

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

            // pull back if previously forwarded
            $submission->forwarded_to_sacdev_at = null;

            $submission->save();
        });

        return redirect()
            ->route('org.moderator.strategic_plans.show', $submission)
            ->with('success', 'Returned to organization with remarks.');
    }

    public function forwardToSacdev(Request $request, StrategicPlanSubmission $submission)
    {
        ['orgId' => $orgId, 'syId' => $syId, 'userId' => $userId] = $this->ctx($request);

        abort_unless($orgId && $syId, 403, 'No active organization / school year selected.');
        abort_unless((int) $submission->organization_id === (int) $orgId, 403);
        abort_unless((int) $submission->target_school_year_id === (int) $syId, 403);

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
