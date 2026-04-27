<?php

namespace App\Http\Middleware;

use App\Models\OrgMembership;
use App\Models\ProjectAssignment;
use App\Models\Project;
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

        if ($user->isSacdev()) {
            return $next($request);
        }

        $project = $request->route('project');

        if (!$project instanceof Project) {
            $project = Project::find($project);
        }

        if (!$project) {
            abort(403, 'Project not found.');
        }

        $orgId = (int) session('active_org_id');
        $syId  = (int) session('encode_sy_id');

        if (
            (int) $project->organization_id !== $orgId ||
            (int) $project->school_year_id !== $syId
        ) {
            abort(403, 'Invalid project context.');
        }

        $hasOrgRoleAccess = OrgMembership::query()
            ->where('user_id', $user->id)
            ->where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->whereNull('archived_at')
            ->whereIn('role', [
                'president',
                'treasurer',
                'moderator',
                'finance_officer',
            ])
            ->exists();

        $projectHeadAssignment = ProjectAssignment::query()
            ->where('project_id', $project->id)
            ->where('user_id', $user->id)
            ->where('assignment_role', 'project_head')
            ->whereNull('archived_at')
            ->first();

        $isProjectHead = (bool) $projectHeadAssignment;

        $isDraftee = ProjectAssignment::query()
            ->where('project_id', $project->id)
            ->where('user_id', $user->id)
            ->where('assignment_role', 'draftee')
            ->whereNull('archived_at')
            ->exists();

        if (!$hasOrgRoleAccess && !$isProjectHead && !$isDraftee) {
            abort(403, 'You are not authorized to access this project.');
        }

        if ($request->routeIs('org.projects.agreement.accept')) {
            return $next($request);
        }

        $needsAgreement = $projectHeadAssignment && !$projectHeadAssignment->agreement_accepted_at;

        $request->attributes->set('needs_project_agreement', $needsAgreement);

        return $next($request);
    }
}