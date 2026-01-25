<?php

namespace App\Http\Middleware;

use App\Models\OrgMembership;
use App\Models\ProjectAssignment;
use App\Models\SchoolYear;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireActiveSYAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

       
        if ($user->system_role === 'sacdev_admin') {
            return $next($request);
        }

        $activeSyId = SchoolYear::activeId();

        // if there is no active SY yet, block org portal access
        if (!$activeSyId) {
            return response()->view('blocked.no-active-sy', [], 403);
        }

        $hasActiveMembership = OrgMembership::query()
            ->where('user_id', $user->id)
            ->where('school_year_id', $activeSyId)
            ->whereNull('archived_at')
            ->exists();

        if ($hasActiveMembership) {
            return $next($request);
        }

        $hasActiveAssignment = ProjectAssignment::query()
            ->where('user_id', $user->id)
            ->whereNull('archived_at')
            ->whereHas('project', function ($q) use ($activeSyId) {
                $q->where('school_year_id', $activeSyId);
            })
            ->exists();

        if ($hasActiveAssignment) {
            return $next($request);
        }

        //no access screen
        return response()->view('blocked.no-access', [], 403);
    }
}
