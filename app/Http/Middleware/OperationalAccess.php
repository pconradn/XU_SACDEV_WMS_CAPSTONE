<?php

namespace App\Http\Middleware;

use App\Models\OrgMembership;
use App\Models\ProjectAssignment;
use Closure;
use Illuminate\Http\Request;

class OperationalAccess
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        $orgId = (int) $request->session()->get('active_org_id');
        $syId  = (int) $request->session()->get('encode_sy_id');

        if (!$orgId || !$syId) {
            abort(403, 'Organization context required.');
        }



        $hasOfficerRole = OrgMembership::query()
            ->where('user_id', $user->id)
            ->where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->whereNull('archived_at')
            ->whereIn('role', ['president', 'treasurer', 'moderator', 'finance_officer'])
            ->exists();


        $isProjectHead = ProjectAssignment::query()
            ->where('user_id', $user->id)
            ->whereNull('archived_at')
            ->whereHas('project', function ($q) use ($orgId, $syId) {
                $q->where('organization_id', $orgId)
                  ->where('school_year_id', $syId);
            })
            ->exists();

        if (!($hasOfficerRole || $isProjectHead)) {
            abort(403, 'Operational access only.');
        }

        return $next($request);
    }
}