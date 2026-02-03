<?php

namespace App\Http\Controllers\Moderator;

use App\Http\Controllers\Controller;
use App\Models\ModeratorSubmission;
use App\Models\OrgModeratorTerm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class B5ModeratorSubmissionController extends Controller
{
    private function ctx(Request $request): array
    {
        return [
            'userId' => (int) auth()->id(),
            'orgId'  => (int) $request->session()->get('active_moderator_org_id'),
            'syId'   => (int) $request->session()->get('active_moderator_sy_id'),
        ];
    }

    /**
     * List assignments (org+SY) for this moderator.
     * For now: also allows selecting an assignment via query param (?term_id=).
     * We’ll wire the blade later.
     */
    public function index(Request $request)
    {
        $userId = (int) auth()->id();

        $terms = OrgModeratorTerm::query()
            ->with(['organization', 'schoolYear'])
            ->where('user_id', $userId)
            ->orderByDesc('school_year_id')
            ->get();

        // Allow selection via query (simple)
        $termId = (int) $request->query('term_id', 0);
        if ($termId > 0) {
            $term = $terms->firstWhere('id', $termId);
            if ($term) {
                $request->session()->put('active_moderator_org_id', (int) $term->organization_id);
                $request->session()->put('active_moderator_sy_id', (int) $term->school_year_id);

                return redirect()->route('moderator.b5.moderator.edit');
            }
        }

        // View later
        return view('moderator.forms.b5_moderator.index', compact('terms'));
    }

    public function edit(Request $request)
    {
        ['userId' => $userId, 'orgId' => $orgId, 'syId' => $syId] = $this->ctx($request);

        if (!$orgId || !$syId) {
            return redirect()->route('moderator.b5.moderator.index')
                ->with('error', 'Please select an assignment first.');
        }

        // Ensure this moderator is assigned to org+sy
        $term = OrgModeratorTerm::query()
            ->where('user_id', $userId)
            ->where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->firstOrFail();

        $submission = ModeratorSubmission::query()
            ->with(['leaderships', 'organization', 'targetSchoolYear'])
            ->firstOrCreate(
                [
                    'organization_id' => $orgId,
                    'target_school_year_id' => $syId,
                ],
                [
                    'moderator_user_id' => $userId,
                    'org_moderator_term_id' => $term->id,
                    'status' => 'draft',
                ]
            );

        // Keep link consistent
        if (!$submission->moderator_user_id) {
            $submission->moderator_user_id = $userId;
        }
        if (!$submission->org_moderator_term_id) {
            $submission->org_moderator_term_id = $term->id;
        }
        $submission->save();

        // lock rules: same as B-2/B-3
        $isLocked = in_array($submission->status, ['submitted_to_sacdev', 'approved_by_sacdev'], true);

        // View later
        return view('moderator.forms.b5_moderator.edit', compact('submission', 'term', 'isLocked'));
    }

    private function rowEmpty(array $row): bool
    {
        $values = [
            $row['organization_name'] ?? null,
            $row['position'] ?? null,
            $row['organization_address'] ?? null,
            $row['inclusive_years'] ?? null,
        ];

        foreach ($values as $v) {
            if ($v !== null && trim((string)$v) !== '') return false;
        }
        return true;
    }

    public function saveDraft(Request $request)
    {
        ['userId' => $userId, 'orgId' => $orgId, 'syId' => $syId] = $this->ctx($request);

        $submission = ModeratorSubmission::query()
            ->with(['leaderships'])
            ->where('organization_id', $orgId)
            ->where('target_school_year_id', $syId)
            ->firstOrFail();

        // Only moderator owner can edit
        if ((int) $submission->moderator_user_id !== $userId) {
            abort(403);
        }

        // Lock only when submitted/approved
        if (in_array($submission->status, ['submitted_to_sacdev', 'approved_by_sacdev'], true)) {
            return back()->with('error', 'This form is locked and cannot be edited unless returned.');
        }

        // Draft validation: types only
        $validated = $request->validate([
            'photo_id' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:4096'],

            'full_name' => ['nullable', 'string', 'max:255'],

            'birthday' => ['nullable', 'date'],
            'age' => ['nullable', 'integer', 'min:0', 'max:120'],
            'sex' => ['nullable', 'string', 'max:20'],
            'religion' => ['nullable', 'string', 'max:255'],

            'university_designation' => ['nullable', 'string', 'max:255'],
            'unit_department' => ['nullable', 'string', 'max:255'],
            'employment_status' => ['nullable', 'string', 'max:255'],
            'years_of_service' => ['nullable', 'integer', 'min:0', 'max:80'],

            'mobile_number' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'landline' => ['nullable', 'string', 'max:30'],
            'facebook_url' => ['nullable', 'url', 'max:255'],
            'city_address' => ['nullable', 'string'],

            'was_moderator_before' => ['nullable', 'boolean'],
            'moderated_org_name' => ['nullable', 'string', 'max:255'],

            'served_nominating_org_before' => ['nullable', 'boolean'],
            'served_nominating_org_years' => ['nullable', 'integer', 'min:0', 'max:80'],

            'skills_and_interests' => ['nullable', 'string'],

            'leaderships' => ['nullable', 'array'],
            'leaderships.*.organization_name' => ['nullable', 'string', 'max:255'],
            'leaderships.*.position' => ['nullable', 'string', 'max:255'],
            'leaderships.*.organization_address' => ['nullable', 'string', 'max:255'],
            'leaderships.*.inclusive_years' => ['nullable', 'string', 'max:30'],
        ]);

        DB::transaction(function () use ($request, $submission, $userId) {
            // upload optional
            if ($request->hasFile('photo_id')) {
                $path = $request->file('photo_id')->store('moderator-ids', 'public');
                $submission->photo_id_path = $path;
            }

            $submission->fill($request->except(['leaderships', 'photo_id']));
            $submission->moderator_user_id = $submission->moderator_user_id ?? $userId;

            $submission->status = 'draft';
            $submission->version = (int) $submission->version + 1;
            $submission->save();

            // sync leadership rows
            $submission->leaderships()->delete();
            foreach (($request->input('leaderships') ?? []) as $i => $row) {
                if (!is_array($row) || $this->rowEmpty($row)) continue;
                $submission->leaderships()->create(array_merge($row, ['sort_order' => $i + 1]));
            }
        });

        return back()->with('success', 'Draft saved.');
    }

    public function submit(Request $request)
    {
        ['userId' => $userId, 'orgId' => $orgId, 'syId' => $syId] = $this->ctx($request);

        $submission = ModeratorSubmission::query()
            ->with(['leaderships'])
            ->where('organization_id', $orgId)
            ->where('target_school_year_id', $syId)
            ->firstOrFail();

        if ((int) $submission->moderator_user_id !== $userId) {
            abort(403);
        }

        if (in_array($submission->status, ['submitted_to_sacdev', 'approved_by_sacdev'], true)) {
            return back()->with('error', 'This form is already submitted/approved.');
        }

        // strict submit validation (match your requirements)
        $request->validate([
            'photo_id' => [$submission->photo_id_path ? 'nullable' : 'required', 'file', 'mimes:jpg,jpeg,png,webp', 'max:4096'],

            'full_name' => ['required', 'string', 'max:255'],
            'birthday' => ['required', 'date'],
            'sex' => ['required', 'string', 'max:20'],

            'university_designation' => ['required', 'string', 'max:255'],
            'unit_department' => ['required', 'string', 'max:255'],
            'employment_status' => ['required', 'string', 'max:255'],
            'years_of_service' => ['required', 'integer', 'min:0', 'max:80'],

            'mobile_number' => ['required', 'string', 'max:30'],
            'email' => ['required', 'email', 'max:255'],
            'city_address' => ['required', 'string'],

            // leadership max 4 enforced later; for now just accept array if present
        ]);

        // Save everything as draft first
        $this->saveDraft($request);

        $submission->refresh();

        $submission->status = 'submitted_to_sacdev';
        $submission->submitted_at = now();

        // clear old SACDEV review fields on new submit
        $submission->sacdev_reviewed_by_user_id = null;
        $submission->sacdev_remarks = null;
        $submission->sacdev_reviewed_at = null;

        $submission->save();

        return back()->with('success', 'Submitted to SACDEV successfully.');
    }

    public function unsubmit(Request $request)
    {
        ['userId' => $userId, 'orgId' => $orgId, 'syId' => $syId] = $this->ctx($request);

        $submission = ModeratorSubmission::query()
            ->where('organization_id', $orgId)
            ->where('target_school_year_id', $syId)
            ->firstOrFail();

        if ((int) $submission->moderator_user_id !== $userId) {
            abort(403);
        }

        // Only allow if currently submitted
        if ($submission->status !== 'submitted_to_sacdev') {
            return back()->with('error', 'You can only unsubmit when the form is submitted to SACDEV.');
        }

        // If SACDEV already reviewed, don’t allow unsubmit (optional safety)
        if ($submission->sacdev_reviewed_at) {
            return back()->with('error', 'Cannot unsubmit because SACDEV has already started reviewing.');
        }

        $submission->status = 'draft';
        $submission->submitted_at = null;
        $submission->save();

        return back()->with('success', 'Submission was reverted back to draft.');
    }
}
