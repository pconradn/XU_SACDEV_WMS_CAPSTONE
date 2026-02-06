<?php

namespace App\Http\Controllers\Org;

use App\Models\User;
use App\Models\SchoolYear;
use Illuminate\Http\Request;
use App\Models\OrgMembership;
use App\Support\InAppNotifier;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\StrategicPlanPartner;
use App\Models\StrategicPlanProject;
use Illuminate\Http\RedirectResponse;
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
    /**
     * IMPORTANT:
     * We now use encode_sy_id as the target SY for re-registration
     * because the whole /org/rereg flow is based on that.
     *
     * So this controller should NOT rely on target_sy_id anymore.
     */
    private function ctx(Request $request): array
    {
        return [
            'orgId'    => (int) $request->session()->get('active_org_id'),
            'targetSy' => (int) $request->session()->get('encode_sy_id'), // SY being re-registered
            'userId'   => (int) $request->user()->id,
        ];
    }

    /**
     * Guard: user must have membership for org + targetSY (any role),
     * because dashboard allows selecting SY only if user has membership there.
     * (The routes already apply org.role:president, but this is a safe backend guard too.)
     */
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
                ->route('org.rereg.index') // or org.dashboard / org.orgs.index etc.
                ->with('error', 'No access to this organization for the selected school year.');
        }

        return null;
    }

    /**
     * Guard: allow edit only when status is editable.
     */
    private function assertEditableStatus(StrategicPlanSubmission $submission): ?RedirectResponse
    {
        $editable = in_array($submission->status, [
            StrategicPlanSubmission::STATUS_DRAFT,
            StrategicPlanSubmission::STATUS_RETURNED_BY_MODERATOR,
            StrategicPlanSubmission::STATUS_RETURNED_BY_SACDEV,
        ], true);

        if (! $editable) {
            return redirect()
                ->route('org.strategic-plans.show', $submission->id) // adjust route if needed
                ->with('error', 'This submission is currently under review and cannot be edited.');
        }

        return null;
    }

    /**
     * GET /org/rereg/b1/edit
     */
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

        // optional flag if your blade wants it
        $canEdit = in_array($submission->status, [
            StrategicPlanSubmission::STATUS_DRAFT,
            StrategicPlanSubmission::STATUS_RETURNED_BY_MODERATOR,
            StrategicPlanSubmission::STATUS_RETURNED_BY_SACDEV,
        ], true);

        return view('org.strategic_plan.edit', compact('submission', 'schoolYear', 'canEdit'));
    }

    /**
     * POST /org/rereg/b1/draft
     */
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

            // Handle logo upload (optional)
            if ($request->hasFile('logo')) {
                $file = $request->file('logo');

                // storage/app/public/strategic-plans/{orgId}/{targetSyId}/...
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
            ->where('school_year_id', $targetSyId) // adjust if your column name differs
            ->where('role', 'moderator')           // adjust to your constant if you have one
            ->first();

        return $membership?->user;
    }




    /**
     * POST /org/rereg/b1/submit
     */


    public function submitToModerator(SubmitStrategicPlanRequest $request)
    {
        ['orgId' => $orgId, 'targetSy' => $targetSyId] = $this->ctx($request);

        if ($orgId <= 0) {
            return redirect()->route('org.home')->with('error', 'Please select an organization first.');
        }

        if ($targetSyId <= 0) {
            return redirect()->route('org.rereg.index')->with('error', 'Please select a target school year first.');
        }

        // if your assertMembership returns ?RedirectResponse, use this:
        if ($resp = $this->assertMembership($request, $orgId, $targetSyId)) {
            return $resp;
        }

        $moderator = $this->moderatorForSy($orgId, $targetSyId);
        if (! $moderator) {
            return redirect()->route('org.rereg.index')
                ->with('error', 'No moderator assigned for this organization and school year.');
        }

        $submissionId = null;

        $resp = DB::transaction(function () use ($orgId, $targetSyId, &$submissionId, $moderator) {

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

            $submission->status = StrategicPlanSubmission::STATUS_SUBMITTED_TO_MODERATOR;
            $submission->submitted_to_moderator_at = now();

            // clear cycles...
            $submission->moderator_reviewed_by = null;
            $submission->moderator_reviewed_at = null;
            $submission->moderator_remarks = null;

            $submission->sacdev_reviewed_by = null;
            $submission->sacdev_reviewed_at = null;
            $submission->sacdev_remarks = null;
            $submission->approved_at = null;

            $submission->save();

            $submissionId = (int) $submission->getKey();

            DB::afterCommit(function () use ($orgId, $targetSyId, $submissionId, $moderator) {
                $dedupeKey = implode(':', [
                    'rereg','strategic_plan','submitted_to_moderator',
                    'org'.$orgId,'sy'.$targetSyId,'sub'.$submissionId,'to_user'.$moderator->getKey(),
                ]);

                InAppNotifier::notifyOnce($moderator, [
                    'dedupe_key'   => $dedupeKey,
                    'title'        => 'Strategic Plan submitted for review',
                    'message'      => 'A Strategic Plan was submitted to you. Please review and return or forward to SACDEV.',
                    'org_id'       => $orgId,
                    'target_sy_id' => $targetSyId,
                    'form'         => 'strategic_plan',
                    'status'       => 'submitted_to_moderator',
                    'action_url'   => route('org.moderator.strategic_plans.show', $submissionId),
                    'meta'         => ['submission_id' => $submissionId],
                ]);
            });

            return null; // success
        });

        // IMPORTANT: if transaction returned a redirect, return it now
        if ($resp instanceof RedirectResponse) {
            return $resp;
        }

        return redirect()
            ->route('org.rereg.b1.edit')
            ->with('success', 'Submitted to moderator for review.');
    }


    /**
     * ---- helpers ----
     */

    private function syncProjects(StrategicPlanSubmission $submission, array $projectsPayload): void
    {
        // Delete existing deep children first
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

    /**
     * LEGACY methods kept so your routes won't break if they still exist,
     * but in the new flow, target SY is encode_sy_id set from dashboard.
     * You can delete these later once you remove the routes.
     */
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
