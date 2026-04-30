<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use App\Models\OrgMembership;
use App\Models\Project;
use App\Models\ProjectAssignment;
use App\Models\User;
use Illuminate\Http\Request;

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
            ->whereIn('role', [
                'treasurer',
                'finance_officer',
                'moderator',
            ])
            ->get();

        $projects = Project::query()
            ->where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->orderBy('title')
            ->get();

        $projectAssignments = ProjectAssignment::query()
            ->whereIn('project_id', $projects->pluck('id'))
            ->whereIn('assignment_role', [
                'project_head',
                'draftee',
            ])
            ->whereNull('archived_at')
            ->get();

        $userIds = collect()
            ->merge($roleMemberships->pluck('user_id'))
            ->merge($projectAssignments->pluck('user_id'))
            ->unique()
            ->values();

        $users = User::query()
            ->whereIn('id', $userIds)
            ->get()
            ->keyBy('id');

        $roleLabels = [
            'treasurer' => 'Treasurer',
            'finance_officer' => 'Finance Officer',
            'moderator' => 'Moderator',
        ];

        $assignmentLabels = [
            'project_head' => 'Project Head',
            'draftee' => 'Draftee',
        ];

        $roleRows = $roleMemberships->map(function ($m) use ($users, $roleLabels) {
            $u = $users->get($m->user_id);

            return [
                'type' => 'Org Role',
                'label' => $roleLabels[$m->role] ?? ucfirst(str_replace('_', ' ', $m->role)),
                'name' => $u?->name ?? 'Unknown',
                'email' => $u?->email ?? '',
                'activated' => (bool) ($u?->password_changed_at),
                'activated_at' => $u?->password_changed_at,
            ];
        });

        $projectRows = $projectAssignments->map(function ($pa) use ($users, $projects, $assignmentLabels) {
            $u = $users->get($pa->user_id);
            $project = $projects->firstWhere('id', $pa->project_id);

            return [
                'type' => $assignmentLabels[$pa->assignment_role] ?? ucfirst(str_replace('_', ' ', $pa->assignment_role)),
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