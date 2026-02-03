<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StrategicPlanSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SacdevStrategicPlanController extends Controller
{
    public function index(Request $request)
    {
        $q = StrategicPlanSubmission::query()
            ->with(['organization', 'targetSchoolYear'])
            ->orderByDesc('updated_at');

        // optional quick filters
        if ($status = $request->get('status')) {
            $q->where('status', $status);
        }

        $submissions = $q->paginate(15);

        return view('admin.strategic_plans.index', compact('submissions'));
    }

    public function show(StrategicPlanSubmission $submission)
    {
        $submission->load([
            'organization',
            'targetSchoolYear',
            'projects.objectives',
            'projects.beneficiaries',
            'projects.deliverables',
            'projects.partners',
            'fundSources',
        ]);

        return view('admin.strategic_plans.show', compact('submission'));
    }

    public function returnToOrg(Request $request, StrategicPlanSubmission $submission)
    {
        $request->validate([
            'remarks' => ['required', 'string', 'min:5'],
        ]);

        DB::transaction(function () use ($request, $submission) {
            $submission->lockForUpdate();

            // Only SACDEV-stage submissions can be returned by SACDEV
            if (!in_array($submission->status, [
                StrategicPlanSubmission::STATUS_FORWARDED_TO_SACDEV,
                StrategicPlanSubmission::STATUS_APPROVED_BY_SACDEV,
            ], true)) {
                abort(403, 'This submission is not in SACDEV review stage.');
            }

            $submission->status = StrategicPlanSubmission::STATUS_RETURNED_BY_SACDEV;
            $submission->sacdev_reviewed_by = auth()->id();
            $submission->sacdev_reviewed_at = now();
            $submission->sacdev_remarks = $request->string('remarks');
            $submission->approved_at = null; // if it was approved before
            $submission->save();
        });

        return redirect()->route('admin.strategic_plans.show', $submission)
            ->with('success', 'Returned to organization for revision.');
    }

    public function approve(Request $request, StrategicPlanSubmission $submission)
    {
        DB::transaction(function () use ($submission) {
            $submission->lockForUpdate();

            if ($submission->status !== StrategicPlanSubmission::STATUS_FORWARDED_TO_SACDEV
                && $submission->status !== StrategicPlanSubmission::STATUS_RETURNED_BY_SACDEV) {
                abort(403, 'This submission is not ready for approval.');
            }

            $submission->status = StrategicPlanSubmission::STATUS_APPROVED_BY_SACDEV;
            $submission->approved_at = now();
            $submission->sacdev_reviewed_by = auth()->id();
            $submission->sacdev_reviewed_at = now();


                        /**
             * Clear MODERATOR review cycle
             */
            $submission->moderator_reviewed_by = null;
            $submission->moderator_reviewed_at = null;
            $submission->moderator_remarks = null;

            /**
             * Clear SACDEV review cycle
             */
            $submission->sacdev_reviewed_by = null;
            $submission->sacdev_reviewed_at = null;
            $submission->sacdev_remarks = null;
            // keep sacdev_remarks as-is (optional)
            $submission->save();
        });

        return redirect()->route('admin.strategic_plans.show', $submission)
            ->with('success', 'Approved by SACDEV.');
    }

    public function revertApproval(Request $request, StrategicPlanSubmission $submission)
    {
        $request->validate([
            'remarks' => ['required', 'string', 'min:8'],
        ]);

        DB::transaction(function () use ($request, $submission) {
            $submission->lockForUpdate();

            if ($submission->status !== StrategicPlanSubmission::STATUS_APPROVED_BY_SACDEV) {
                abort(403, 'Only approved submissions can be reverted.');
            }

            // revert approval -> becomes returned_by_sacdev (editable again)
            $submission->status = StrategicPlanSubmission::STATUS_RETURNED_BY_SACDEV;
            $submission->approved_at = null;

            $submission->sacdev_reviewed_by = auth()->id();
            $submission->sacdev_reviewed_at = now();
            $submission->sacdev_remarks = $request->string('remarks');

            $submission->save();
        });

        return redirect()->route('admin.strategic_plans.show', $submission)
            ->with('success', 'Approval reverted. Submission returned to the organization.');
    }
}
