<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\OrgMembership;

class EnsureOrgModerator
{
    public function handle(Request $request, Closure $next)
    {
        $userId = (int) auth()->id();

        // Use the SAME context keys the org portal uses
        $orgId = (int) $request->session()->get('active_org_id');
        $syId  = (int) $request->session()->get('encode_sy_id');

        // If you require context first, redirect them to context page
        if (! $orgId || ! $syId) {
            abort(403, 'No active organization selected.');
            // OR: return redirect()->route('context.show');
        }

        // Moderator check MUST come from org_memberships
        $isModerator = OrgMembership::query()
            ->where('user_id', $userId)
            ->where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->where('role', 'moderator')
            ->exists();

        if (! $isModerator) {
            abort(403, 'Moderator access only.');
        }

        return $next($request);
    }
}
