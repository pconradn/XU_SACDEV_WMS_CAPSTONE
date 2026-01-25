<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use App\Models\OrgMembership;
use App\Models\Project;
use App\Models\ProjectAssignment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

//CONTROLLER TO VIEW ACTIVATION STATUS OF ACCOUNT (if password is changed)

class ActivationStatusController extends Controller
{
    public function index(Request $request)
    {
        $orgId = (int) $request->session()->get('active_org_id');
        $syId  = (int) $request->session()->get('encode_sy_id');

        
        $roleMemberships = OrgMembership::query()
            ->where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->whereNull('archived_at')
            ->whereIn('role', ['treasurer', 'moderator'])
            ->get();

      
        $projects = Project::query()
            ->where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->orderBy('title')
            ->get();

      
        $projectHeads = ProjectAssignment::query()
            ->whereIn('project_id', $projects->pluck('id'))
            ->where('assignment_role', 'project_head')
            ->whereNull('archived_at')
            ->get();

    
        $userIds = collect()
            ->merge($roleMemberships->pluck('user_id'))
            ->merge($projectHeads->pluck('user_id'))
            ->unique()
            ->values();

        $users = User::query()
            ->whereIn('id', $userIds)
            ->get()
            ->keyBy('id');

        $roleRows = $roleMemberships->map(function ($m) use ($users) {
            $u = $users->get($m->user_id);

            return [
                'type' => 'Org Role',
                'label' => ucfirst($m->role),
                'name' => $u?->name ?? 'Unknown',
                'email' => $u?->email ?? '',
                'activated' => (bool) ($u?->password_changed_at),
                'activated_at' => $u?->password_changed_at,
            ];
        });

        $projectRows = $projectHeads->map(function ($pa) use ($users, $projects) {
            $u = $users->get($pa->user_id);
            $project = $projects->firstWhere('id', $pa->project_id);

            return [
                'type' => 'Project Head',
                'label' => $project?->title ?? 'Unknown Project',
                'name' => $u?->name ?? 'Unknown',
                'email' => $u?->email ?? '',
                'activated' => (bool) ($u?->password_changed_at),
                'activated_at' => $u?->password_changed_at,
            ];
        });

        return view('org.activation-status.index', [
            'syId' => $syId,
            'roleRows' => $roleRows,
            'projectRows' => $projectRows,
        ]);
    }
}
