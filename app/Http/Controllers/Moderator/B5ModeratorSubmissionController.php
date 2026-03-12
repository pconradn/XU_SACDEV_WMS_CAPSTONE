<?php

namespace App\Http\Controllers\Moderator;

use App\Http\Controllers\Controller;
use App\Models\ModeratorSubmission;
use App\Models\OrgMembership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class B5ModeratorSubmissionController extends Controller
{
    private function ctx(Request $request): array
    {
        return [
            'userId' => (int) auth()->id(),
            'orgId'  => (int) $request->session()->get('active_org_id'),
            'syId'   => (int) $request->session()->get('encode_sy_id'),
        ];
    }

    private function assertModeratorContext(int $userId, int $orgId, int $syId): void
    {
        abort_unless($orgId && $syId, 403, 'No active organization / school year selected.');

        $ok = OrgMembership::query()
            ->where('user_id', $userId)
            ->where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->where('role', 'moderator')
            ->exists();

        abort_unless($ok, 403, 'Moderator access only.');
    }

    private function isLocked(ModeratorSubmission $submission): bool
    {
        return in_array($submission->status, ['submitted_to_sacdev', 'approved_by_sacdev'], true);
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
            if ($v !== null && trim((string) $v) !== '') return false;
        }
        return true;
    }

    public function edit(Request $request)
    {
        ['userId' => $userId, 'orgId' => $orgId, 'syId' => $syId] = $this->ctx($request);

        $this->assertModeratorContext($userId, $orgId, $syId);

        $submission = ModeratorSubmission::query()
            ->with(['leaderships', 'organization', 'targetSchoolYear'])
            ->firstOrCreate(
                [
                    'organization_id'       => $orgId,
                    'target_school_year_id' => $syId,
                ],
                [
                    'moderator_user_id' => $userId,
                    'status'            => 'draft',
                ]
            );

        // ensure ownership
        if (! $submission->moderator_user_id) {
            $submission->moderator_user_id = $userId;
            $submission->save();
        }

        abort_unless((int) $submission->moderator_user_id === $userId, 403);

        $isLocked = $this->isLocked($submission);

        return view('moderator.forms.b5_moderator.edit', compact('submission', 'isLocked'));
    }

    public function saveDraft(Request $request)
    {
        ['userId' => $userId, 'orgId' => $orgId, 'syId' => $syId] = $this->ctx($request);

        $this->assertModeratorContext($userId, $orgId, $syId);

        $submission = ModeratorSubmission::query()
            ->with(['leaderships'])
            ->where('organization_id', $orgId)
            ->where('target_school_year_id', $syId)
            ->firstOrFail();

        abort_unless((int) $submission->moderator_user_id === $userId, 403);

        if ($this->isLocked($submission)) {
            return back()->with('error', 'This form is locked and cannot be edited unless returned.');
        }

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
            if ($request->hasFile('photo_id')) {
                $path = $request->file('photo_id')->store('moderator-ids', 'public');
                $submission->photo_id_path = $path;
            }

            $submission->fill($request->except(['leaderships', 'photo_id']));
            $submission->moderator_user_id = $submission->moderator_user_id ?: $userId;
            $submission->status = 'draft';
            $submission->version = (int) $submission->version + 1;
            $submission->save();

            $submission->leaderships()->delete();

            foreach (($request->input('leaderships') ?? []) as $i => $row) {
                if (!is_array($row) || $this->rowEmpty($row)) continue;

                $submission->leaderships()->create([
                    'organization_name'    => $row['organization_name'] ?? null,
                    'position'             => $row['position'] ?? null,
                    'organization_address' => $row['organization_address'] ?? null,
                    'inclusive_years'      => $row['inclusive_years'] ?? null,
                    'sort_order'           => $i + 1,
                ]);
            }
        });

        return back()->with('success', 'Draft saved.');
    }

    public function submit(Request $request)
    {
        ['userId' => $userId, 'orgId' => $orgId, 'syId' => $syId] = $this->ctx($request);

        $this->assertModeratorContext($userId, $orgId, $syId);

        $submission = ModeratorSubmission::query()
            ->with(['leaderships'])
            ->where('organization_id', $orgId)
            ->where('target_school_year_id', $syId)
            ->firstOrFail();

        abort_unless((int) $submission->moderator_user_id === $userId, 403);

        if (in_array($submission->status, ['submitted_to_sacdev', 'approved_by_sacdev'], true)) {
            return back()->with('error', 'This form is already submitted/approved.');
        }

       
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
        ]);

       
        $this->saveDraft($request);

        $submission->refresh();

        $submission->status = 'submitted_to_sacdev';
        $submission->submitted_at = now();

      
        $submission->sacdev_reviewed_by_user_id = null;
        $submission->sacdev_remarks = null;
        $submission->sacdev_reviewed_at = null;

      
        $submission->edit_requested = false;
        $submission->edit_requested_at = null;
        $submission->edit_requested_by_user_id = null;
        $submission->edit_request_message = null;

        $submission->save();

        return back()->with('success', 'Submitted to SACDEV successfully.');
    }

    public function unsubmit(Request $request)
    {
        ['userId' => $userId, 'orgId' => $orgId, 'syId' => $syId] = $this->ctx($request);

        $this->assertModeratorContext($userId, $orgId, $syId);

        $submission = ModeratorSubmission::query()
            ->where('organization_id', $orgId)
            ->where('target_school_year_id', $syId)
            ->firstOrFail();

        abort_unless((int) $submission->moderator_user_id === $userId, 403);

        if ($submission->status !== 'submitted_to_sacdev') {
            return back()->with('error', 'You can only unsubmit when the form is submitted to SACDEV.');
        }

       
        if ($submission->sacdev_reviewed_at) {
            return back()->with('error', 'Cannot unsubmit because SACDEV has already started reviewing.');
        }

        $submission->status = 'draft';
        $submission->submitted_at = null;
        $submission->save();

        return back()->with('success', 'Submission was reverted back to draft.');
    }

    public function requestEdit(Request $request)
    {
        ['userId' => $userId, 'orgId' => $orgId, 'syId' => $syId] = $this->ctx($request);

        $this->assertModeratorContext($userId, $orgId, $syId);

        $submission = ModeratorSubmission::query()
            ->where('organization_id', $orgId)
            ->where('target_school_year_id', $syId)
            ->firstOrFail();

        abort_unless((int) $submission->moderator_user_id === $userId, 403);

     
        if (!in_array($submission->status, ['submitted_to_sacdev', 'approved_by_sacdev'], true)) {
            return back()->with('error', 'Request edit is only needed when the form is submitted or approved.');
        }

        if ($submission->edit_requested) {
            return back()->with('error', 'Edit request is already pending.');
        }

        $data = $request->validate([
            'edit_request_message' => ['nullable', 'string', 'max:2000'],
        ]);

        $submission->edit_requested = true;
        $submission->edit_requested_at = now();
        $submission->edit_requested_by_user_id = $userId;
        $submission->edit_request_message = $data['edit_request_message'] ?? null;
        $submission->save();

        return back()->with('success', 'Edit request sent to SACDEV.');
    }



}
