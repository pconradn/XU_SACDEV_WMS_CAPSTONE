<?php

namespace App\Http\Middleware;

use App\Models\OrgMembership;
use App\Models\ProjectAssignment;
use App\Models\SchoolYear;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireOperationalAccess
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

        $hasActiveMembership = $activeSyId
            ? OrgMembership::query()
                ->where('user_id', $user->id)
                ->where('school_year_id', $activeSyId)
                ->whereNull('archived_at')
                ->exists()
            : false;

        if ($hasActiveMembership) {
            return $next($request);
        }


        $hasPendingAssignments = ProjectAssignment::query()
            ->where('user_id', $user->id)
            ->whereNull('archived_at')
            ->whereHas('project', function ($q) {
            
                $q->where('status', '!=', 'accomplished');
            })
            ->exists();

        if ($hasPendingAssignments) {
            return $next($request);
        }


        return response()->view('blocked.no-access', [], 403);
    }
}