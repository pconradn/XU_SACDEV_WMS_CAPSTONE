<?php

namespace App\Http\Middleware;

use App\Models\Project;
use App\Models\ProjectAssignment;
use Closure;
use Illuminate\Http\Request;

class RequireProjectRole
{
    public function handle(Request $request, Closure $next, string $role)
    {
        $user = $request->user();
        if (!$user) abort(403);

        // Admin bypass (optional)
        if ($user->system_role === 'sacdev_admin') {
            return $next($request);
        }

        /** @var Project|null $project */
        $project = $request->route('project');

        if (!$project instanceof Project) {
            abort(403, 'Project context required.');
        }

        $hasRole = ProjectAssignment::query()
            ->where('project_id', $project->id)
            ->where('user_id', $user->id)
            ->whereNull('archived_at')
            ->where('assignment_role', $role) // you used assignment_role = 'project_head'
            ->exists();

        if (!$hasRole) {
            abort(403, 'Not allowed for this project.');
        }

        return $next($request);
    }
}