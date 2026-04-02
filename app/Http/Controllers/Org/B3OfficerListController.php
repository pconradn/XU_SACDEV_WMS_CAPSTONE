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
            'targetSyId' => (int) $request->session()->get('encode_sy_id'), 
            'userId'     => (int) $request->user()->id,
        ];
    }


    public function index(Request $request)
    {
        ['orgId' => $orgId, 'targetSyId' => $targetSyId] = $this->ctx($request);

     
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


    public function setTargetSy(Request $request)
    {
        $data = $request->validate([
            'target_school_year_id' => ['required', 'integer', 'exists:school_years,id'],
        ]);

       
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

      
        $isLocked = in_array($registration->status, ['submitted_to_sacdev', 'approved_by_sacdev'], true);

        $currentUser = auth()->user();

        return view('org.forms.b3_officers.edit', [
            'registration' => $registration,
            'targetSyId' => $targetSyId,
            'schoolYear' => $schoolYear,
            'isLocked' => $isLocked,
            'currentUser' => $currentUser,
        ]);
    }

    private function rowEmpty(array $row): bool
    {
        $values = [
            $row['position'] ?? null,
            $row['officer_name'] ?? null,
            $row['student_id_number'] ?? null,
            $row['course_and_year'] ?? null,

            $row['first_sem_qpi'] ?? null,
            $row['second_sem_qpi'] ?? null,
            $row['intersession_qpi'] ?? null,

            $row['mobile_number'] ?? null,
        ];

        foreach ($values as $v) {
            if ($v !== null && trim((string) $v) !== '') {
                return false;
            }
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

            $registration->certified = (bool) $request->input('certified', false);
            $registration->encoded_by_user_id = $registration->encoded_by_user_id ?? auth()->id();
            $registration->status = 'draft';
            $registration->save();

            $registration->items()->delete();

        $sort = 1;

        foreach (($request->input('items') ?? []) as $i => $row) {

            if (!is_array($row) || $this->rowEmpty($row)) {
                continue;
            }

            $isMajorOfficer = !empty($row['major_officer_role']);
            $majorRole = $row['major_officer_role'] ?? null;

            $registration->items()->create([

                'position' => $row['position'] ?? '',

                'officer_name' => $row['officer_name'] ?? '',

                'student_id_number' => $row['student_id_number'] ?? '',

                'course_and_year' => $row['course_and_year'] ?? '',

                'first_sem_qpi' => $row['first_sem_qpi'] ?? null,

                'second_sem_qpi' => $row['second_sem_qpi'] ?? null,

                'intersession_qpi' => $row['intersession_qpi'] ?? null,
                'latest_qpi' => $row['second_sem_qpi'] ?? $row['latest_qpi'] ?? null,

                'mobile_number' => $row['mobile_number'] ?? '',

                'sort_order' => $sort++,

                'is_major_officer' => $isMajorOfficer,

                'major_officer_role' => $isMajorOfficer ? $majorRole : null,

                'propagated_to_memberships' => false,
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


        $validator = \Validator::make($request->all(), [
            'certified' => ['nullable', 'boolean'],

            'items' => ['nullable', 'array'],
            'items.*.position' => ['nullable', 'string', 'max:255'],
            'items.*.officer_name' => ['nullable', 'string', 'max:255'],
            'items.*.student_id_number' => ['nullable', 'string', 'max:50'],
            'items.*.course_and_year' => ['nullable', 'string', 'max:255'],
            
            'items.*.mobile_number' => ['nullable', 'string', 'max:30'],

            'items.*.first_sem_qpi' => ['nullable', 'numeric', 'min:0', 'max:4'],
            'items.*.second_sem_qpi' => ['nullable', 'numeric', 'min:0', 'max:4'],
            'items.*.intersession_qpi' => ['nullable', 'numeric', 'min:0', 'max:4'],

            'items.*.major_officer_role' => [
                'nullable',
                Rule::in(['president', 'vice_president', 'treasurer', 'finance_officer'])
            ],

        ]);

        if ($validator->fails()) {

            // Save as draft
            $this->persistDraft($request, $registration);

            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Submission has errors. Saved as draft instead.');
        }

        $this->persistDraft($request, $registration);
        $this->persistDraft($request, $registration);

        $registration->refresh();

        if ($registration->timelines()->count() === 0) {

            $registration->timelines()->create([
                'user_id' => auth()->id(),
                'action' => 'draft_created',
                'remarks' => null,
                'old_status' => null,
                'new_status' => 'draft',
            ]);

        }

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

      
        if (!in_array($registration->status, ['draft', 'returned_by_sacdev'], true)) {
            return back()->with('error', 'This form cannot be submitted at its current status.');
        }

        $items = collect($request->input('items', []));

        $hasAnyData = $items->contains(function ($row) {
            if (!is_array($row)) return false;

            foreach ($row as $v) {
                if (!is_null($v) && trim((string)$v) !== '') {
                    return true;
                }
            }
            return false;
        });

        
        $request->validate([
            'certified' => ['required', Rule::in(['1', 1, true, 'on'])],

            'items' => ['nullable', 'array'],

            'items.*.position' => ['required', 'string', 'max:255', 'regex:/^[\pL\pN\s\.\-\,\(\)\'\"\/]+$/u'],
            'items.*.officer_name' => ['nullable', 'string', 'max:255', 'regex:/^[\pL\s\.\-\,\(\)\'\"]+$/u'],
            'items.*.student_id_number' => ['nullable', 'string', 'max:50','regex:/^[A-Za-z0-9\-]+$/'],
            'items.*.course_and_year' => ['nullable', 'string', 'max:255','regex:/^[\pL\pN\s\.\-\,\(\)\'\"\/]+$/u'],
            'items.*.mobile_number' => ['nullable', 'string', 'max:30','regex:/^[0-9+\-\s]+$/'],

            'items.*.first_sem_qpi' => ['nullable', 'numeric', 'min:0', 'max:4','regex:/^\d+(\.\d{1,2})?$/'],
            'items.*.second_sem_qpi' => ['nullable', 'numeric', 'min:0', 'max:4','regex:/^\d+(\.\d{1,2})?$/'],
            'items.*.intersession_qpi' => ['nullable', 'numeric', 'min:0', 'max:4','regex:/^\d+(\.\d{1,2})?$/'],

            'items.*.major_officer_role' => [
                'nullable',
                Rule::in(['president', 'vice_president', 'treasurer', 'finance_officer'])
            ],


        ]);

        foreach ($request->input('items', []) as $index => $row) {

            if ($this->rowEmpty($row)) continue;

            $requiredFields = [
                'position',
                'officer_name',
                'student_id_number',
                'course_and_year',
                'mobile_number',
            ];

            foreach ($requiredFields as $field) {
                if (empty($row[$field])) {
                    return back()
                        ->withErrors(["items.$index.$field" => "This field is required."])
                        ->withInput()
                        ->with('error', 'Incomplete officer row. Saved as draft.');
                }
            }
        }

   
        $this->persistDraft($request, $registration);

        $oldStatus = $registration->status;
        $registration->refresh();
        $registration->status = 'submitted_to_sacdev';
        $registration->submitted_at = now();

      
        $registration->sacdev_reviewed_by_user_id = null;
        $registration->sacdev_remarks = null;
        $registration->sacdev_reviewed_at = null;
        $registration->returned_at = null;

        if (isset($registration->edit_requested)) {
            $registration->edit_requested = false;
            $registration->edit_request_reason = null;
            $registration->edit_requested_by_user_id = null;
            $registration->edit_requested_at = null;
        }

        $registration->save();

        $registration->timelines()->create([
            'user_id' => auth()->id(),
            'action' => 'submitted_to_sacdev',
            'remarks' => null,
            'old_status' => $oldStatus,
            'new_status' => 'submitted_to_sacdev',
        ]);


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
            return back()->with('error', 'This form cannot be pulled back as it is not yet submitted or it is already under review');
        }
        $oldStatus = $registration->status;
        $registration->status = 'draft';
        $registration->submitted_at = null;
        $registration->save();

        $registration->timelines()->create([
            'user_id' => auth()->id(),
            'action' => 'unsubmitted',
            'remarks' => null,
            'old_status' => $oldStatus,
            'new_status' => 'draft',
        ]);

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
