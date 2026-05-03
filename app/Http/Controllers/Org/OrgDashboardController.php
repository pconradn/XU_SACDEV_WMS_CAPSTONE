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

use App\Services\OrgUserTaskService;

class OrgDashboardController extends Controller
{
    private function selectedSyId(Request $request): ?int
    {
        $encodeSyId = (int) $request->session()->get('encode_sy_id', 0);
        return $encodeSyId > 0 ? $encodeSyId : SchoolYear::activeId();
    }

    public function pendingTasksPartial(Request $request)
    {
        $user = $request->user();

        $selectedSyId = $this->selectedSyId($request);

        $memberships = OrgMembership::query()
            ->where('user_id', $user->id)
            ->where('school_year_id', $selectedSyId)
            ->whereNull('archived_at')
            ->get();

        $currentOrgId = (int) $request->session()->get('active_org_id', 0);

        $currentMembership = $memberships->firstWhere('organization_id', $currentOrgId)
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


        $service = app(OrgUserTaskService::class);

        $data = $service->getTasks(
            $user,
            $currentOrg?->id,
            $selectedSyId
        );

        

        $pendingTasks = $this->filterPostImplementationTasks($data['tasks']);

     

        $pendingCount = $pendingTasks->count();
        $roles = $data['roles'];

        $pendingApprovalCount = $pendingTasks
            ->where('category', 'approval')
            ->count();

        $projectHeadPendingCount = $pendingTasks
            ->where('category', '!=', 'rereg')
            ->where('category', '!=', 'approval')
            ->count();

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

        return view('portals.partials._org_dashboard_pending_tasks', compact(
            'pendingTasks',
            'pendingCount',
            'roles',
            'projectsWithoutHeadCount',
            'projectHeadPendingCount',
            'pendingApprovalCount',
        ));
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

        // ================= PROJECT COUNTS =================
        $projectCount = 0;
        $documentCount = 0;
        $projectsWithoutHeadCount = 0;
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


            $projectCount = Project::where('organization_id', $currentOrg->id)
                ->where('school_year_id', $selectedSyId)
                ->count();

            $documentCount = ProjectDocument::whereHas('project', function ($q) use ($selectedSyId, $currentOrg) {
                    $q->where('school_year_id', $selectedSyId)
                    ->where('organization_id', $currentOrg->id);
                })
                ->count();

            $projectsWithoutHeadCount = Project::query()
                ->where('organization_id', $currentOrg->id)
                ->where('school_year_id', $selectedSyId)
                ->whereDoesntHave('assignments', function ($q) {
                    $q->whereNull('archived_at')
                    ->where('assignment_role', 'project_head');
                })
                ->count();
        }

        // ================= ASSIGNED PROJECTS (KEEP) =================
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
                ->whereIn('assignment_role', ['project_head', 'draftee'])
                ->whereHas('project', function ($q) use ($selectedSyId, $currentOrg) {
                    $q->where('school_year_id', $selectedSyId)
                    ->where('organization_id', $currentOrg->id);
                })
                ->get()
                ->pluck('project')
                ->unique('id')
                ->values();
        }

        // ================= SERVICE =================
        $service = app(OrgUserTaskService::class);

        $data = $service->getTasks(
            $user,
            $currentOrg?->id,
            $selectedSyId
        );

        $pendingTasks = $this->filterPostImplementationTasks($data['tasks']);


        $pendingCount = $pendingTasks->count();

        $pendingApprovalCount = $pendingTasks
            ->where('category', 'approval')
            ->count();

        $projectHeadPendingCount = $pendingTasks
            ->where('category', '!=', 'rereg')
            ->where('category', '!=', 'approval')
            ->count();



        return view('portals.org-dashboard', [
            'activeSy' => $activeSy,
            'selectedSy' => $selectedSy,
            'memberships' => $memberships,
            'currentOrg' => $currentOrg,
            'roles' => $roles,

            'pendingTasks' => $pendingTasks,
            'pendingCount' => $pendingCount,
            'pendingApprovalCount' => $pendingApprovalCount,
            'projectHeadPendingCount' => $projectHeadPendingCount,

            'assignedProjects' => $assignedProjects,
            'projectHeadCount' => $projectHeadCount,

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

    private function filterPostImplementationTasks($pendingTasks)
    {
    return collect($pendingTasks)->filter(function ($task) {
        $project = $task->project ?? null;
        $document = $task->document ?? null;

        if (!$project) {
            return true;
        }

        $project->loadMissing('documents.formType');

        if ($document) {
            $document->loadMissing('formType');
        }

        $taskFormTypeId = $task->form_type_id
            ?? $task->formType?->id
            ?? $document?->form_type_id
            ?? null;

        $taskFormTypeCode = $task->formType?->code
            ?? $document?->formType?->code
            ?? null;

        $taskFormName = $task->formType?->name
            ?? $task->form_name
            ?? $document?->formType?->name
            ?? null;

        $matchingDocument = $project->documents
            ?->first(function ($doc) use ($taskFormTypeId, $taskFormTypeCode, $taskFormName) {
                if (!$doc->formType) {
                    return false;
                }

                if ($taskFormTypeId && (int) $doc->form_type_id === (int) $taskFormTypeId) {
                    return true;
                }

                if ($taskFormTypeCode && $doc->formType->code === $taskFormTypeCode) {
                    return true;
                }

                if ($taskFormName && $doc->formType->name === $taskFormName) {
                    return true;
                }

                return false;
            });

        $formPhase = $task->formType->phase
            ?? $document?->formType?->phase
            ?? $matchingDocument?->formType?->phase
            ?? null;



        if ($formPhase !== 'post_implementation') {
            return true;
        }

        $projectProposal = $project->documents
            ?->first(function ($doc) {
                return (int) $doc->is_active === 1
                    && $doc->formType?->code === 'PROJECT_PROPOSAL';
            });

            

        return $projectProposal?->status === 'approved_by_sacdev';
    })->values();
    }


}