<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use App\Models\OfficerSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class B3OfficerListController extends Controller
{
    private function ctx(Request $request): array
    {
        return [
            'orgId' => (int) $request->session()->get('active_org_id'),
            'targetSyId' => (int) $request->session()->get('encode_sy_id'), // match your B-2 target SY session key if different
        ];
    }

    public function index(Request $request)
    {
        ['orgId' => $orgId, 'targetSyId' => $targetSyId] = $this->ctx($request);

        $schoolYears = \App\Models\SchoolYear::query()->orderByDesc('id')->get();

        $registration = null;
        if ($orgId && $targetSyId) {
            $registration = OfficerSubmission::query()
                ->where('organization_id', $orgId)
                ->where('target_school_year_id', $targetSyId)
                ->first();
        }

        return view('org.forms.b3_officers.index', compact('schoolYears', 'targetSyId', 'registration'));
    }

    public function setTargetSy(Request $request)
    {
        $data = $request->validate([
            'target_school_year_id' => ['required', 'integer'],
        ]);

        // Use same session key pattern you use for B-2 target SY.
        $request->session()->put('encode_sy_id', (int) $data['target_school_year_id']);

        return redirect()->route('org.b3.officers-list.edit');
    }

    public function edit(Request $request)
    {
        ['orgId' => $orgId, 'targetSyId' => $targetSyId] = $this->ctx($request);

        if (!$orgId || !$targetSyId) {
            return redirect()->route('org.b3.officers-list.index')
                ->with('error', 'Please select a Target School Year first.');
        }

        $registration = OfficerSubmission::query()
            ->with('items')
            ->firstOrCreate(
                ['organization_id' => $orgId, 'target_school_year_id' => $targetSyId],
                ['status' => 'draft', 'encoded_by_user_id' => auth()->id()]
            );

        $isLocked = in_array($registration->status, ['submitted_to_sacdev', 'approved_by_sacdev'], true);

        return view('org.forms.b3_officers.edit', compact('registration', 'targetSyId', 'isLocked'));
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
            if ($v !== null && trim((string)$v) !== '') return false;
        }

        return true;
    }

    private function isEditable(OfficerSubmission $registration): bool
    {
        return in_array($registration->status, ['draft', 'returned_by_sacdev'], true);
    }

    private function persistDraft(Request $request, OfficerSubmission $registration): void
    {
        DB::transaction(function () use ($request, $registration) {
            $registration->fill($request->except(['items']));
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

        // Draft: validate formats only (nullable)
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

        // Submit validation
        $request->validate([
            'certified' => ['required', Rule::in(['1', 1, true, 'on'])],

            'items' => ['nullable', 'array'], // no minimum yet (as you said)
            'items.*.position' => ['required_with:items.*.officer_name,items.*.student_id_number,items.*.course_and_year,items.*.mobile_number', 'nullable', 'string', 'max:255'],
            'items.*.officer_name' => ['required_with:items.*.position,items.*.student_id_number,items.*.course_and_year,items.*.mobile_number', 'nullable', 'string', 'max:255'],
            'items.*.student_id_number' => ['required_with:items.*.position,items.*.officer_name,items.*.course_and_year,items.*.mobile_number', 'nullable', 'string', 'max:50'],
            'items.*.course_and_year' => ['required_with:items.*.position,items.*.officer_name,items.*.student_id_number,items.*.mobile_number', 'nullable', 'string', 'max:255'],
            'items.*.latest_qpi' => ['nullable', 'numeric', 'min:0', 'max:4'],
            'items.*.mobile_number' => ['required_with:items.*.position,items.*.officer_name,items.*.student_id_number,items.*.course_and_year', 'nullable', 'string', 'max:30'],
        ]);

        // Save content (sets status draft)
        $this->persistDraft($request, $registration);

        // Then lock it as submitted
        $registration->refresh();
        $registration->status = 'submitted_to_sacdev';
        $registration->submitted_at = now();

        // clear old SACDEV remarks on new submit
        $registration->sacdev_reviewed_by_user_id = null;
        $registration->sacdev_remarks = null;
        $registration->sacdev_reviewed_at = null;
        $registration->returned_at = null;

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

        // Allow ONLY if submitted
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

        // Only allow requesting edit if not editable already
        if (in_array($submission->status, ['draft', 'returned_by_sacdev'], true)) {
            return back()->with('error', 'This form is already editable. No need to request edit.');
        }

        // Only allow request if submitted or approved
        if (!in_array($submission->status, ['submitted_to_sacdev', 'approved_by_sacdev'], true)) {
            return back()->with('error', 'Edit request is not available for this status.');
        }

        $data = $request->validate([
            'edit_request_reason' => ['required', 'string', 'min:5', 'max:2000'],
        ]);

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
