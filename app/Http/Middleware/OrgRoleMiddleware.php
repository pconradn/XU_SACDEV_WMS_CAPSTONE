<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\OrgMembership;

use App\Models\OrganizationSchoolYear;

class OrgRoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $userId = auth()->id();
        $orgId  = (int) $request->session()->get('active_org_id');
        $syId   = (int) $request->session()->get('encode_sy_id');

        if (!$userId || !$orgId || !$syId) {
            abort(403, 'Missing organization or school year context.');
        }

        $hasRole = OrgMembership::query()
            ->where('user_id', $userId)
            ->where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->whereNull('archived_at')
            ->whereIn('role', $roles)
            ->exists();

        // Fallback: if role check includes "president", allow OSY president too
        if (!$hasRole && in_array('president', $roles, true)) {
            $hasRole = OrganizationSchoolYear::query()
                ->where('organization_id', $orgId)
                ->where('school_year_id', $syId)
                ->where('president_user_id', $userId)
                ->exists();
        }

        if (!$hasRole) {
            abort(403, 'You do not have permission to access this section.');
        }

        return $next($request);
    }
}
