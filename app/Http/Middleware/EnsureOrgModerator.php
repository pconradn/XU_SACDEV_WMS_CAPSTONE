<?php

namespace App\Http\Middleware;

use App\Models\SchoolYear;
use Closure;
use Illuminate\Http\Request;

class EnsureOrgModerator
{
    public function handle(Request $request, Closure $next)
    {
        $userId = (int) auth()->id();
        $orgId  = (int) $request->session()->get('active_org_id');

        if (!$userId || !$orgId) {
            abort(403, 'No active organization selected.');
        }

        $activeSyId = (int) SchoolYear::query()->where('is_active', true)->value('id');
        if (!$activeSyId) {
            abort(403, 'No active school year.');
        }

        $isModerator = \App\Models\OrgMembership::query()
            ->where('organization_id', $orgId)
            ->where('school_year_id', $activeSyId)
            ->where('user_id', $userId)
            ->where('role', 'moderator')
            ->whereNull('archived_at')
            ->exists();

        if (!$isModerator) {
            abort(403, 'Moderator access only.');
        }

        return $next($request);
    }
}
