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
}
