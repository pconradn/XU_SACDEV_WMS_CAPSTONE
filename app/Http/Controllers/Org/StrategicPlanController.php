<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\OrgMembership;
use App\Models\SchoolYear;
use App\Models\StrategicPlanSubmission;
use App\Models\User;
use App\Support\Audit;
use App\Support\InAppNotifier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class StrategicPlanController extends Controller
{
  
    private function ctx(Request $request): array
    {
        return [
            'orgId'    => (int) $request->session()->get('active_org_id'),
            'targetSy' => (int) $request->session()->get('encode_sy_id'), 
            'userId'   => (int) $request->user()->id,
        ];
    }

    private function assertMembership(Request $request, int $orgId, int $targetSyId): ?RedirectResponse
    {
        $userId = (int) $request->user()->id;

        $ok = OrgMembership::query()
            ->where('user_id', $userId)
            ->where('organization_id', $orgId)
            ->where('school_year_id', $targetSyId)
            ->whereNull('archived_at')
            ->exists();

        if (! $ok) {
            return redirect()
                ->route('org.rereg.index') 
                ->with('error', 'No access to this organization for the selected school year.');
        }

        return null;
    }

    public function edit(Request $request)
    {
        ['orgId' => $orgId, 'targetSy' => $targetSyId, 'userId' => $userId] = $this->ctx($request);

        if ($orgId <= 0) {
            return redirect()->route('org.home')->with('status', 'Please select an organization first.');
        }

        if ($targetSyId <= 0) {
            return redirect()->route('org.rereg.index')->with('status', 'Please select a target school year first.');
        }

        if ($resp = $this->assertMembership($request, $orgId, $targetSyId)) {
            return $resp;
        }

        $submission = StrategicPlanSubmission::query()
            ->with([
                'projects.objectives',
                'projects.beneficiaries',
                'projects.deliverables',
                'projects.partners',
                'fundSources',
            ])
            ->where('organization_id', $orgId)
            ->where('target_school_year_id', $targetSyId)
            ->first();

        if (!$submission) {
            $submission = StrategicPlanSubmission::create([
                'organization_id'       => $orgId,
                'target_school_year_id' => $targetSyId,
                'submitted_by_user_id'  => $userId,
                'status'                => StrategicPlanSubmission::STATUS_DRAFT,
                'org_name'              => '',
            ]);

            $submission->load([
                'projects.objectives',
                'projects.beneficiaries',
                'projects.deliverables',
                'projects.partners',
                'fundSources',
            ]);
        }

        $schoolYear = SchoolYear::findOrFail($targetSyId);

        $isPresident = OrgMembership::where('user_id', $userId)
            ->where('organization_id', $orgId)
            ->where('school_year_id', $targetSyId)
            ->where('role', 'president')
            ->whereNull('archived_at')
            ->exists();

        $isApproved = $submission->status === StrategicPlanSubmission::STATUS_APPROVED_BY_SACDEV;

        $canEdit = $isPresident && !$isApproved;

        $organization = Organization::findOrFail($orgId);

        return view('org.strategic_plan.edit', compact(
            'submission',
            'schoolYear',
            'organization',
            'isPresident',
            'canEdit',
            'isApproved'
        ));
    }



    private function moderatorForSy(int $orgId, int $targetSyId): ?User
    {
        $membership = OrgMembership::query()
            ->with('user')
            ->where('organization_id', $orgId)
            ->where('school_year_id', $targetSyId) 
            ->where('role', 'moderator') 
            ->where('archived_at', null)         
            ->first();

        //dd($membership);

        return $membership?->user;
    }


    public function submitToModerator(Request $request)
    {
        ['orgId' => $orgId, 'targetSy' => $targetSyId] = $this->ctx($request);

        $request->validate([
            'confirm' => ['nullable', 'in:yes'],
        ]);

        if ($orgId <= 0) {
            return redirect()->route('org.home')->with('error', 'Please select an organization first.');
        }

        if ($targetSyId <= 0) {
            return redirect()->route('org.rereg.index')->with('error', 'Please select a target school year first.');
        }

        if ($resp = $this->assertMembership($request, $orgId, $targetSyId)) {
            return $resp;
        }

        if ($resp = $this->assertPresident($request, $orgId, $targetSyId)) {
            return $resp;
        }

        $moderator = $this->moderatorForSy($orgId, $targetSyId);

        if (! $moderator) {
            return redirect()->route('org.rereg.index')
                ->with('error', 'No moderator assigned for this organization and school year.');
        }

        $result = DB::transaction(function () use ($orgId, $targetSyId) {

            $submission = StrategicPlanSubmission::query()
                ->where('organization_id', $orgId)
                ->where('target_school_year_id', $targetSyId)
                ->lockForUpdate()
                ->firstOrFail();

            if ($submission->status === StrategicPlanSubmission::STATUS_APPROVED_BY_SACDEV) {
                return redirect()
                    ->route('org.rereg.b1.edit')
                    ->with('error', 'This strategic plan is already approved.');
            }

            $editable = in_array($submission->status, [
                StrategicPlanSubmission::STATUS_DRAFT,
                StrategicPlanSubmission::STATUS_RETURNED_BY_MODERATOR,
                StrategicPlanSubmission::STATUS_RETURNED_BY_SACDEV,
            ], true);

            if (! $editable) {
                return redirect()
                    ->route('org.rereg.b1.edit')
                    ->with('error', 'This submission cannot be submitted right now.');
            }

            // PURE DB CHECKS (new system)

            if (
                empty($submission->org_name) ||
                empty($submission->mission) ||
                empty($submission->vision)
            ) {
                return redirect()
                    ->route('org.rereg.b1.edit')
                    ->with('error', 'Complete organization profile before submitting.');
            }

            if ($submission->projects()->count() === 0) {
                return redirect()
                    ->route('org.rereg.b1.edit')
                    ->with('error', 'Add at least one project before submitting.');
            }

            if ($submission->fundSources()->whereNotNull('amount')->count() === 0) {
                return redirect()
                    ->route('org.rereg.b1.edit')
                    ->with('error', 'Add at least one fund source with value before submitting.');
            }

            $oldStatus = $submission->getOriginal('status');

            $submission->status = StrategicPlanSubmission::STATUS_SUBMITTED_TO_MODERATOR;
            $submission->submitted_to_moderator_at = now();

            $submission->moderator_reviewed_by = null;
            $submission->moderator_reviewed_at = null;
            $submission->moderator_remarks = null;

            $submission->sacdev_reviewed_by = null;
            $submission->sacdev_reviewed_at = null;
            $submission->sacdev_remarks = null;
            $submission->approved_at = null;

            $submission->save();

            $submission->timelines()->create([
                'user_id' => Auth::id(),
                'action' => 'submitted_to_moderator',
                'remarks' => null,
                'old_status' => $oldStatus,
                'new_status' => StrategicPlanSubmission::STATUS_SUBMITTED_TO_MODERATOR,
            ]);

            Audit::log(
                'strategic_plan_submitted_to_moderator',
                'Strategic Plan submitted to moderator',
                [
                    'actor_user_id'   => Auth::id(),
                    'organization_id' => $orgId,
                    'school_year_id'  => $targetSyId,
                    'meta' => [
                        'submission_id' => $submission->id,
                    ],
                ]
            );

            return (int) $submission->getKey();
        });

        if ($result instanceof RedirectResponse) {
            return $result;
        }

        $submissionId = (int) $result;

        $dedupeKey = implode(':', [
            'rereg','strategic_plan','submitted_to_moderator',
            'org'.$orgId,'sy'.$targetSyId,'sub'.$submissionId,
            'to_user'.$moderator->getKey(),
            now()->timestamp
        ]);

        InAppNotifier::notifyOnce($moderator, [
            'dedupe_key'   => $dedupeKey,
            'title'        => 'Strategic Plan Submitted for Review',
            'message'      => 'A Strategic Plan has been submitted for your review.',
            'org_id'       => $orgId,
            'target_sy_id' => $targetSyId,
            'form'         => 'strategic_plan',
            'status'       => 'submitted_to_moderator',
            'action_url'   => route('org.moderator.strategic_plans.show', $submissionId),
            'meta'         => ['submission_id' => $submissionId],
            'send_mail'    => true,
        ]);

        return redirect()
            ->route('org.rereg.b1.edit')
            ->with('success', 'Submitted to moderator for review.');
    }

 
    public function selectSy(Request $request)
    {
        return redirect()
            ->route('org.rereg.index')
            ->with('status', 'Please select the target school year from the Re-Registration dashboard.');
    }

    public function storeSelectedSy(Request $request)
    {
        return redirect()
            ->route('org.rereg.index')
            ->with('status', 'Please select the target school year from the Re-Registration dashboard.');
    }










    //partials editing =======================

    public function deleteProject(Request $request, $projectId)
    {
        ['orgId' => $orgId, 'targetSy' => $targetSyId] = $this->ctx($request);

        if ($resp = $this->assertMembership($request, $orgId, $targetSyId)) {
            return $resp;
        }

        if ($resp = $this->assertPresident($request, $orgId, $targetSyId)) {
            return $resp;
        }

        return DB::transaction(function () use ($projectId, $orgId, $targetSyId) {

            $project = \App\Models\StrategicPlanProject::with('submission')
                ->where('id', $projectId)
                ->whereHas('submission', function ($q) use ($orgId, $targetSyId) {
                    $q->where('organization_id', $orgId)
                    ->where('target_school_year_id', $targetSyId);
                })
                ->firstOrFail();

            $submission = $project->submission;

            if ($resp = $this->assertNotApproved($submission)) {
                return $resp;
            }

            $wasReset = $this->handleEditTransition($submission);

            $project->objectives()->delete();
            $project->beneficiaries()->delete();
            $project->deliverables()->delete();
            $project->partners()->delete();

            $project->delete();

            if ($wasReset) {
                return back()->with('warning', 'Changes saved. Submission has been reset to draft and requires resubmission.');
            }

            return back()->with('success', 'Project deleted.');
        });
    }

    public function updateProject(Request $request, $projectId)
    {
        ['orgId' => $orgId, 'targetSy' => $targetSyId] = $this->ctx($request);
        $request->merge([
            'budget' => str_replace(',', '', $request->input('budget'))
        ]);
        $validated = $request->validate([
            'category' => ['required','in:org_dev,student_services,community_involvement'],
            'target_date' => ['nullable','date'],
            'title' => ['required','string','max:255'],
            'implementing_body' => ['nullable','string','max:255'],
            'budget' => ['required','numeric','min:0'],
            'objectives' => ['nullable','array'],
            'beneficiaries' => ['nullable','array'],
            'deliverables' => ['nullable','array'],
            'partners' => ['nullable','array'],
        ]);

        if ($resp = $this->assertMembership($request, $orgId, $targetSyId)) {
            return $resp;
        }

        if ($resp = $this->assertPresident($request, $orgId, $targetSyId)) {
            return $resp;
        }

        return DB::transaction(function () use ($validated, $projectId, $orgId, $targetSyId) {

            $project = \App\Models\StrategicPlanProject::with('submission')
                ->where('id', $projectId)
                ->whereHas('submission', function ($q) use ($orgId, $targetSyId) {
                    $q->where('organization_id', $orgId)
                    ->where('target_school_year_id', $targetSyId);
                })
                ->firstOrFail();

            $submission = $project->submission;

            if ($resp = $this->assertNotApproved($submission)) {
                return $resp;
            }

            $hasChanges = $this->hasProjectChanges($project, $validated);

            if (! $hasChanges) {
                return back()->with('info', 'No changes detected.');
            }

            $wasReset = $this->handleEditTransition($submission);

            $project->update([
                'category' => $validated['category'],
                'target_date' => $validated['target_date'] ?? null,
                'title' => trim($validated['title']),
                'implementing_body' => trim($validated['implementing_body'] ?? ''),
                'budget' => $validated['budget'],
            ]);

            $project->objectives()->delete();
            $project->beneficiaries()->delete();
            $project->deliverables()->delete();
            $project->partners()->delete();

            foreach ($validated['objectives'] ?? [] as $text) {
                if (trim($text) !== '') {
                    $project->objectives()->create(['text' => trim($text)]);
                }
            }

            foreach ($validated['beneficiaries'] ?? [] as $text) {
                if (trim($text) !== '') {
                    $project->beneficiaries()->create(['text' => trim($text)]);
                }
            }

            foreach ($validated['deliverables'] ?? [] as $text) {
                if (trim($text) !== '') {
                    $project->deliverables()->create(['text' => trim($text)]);
                }
            }

            foreach ($validated['partners'] ?? [] as $text) {
                if (trim($text) !== '') {
                    $project->partners()->create(['text' => trim($text)]);
                }
            }

            if ($wasReset) {
                return back()->with('warning', 'Changes saved. Submission has been reset to draft and requires resubmission.');
            }

            return back()->with('success', 'Project updated.');
        });
    }

    public function storeProject(Request $request)
    {
        ['orgId' => $orgId, 'targetSy' => $targetSyId] = $this->ctx($request);
        $request->merge([
            'budget' => str_replace(',', '', $request->input('budget'))
        ]);
        $validated = $request->validate([
            'category' => ['required','in:org_dev,student_services,community_involvement'],
            'target_date' => ['nullable','date'],
            'title' => ['required','string','max:255'],
            'implementing_body' => ['nullable','string','max:255'],
            'budget' => ['required','numeric','min:0'],
            'objectives' => ['nullable','array'],
            'objectives.*' => ['nullable','string','max:500'],
            'beneficiaries' => ['nullable','array'],
            'beneficiaries.*' => ['nullable','string','max:500'],
            'deliverables' => ['nullable','array'],
            'deliverables.*' => ['nullable','string','max:500'],
            'partners' => ['nullable','array'],
            'partners.*' => ['nullable','string','max:500'],
        ]);

        if ($resp = $this->assertMembership($request, $orgId, $targetSyId)) {
            return $resp;
        }

        if ($resp = $this->assertPresident($request, $orgId, $targetSyId)) {
            return $resp;
        }

        return DB::transaction(function () use ($validated, $orgId, $targetSyId) {

            $submission = StrategicPlanSubmission::where('organization_id', $orgId)
                ->where('target_school_year_id', $targetSyId)
                ->lockForUpdate()
                ->firstOrFail();

            if ($resp = $this->assertNotApproved($submission)) {
                return $resp;
            }

            $wasReset = $this->handleEditTransition($submission);

            $project = $submission->projects()->create([
                'category' => $validated['category'],
                'target_date' => $validated['target_date'] ?? null,
                'title' => trim($validated['title']),
                'implementing_body' => trim($validated['implementing_body'] ?? ''),
                'budget' => $validated['budget'],
            ]);

            foreach ($validated['objectives'] ?? [] as $text) {
                if (trim($text) !== '') {
                    $project->objectives()->create(['text' => trim($text)]);
                }
            }

            foreach ($validated['beneficiaries'] ?? [] as $text) {
                if (trim($text) !== '') {
                    $project->beneficiaries()->create(['text' => trim($text)]);
                }
            }

            foreach ($validated['deliverables'] ?? [] as $text) {
                if (trim($text) !== '') {
                    $project->deliverables()->create(['text' => trim($text)]);
                }
            }

            foreach ($validated['partners'] ?? [] as $text) {
                if (trim($text) !== '') {
                    $project->partners()->create(['text' => trim($text)]);
                }
            }

            if ($wasReset) {
                return back()->with('warning', 'Changes saved. Submission has been reset to draft and requires resubmission.');
            }

            return back()->with('success', 'Project added.');
        });
    }

    public function saveFundSources(Request $request)
    {
        ['orgId' => $orgId, 'targetSy' => $targetSyId] = $this->ctx($request);

        $data = $request->all();

        foreach ($data['fund_sources'] ?? [] as $i => $fs) {
            if (isset($fs['amount'])) {
                $data['fund_sources'][$i]['amount'] = str_replace(',', '', $fs['amount']);
            }
        }

        $request->merge($data);

        $validated = $request->validate([
            'fund_sources' => ['nullable','array','min:0'],
            'fund_sources.*.type' => ['required','in:org_funds,aeco,pta,membership_fee,raised_funds,other'],
            'fund_sources.*.label' => ['nullable','string','max:255'],
            'fund_sources.*.amount' => ['nullable','numeric','min:0'],
        ]);

        if ($orgId <= 0) {
            return back()->with('error', 'No organization selected.');
        }

        if ($targetSyId <= 0) {
            return back()->with('error', 'No school year selected.');
        }

        if ($resp = $this->assertMembership($request, $orgId, $targetSyId)) {
            return $resp;
        }

        if ($resp = $this->assertPresident($request, $orgId, $targetSyId)) {
            return $resp;
        }

        return DB::transaction(function () use ($validated, $orgId, $targetSyId) {

            $submission = StrategicPlanSubmission::query()
                ->where('organization_id', $orgId)
                ->where('target_school_year_id', $targetSyId)
                ->lockForUpdate()
                ->firstOrFail();

            if ($resp = $this->assertNotApproved($submission)) {
                return $resp;
            }

            $incoming = $validated['fund_sources'] ?? [];

            if (! $this->hasFundSourceChanges($submission, $incoming)) {
                return back()->with('info', 'No changes detected.');
            }

            $wasReset = $this->handleEditTransition($submission);

            $submission->fundSources()->delete();

            foreach ($incoming as $fs) {

                $amount = $fs['amount'] ?? null;

                if ($amount === null || $amount === '') {
                    continue;
                }

                $submission->fundSources()->create([
                    'type' => $fs['type'],
                    'label' => trim($fs['label'] ?? ''),
                    'amount' => $amount,
                ]);
            }

            if ($wasReset) {
                return back()->with('warning', 'Changes saved. Submission has been reset to draft and requires resubmission.');
            }

            return back()->with('success', 'Fund sources saved.');
        });
    }

    public function saveProfile(Request $request)
    {
        ['orgId' => $orgId, 'targetSy' => $targetSyId, 'userId' => $userId] = $this->ctx($request);

        $request->merge([
            'org_acronym' => $request->org_acronym ? strtoupper(trim($request->org_acronym)) : null,
            'org_name' => $request->org_name ? trim($request->org_name) : null,
            'mission' => $request->mission ? trim($request->mission) : null,
            'vision' => $request->vision ? trim($request->vision) : null,
        ]);

        $validated = $request->validate([
            'logo' => ['nullable','image','mimes:jpg,jpeg,png','max:2048'],
            'org_acronym' => ['required','string','max:50','regex:/^[A-Z0-9\s\-]+$/'],
            'org_name' => ['required','string','max:255'],
            'mission' => ['required','string','max:2000'],
            'vision' => ['required','string','max:2000'],
        ]);

        if ($orgId <= 0) {
            return redirect()->route('org.home')->with('error', 'Please select an organization first.');
        }

        if ($targetSyId <= 0) {
            return redirect()->route('org.rereg.index')->with('error', 'Please select a target school year first.');
        }

        if ($resp = $this->assertMembership($request, $orgId, $targetSyId)) {
            return $resp;
        }

        if ($resp = $this->assertPresident($request, $orgId, $targetSyId)) {
            return $resp;
        }

        return DB::transaction(function () use ($request, $validated, $orgId, $targetSyId, $userId) {

            $submission = StrategicPlanSubmission::query()
                ->where('organization_id', $orgId)
                ->where('target_school_year_id', $targetSyId)
                ->lockForUpdate()
                ->firstOrFail();

            if ($resp = $this->assertNotApproved($submission)) {
                return $resp;
            }

            if (! $this->hasProfileChanges($submission, $validated, $request)) {
                return back()->with('info', 'No changes detected.');
            }

            $wasReset = $this->handleEditTransition($submission);

            if ($request->hasFile('logo')) {

                $file = $request->file('logo');

                $filename = 'logo.' . $file->getClientOriginalExtension();

                $path = $file->storeAs(
                    "strategic-plans/{$orgId}/{$targetSyId}",
                    $filename,
                    'public'
                );

                if ($submission->logo_path && Storage::disk('public')->exists($submission->logo_path)) {
                    Storage::disk('public')->delete($submission->logo_path);
                }

                $submission->logo_path = $path;
                $submission->logo_original_name = $file->getClientOriginalName();
                $submission->logo_mime = $file->getMimeType();
                $submission->logo_size_bytes = $file->getSize();
            }

            $submission->update([
                'org_acronym' => $validated['org_acronym'],
                'org_name'    => $validated['org_name'],
                'mission'     => $validated['mission'],
                'vision'      => $validated['vision'],
                'submitted_by_user_id' => $submission->submitted_by_user_id ?: $userId,
            ]);

            if ($wasReset) {
                return back()->with('warning', 'Changes saved. Submission has been reset to draft and requires resubmission.');
            }

            return back()->with('success', 'Saved successfully.');
        });
    }


    //new dependencies ========================

    private function assertPresident(Request $request, int $orgId, int $targetSyId): ?RedirectResponse
    {
        $userId = (int) $request->user()->id;

        $isPresident = OrgMembership::query()
            ->where('user_id', $userId)
            ->where('organization_id', $orgId)
            ->where('school_year_id', $targetSyId)
            ->where('role', 'president')
            ->whereNull('archived_at')
            ->exists();

        if (! $isPresident) {
            return back()->with('error', 'Only the president can edit the strategic plan.');
        }

        return null;
    }

    private function assertNotApproved(StrategicPlanSubmission $submission): ?RedirectResponse
    {
        if ($submission->status === StrategicPlanSubmission::STATUS_APPROVED_BY_SACDEV) {
            return back()->with('error', 'This strategic plan is already approved and cannot be modified.');
        }

        return null;
    }

    private function handleEditTransition(StrategicPlanSubmission $submission): bool
    {
        if (!in_array($submission->status, [
            StrategicPlanSubmission::STATUS_DRAFT,
            StrategicPlanSubmission::STATUS_RETURNED_BY_MODERATOR,
            StrategicPlanSubmission::STATUS_RETURNED_BY_SACDEV,
        ], true)) {

            $oldStatus = $submission->status;

            $submission->status = StrategicPlanSubmission::STATUS_DRAFT;

            $submission->submitted_to_moderator_at = null;

            $submission->moderator_reviewed_by = null;
            $submission->moderator_reviewed_at = null;
            $submission->moderator_remarks = null;

            $submission->sacdev_reviewed_by = null;
            $submission->sacdev_reviewed_at = null;
            $submission->sacdev_remarks = null;
            $submission->approved_at = null;

            $submission->save();

            $submission->timelines()->create([
                'user_id' => auth()->id(),
                'action' => 'edited_after_submission',
                'old_status' => $oldStatus,
                'new_status' => StrategicPlanSubmission::STATUS_DRAFT,
            ]);

            return true;
        }

        return false;
    }


    private function hasProfileChanges(StrategicPlanSubmission $submission, array $data, Request $request): bool
    {
        if (
            $submission->org_acronym !== $data['org_acronym'] ||
            $submission->org_name !== $data['org_name'] ||
            $submission->mission !== $data['mission'] ||
            $submission->vision !== $data['vision']
        ) {
            return true;
        }

        if ($request->hasFile('logo')) {
            return true;
        }

        return false;
    }

    private function hasFundSourceChanges(StrategicPlanSubmission $submission, array $incoming): bool
    {
        $existing = $submission->fundSources()
            ->get()
            ->map(fn ($f) => [
                'type' => $f->type,
                'label' => trim((string) $f->label),
                'amount' => (float) $f->amount,
            ])
            ->values()
            ->toArray();

        $normalizedIncoming = collect($incoming ?? [])
            ->map(function ($f) {
                return [
                    'type' => $f['type'],
                    'label' => trim((string) ($f['label'] ?? '')),
                    'amount' => isset($f['amount']) ? (float) $f['amount'] : null,
                ];
            })
            ->filter(fn ($f) => $f['amount'] !== null)
            ->values()
            ->toArray();

        return $existing !== $normalizedIncoming;
    }    

    private function hasProjectChanges($project, array $data): bool
    {
        $normalize = function ($arr) {
            return collect($arr ?? [])
                ->map(fn ($v) => trim((string) $v))
                ->filter(fn ($v) => $v !== '')
                ->values()
                ->toArray();
        };

        if (
            $project->category !== $data['category'] ||
            $project->target_date != ($data['target_date'] ?? null) ||
            $project->title !== trim($data['title']) ||
            $project->implementing_body !== trim($data['implementing_body'] ?? '') ||
            (float) $project->budget !== (float) $data['budget']
        ) {
            return true;
        }

        if ($normalize($project->objectives->pluck('text')->toArray()) !== $normalize($data['objectives'] ?? [])) {
            return true;
        }

        if ($normalize($project->beneficiaries->pluck('text')->toArray()) !== $normalize($data['beneficiaries'] ?? [])) {
            return true;
        }

        if ($normalize($project->deliverables->pluck('text')->toArray()) !== $normalize($data['deliverables'] ?? [])) {
            return true;
        }

        if ($normalize($project->partners->pluck('text')->toArray()) !== $normalize($data['partners'] ?? [])) {
            return true;
        }

        return false;
    }


}
