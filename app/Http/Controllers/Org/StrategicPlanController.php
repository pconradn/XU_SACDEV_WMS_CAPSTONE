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





    private function validateSubmit(Request $request): array
    {
        return $request->validate([
            'confirm' => ['required', 'in:yes'],

            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],

            'org_acronym' => ['required', 'string', 'max:50', 'regex:/^[A-Z0-9\s\-]+$/'],
            'org_name' => ['required', 'string', 'max:255', 'regex:/^[\pL\pN\s\-\.\,\(\)\'\"]+$/u'],
            'mission' => ['required', 'string', 'max:2000', 'regex:/^[\pL\pN\s\-\.\,\(\)\'\"]+$/u'],
            'vision' => ['required', 'string', 'max:2000', 'regex:/^[\pL\pN\s\-\.\,\(\)\'\"]+$/u'],
        ], [
            'confirm.required' => 'Please confirm before submitting.',
            'confirm.in' => 'Invalid confirmation value.',
        ]);
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

 
    private function assertEditableStatus(StrategicPlanSubmission $submission): ?RedirectResponse
    {
        $editable = in_array($submission->status, [
            StrategicPlanSubmission::STATUS_DRAFT,
            StrategicPlanSubmission::STATUS_RETURNED_BY_MODERATOR,
            StrategicPlanSubmission::STATUS_RETURNED_BY_SACDEV,
        ], true);

        if (! $editable) {
            return redirect()
                ->route('org.rereg.b1.edit', $submission->id) 
                ->with('error', 'This submission cannot be edited.');
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

        $this->assertMembership($request, $orgId, $targetSyId);

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

        $canEdit = in_array($submission->status, [
            StrategicPlanSubmission::STATUS_DRAFT,
            StrategicPlanSubmission::STATUS_RETURNED_BY_MODERATOR,
            StrategicPlanSubmission::STATUS_RETURNED_BY_SACDEV,
        ], true);

        $organization = Organization::findOrFail($orgId);

        return view('org.strategic_plan.edit', compact(
            'submission',
            'schoolYear',
            'canEdit',
            'organization'
        ));
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

        DB::transaction(function () use ($validated, $orgId, $targetSyId) {

            $submission = StrategicPlanSubmission::query()
                ->where('organization_id', $orgId)
                ->where('target_school_year_id', $targetSyId)
                ->lockForUpdate()
                ->firstOrFail();

            $submission->fundSources()->delete();

            foreach ($validated['fund_sources'] as $fs) {

                $amount = $fs['amount'] ?? null;

                if ($amount === null || $amount === '') {
                    continue;
                }

                $submission->fundSources()->create([
                    'type' => $fs['type'],
                    'label' => trim($fs['label'] ?? ''),
                    'amount' => $fs['amount'],
                ]);
            }
        });

        return back()->with('success', 'Fund sources saved.');
    }


    public function storeProject(Request $request)
    {
        ['orgId' => $orgId, 'targetSy' => $targetSyId] = $this->ctx($request);

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

        DB::transaction(function () use ($validated, $orgId, $targetSyId) {

            $submission = StrategicPlanSubmission::where('organization_id', $orgId)
                ->where('target_school_year_id', $targetSyId)
                ->lockForUpdate()
                ->firstOrFail();

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
        });

        return back()->with('success', 'Project added.');
    }


    public function updateProject(Request $request, $projectId)
    {
        ['orgId' => $orgId, 'targetSy' => $targetSyId] = $this->ctx($request);

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

        DB::transaction(function () use ($validated, $projectId, $orgId, $targetSyId) {

            $project = \App\Models\StrategicPlanProject::where('id', $projectId)
                ->whereHas('submission', function ($q) use ($orgId, $targetSyId) {
                    $q->where('organization_id', $orgId)
                    ->where('target_school_year_id', $targetSyId);
                })
                ->firstOrFail();

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
        });

        return back()->with('success', 'Project updated.');
    }

    public function deleteProject(Request $request, $projectId)
    {
        ['orgId' => $orgId, 'targetSy' => $targetSyId] = $this->ctx($request);

        DB::transaction(function () use ($projectId, $orgId, $targetSyId) {

            $project = \App\Models\StrategicPlanProject::where('id', $projectId)
                ->whereHas('submission', function ($q) use ($orgId, $targetSyId) {
                    $q->where('organization_id', $orgId)
                    ->where('target_school_year_id', $targetSyId);
                })
                ->firstOrFail();

            $project->objectives()->delete();
            $project->beneficiaries()->delete();
            $project->deliverables()->delete();
            $project->partners()->delete();

            $project->delete();
        });

        return back()->with('success', 'Project deleted.');
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

        DB::transaction(function () use ($request, $validated, $orgId, $targetSyId, $userId) {

            $submission = StrategicPlanSubmission::query()
                ->where('organization_id', $orgId)
                ->where('target_school_year_id', $targetSyId)
                ->lockForUpdate()
                ->firstOrFail();

            $this->assertEditableStatus($submission);

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
        });

        return back()->with('success', 'Organization profile saved.');
    }

    public function submitToModerator(Request $request)
    {
        ['orgId' => $orgId, 'targetSy' => $targetSyId] = $this->ctx($request);

        $request->validate([
            'confirm' => ['required', 'in:yes'],
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

            if ($submission->fundSources()->count() === 0) {
                return redirect()
                    ->route('org.rereg.b1.edit')
                    ->with('error', 'Add at least one fund source before submitting.');
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

}
