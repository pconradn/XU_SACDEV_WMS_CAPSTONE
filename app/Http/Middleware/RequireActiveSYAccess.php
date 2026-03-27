<?php

namespace App\Http\Middleware;

use App\Models\OrgMembership;
use App\Models\ProjectAssignment;
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

        if ($user->isSacdev()) {
            return $next($request);
        }

        
        $hasAnyMembership = OrgMembership::query()
            ->where('user_id', $user->id)
            ->whereNull('archived_at')
            ->exists();

        if ($hasAnyMembership) {
            return $next($request);
        }

        
        $hasAnyAssignment = ProjectAssignment::query()
            ->where('user_id', $user->id)
            ->whereNull('archived_at')
            ->exists();

        if ($hasAnyAssignment) {
            return $next($request);
        }

        return response()->view('blocked.no-access', [], 403);
    }
}
