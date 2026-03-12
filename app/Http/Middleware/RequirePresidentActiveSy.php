<?php

namespace App\Http\Middleware;

use App\Models\OrgMembership;
use App\Models\SchoolYear;
use Closure;
use Illuminate\Http\Request;

class RequirePresidentActiveSy
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if (!$user) abort(403);

        $activeSy = SchoolYear::activeYear();
        if (!$activeSy) {
            abort(403, 'No active school year.');
        }

        $orgId = (int) $request->session()->get('active_org_id', 0);
        if (!$orgId) {
            return redirect()->route('org.home')
                ->with('status', 'Please select an organization first.');
        }

        $isPresident = OrgMembership::query()
            ->where('user_id', $user->id)
            ->where('organization_id', $orgId)
            ->where('school_year_id', $activeSy->id)
            ->whereNull('archived_at')
            ->where('role', 'president')
            ->exists();

        if (!$isPresident) {
            abort(403, 'Only the President of the ACTIVE school year can access this.');
        }

        return $next($request);
    }
}
