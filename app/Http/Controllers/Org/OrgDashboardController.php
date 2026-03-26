<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use App\Models\OrgMembership;
use App\Models\ProjectAssignment;
use App\Models\ProjectDocument;
use App\Models\Project;
use App\Models\SchoolYear;
use Illuminate\Http\Request;

class OrgDashboardController extends Controller
{
    private function selectedSyId(Request $request): ?int
    {
        $encodeSyId = (int) $request->session()->get('encode_sy_id', 0);
        return $encodeSyId > 0 ? $encodeSyId : SchoolYear::activeId();
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


        $pendingTasks = collect();

        if ($currentOrg) {
            $pendingTasks = ProjectDocument::with(['project', 'signatures'])
                ->whereHas('project', function ($q) use ($selectedSyId, $currentOrg) {
                    $q->where('school_year_id', $selectedSyId)
                      ->where('organization_id', $currentOrg->id);
                })
                ->get()
                ->filter(function ($doc) use ($user) {
                    $pending = $doc->currentPendingSignature();
                    return $pending && $pending->user_id === $user->id;
                })
                ->values();
        }

   
        $assignedProjects = collect();

        if ($currentOrg) {
            $assignedProjects = ProjectAssignment::with('project')
                ->where('user_id', $user->id)
                ->whereNull('archived_at')
                ->whereHas('project', function ($q) use ($selectedSyId, $currentOrg) {
                    $q->where('school_year_id', $selectedSyId)
                      ->where('organization_id', $currentOrg->id);
                })
                ->get()
                ->pluck('project')
                ->unique('id')
                ->values();
        }

  
        $pendingCount = $pendingTasks->count();

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

    
        return view('portals.org-dashboard', [
            'activeSy' => $activeSy,
            'selectedSy' => $selectedSy,
            'memberships' => $memberships,
            'currentOrg' => $currentOrg,
            'roles' => $roles,
            'projectHeadCount' => $projectHeadCount,

            'pendingTasks' => $pendingTasks,
            'assignedProjects' => $assignedProjects,
            'pendingCount' => $pendingCount,
            'projectCount' => $projectCount,
            'documentCount' => $documentCount,
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