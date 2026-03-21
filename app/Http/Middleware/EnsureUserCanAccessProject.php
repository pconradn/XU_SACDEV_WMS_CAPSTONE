<?php

namespace App\Http\Middleware;

use App\Models\OrgMembership;
use App\Models\ProjectAssignment;
use Closure;
use Illuminate\Http\Request;

class EnsureUserCanAccessProject
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if (!$user) {
            abort(403);
        }

        // SACDEV admin always allowed
        if ($user->system_role === 'sacdev_admin') {
            return $next($request);
        }

        $project = $request->route('project');

        if (!$project instanceof \App\Models\Project) {
            $project = \App\Models\Project::find($project);
        }

        if (!$project) {
            abort(403, 'Project not found.');
        }

        if (!$project) {
            abort(403, 'Project not found.');
        }

        $orgId = (int) session('active_org_id');
        $syId  = (int) session('encode_sy_id');

        /*
        |--------------------------------------------------------------------------
        | Ensure project belongs to context
        |--------------------------------------------------------------------------
        */
        //dd($request->route('project'));

        if (
            (int) $project->organization_id !== $orgId ||
            (int) $project->school_year_id !== $syId
        ) {
            abort(403, 'Invalid project context.');
        }

        /*
        |--------------------------------------------------------------------------
        | Check Org Roles (President, Treasurer, Moderator)
        |--------------------------------------------------------------------------
        */

        $hasOrgRoleAccess = OrgMembership::query()
            ->where('user_id', $user->id)
            ->where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->whereNull('archived_at')
            ->whereIn('role', [
                'president',
                'treasurer',
                'moderator',
                'auditor',
            ])
            ->exists();

        /*
        |--------------------------------------------------------------------------
        | Check Project Head assignment
        |--------------------------------------------------------------------------
        */

        $isProjectHead = ProjectAssignment::query()
            ->where('project_id', $project->id)
            ->where('user_id', $user->id)
            ->where('assignment_role', 'project_head')
            ->whereNull('archived_at')
            ->exists();

        /*
        |--------------------------------------------------------------------------
        | Allow if ANY valid role
        |--------------------------------------------------------------------------
        */

        if (!$hasOrgRoleAccess && !$isProjectHead) {
            abort(403, 'You are not authorized to access this project.');
        }

        return $next($request);
    }
}