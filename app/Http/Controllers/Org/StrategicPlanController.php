<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use App\Http\Requests\Org\SaveDraftStrategicPlanRequest;
use App\Http\Requests\Org\SubmitStrategicPlanRequest;
use App\Models\OrgMembership;
use App\Models\SchoolYear;
use App\Models\StrategicPlanBeneficiary;
use App\Models\StrategicPlanDeliverable;
use App\Models\StrategicPlanFundSource;
use App\Models\StrategicPlanObjective;
use App\Models\StrategicPlanPartner;
use App\Models\StrategicPlanProject;
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

        return view('org.strategic_plan.edit', compact('submission', 'schoolYear', 'canEdit'));
    }

   
    public function saveDraft(SaveDraftStrategicPlanRequest $request)
    {
        ['orgId' => $orgId, 'targetSy' => $targetSyId, 'userId' => $userId] = $this->ctx($request);

        if ($orgId <= 0) {
            return redirect()->route('org.home')->with('status', 'Please select an organization first.');
        }

        if ($targetSyId <= 0) {
            return redirect()->route('org.rereg.index')->with('status', 'Please select a target school year first.');
        }

        $this->assertMembership($request, $orgId, $targetSyId);

        $validated = $request->validated();
        $validated['projects'] = array_values($validated['projects'] ?? []);
        $validated['fund_sources'] = array_values($validated['fund_sources'] ?? []);

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

            $submission->fill([
                'org_acronym' => $validated['org_acronym'] ?? null,
                'org_name'    => $validated['org_name'] ?? ($submission->org_name ?? ''),
                'mission'     => $validated['mission'] ?? null,
                'vision'      => $validated['vision'] ?? null,
            ]);

            // stays draft on save
            $submission->status = StrategicPlanSubmission::STATUS_DRAFT;
            $submission->submitted_by_user_id = $submission->submitted_by_user_id ?: $userId;

            $submission->save();

            $this->syncProjects($submission, $validated['projects'] ?? []);
            $this->syncFundSources($submission, $validated['fund_sources'] ?? []);
            $this->recomputeTotals($submission);
        });

        return redirect()
            ->route('org.rereg.b1.edit')
            ->with('success', 'Draft saved.');
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



    public function submitToModerator(SubmitStrategicPlanRequest $request)
    {
        ['orgId' => $orgId, 'targetSy' => $targetSyId] = $this->ctx($request);

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

        $result = DB::transaction(function () use ($request, $orgId, $targetSyId) {

            $submission = StrategicPlanSubmission::query()
                ->where('organization_id', $orgId)
                ->where('target_school_year_id', $targetSyId)
                ->lockForUpdate()
                ->firstOrFail();

            $editable = in_array($submission->status, [
                StrategicPlanSubmission::STATUS_DRAFT,
                StrategicPlanSubmission::STATUS_RETURNED_BY_MODERATOR,
                StrategicPlanSubmission::STATUS_RETURNED_BY_SACDEV,
                StrategicPlanSubmission::STATUS_SUBMITTED_TO_MODERATOR,
            ], true);

            //dd($editable);

            if (! $editable) {
                return redirect()
                    ->route('org.rereg.b1.edit')
                    ->with('error', 'This submission cannot be submitted right now.');
            }

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

            Audit::log(
                'strategic_plan_submitted_to_moderator',
                'Strategic Plan submitted to moderator',
                [
                    'actor_user_id'   => Auth::id(),
                    'organization_id' => $orgId,
                    'school_year_id'  => $targetSyId,
                    'meta' => [
                        'submission_id' => $submission->id,
                        'logo_uploaded' => $submission->logo_path ? true : false,
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
            'org'.$orgId,'sy'.$targetSyId,'sub'.$submissionId,'to_user'.$moderator->getKey(),
        ]);

        InAppNotifier::notifyOnce($moderator, [
            'dedupe_key'   => $dedupeKey,
            'title'        => 'Strategic Plan Submitted for Review',
            'message'      => 'A Strategic Plan has been submitted to you for review. Please evaluate it and either return it with feedback or forward it to SACDEV.',
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




    

    private function syncProjects(StrategicPlanSubmission $submission, array $projectsPayload): void
    {
       
        $existingProjects = $submission->projects()->get();

        foreach ($existingProjects as $p) {
            $p->objectives()->delete();
            $p->beneficiaries()->delete();
            $p->deliverables()->delete();
            $p->partners()->delete();
        }
        $submission->projects()->delete();

        foreach ($projectsPayload as $proj) {
            if (empty($proj['title'])) {
                continue;
            }

            $project = StrategicPlanProject::create([
                'submission_id'     => $submission->id,
                'category'          => $proj['category'] ?? null,
                'target_date'       => $proj['target_date'] ?? null,
                'title'             => $proj['title'],
                'implementing_body' => $proj['implementing_body'] ?? null,
                'budget'            => $proj['budget'] ?? 0,
            ]);

            foreach (($proj['objectives'] ?? []) as $text) {
                if (trim((string) $text) === '') continue;
                StrategicPlanObjective::create(['project_id' => $project->id, 'text' => $text]);
            }

            foreach (($proj['beneficiaries'] ?? []) as $text) {
                if (trim((string) $text) === '') continue;
                StrategicPlanBeneficiary::create(['project_id' => $project->id, 'text' => $text]);
            }

            foreach (($proj['deliverables'] ?? []) as $text) {
                if (trim((string) $text) === '') continue;
                StrategicPlanDeliverable::create(['project_id' => $project->id, 'text' => $text]);
            }

            foreach (($proj['partners'] ?? []) as $text) {
                if (trim((string) $text) === '') continue;
                StrategicPlanPartner::create(['project_id' => $project->id, 'text' => $text]);
            }
        }
    }

    private function syncFundSources(StrategicPlanSubmission $submission, array $fundSourcesPayload): void
    {
        $submission->fundSources()->delete();

        foreach ($fundSourcesPayload as $fs) {
            $type = $fs['type'] ?? null;
            if (!$type) continue;

            $amount = $fs['amount'] ?? 0;

            StrategicPlanFundSource::create([
                'submission_id' => $submission->id,
                'type'          => $type,
                'label'         => $fs['label'] ?? null,
                'amount'        => $amount,
            ]);
        }
    }

    private function recomputeTotals(StrategicPlanSubmission $submission): void
    {
        $projects = $submission->projects()->get();

        $orgDev  = $projects->where('category', StrategicPlanProject::CAT_ORG_DEV)->sum('budget');
        $stud    = $projects->where('category', StrategicPlanProject::CAT_STUDENT_SERVICES)->sum('budget');
        $comm    = $projects->where('category', StrategicPlanProject::CAT_COMMUNITY_INVOLVEMENT)->sum('budget');
        $overall = $orgDev + $stud + $comm;

        $submission->total_org_dev = $orgDev;
        $submission->total_student_services = $stud;
        $submission->total_community_involvement = $comm;
        $submission->total_overall = $overall;

        $submission->save();
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
