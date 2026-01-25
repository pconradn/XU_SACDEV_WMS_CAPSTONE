<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use App\Models\OrgMembership;
use App\Models\ProjectAssignment;
use App\Models\SchoolYear;
use Illuminate\Http\Request;

class OrgDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $activeSy = SchoolYear::activeYear();

        // Middleware already blocks if no active SY, but keep safe:
        if (!$activeSy) {
            return view('blocked.no-active-sy');
        }

        // Org memberships for ACTIVE SY only (multi-org allowed)
        $memberships = OrgMembership::query()
            ->with('organization')
            ->where('user_id', $user->id)
            ->where('school_year_id', $activeSy->id)
            ->whereNull('archived_at')
            ->get();

        // Pick current org from session, otherwise first org
        $sessionOrgId = (int) $request->session()->get('active_org_id', 0);

        $currentMembership = $memberships->firstWhere('organization_id', $sessionOrgId)
            ?? $memberships->first();

        if ($currentMembership) {
            $request->session()->put('active_org_id', $currentMembership->organization_id);
        } else {
            $request->session()->forget('active_org_id');
        }

        $currentOrg = $currentMembership?->organization;

        // Roles for the selected org (could be multiple roles if you allow)
        $roles = $currentOrg
            ? $memberships->where('organization_id', $currentOrg->id)->pluck('role')->unique()->values()
            : collect();

        // Project head assignments count in ACTIVE SY for selected org
        $projectHeadCount = 0;
        if ($currentOrg) {
            $projectHeadCount = ProjectAssignment::query()
                ->where('user_id', $user->id)
                ->whereNull('archived_at')
                ->where('assignment_role', 'project_head')
                ->whereHas('project', function ($q) use ($activeSy, $currentOrg) {
                    $q->where('school_year_id', $activeSy->id)
                      ->where('organization_id', $currentOrg->id);
                })
                ->count();
        }

        return view('portals.org-dashboard', [
            'activeSy' => $activeSy,
            'memberships' => $memberships,
            'currentOrg' => $currentOrg,
            'roles' => $roles,
            'projectHeadCount' => $projectHeadCount,
        ]);
    }

    public function switchOrg(Request $request)
    {
        $user = $request->user();
        $activeSyId = SchoolYear::activeId();

        $data = $request->validate([
            'organization_id' => ['required', 'integer'],
        ]);

        $orgId = (int) $data['organization_id'];

        // Security: ensure user actually has membership in that org for active SY
        $allowed = OrgMembership::query()
            ->where('user_id', $user->id)
            ->where('school_year_id', $activeSyId)
            ->where('organization_id', $orgId)
            ->whereNull('archived_at')
            ->exists();

        if (!$allowed) {
            return back()->with('status', 'You do not have access to that organization for the active school year.');
        }

        $request->session()->put('active_org_id', $orgId);

        session(['active_org_id' => $orgId]);

        return redirect()->route('org.home');
    }
}
