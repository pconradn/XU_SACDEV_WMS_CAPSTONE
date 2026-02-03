<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OfficerSubmission;
use App\Models\SchoolYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SacdevB3OfficerSubmissionController extends Controller
{
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
        if ($submission->status !== 'submitted_to_sacdev') {
            return back()->with('error', 'Only submitted forms can be returned.');
        }

        $data = $request->validate([
            'sacdev_remarks' => ['required', 'string', 'min:3'],
        ]);

        $submission->status = 'returned_by_sacdev';
        $submission->returned_at = now();

        $submission->sacdev_reviewed_by_user_id = Auth::id();
        $submission->sacdev_remarks = $data['sacdev_remarks'];
        $submission->sacdev_reviewed_at = now();

        $submission->save();

        return redirect()
            ->route('admin.officer_submissions.show', $submission->id)
            ->with('success', 'Returned to organization with remarks.');
    }

    public function approve(Request $request, OfficerSubmission $submission)
    {
        if ($submission->status !== 'submitted_to_sacdev') {
            return back()->with('error', 'Only submitted forms can be approved.');
        }

        $data = $request->validate([
            'sacdev_remarks' => ['nullable', 'string'],
        ]);

        $submission->status = 'approved_by_sacdev';
        $submission->approved_at = now();

        $submission->sacdev_reviewed_by_user_id = Auth::id();
        $submission->sacdev_remarks = $data['sacdev_remarks'] ?? null;
        $submission->sacdev_reviewed_at = now();

        $submission->save();

        return redirect()
            ->route('admin.officer_submissions.show', $submission->id)
            ->with('success', 'Approved successfully.');
    }

    public function allowEdit(Request $request, OfficerSubmission $submission)
    {
        if (!$submission->edit_requested) {
            return back()->with('error', 'No edit request is pending for this submission.');
        }

        // Allowed when submitted or approved (since those are locked states)
        if (!in_array($submission->status, ['submitted_to_sacdev', 'approved_by_sacdev'], true)) {
            return back()->with('error', 'Cannot allow edit for this status.');
        }

        $data = $request->validate([
            'sacdev_remarks' => ['nullable', 'string', 'max:5000'],
        ]);

        // Convert to returned so org can edit + resubmit
        $submission->status = 'returned_by_sacdev';
        $submission->returned_at = now();

        $submission->sacdev_reviewed_by_user_id = auth()->id();
        $submission->sacdev_reviewed_at = now();

        // Keep a clear remark trail
        $base = "Edit request granted. Please update the form then resubmit.";
        $extra = trim((string)($data['sacdev_remarks'] ?? ''));
        $submission->sacdev_remarks = $extra ? ($base . "\n\nSACDEV Note: " . $extra) : $base;

        // Clear edit request flags
        $submission->edit_requested = false;
        $submission->edit_request_reason = null;
        $submission->edit_requested_by_user_id = null;
        $submission->edit_requested_at = null;

        // If it was approved, we are intentionally “undoing” approval by returning it.
        $submission->approved_at = null;

        // If it was submitted, you can keep submitted_at for audit, or clear it. I recommend keep it.
        // $submission->submitted_at = $submission->submitted_at;

        $submission->save();

        return back()->with('success', 'Edit request granted. The organization can now edit and resubmit.');
    }


}
