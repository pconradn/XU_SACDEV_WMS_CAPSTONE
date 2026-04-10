<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use App\Models\OrgMembership;
use App\Models\ProjectAssignment;
use App\Models\ProjectDocument;
use App\Models\Project;
use App\Models\SchoolYear;
use Illuminate\Http\Request;
use App\Services\ProjectFormRequirementResolver;
use App\Services\ProjectFormRouteResolver;

use App\Models\StrategicPlanSubmission;
use App\Models\OfficerSubmission;
use App\Models\PresidentRegistration;
use App\Models\OrgConstitutionSubmission;
use App\Models\ModeratorSubmission;

class OrgDashboardController extends Controller
{
    private function selectedSyId(Request $request): ?int
    {
        $encodeSyId = (int) $request->session()->get('encode_sy_id', 0);
        return $encodeSyId > 0 ? $encodeSyId : SchoolYear::activeId();
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

    public function index(Request $request)
    {
        $user = $request->user();

        $activeSy = SchoolYear::activeYear();
        $selectedSyId = $this->selectedSyId($request);

        if (!$selectedSyId) {
            return view('blocked.no-active-sy');
        }

        $selectedSy = SchoolYear::find($selectedSyId);

        $memberships = OrgMembership::query()
            ->with('organization')
            ->where('user_id', $user->id)
            ->where('school_year_id', $selectedSyId)
            ->whereNull('archived_at')
            ->get();

        $sessionOrgId = (int) $request->session()->get('active_org_id', 0);

        $currentMembership = $memberships->firstWhere('organization_id', $sessionOrgId)
            ?? $memberships->first();

        if ($currentMembership) {
            $request->session()->put('active_org_id', $currentMembership->organization_id);
        } else {
            $request->session()->forget('active_org_id');
        }

        $currentOrg = $currentMembership?->organization;

        $roles = $currentOrg
            ? $memberships->where('organization_id', $currentOrg->id)->pluck('role')->unique()->values()
            : collect();

        $resolver = app(ProjectFormRequirementResolver::class);


        $projectHeadCount = 0;

        if ($currentOrg) {
            $projectHeadCount = ProjectAssignment::query()
                ->where('user_id', $user->id)
                ->whereNull('archived_at')
                ->where('assignment_role', 'project_head')
                ->whereHas('project', function ($q) use ($selectedSyId, $currentOrg) {
                    $q->where('school_year_id', $selectedSyId)
                        ->where('organization_id', $currentOrg->id);
                })
                ->count();
        }

  
        $approvalTasks = collect();

        if ($currentOrg) {

                $approvalTasks = ProjectDocument::with([
                    'project',
                    'formType',
                    'signatures.user',

                ])
                ->whereHas('project', function ($q) use ($selectedSyId, $currentOrg) {
                    $q->where('school_year_id', $selectedSyId)
                        ->where('organization_id', $currentOrg->id);
                })
                ->get()
                ->filter(function ($doc) use ($user) {
                    $pending = $doc->currentPendingSignature();

                    return $pending
                        && $pending->user_id === $user->id
                        && $pending->role !== 'project_head';
                })
                ->values();



            $approvalTasks = $approvalTasks->map(function ($task) {
                $task->category = 'approval';
                return $task;
            });
        }


        $assignedProjects = collect();

        if ($currentOrg) {
            $assignedProjects = ProjectAssignment::query()
                ->with([
                    'project' => function ($q) {
                        $q->with([
                            'documents.formType',
                            'documents.signatures.user',
                        ]);
                    },
                ])
                ->where('user_id', $user->id)
                ->whereNull('archived_at')
                ->where('assignment_role', 'project_head')
                ->whereHas('project', function ($q) use ($selectedSyId, $currentOrg) {
                    $q->where('school_year_id', $selectedSyId)
                        ->where('organization_id', $currentOrg->id);
                })
                ->get()
                ->pluck('project')
                ->unique('id')
                ->values();
        }

        $isModerator = $roles->contains('moderator');
        $isPresident = !$isModerator && $roles->contains('president');



        $reregTasks = collect();
        $projectHeadTasks = collect();


        $orgId = $currentOrg->id;
        $syId = $selectedSyId;

        $sp = StrategicPlanSubmission::where('organization_id', $orgId)
            ->where('target_school_year_id', $syId)
            ->first();

        $officers = OfficerSubmission::where('organization_id', $orgId)
            ->where('target_school_year_id', $syId)
            ->first();

        /*
        |--------------------------------------------------------------------------
        | B1 Strategic Plan
        |--------------------------------------------------------------------------
        */
        if (!$sp || in_array($sp->status, ['draft', 'returned_by_moderator', 'returned_by_sacdev'])) {
            $reregTasks->push((object)[
                'category' => 'rereg',
                'state' => 'required',
                'form_name' => 'Strategic Plan',
                'status' => $sp->status ?? 'not_started',
                'link' => route('org.rereg.b1.edit'),
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | B3 Officers
        |--------------------------------------------------------------------------
        */
        if (!$officers || in_array($officers->status, ['draft', 'returned'])) {
            $reregTasks->push((object)[
                'category' => 'rereg',
                'state' => 'required',
                'form_name' => 'Officers List',
                'status' => $officers->status ?? 'not_started',
                'link' => route('org.rereg.b3.officers-list.edit'),
            ]);
        }



        

        if ($currentOrg) {
            foreach ($assignedProjects as $project) {
                $requiredForms = $resolver->resolve($project);


                $project->resolved_forms = $requiredForms;

                if (
                    $project->requires_clearance == 1 &&
                    in_array($project->clearance_status, ['required', 'rejected'])
                ) {
                    $projectHeadTasks->push((object) [
                        'category'   => 'project_head',
                        'state'      => $project->clearance_status === 'rejected' ? 'revision' : 'required',
                        'phase'      => 'off-campus',
                        'form_name'  => 'Off-Campus Clearance',
                        'project'    => $project,
                        'status'     => $project->clearance_status,
                        'form_type_id' => null,
                        'form_code'  => 'OFF_CAMPUS_CLEARANCE',
                    ]);
                }





                foreach ($requiredForms as $req) {

                    $doc = null;

                    $doc = $this->findDocument($project, $req);

                    if (!$doc || $doc->status !== 'approved_by_sacdev') {

                        $status = $doc?->status ?? 'not_started';

                        $type = 'required';

                        if ($status === 'returned') {
                            $type = 'revision';
                        }

                        $projectHeadTasks->push((object) [
                            'category' => 'project_head',
                            'state' => $type,
                            'phase' => $req->phase ?? 'other',
                            'form_name' => $req->name ?? $req->code,
                            'project' => $project,
                            'status' => $status,
                            'form_type_id' => $req->id,
                            'form_code' => $req->code,
                        ]);



                    }
                }
            }
        }


        if ($currentOrg) {

            $orgId = $currentOrg->id;
            $syId = $selectedSyId;

            $hasModerator = OrgMembership::where('organization_id', $orgId)
                ->where('school_year_id', $syId)
                ->where('role', 'moderator')
                ->whereNull('archived_at')
                ->exists();

            $sp = StrategicPlanSubmission::where('organization_id', $orgId)
                ->where('target_school_year_id', $syId)
                ->first();

            $officers = OfficerSubmission::where('organization_id', $orgId)
                ->where('target_school_year_id', $syId)
                ->first();

            $pres = PresidentRegistration::where('organization_id', $orgId)
                ->where('target_school_year_id', $syId)
                ->first();

            $consti = OrgConstitutionSubmission::where('organization_id', $orgId)
                ->where('school_year_id', $syId)
                ->first();

   

 
        }      

 
        $assignedProjects = $assignedProjects->map(function ($project) use ($resolver) {
            $requiredForms = $project->resolved_forms;

            $pendingRequiredCount = 0;

            foreach ($requiredForms as $req) {
                $doc = null;

                $doc = $this->findDocument($project, $req);

                if (!$doc || $doc->status !== 'approved_by_sacdev') {
                    $pendingRequiredCount++;
                }
            }

            $project->pending_required_count = $pendingRequiredCount;

            return $project;
        })->values();


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

                if (!$code) {
                    return $task;
                }

                if ($code === 'PROJECT_PROPOSAL') {

                    $task->link = route('org.projects.documents.combined-proposal.create', [
                        'project' => $task->project->id,
                    ]);

                } else {

                    // fallback to resolver
                    $task->link = ProjectFormRouteResolver::resolve($task);
                }
            
            }

            return $task;
        });
        
        
        
        $pendingApprovalCount = $approvalTasks->count();
        $projectHeadPendingCount = $projectHeadTasks->count();
        $reregCount = $reregTasks->count();

        $pendingCount = $pendingApprovalCount + $projectHeadPendingCount + $reregCount;


        $projectCount = 0;
        $documentCount = 0;

        if ($currentOrg) {
            $projectCount = Project::where('organization_id', $currentOrg->id)
                ->where('school_year_id', $selectedSyId)
                ->count();

            $documentCount = ProjectDocument::whereHas('project', function ($q) use ($selectedSyId, $currentOrg) {
                    $q->where('school_year_id', $selectedSyId)
                        ->where('organization_id', $currentOrg->id);
                })
                ->count();
        }

        $projectsWithoutHeadCount = 0;

        if ($currentOrg) {
            $projectsWithoutHeadCount = Project::query()
                ->where('organization_id', $currentOrg->id)
                ->where('school_year_id', $selectedSyId)
                ->whereDoesntHave('assignments', function ($q) {
                    $q->whereNull('archived_at')
                    ->where('assignment_role', 'project_head');
                })
                ->count();
        }

        return view('portals.org-dashboard', [
            'activeSy' => $activeSy,
            'selectedSy' => $selectedSy,
            'memberships' => $memberships,
            'currentOrg' => $currentOrg,
            'roles' => $roles,
            'projectHeadCount' => $projectHeadCount,

            // blade expects these
            'pendingTasks' => $pendingTasks,
            'assignedProjects' => $assignedProjects,
            'pendingCount' => $pendingCount,
            'pendingApprovalCount' => $pendingApprovalCount,
            'projectHeadPendingCount' => $projectHeadPendingCount,

            'projectCount' => $projectCount,
            'documentCount' => $documentCount,

            'projectsWithoutHeadCount' => $projectsWithoutHeadCount,
        ]);
    }

    public function switchOrg(Request $request)
    {
        $user = $request->user();
        $selectedSyId = $this->selectedSyId($request);

        $data = $request->validate([
            'organization_id' => ['required', 'integer'],
        ]);

        $orgId = (int) $data['organization_id'];

        $allowed = OrgMembership::query()
            ->where('user_id', $user->id)
            ->where('school_year_id', $selectedSyId)
            ->where('organization_id', $orgId)
            ->whereNull('archived_at')
            ->exists();

        if (!$allowed) {
            return back()->with('status', 'You do not have access to that organization for the selected school year.');
        }

        $request->session()->put('active_org_id', $orgId);

        return redirect()->route('org.home');
    }
}