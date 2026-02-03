<?php

namespace App\Http\Controllers\SACDEV;

use App\Http\Controllers\Controller;
use App\Models\PresidentRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SacdevB2PresidentRegistrationController extends Controller
{
    public function index(Request $request)
    {
        // Optional filter by target SY (like org side). If you already store active_sy_id in session,
        // use that as default. Otherwise allow showing all.
        $targetSyId = (int) ($request->input('target_school_year_id') ?? session('encode_sy_id') ?? 0);

        $q = PresidentRegistration::query()
            ->with(['organization', 'targetSchoolYear'])
            ->orderByDesc('submitted_at')
            ->orderByDesc('updated_at');

        if ($targetSyId > 0) {
            $q->where('target_school_year_id', $targetSyId);
        }

        // Common list default: show anything SACDEV needs to see
        // submitted / returned / approved
        $status = $request->input('status');
        if ($status) {
            $q->where('status', $status);
        } else {
            $q->whereIn('status', ['submitted_to_sacdev', 'returned_by_sacdev', 'approved_by_sacdev']);
        }

        $registrations = $q->paginate(15)->withQueryString();

        // If you have SchoolYear model, pass it here. Otherwise remove.
        $schoolYears = \App\Models\SchoolYear::query()->orderByDesc('id')->get();

        return view('admin.forms.b2_president.index', compact('registrations', 'schoolYears', 'targetSyId', 'status'));
    }

    public function show(PresidentRegistration $registration)
    {
        $registration->load(['organization', 'leaderships', 'trainings', 'awards', 'targetSchoolYear']);

        // SACDEV view is always read-only for the form fields.
        $isLocked = true;

        return view('admin.forms.b2_president.show', compact('registration', 'isLocked'));
    }

    public function returnToOrg(Request $request, PresidentRegistration $registration)
    {
        // Only allow return if currently submitted
        if ($registration->status !== 'submitted_to_sacdev') {
            return back()->with('error', 'Only submitted forms can be returned.');
        }

        $data = $request->validate([
            'sacdev_remarks' => ['required', 'string', 'min:3'],
        ]);

        $registration->status = 'returned_by_sacdev';
        $registration->returned_at = now();

        $registration->sacdev_reviewed_by_user_id = Auth::id();
        $registration->sacdev_remarks = $data['sacdev_remarks'];
        $registration->sacdev_reviewed_at = now();

        $registration->save();

        return redirect()
            ->route('admin.b2.president.show', $registration->id)
            ->with('success', 'Returned to organization with remarks.');
    }

    public function approve(Request $request, PresidentRegistration $registration)
    {
        // Only allow approve if currently submitted
        if ($registration->status !== 'submitted_to_sacdev') {
            return back()->with('error', 'Only submitted forms can be approved.');
        }

        $data = $request->validate([
            'sacdev_remarks' => ['nullable', 'string'],
        ]);

        $registration->status = 'approved_by_sacdev';
        $registration->approved_at = now();

        $registration->sacdev_reviewed_by_user_id = Auth::id();
        $registration->sacdev_remarks = $data['sacdev_remarks'] ?? null; // optional note
        $registration->sacdev_reviewed_at = now();

        $registration->save();



        return redirect()
            ->route('admin.b2.president.show', $registration->id)
            ->with('success', 'Approved successfully.');
    }
}
