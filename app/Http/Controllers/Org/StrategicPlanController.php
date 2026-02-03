<?php

namespace App\Http\Controllers\Org;

use App\Models\SchoolYear;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\StrategicPlanPartner;
use App\Models\StrategicPlanProject;
use App\Models\StrategicPlanObjective;
use App\Models\StrategicPlanFundSource;
use App\Models\StrategicPlanSubmission;
use Illuminate\Support\Facades\Storage;
use App\Models\StrategicPlanBeneficiary;
use App\Models\StrategicPlanDeliverable;
use App\Http\Requests\Org\SubmitStrategicPlanRequest;
use App\Http\Requests\Org\SaveDraftStrategicPlanRequest;

class StrategicPlanController extends Controller
{
    private function ctx(Request $request): array
    {
        return [
            'orgId' => (int) $request->session()->get('active_org_id'),
            'syId'  => (int) $request->session()->get('encode_sy_id'), // target SY (SY X)
            'userId'=> (int) $request->user()->id,
        ];
    }

    public function edit(Request $request)
    {
        $targetSyId = (int) $request->session()->get('target_sy_id');

        if (!$targetSyId) {
            return redirect()->route('org.strategic_plan.select_sy')
                ->with('info', 'Please select the school year you want to submit for.');
        }

    
        ['orgId' => $orgId, 'userId' => $userId] = $this->ctx($request);

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

        if (! $submission) {
            $submission = StrategicPlanSubmission::create([
                'organization_id'        => $orgId,
                'target_school_year_id'  => $targetSyId,
                'submitted_by_user_id'   => $userId,
                'status'                 => StrategicPlanSubmission::STATUS_DRAFT,
                'org_name'               => '',
            ]);

            $submission->load(['projects', 'fundSources']);
        }

        $schoolYear = SchoolYear::findOrFail($targetSyId);

        return view('org.strategic_plan.edit', compact('submission', 'schoolYear'));
    }


    public function saveDraft(SaveDraftStrategicPlanRequest $request)
    {
        $targetSyId = (int) $request->session()->get('target_sy_id');

        if (!$targetSyId) {
            return redirect()->route('org.strategic_plan.select_sy')
                ->with('info', 'Please select the school year you want to submit for.');
        }

        ['orgId' => $orgId, 'userId' => $userId] = $this->ctx($request);

        $validated = $request->validated();
        $validated['projects'] = array_values($validated['projects'] ?? []);
        $validated['fund_sources'] = array_values($validated['fund_sources'] ?? []);

        DB::transaction(function () use ($request, $validated, $orgId, $targetSyId, $userId) {

            $submission = StrategicPlanSubmission::query()
                ->where('organization_id', $orgId)
                ->where('target_school_year_id', $targetSyId)
                ->lockForUpdate()
                ->firstOrFail();

            // Prevent editing ONLY once approved by SACDEV
            if (in_array($submission->status, [
                StrategicPlanSubmission::STATUS_FORWARDED_TO_SACDEV,
                StrategicPlanSubmission::STATUS_APPROVED_BY_SACDEV,
            ])) {
                abort(403, 'This submission is under review by SACDEV and can no longer be edited.');
            }

            // Handle logo upload (optional)
            if ($request->hasFile('logo')) {
                $file = $request->file('logo');

                // store under: storage/app/public/strategic-plans/{orgId}/{targetSyId}/...
                $path = $file->store("public/strategic-plans/{$orgId}/{$targetSyId}");

                if ($submission->logo_path && Storage::exists($submission->logo_path)) {
                    Storage::delete($submission->logo_path);
                }

                $submission->logo_path = $path;
                $submission->logo_original_name = $file->getClientOriginalName();
                $submission->logo_mime = $file->getMimeType();
                $submission->logo_size_bytes = $file->getSize();
            }

            $submission->fill([
                'org_acronym' => $validated['org_acronym'] ?? null,
                'org_name'    => $validated['org_name'] ?? $submission->org_name,
                'mission'     => $validated['mission'] ?? null,
                'vision'      => $validated['vision'] ?? null,
            ]);

            // keep draft on save
            $submission->status = StrategicPlanSubmission::STATUS_DRAFT;

            $submission->save();

            $this->syncProjects($submission, $validated['projects'] ?? []);
            $this->syncFundSources($submission, $validated['fund_sources'] ?? []);
            $this->recomputeTotals($submission);
        });

        return redirect()
            ->route('org.strategic_plan.edit')
            ->with('success', 'Draft saved.');
    }


    public function submitToModerator(SubmitStrategicPlanRequest $request)
    {
        $targetSyId = (int) $request->session()->get('target_sy_id');

        if (!$targetSyId) {
            return redirect()->route('org.strategic_plan.select_sy')
                ->with('info', 'Please select the school year you want to submit for.');
        }

        ['orgId' => $orgId] = $this->ctx($request);

        DB::transaction(function () use ($orgId, $targetSyId) {
            $submission = StrategicPlanSubmission::query()
                ->where('organization_id', $orgId)
                ->where('target_school_year_id', $targetSyId)
                ->lockForUpdate()
                ->firstOrFail();

            if (in_array($submission->status, [
                StrategicPlanSubmission::STATUS_FORWARDED_TO_SACDEV,
                StrategicPlanSubmission::STATUS_APPROVED_BY_SACDEV,
            ])) {
                abort(403, 'This submission is under review by SACDEV and can no longer be edited.');
            }


            $submission->status = StrategicPlanSubmission::STATUS_SUBMITTED_TO_MODERATOR;
            $submission->submitted_to_moderator_at = now();

            $submission->moderator_reviewed_by = null;
            $submission->moderator_reviewed_at = null;
            $submission->moderator_remarks = null;

            $submission->save();
        });

        return redirect()
            ->route('org.strategic_plan.edit')
            ->with('success', 'Submitted to moderator for review.');
    }


    private function syncProjects(StrategicPlanSubmission $submission, array $projectsPayload): void
    {
        // Delete existing
        $existingProjects = $submission->projects()->get();

        foreach ($existingProjects as $p) {
            $p->objectives()->delete();
            $p->beneficiaries()->delete();
            $p->deliverables()->delete();
            $p->partners()->delete();
        }
        $submission->projects()->delete();

        // Create new snapshot
        foreach ($projectsPayload as $proj) {
            if (empty($proj['title'])) {
                continue; // skip empty rows
            }

            $project = StrategicPlanProject::create([
                'submission_id'      => $submission->id,
                'category'           => $proj['category'],
                'target_date'        => $proj['target_date'] ?? null,
                'title'              => $proj['title'],
                'implementing_body'  => $proj['implementing_body'] ?? null,
                'budget'             => $proj['budget'] ?? 0,
            ]);

            foreach (($proj['objectives'] ?? []) as $text) {
                if (trim((string)$text) === '') continue;
                StrategicPlanObjective::create(['project_id' => $project->id, 'text' => $text]);
            }

            foreach (($proj['beneficiaries'] ?? []) as $text) {
                if (trim((string)$text) === '') continue;
                StrategicPlanBeneficiary::create(['project_id' => $project->id, 'text' => $text]);
            }

            foreach (($proj['deliverables'] ?? []) as $text) {
                if (trim((string)$text) === '') continue;
                StrategicPlanDeliverable::create(['project_id' => $project->id, 'text' => $text]);
            }

            foreach (($proj['partners'] ?? []) as $text) {
                if (trim((string)$text) === '') continue;
                StrategicPlanPartner::create(['project_id' => $project->id, 'text' => $text]);
            }
        }
    }

    private function syncFundSources(StrategicPlanSubmission $submission, array $fundSourcesPayload): void
    {
        $submission->fundSources()->delete();

        foreach ($fundSourcesPayload as $fs) {
            $type = $fs['type'] ?? null;
            if (! $type) continue;

            $amount = $fs['amount'] ?? 0;

            StrategicPlanFundSource::create([
                'submission_id' => $submission->id,
                'type' => $type,
                'label' => $fs['label'] ?? null,
                'amount' => $amount,
            ]);
        }
    }

    private function recomputeTotals(StrategicPlanSubmission $submission): void
    {
        $projects = $submission->projects()->get();

        $orgDev = $projects->where('category', StrategicPlanProject::CAT_ORG_DEV)->sum('budget');
        $stud   = $projects->where('category', StrategicPlanProject::CAT_STUDENT_SERVICES)->sum('budget');
        $comm   = $projects->where('category', StrategicPlanProject::CAT_COMMUNITY_INVOLVEMENT)->sum('budget');
        $overall = $orgDev + $stud + $comm;

        $submission->total_org_dev = $orgDev;
        $submission->total_student_services = $stud;
        $submission->total_community_involvement = $comm;
        $submission->total_overall = $overall;

        $submission->save();
    }

    public function selectSy(Request $request)
    {
        // current active SY
        $activeSy = SchoolYear::query()->where('is_active', true)->first();

        // allow active + next (simple rule)
        //$allowed = SchoolYear::query()
        //    ->orderBy('start_date')
        //    ->get(); // you can filter more strictly later

        // If you want strictly active + next:
        $allowed = SchoolYear::query()
            ->orderBy('start_date')
            ->when($activeSy, function ($q) use ($activeSy) {
                 $q->where('id', $activeSy->id)
                  ->orWhere('start_date', '>', $activeSy->start_date);
            })
            ->take(2)
            ->get();

        $selectedId = (int) $request->session()->get('target_sy_id');

        return view('org.strategic_plan.select_sy', [
            'activeSy' => $activeSy,
            'schoolYears' => $allowed,
            'selectedId' => $selectedId,
        ]);
    }

    public function storeSelectedSy(Request $request)
    {
        $activeSy = SchoolYear::query()->where('is_active', true)->first();

        $request->validate([
            'target_school_year_id' => ['required', 'integer', Rule::exists('school_years', 'id')],
        ]);

        $targetId = (int) $request->input('target_school_year_id');

        // Optional guard: must be active or after active
        if ($activeSy && $targetId !== (int)$activeSy->id) {
            $target = SchoolYear::find($targetId);

            if (!$target || ($activeSy->start_date && $target->start_date && $target->start_date < $activeSy->start_date)) {
                return back()->withErrors([
                    'target_school_year_id' => 'Invalid target school year selection.',
                ]);
            }
        }

        $request->session()->put('target_sy_id', $targetId);

        return redirect()->route('org.strategic_plan.edit')
            ->with('success', 'Target school year selected.');
    }



}
