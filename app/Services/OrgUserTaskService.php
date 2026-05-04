<?php

namespace App\Services;

use App\Models\OrgMembership;
use App\Models\ProjectAssignment;
use App\Models\ProjectDocument;
use App\Models\Project;
use App\Models\SchoolYear;

use App\Models\StrategicPlanSubmission;
use App\Models\OfficerSubmission;
use App\Models\PresidentRegistration;
use App\Models\OrgConstitutionSubmission;
use App\Models\ModeratorSubmission;

use App\Services\ProjectFormRequirementResolver;
use App\Services\ProjectFormRouteResolver;

class OrgUserTaskService
{
    public function getTasks($user, $orgId, $syId)
    {
        $memberships = OrgMembership::query()
            ->where('user_id', $user->id)
            ->where('school_year_id', $syId)
            ->whereNull('archived_at')
            ->get();

        $currentMembership = $memberships->firstWhere('organization_id', $orgId)
            ?? $memberships->first();

        $currentOrg = $currentMembership?->organization;

        $roles = $currentOrg
            ? $memberships->where('organization_id', $currentOrg->id)->pluck('role')->unique()->values()
            : collect();

        $resolver = app(ProjectFormRequirementResolver::class);

        $approvalTasks = collect();
        $projectHeadTasks = collect();
        $reregTasks = collect();

        if ($currentOrg) {
            $orgId = $currentOrg->id;
            $syId = $syId;

            // ================= APPROVAL =================
            $approvalTasks = ProjectDocument::with([
                    'project',
                    'formType',
                    'signatures.user',
                ])
                ->whereHas('project', function ($q) use ($syId, $currentOrg) {
                    $q->where('school_year_id', $syId)
                      ->where('organization_id', $currentOrg->id);
                })
                ->get()
                ->filter(function ($doc) use ($user) {
                    $pending = $doc->currentPendingSignature();

                    return $pending
                        && $pending->user_id === $user->id
                        && $pending->role !== 'project_head';
                })
                ->map(function ($task) {
                    $task->category = 'approval';
                    return $task;
                })
                ->values();

            // ================= PROJECT HEAD =================
            $assignedProjects = ProjectAssignment::query()
                ->with([
                    'project.documents.formType',
                    'project.documents.signatures.user',
                ])
                ->where('user_id', $user->id)
                ->whereNull('archived_at')
                ->where('assignment_role', 'project_head')
                ->whereHas('project', function ($q) use ($syId, $currentOrg) {
                    $q->where('school_year_id', $syId)
                      ->where('organization_id', $currentOrg->id);
                })
                ->get()
                ->pluck('project')
                ->unique('id')
                ->values();

            foreach ($assignedProjects as $project) {
                $requiredForms = $resolver->resolve($project);





                if (
                    (int) $project->requires_clearance === 1 &&
                    (
                        empty($project->clearance_file_path)
                        || $project->clearance_status === 'rejected'
                    )
                ) {
                    $projectHeadTasks->push((object)[
                        'category'     => 'project_head',
                        'state'        => $project->clearance_status === 'rejected' ? 'revision' : 'required',
                        'phase'        => 'off-campus',
                        'form_name'    => 'Off-Campus Clearance',
                        'project'      => $project,
                        'status'       => $project->clearance_status,
                        'form_type_id' => null,
                        'form_code'    => 'OFF_CAMPUS_CLEARANCE',
                    ]);
                }

                foreach ($requiredForms as $req) {

                    $phase = $req->phase ?? 'other';

                    if (
                        $phase === 'post_implementation' &&
                        $project->implementation_start_date &&
                        now()->startOfDay()->lt(\Carbon\Carbon::parse($project->implementation_start_date)->startOfDay())
                    ) {
                        continue;
                    }

                    $doc = $this->findDocument($project, $req);

                    $status = $doc?->status ?? 'not_started';

                    if (!$doc || $status === 'draft') {
                        $hasReturnRemarks = $doc && filled($doc->remarks ?? null);

                        $projectHeadTasks->push((object)[
                            'category' => 'project_head',
                            'state' => $hasReturnRemarks ? 'revision' : 'required',
                            'phase' => $req->phase ?? 'other',
                            'form_name' => $req->name ?? $req->code,
                            'project' => $project,
                            'status' => $status,
                            'form_type_id' => $req->id,
                            'form_code' => $req->code,
                            'remarks' => $doc?->remarks,
                        ]);
                    }
                }
            }

            // ================= REREG =================
            $isModerator = $roles->contains('moderator');
            $isPresident = !$isModerator && $roles->contains('president');

            $sp = StrategicPlanSubmission::where('organization_id', $orgId)
                ->where('target_school_year_id', $syId)
                ->first();

            $officers = OfficerSubmission::where('organization_id', $orgId)
                ->where('target_school_year_id', $syId)
                ->first();

            $constitution = OrgConstitutionSubmission::where('organization_id', $orgId)
                ->where('school_year_id', $syId)
                ->latest()
                ->first();

            $moderatorSubmission = ModeratorSubmission::where('organization_id', $orgId)
                ->where('target_school_year_id', $syId)
                ->first();

            if ($isModerator && !$moderatorSubmission) {
                $reregTasks->push((object)[
                    'category' => 'rereg',
                    'state' => 'required',
                    'form_name' => 'Submit Org Moderator Details',
                    'status' => 'not_submitted',
                    'link' => route('org.rereg.moderator.edit'),
                ]);
            }

            $hasModerator = OrgMembership::where('organization_id', $orgId)
                ->where('school_year_id', $syId)
                ->where('role', 'moderator')
                ->whereNull('archived_at')
                ->exists();

            $presidentMembership = OrgMembership::where('organization_id', $orgId)
                ->where('school_year_id', $syId)
                ->where('role', 'president')
                ->whereNull('archived_at')
                ->with('user.profile')
                ->first();

            $presProfile = $presidentMembership?->user?->profile;

            $isPresidentProfileComplete =
                $presProfile
                && $presProfile->first_name
                && $presProfile->last_name
                && $presProfile->birthday
                && $presProfile->sex
                && $presProfile->mobile_number
                && $presProfile->email
                && $presProfile->home_address
                && $presProfile->city_address;

            if ($isPresident && !$isPresidentProfileComplete) {
                $reregTasks->push((object)[
                    'category' => 'rereg',
                    'state' => 'required',
                    'form_name' => 'Complete President Profile',
                    'status' => 'incomplete',
                    'link' => route('org.profile.edit'),
                ]);
            }

            if ($isPresident && !$hasModerator) {
                $reregTasks->push((object)[
                    'category' => 'rereg',
                    'state' => 'required',
                    'form_name' => 'Assign Moderator',
                    'status' => 'not_assigned',
                    'link' => route('org.rereg.assign.moderator.edit'),
                ]);
            }

            if ($isModerator && $sp && $sp->status === 'submitted_to_moderator') {
                $reregTasks->push((object)[
                    'category' => 'rereg',
                    'state' => 'required',
                    'form_name' => 'Review Strategic Plan',
                    'status' => $sp->status,
                    'link' => route('org.rereg.b1.edit'),
                ]);
            }

            $moderatorMembership = OrgMembership::where('organization_id', $orgId)
                ->where('school_year_id', $syId)
                ->where('role', 'moderator')
                ->whereNull('archived_at')
                ->with('user.profile')
                ->first();

            $modProfile = $moderatorMembership?->user?->profile;

            $isModeratorProfileComplete =
                $modProfile
                && $modProfile->first_name
                && $modProfile->last_name
                && $modProfile->birthday
                && $modProfile->sex
                && $modProfile->mobile_number
                && $modProfile->email
                && $modProfile->home_address
                && $modProfile->city_address;

            if ($isModerator && !$isModeratorProfileComplete) {
                $reregTasks->push((object)[
                    'category' => 'rereg',
                    'state' => 'required',
                    'form_name' => 'Complete Moderator Profile',
                    'status' => 'incomplete',
                    'link' => route('org.profile.edit'),
                ]);
            }

            if ($isPresident && (!$sp || in_array($sp->status, ['draft', 'returned_by_moderator', 'returned_by_sacdev']))) {
                $reregTasks->push((object)[
                    'category' => 'rereg',
                    'state' => 'required',
                    'form_name' => 'Strategic Plan',
                    'status' => $sp->status ?? 'not_started',
                    'link' => route('org.rereg.b1.edit'),
                ]);
            }

            if ($isPresident && (!$officers || in_array($officers->status, ['draft', 'returned_by_sacdev']))) {
                $reregTasks->push((object)[
                    'category' => 'rereg',
                    'state' => 'required',
                    'form_name' => 'Officers List',
                    'status' => $officers->status ?? 'not_started',
                    'link' => route('org.rereg.b3.officers-list.edit'),
                ]);
            }

            if ($isPresident && (!$constitution || in_array($constitution->status, ['returned', 'draft']))) {
                $reregTasks->push((object)[
                    'category' => 'rereg',
                    'state' => 'required',
                    'form_name' => 'Organization Constitution',
                    'status' => $constitution->status ?? 'not_started',
                    'link' => route('org.rereg.index'),
                ]);
            }



        }

        $pendingTasks = collect()
            ->merge($approvalTasks)
            ->merge($projectHeadTasks)
            ->merge($reregTasks)
            ->sortBy(function ($task) {

                $order = [
                    'pre_implementation',
                    'off-campus',
                    'other',
                    'post_implementation',
                    'notice',
                ];

                $phase = $task->phase
                    ?? $task->formType->phase
                    ?? 'other';

                return array_search($phase, $order) !== false
                    ? array_search($phase, $order)
                    : 999;
            })
            ->values();

        $pendingTasks = $pendingTasks->map(function ($task) {

            if (!isset($task->link)) {

                $code = $task->formType->code ?? $task->form_code ?? null;

                if (!$code) return $task;

                if ($code === 'PROJECT_PROPOSAL') {
                    $task->link = route('org.projects.documents.combined-proposal.create', [
                        'project' => $task->project->id,
                    ]);
                } else {
                    $task->link = ProjectFormRouteResolver::resolve($task);
                }
            }

            return $task;
        });

        return [
            'tasks' => $pendingTasks,
            'count' => $pendingTasks->count(),
            'approval_count' => $approvalTasks->count(),
            'project_head_count' => $projectHeadTasks->count(),
            'rereg_count' => $reregTasks->count(),
            'roles' => $roles,
        ];
    }

    private function findDocument($project, $req)
    {
        if (!empty($req->id)) {
            return $project->documents
                ->first(fn ($d) => (int) $d->form_type_id === (int) $req->id);
        }

        if (!empty($req->code)) {
            return $project->documents
                ->first(fn ($d) => $d->formType?->code === $req->code);
        }

        return null;
    }
}