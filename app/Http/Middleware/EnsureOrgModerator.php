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

        
        $orgId = (int) $request->session()->get('active_org_id');
        $syId  = (int) $request->session()->get('encode_sy_id');

       
        if (! $orgId || ! $syId) {
            abort(403, 'No active organization selected.');
          
        }

 
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
