<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use App\Models\OfficerSubmission;
use App\Models\SchoolYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class B3OfficerListController extends Controller
{
    private function ctx(Request $request): array
    {
        return [
            'orgId'      => (int) $request->session()->get('active_org_id'),
            'targetSyId' => (int) $request->session()->get('encode_sy_id'), // re-reg target SY
            'userId'     => (int) $request->user()->id,
        ];
    }

    /**
     * Optional landing page. Most flows go straight to edit.
     * Keep it working but align with new routes + new "encode_sy_id".
     */
    public function index(Request $request)
    {
        ['orgId' => $orgId, 'targetSyId' => $targetSyId] = $this->ctx($request);

        // Only show the selected SY (for clarity) + you can still pass it for display
        $schoolYear = $targetSyId ? SchoolYear::find($targetSyId) : null;

        $registration = null;
        if ($orgId > 0 && $targetSyId > 0) {
            $registration = OfficerSubmission::query()
                ->where('organization_id', $orgId)
                ->where('target_school_year_id', $targetSyId)
                ->latest('id')
                ->first();
        }

        return view('org.forms.b3_officers.index', [
            'targetSyId' => $targetSyId,
            'schoolYear' => $schoolYear,
            'registration' => $registration,
        ]);
    }

    /**
     * We no longer set target SY inside B3.
     * The rereg dashboard (or global selector) sets encode_sy_id already.
     * Keep this method for backward compatibility, but just redirect back to rereg dashboard.
     */
    public function setTargetSy(Request $request)
    {
        $data = $request->validate([
            'target_school_year_id' => ['required', 'integer', 'exists:school_years,id'],
        ]);

        // If you still want to allow changing here, we update encode_sy_id
        $request->session()->put('encode_sy_id', (int) $data['target_school_year_id']);

        return redirect()->route('org.rereg.index')->with('status', 'Target SY updated.');
    }

    public function edit(Request $request)
    {
        ['orgId' => $orgId, 'targetSyId' => $targetSyId] = $this->ctx($request);

        if ($orgId <= 0) {
            return redirect()->route('org.home')->with('status', 'Please select an organization first.');
        }

        if ($targetSyId <= 0) {
            return redirect()->route('org.rereg.index')->with('status', 'Please select a target school year first.');
        }

        $registration = OfficerSubmission::query()
            ->with('items')
            ->firstOrCreate(
                [
                    'organization_id' => $orgId,
                    'target_school_year_id' => $targetSyId,
                ],
                [
                    'status' => 'draft',
                    'encoded_by_user_id' => auth()->id(),
                ]
            );

        $schoolYear = SchoolYear::find($targetSyId);

        // lock if already submitted or approved
        $isLocked = in_array($registration->status, ['submitted_to_sacdev', 'approved_by_sacdev'], true);

        return view('org.forms.b3_officers.edit', [
            'registration' => $registration,
            'targetSyId' => $targetSyId,
            'schoolYear' => $schoolYear,
            'isLocked' => $isLocked,
        ]);
    }

    private function rowEmpty(array $row): bool
    {
        $values = [
            $row['position'] ?? null,
            $row['officer_name'] ?? null,
            $row['student_id_number'] ?? null,
            $row['course_and_year'] ?? null,
            $row['latest_qpi'] ?? null,
            $row['mobile_number'] ?? null,
        ];

        foreach ($values as $v) {
            if ($v !== null && trim((string) $v) !== '') return false;
        }

        return true;
    }

    private function isEditable(OfficerSubmission $registration): bool
    {
        // allow edits only if draft or returned
        return in_array($registration->status, ['draft', 'returned_by_sacdev'], true);
    }

    private function persistDraft(Request $request, OfficerSubmission $registration): void
    {
        DB::transaction(function () use ($request, $registration) {

            // keep it simple: just store certified + remarks fields if you have them
            // (avoid mass-assigning unknown fields)
            $registration->certified = (bool) $request->input('certified', false);
            $registration->encoded_by_user_id = $registration->encoded_by_user_id ?? auth()->id();
            $registration->status = 'draft';
            $registration->save();

            $registration->items()->delete();

            foreach (($request->input('items') ?? []) as $i => $row) {
                if (!is_array($row) || $this->rowEmpty($row)) continue;

                $registration->items()->create([
                    'position' => $row['position'] ?? '',
                    'officer_name' => $row['officer_name'] ?? '',
                    'student_id_number' => $row['student_id_number'] ?? '',
                    'course_and_year' => $row['course_and_year'] ?? '',
                    'latest_qpi' => $row['latest_qpi'] ?? null,
                    'mobile_number' => $row['mobile_number'] ?? '',
                    'sort_order' => $i + 1,
                ]);
            }
        });
    }

    public function saveDraft(Request $request)
    {
        ['orgId' => $orgId, 'targetSyId' => $targetSyId] = $this->ctx($request);

        $registration = OfficerSubmission::query()
            ->with('items')
            ->where('organization_id', $orgId)
            ->where('target_school_year_id', $targetSyId)
            ->firstOrFail();

        if (!$this->isEditable($registration)) {
            return back()->with('error', 'This form cannot be edited at its current status.');
        }

        // Draft validation: formats only (nullable)
        $request->validate([
            'certified' => ['nullable', 'boolean'],

            'items' => ['nullable', 'array'],
            'items.*.position' => ['nullable', 'string', 'max:255'],
            'items.*.officer_name' => ['nullable', 'string', 'max:255'],
            'items.*.student_id_number' => ['nullable', 'string', 'max:50'],
            'items.*.course_and_year' => ['nullable', 'string', 'max:255'],
            'items.*.latest_qpi' => ['nullable', 'numeric', 'min:0', 'max:4'],
            'items.*.mobile_number' => ['nullable', 'string', 'max:30'],
        ]);

        $this->persistDraft($request, $registration);

        return back()->with('success', 'Draft saved.');
    }

    public function submit(Request $request)
    {
        ['orgId' => $orgId, 'targetSyId' => $targetSyId] = $this->ctx($request);

        $registration = OfficerSubmission::query()
            ->with('items')
            ->where('organization_id', $orgId)
            ->where('target_school_year_id', $targetSyId)
            ->firstOrFail();

        // Allow submit only from draft/returned
        if (!in_array($registration->status, ['draft', 'returned_by_sacdev'], true)) {
            return back()->with('error', 'This form cannot be submitted at its current status.');
        }

        $request->validate([
            'certified' => ['required', Rule::in(['1', 1, true, 'on'])],

            'items' => ['nullable', 'array'],
            'items.*.position' => ['required_with:items.*.officer_name,items.*.student_id_number,items.*.course_and_year,items.*.mobile_number', 'nullable', 'string', 'max:255'],
            'items.*.officer_name' => ['required_with:items.*.position,items.*.student_id_number,items.*.course_and_year,items.*.mobile_number', 'nullable', 'string', 'max:255'],
            'items.*.student_id_number' => ['required_with:items.*.position,items.*.officer_name,items.*.course_and_year,items.*.mobile_number', 'nullable', 'string', 'max:50'],
            'items.*.course_and_year' => ['required_with:items.*.position,items.*.officer_name,items.*.student_id_number,items.*.mobile_number', 'nullable', 'string', 'max:255'],
            'items.*.latest_qpi' => ['nullable', 'numeric', 'min:0', 'max:4'],
            'items.*.mobile_number' => ['required_with:items.*.position,items.*.officer_name,items.*.student_id_number,items.*.course_and_year', 'nullable', 'string', 'max:30'],
        ]);

        // Save content (forces draft)
        $this->persistDraft($request, $registration);

        // Then lock as submitted
        $registration->refresh();
        $registration->status = 'submitted_to_sacdev';
        $registration->submitted_at = now();

        // clear SACDEV cycle fields (if your table has them)
        $registration->sacdev_reviewed_by_user_id = null;
        $registration->sacdev_remarks = null;
        $registration->sacdev_reviewed_at = null;
        $registration->returned_at = null;

        // clear edit request flags (if your table has them)
        if (isset($registration->edit_requested)) {
            $registration->edit_requested = false;
            $registration->edit_request_reason = null;
            $registration->edit_requested_by_user_id = null;
            $registration->edit_requested_at = null;
        }

        $registration->save();

        return back()->with('success', 'Submitted to SACDEV successfully.');
    }

    public function unsubmit(Request $request)
    {
        ['orgId' => $orgId, 'targetSyId' => $targetSyId] = $this->ctx($request);

        $registration = OfficerSubmission::query()
            ->where('organization_id', $orgId)
            ->where('target_school_year_id', $targetSyId)
            ->firstOrFail();

        if ($registration->status !== 'submitted_to_sacdev') {
            return back()->with('error', 'This form cannot be pulled back because it is not currently submitted to SACDEV.');
        }

        $registration->status = 'draft';
        $registration->submitted_at = null;
        $registration->save();

        return back()->with('success', 'Submission pulled back successfully. You may now edit and resubmit.');
    }

    public function requestEdit(Request $request)
    {
        ['orgId' => $orgId, 'targetSyId' => $targetSyId] = $this->ctx($request);

        $submission = OfficerSubmission::query()
            ->where('organization_id', $orgId)
            ->where('target_school_year_id', $targetSyId)
            ->firstOrFail();

        if (in_array($submission->status, ['draft', 'returned_by_sacdev'], true)) {
            return back()->with('error', 'This form is already editable. No need to request edit.');
        }

        if (!in_array($submission->status, ['submitted_to_sacdev', 'approved_by_sacdev'], true)) {
            return back()->with('error', 'Edit request is not available for this status.');
        }

        $data = $request->validate([
            'edit_request_reason' => ['required', 'string', 'min:5', 'max:2000'],
        ]);

        if (!isset($submission->edit_requested)) {
            // if your table does not support edit-request fields, fail loudly
            return back()->with('error', 'Edit request feature is not enabled for this form yet.');
        }

        if ($submission->edit_requested) {
            return back()->with('error', 'An edit request is already pending.');
        }

        $submission->edit_requested = true;
        $submission->edit_request_reason = $data['edit_request_reason'];
        $submission->edit_requested_by_user_id = auth()->id();
        $submission->edit_requested_at = now();
        $submission->save();

        return back()->with('success', 'Edit request sent to SACDEV.');
    }
}
