<?php

namespace App\Http\Middleware;

use App\Models\OrgMembership;
use App\Models\OrganizationSchoolYear;
use App\Models\ProjectAssignment;
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

        /*
        |--------------------------------------------------------------------------
        | SACDEV admin always allowed
        |--------------------------------------------------------------------------
        */

        if ($user->system_role === 'sacdev_admin') {
            return $next($request);
        }

        /*
        |--------------------------------------------------------------------------
        | Require context
        |--------------------------------------------------------------------------
        */

        $orgId = (int) session('active_org_id');
        $syId  = (int) session('encode_sy_id');

        if (!$orgId || !$syId) {
            abort(403, 'Organization and school year context required.');
        }

        /*
        |--------------------------------------------------------------------------
        | 1. Membership access (PRIMARY rule)
        |--------------------------------------------------------------------------
        */

        $hasMembership = OrgMembership::query()
            ->where('user_id', $user->id)
            ->where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->whereNull('archived_at')
            ->exists();

        if ($hasMembership) {
            return $next($request);
        }

        /*
        |--------------------------------------------------------------------------
        | 2. President access via org_school_year record
        |--------------------------------------------------------------------------
        */

        $isPresident = OrganizationSchoolYear::query()
            ->where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->where('president_user_id', $user->id)
            ->exists();

        if ($isPresident) {
            return $next($request);
        }

        /*
        |--------------------------------------------------------------------------
        | 3. Assigned to project in this org + SY (carry-over / preparation)
        |--------------------------------------------------------------------------
        */

        $hasAssignment = ProjectAssignment::query()
            ->where('user_id', $user->id)
            ->whereNull('archived_at')
            ->whereHas('project', function ($q) use ($orgId, $syId) {

                $q->where('organization_id', $orgId)
                  ->where('school_year_id', $syId)
                  ->where('status', '!=', 'accomplished');

            })
            ->exists();

        if ($hasAssignment) {
            return $next($request);
        }

        /*
        |--------------------------------------------------------------------------
        | No access
        |--------------------------------------------------------------------------
        */

        return response()->view('blocked.no-access', [], 403);
    }
}