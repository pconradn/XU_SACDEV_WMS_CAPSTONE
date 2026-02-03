<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ModeratorSubmission;
use App\Models\OrgModeratorTerm;
use App\Models\SchoolYear;
use Illuminate\Http\Request;

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

    public function show(ModeratorSubmission $submission)
    {
        $submission->load(['organization', 'targetSchoolYear', 'moderatorUser', 'leaderships', 'term']);

        return view('admin.forms.b5_moderator.show', compact('submission'));
    }

    public function returnToModerator(Request $request, ModeratorSubmission $submission)
    {
        $data = $request->validate([
            'sacdev_remarks' => ['required', 'string', 'min:3', 'max:5000'],
        ]);

        if ($submission->status === 'approved_by_sacdev') {
            return back()->with('error', 'This form is already approved. Use a revert flow if you want later.');
        }

        $submission->status = 'returned_by_sacdev';
        $submission->returned_at = now();

        $submission->sacdev_reviewed_by_user_id = auth()->id();
        $submission->sacdev_remarks = $data['sacdev_remarks'];
        $submission->sacdev_reviewed_at = now();
        $submission->save();

        return back()->with('success', 'Returned to moderator with remarks.');
    }

    public function approve(Request $request, ModeratorSubmission $submission)
    {
        if ($submission->status !== 'submitted_to_sacdev') {
            return back()->with('error', 'Only submitted forms can be approved.');
        }

        $submission->status = 'approved_by_sacdev';
        $submission->approved_at = now();

        $submission->sacdev_reviewed_by_user_id = auth()->id();
        $submission->sacdev_reviewed_at = now();
        $submission->save();

        // Activate the assignment/term (best practice)
        if ($submission->org_moderator_term_id) {
            $term = OrgModeratorTerm::query()->find($submission->org_moderator_term_id);
            if ($term) {
                $term->status = 'active';
                $term->activated_at = now();
                $term->save();
            }
        }

        return back()->with('success', 'Moderator form approved.');
    }


    public function allowEdit(Request $request, ModeratorSubmission $submission)
    {
        // Must have pending request
        if (!$submission->edit_requested) {
            return back()->with('error', 'No edit request is pending for this submission.');
        }

        // Only makes sense if locked
        if (!in_array($submission->status, ['submitted_to_sacdev', 'approved_by_sacdev'], true)) {
            return back()->with('error', 'Allow edit is only valid when the form is submitted or approved.');
        }

        $data = $request->validate([
            'sacdev_remarks' => ['nullable', 'string', 'max:5000'],
        ]);

        // Unlock by moving to returned status (so moderator can edit + resubmit)
        $submission->status = 'returned_by_sacdev';
        $submission->returned_at = now();

        // If it was approved, remove approval timestamp to “unfinalize”
        if ($submission->approved_at) {
            $submission->approved_at = null;
        }

        // Add a remark (optional)
        $msg = trim((string)($data['sacdev_remarks'] ?? ''));
        if ($msg === '') {
            $msg = 'Edit request granted. Please update the form and resubmit.';
        }

        $submission->sacdev_reviewed_by_user_id = auth()->id();
        $submission->sacdev_remarks = $msg;
        $submission->sacdev_reviewed_at = now();

        // Clear the request flag
        $submission->edit_requested = false;
        $submission->edit_requested_at = null;
        $submission->edit_requested_by_user_id = null;
        $submission->edit_request_message = null;

        $submission->save();

        return back()->with('success', 'Edit access granted. Submission returned for revision.');
    }



    public function revertApproval(Request $request, ModeratorSubmission $submission)
    {
        if ($submission->status !== 'approved_by_sacdev') {
            return back()->with('error', 'Only approved submissions can be reverted.');
        }

        $data = $request->validate([
            'sacdev_remarks' => ['required', 'string', 'min:3', 'max:5000'],
        ]);

        // Revert to returned state so moderator can revise & resubmit
        $submission->status = 'returned_by_sacdev';
        $submission->returned_at = now();
        $submission->approved_at = null;

        $submission->sacdev_reviewed_by_user_id = auth()->id();
        $submission->sacdev_remarks = $data['sacdev_remarks'];
        $submission->sacdev_reviewed_at = now();

        // You can optionally flag edit_requested=false too
        $submission->edit_requested = false;
        $submission->edit_requested_at = null;
        $submission->edit_requested_by_user_id = null;
        $submission->edit_request_message = null;

        $submission->save();

        return back()->with('success', 'Approval reverted and returned for revision.');
    }
   



}
