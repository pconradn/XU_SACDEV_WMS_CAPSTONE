<?php

namespace App\Http\Middleware;

use App\Models\Project;
use App\Models\ProjectAssignment;
use App\Models\OrgMembership;
use Closure;
use Illuminate\Http\Request;

class RequireProjectRole
{
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        $user = $request->user();
        if (!$user) abort(403);

        
        if ($user->system_role === 'sacdev_admin') {
            return $next($request);
        }

        /** @var Project|null $project */
        $project = $request->route('project');

        if (!$project instanceof Project) {
            abort(403, 'Project context required.');
        }

        $orgId = $project->organization_id;
        $syId  = $project->school_year_id;

  

        $hasProjectAssignment = ProjectAssignment::query()
            ->where('project_id', $project->id)
            ->where('user_id', $user->id)
            ->whereNull('archived_at')
            ->whereIn('assignment_role', $roles)
            ->exists();


        $hasOrgRole = OrgMembership::query()
            ->where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->where('user_id', $user->id)
            ->whereNull('archived_at')
            ->whereIn('role', $roles)
            ->exists();

            //dd($roles);
            

        if (!($hasProjectAssignment || $hasOrgRole)) {
            abort(403, 'Not allowed for this project.');
        }

        return $next($request);
    }
}