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

        
        if ($user->isSacdev()) {
            return $next($request);
        }

        /** @var Project|null $project */
        $projectParam = $request->route('project');

        if ($projectParam instanceof Project) {
            $project = $projectParam;
        } else {
            if (!is_numeric($projectParam)) {
                abort(403, 'Project context required.');
            }

            $project = Project::find((int) $projectParam);
        }

        if (!$project) {
            abort(403, 'Project not found.');
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
            return redirect()
                ->route('org.projects.documents.hub', $project)
                ->with('error', 'You are not allowed to perform this action.');
        }

        return $next($request);
    }


    

}