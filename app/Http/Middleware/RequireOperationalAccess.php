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

        // SACDEV admins bypass
        if ($user->system_role === 'sacdev_admin') {
            return $next($request);
        }

        $activeSyId = SchoolYear::activeId();

        // Case A: has active membership (normal)
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

        // Case B: has pending assignments from ANY SY (carry-over)
        $hasPendingAssignments = ProjectAssignment::query()
            ->where('user_id', $user->id)
            ->whereNull('archived_at')
            ->whereHas('project', function ($q) {
                // adjust to your actual status column
                $q->where('status', '!=', 'accomplished');
            })
            ->exists();

        if ($hasPendingAssignments) {
            return $next($request);
        }

        // No access at all
        return response()->view('blocked.no-access', [], 403);
    }
}