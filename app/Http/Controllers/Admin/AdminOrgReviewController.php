<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OfficerEntry;
use App\Models\Organization;
use App\Models\OrgMembership;
use App\Models\Project;
use App\Models\ProjectAssignment;
use App\Models\SchoolYear;
use App\Models\User;
use Illuminate\Http\Request;

class AdminOrgReviewController extends Controller
{
    public function index()
    {
        return view('admin.review.index', [
            'organizations' => Organization::orderBy('name')->get(),
            'schoolYears' => SchoolYear::orderByDesc('id')->get(),
            'activeSy' => SchoolYear::activeYear(),
        ]);
    }

    public function show(Request $request)
    {
        $data = $request->validate([
            'organization_id' => ['required', 'integer', 'exists:organizations,id'],
            'school_year_id' => ['required', 'integer', 'exists:school_years,id'],
        ]);

        $orgId = (int) $data['organization_id'];
        $syId  = (int) $data['school_year_id'];

        $org = Organization::findOrFail($orgId);
        $sy  = SchoolYear::findOrFail($syId);

        // Officers
        $officers = OfficerEntry::query()
            ->where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->orderBy('full_name')
            ->get();

        // Projects
        $projects = Project::query()
            ->where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->orderBy('title')
            ->get();

        // Org roles: president/treasurer/moderator (active only)
        $memberships = OrgMembership::query()
            ->where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->whereNull('archived_at')
            ->whereIn('role', ['president', 'treasurer', 'moderator'])
            ->get();

        // Project heads
        $projectHeads = ProjectAssignment::query()
            ->whereIn('project_id', $projects->pluck('id'))
            ->where('assignment_role', 'project_head')
            ->whereNull('archived_at')
            ->get();

        // Users involved (for activation)
        $userIds = collect()
            ->merge($memberships->pluck('user_id'))
            ->merge($projectHeads->pluck('user_id'))
            ->unique()
            ->values();

        $users = User::query()
            ->whereIn('id', $userIds)
            ->get()
            ->keyBy('id');

        // Map memberships to display objects
        $roleRows = $memberships->map(function ($m) use ($users) {
            $u = $users->get($m->user_id);

            return [
                'role' => ucfirst($m->role),
                'name' => $u?->name ?? 'Unknown',
                'email' => $u?->email ?? '',
                'activated' => (bool)($u?->password_changed_at),
            ];
        })->sortBy('role')->values();

        // Map project heads to display objects
        $headRows = $projectHeads->map(function ($pa) use ($users, $projects) {
            $u = $users->get($pa->user_id);
            $project = $projects->firstWhere('id', $pa->project_id);

            return [
                'project' => $project?->title ?? 'Unknown Project',
                'name' => $u?->name ?? 'Unknown',
                'email' => $u?->email ?? '',
                'activated' => (bool)($u?->password_changed_at),
            ];
        })->sortBy('project')->values();

        return view('admin.review.show', compact(
            'org', 'sy', 'officers', 'projects', 'roleRows', 'headRows'
        ));
    }
}
