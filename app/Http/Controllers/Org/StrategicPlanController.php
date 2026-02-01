<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use App\Http\Requests\Org\SaveDraftStrategicPlanRequest;
use App\Http\Requests\Org\SubmitStrategicPlanRequest;
use App\Models\SchoolYear;
use App\Models\StrategicPlanSubmission;
use App\Models\StrategicPlanProject;
use App\Models\StrategicPlanObjective;
use App\Models\StrategicPlanBeneficiary;
use App\Models\StrategicPlanDeliverable;
use App\Models\StrategicPlanPartner;
use App\Models\StrategicPlanFundSource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
        ['orgId' => $orgId, 'syId' => $syId, 'userId' => $userId] = $this->ctx($request);

        // Find or create draft (one per org + target sy)
        $submission = StrategicPlanSubmission::query()
            ->with([
                'projects.objectives',
                'projects.beneficiaries',
                'projects.deliverables',
                'projects.partners',
                'fundSources',
            ])
            ->where('organization_id', $orgId)
            ->where('target_school_year_id', $syId)
            ->first();

        if (! $submission) {
            $submission = StrategicPlanSubmission::create([
                'organization_id' => $orgId,
                'target_school_year_id' => $syId,
                'submitted_by_user_id' => $userId,
                'status' => StrategicPlanSubmission::STATUS_DRAFT,
                'org_name' => '', // required later; keep empty for now
            ]);
            $submission->load(['projects', 'fundSources']);
        }

        $schoolYear = SchoolYear::find($syId);

        return view('org.strategic_plan.edit', compact('submission', 'schoolYear'));
    }

    public function saveDraft(SaveDraftStrategicPlanRequest $request)
    {
        ['orgId' => $orgId, 'syId' => $syId, 'userId' => $userId] = $this->ctx($request);

        $validated = $request->validated();

        DB::transaction(function () use ($request, $validated, $orgId, $syId, $userId) {

            $submission = StrategicPlanSubmission::query()
                ->where('organization_id', $orgId)
                ->where('target_school_year_id', $syId)
                ->lockForUpdate()
                ->firstOrFail();

            // Prevent editing once submitted (basic guard)
            if ($submission->status !== StrategicPlanSubmission::STATUS_DRAFT
                && $submission->status !== StrategicPlanSubmission::STATUS_RETURNED_BY_MODERATOR
                && $submission->status !== StrategicPlanSubmission::STATUS_RETURNED_BY_SACDEV) {
                abort(403, 'This submission can no longer be edited.');
            }

            // Handle logo upload (optional)
            if ($request->hasFile('logo')) {
                $file = $request->file('logo');

                // store under: storage/app/public/strategic-plans/{orgId}/{syId}/logo_xxx.png
                $path = $file->store("public/strategic-plans/{$orgId}/{$syId}");

                // Delete old logo if exists
                if ($submission->logo_path && Storage::exists($submission->logo_path)) {
                    Storage::delete($submission->logo_path);
                }

                $submission->logo_path = $path;
                $submission->logo_original_name = $file->getClientOriginalName();
                $submission->logo_mime = $file->getMimeType();
                $submission->logo_size_bytes = $file->getSize();
            }

            // Update identity fields
            $submission->fill([
                'org_acronym' => $validated['org_acronym'] ?? null,
                'org_name'    => $validated['org_name'] ?? $submission->org_name,
                'mission'     => $validated['mission'] ?? null,
                'vision'      => $validated['vision'] ?? null,
            ]);

            $submission->status = StrategicPlanSubmission::STATUS_DRAFT; // keep draft on save
            $submission->save();

            // Replace projects snapshot for simplicity
            // (You can optimize later; snapshot replace is clean for v1)
            $this->syncProjects($submission, $validated['projects'] ?? []);

            // Replace fund sources snapshot
            $this->syncFundSources($submission, $validated['fund_sources'] ?? []);

            // Recompute totals from saved projects + sources
            $this->recomputeTotals($submission);
        });

        return redirect()
            ->route('org.strategic_plan.edit')
            ->with('success', 'Draft saved.');
    }

    public function submitToModerator(SubmitStrategicPlanRequest $request)
    {
        ['orgId' => $orgId, 'syId' => $syId] = $this->ctx($request);

        DB::transaction(function () use ($orgId, $syId) {
            $submission = StrategicPlanSubmission::query()
                ->where('organization_id', $orgId)
                ->where('target_school_year_id', $syId)
                ->lockForUpdate()
                ->firstOrFail();

            if ($submission->status !== StrategicPlanSubmission::STATUS_DRAFT
                && $submission->status !== StrategicPlanSubmission::STATUS_RETURNED_BY_MODERATOR
                && $submission->status !== StrategicPlanSubmission::STATUS_RETURNED_BY_SACDEV) {
                abort(403, 'This submission cannot be submitted.');
            }

            // Mark as submitted to moderator
            $submission->status = StrategicPlanSubmission::STATUS_SUBMITTED_TO_MODERATOR;
            $submission->submitted_to_moderator_at = now();
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
}
