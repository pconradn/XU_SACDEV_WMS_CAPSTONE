<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use App\Models\PresidentRegistration;
use App\Models\SchoolYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PresidentRegistrationController extends Controller
{
    private function ctx(Request $request): array
    {
        return [
            'orgId' => (int) $request->session()->get('active_org_id'),
            'activeSyId' => (int) $request->session()->get('encode_sy_id'),
            'targetSyId' => (int) ($request->session()->get('target_sy_id') ?? 0),
        ];
    }

    public function index(Request $request)
    {
        ['orgId' => $orgId, 'activeSyId' => $activeSyId, 'targetSyId' => $targetSyId] = $this->ctx($request);

        $schoolYears = SchoolYear::query()->orderByDesc('id')->get();

        // fallback: if no target selected, use active SY (or next SY if your flow expects that)
        if (!$targetSyId) {
            $targetSyId = $activeSyId;
            $request->session()->put('target_sy_id', $targetSyId);
        }

        $registration = PresidentRegistration::query()
            ->where('organization_id', $orgId)
            ->where('target_school_year_id', $targetSyId)
            ->latest('id')
            ->first();

        return view('org.forms.b2_president.index', compact('schoolYears', 'targetSyId', 'registration'));
    }

    public function setTargetSy(Request $request)
    {
        $request->validate([
            'target_school_year_id' => ['required', 'integer', 'exists:school_years,id'],
        ]);

        $request->session()->put('target_sy_id', (int) $request->input('target_school_year_id'));

        return redirect()->route('org.b2.president.edit');
    }

    public function edit(Request $request)
    {
        ['orgId' => $orgId, 'activeSyId' => $activeSyId, 'targetSyId' => $targetSyId] = $this->ctx($request);

        if (!$targetSyId) {
            $targetSyId = $activeSyId;
            $request->session()->put('target_sy_id', $targetSyId);
        }

        $registration = PresidentRegistration::query()
            ->with(['leaderships', 'trainings', 'awards'])
            ->firstOrCreate(
                [
                    'organization_id' => $orgId,
                    'target_school_year_id' => $targetSyId,
                ],
                [
                    'encoded_by_user_id' => auth()->id(),
                    'status' => 'draft',
                    'version' => 1,
                ]
            );

        $isLocked = in_array($registration->status, ['submitted_to_sacdev', 'approved_by_sacdev'], true);

        return view('org.forms.b2_president.edit', compact('registration', 'targetSyId', 'isLocked'));
    }

    public function saveDraft(Request $request)
    {
        ['orgId' => $orgId, 'targetSyId' => $targetSyId] = $this->ctx($request);

        $registration = PresidentRegistration::query()
            ->with(['leaderships', 'trainings', 'awards'])
            ->where('organization_id', $orgId)
            ->where('target_school_year_id', $targetSyId)
            ->firstOrFail();

        if ($registration->status === 'approved_by_sacdev') {
            return back()->with('error', 'This form is already approved and cannot be edited.');
        }
        if ($registration->status === 'submitted_to_sacdev') {
            return back()->with('error', 'This form is already submitted and cannot be edited unless returned.');
        }

        // draft validation: validate only formats if present
        $validated = $request->validate([
            'photo_id' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:4096'],

            'full_name' => ['nullable', 'string', 'max:255'],
            'course_and_year' => ['nullable', 'string', 'max:255'],

            'birthday' => ['nullable', 'date'],
            'age' => ['nullable', 'integer', 'min:0', 'max:120'],
            'sex' => ['nullable', 'string', 'max:20'],
            'religion' => ['nullable', 'string', 'max:255'],

            'mobile_number' => ['nullable', 'string', 'max:30'],
            'city_landline' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'id_number' => ['nullable', 'string', 'max:50'],
            'provincial_landline' => ['nullable', 'string', 'max:30'],
            'facebook_url' => ['nullable', 'url', 'max:255'],
            'home_address' => ['nullable', 'string'],
            'city_address' => ['nullable', 'string'],

            'father_name' => ['nullable', 'string', 'max:255'],
            'father_occupation' => ['nullable', 'string', 'max:255'],
            'father_mobile' => ['nullable', 'string', 'max:30'],

            'mother_name' => ['nullable', 'string', 'max:255'],
            'mother_occupation' => ['nullable', 'string', 'max:255'],
            'mother_mobile' => ['nullable', 'string', 'max:30'],

            'guardian_name' => ['nullable', 'string', 'max:255'],
            'guardian_relationship' => ['nullable', 'string', 'max:255'],
            'guardian_mobile' => ['nullable', 'string', 'max:30'],

            'siblings_count' => ['nullable', 'integer', 'min:0', 'max:30'],

            'high_school_name' => ['nullable', 'string', 'max:255'],
            'high_school_address' => ['nullable', 'string', 'max:255'],
            'high_school_year_graduated' => ['nullable', 'string', 'max:10'],

            'grade_school_name' => ['nullable', 'string', 'max:255'],
            'grade_school_address' => ['nullable', 'string', 'max:255'],
            'grade_school_year_graduated' => ['nullable', 'string', 'max:10'],

            'scholarship_name' => ['nullable', 'string', 'max:255'],
            'scholarship_year_granted' => ['nullable', 'string', 'max:10'],

            'skills_and_interests' => ['nullable', 'string'],

            // checkbox (draft can be false)
            'certified' => ['nullable', 'boolean'],

            // dynamic arrays
            'leaderships' => ['nullable', 'array'],
            'leaderships.*.organization_name' => ['nullable', 'string', 'max:255'],
            'leaderships.*.position' => ['nullable', 'string', 'max:255'],
            'leaderships.*.organization_address' => ['nullable', 'string', 'max:255'],
            'leaderships.*.inclusive_years' => ['nullable', 'string', 'max:30'],

            'trainings' => ['nullable', 'array'],
            'trainings.*.seminar_title' => ['nullable', 'string', 'max:255'],
            'trainings.*.organizer' => ['nullable', 'string', 'max:255'],
            'trainings.*.venue' => ['nullable', 'string', 'max:255'],
            'trainings.*.date_from' => ['nullable', 'date'],
            'trainings.*.date_to' => ['nullable', 'date'],

            'awards' => ['nullable', 'array'],
            'awards.*.award_name' => ['nullable', 'string', 'max:255'],
            'awards.*.award_description' => ['nullable', 'string'],
            'awards.*.conferred_by' => ['nullable', 'string', 'max:255'],
            'awards.*.date_received' => ['nullable', 'date'],
        ]);

        DB::transaction(function () use ($request, $registration) {
            // handle upload (optional for draft)
            if ($request->hasFile('photo_id')) {
                $path = $request->file('photo_id')->store('president-ids', 'public');
                $registration->photo_id_path = $path;
            }

            // update main fields
            $registration->fill($request->except(['leaderships', 'trainings', 'awards', 'photo_id']));
            $registration->encoded_by_user_id = $registration->encoded_by_user_id ?? auth()->id();
            $registration->status = 'draft';
            $registration->version = (int) $registration->version + 1;
            $registration->save();

            // sync children (simple approach: delete + recreate)
            $registration->leaderships()->delete();
            foreach (($request->input('leaderships') ?? []) as $i => $row) {
                if ($this->rowEmpty($row)) continue;
                $registration->leaderships()->create(array_merge($row, ['sort_order' => $i + 1]));
            }

            $registration->trainings()->delete();
            foreach (($request->input('trainings') ?? []) as $i => $row) {
                if ($this->rowEmpty($row)) continue;
                $registration->trainings()->create(array_merge($row, ['sort_order' => $i + 1]));
            }

            $registration->awards()->delete();
            foreach (($request->input('awards') ?? []) as $i => $row) {
                if ($this->rowEmpty($row)) continue;
                $registration->awards()->create(array_merge($row, ['sort_order' => $i + 1]));
            }
        });

        return back()->with('success', 'Draft saved.');
    }

    public function submit(Request $request)
    {
        ['orgId' => $orgId, 'targetSyId' => $targetSyId] = $this->ctx($request);

        $registration = PresidentRegistration::query()
            ->with(['leaderships', 'trainings', 'awards'])
            ->where('organization_id', $orgId)
            ->where('target_school_year_id', $targetSyId)
            ->firstOrFail();

        if (in_array($registration->status, ['submitted_to_sacdev', 'approved_by_sacdev'], true)) {
            return back()->with('error', 'This form is already submitted/approved.');
        }

        // submit validation: strict required fields
        $request->validate([
            'photo_id' => [$registration->photo_id_path ? 'nullable' : 'required', 'file', 'mimes:jpg,jpeg,png,webp', 'max:4096'],

            'full_name' => ['required', 'string', 'max:255'],
            'course_and_year' => ['required', 'string', 'max:255'],

            'birthday' => ['required', 'date'],
            'sex' => ['required', 'string', 'max:20'],

            'mobile_number' => ['required', 'string', 'max:30'],
            'email' => ['required', 'email', 'max:255'],
            'id_number' => ['required', 'string', 'max:50'],
            'home_address' => ['required', 'string'],
            'city_address' => ['required', 'string'],

            'certified' => ['required', Rule::in(['1', 1, true, 'on'])],
        ]);

        // Save all fields like draft, then lock + set submitted
        $this->saveDraft($request);

        $registration->refresh();

        $registration->status = 'submitted_to_sacdev';
        $registration->submitted_at = now();

        // clear old SACDEV remarks on new submit (optional but recommended)
        $registration->sacdev_reviewed_by_user_id = null;
        $registration->sacdev_remarks = null;
        $registration->sacdev_reviewed_at = null;

        $registration->save();

        return back()->with('success', 'Submitted to SACDEV successfully.');
    }

    private function rowEmpty(array $row): bool
    {
        foreach ($row as $v) {
            if ($v !== null && trim((string) $v) !== '') return false;
        }
        return true;
    }


    public function unsubmit(Request $request)
    {
        ['orgId' => $orgId, 'targetSyId' => $targetSyId] = $this->ctx($request);

        $registration = PresidentRegistration::query()
            ->where('organization_id', $orgId)
            ->where('target_school_year_id', $targetSyId)
            ->firstOrFail();

        // Allow ONLY if currently submitted to SACDEV
        if ($registration->status !== 'submitted_to_sacdev') {
            return back()->with(
                'error',
                'This form cannot be pulled back because it is not currently submitted to SACDEV.'
            );
        }

        $registration->status = 'draft';
        $registration->submitted_at = null;

        $registration->save();

        return back()->with(
            'success',
            'Submission pulled back successfully. You may now edit and resubmit the form.'
        );
    }

}
