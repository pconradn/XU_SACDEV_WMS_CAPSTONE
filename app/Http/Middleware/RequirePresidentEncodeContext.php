<?php

namespace App\Http\Middleware;

use App\Models\OrganizationSchoolYear;
use App\Models\OrgMembership;
use App\Models\SchoolYear;
use Closure;
use Illuminate\Http\Request;

class RequirePresidentEncodeContext
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

      
        $activeSy = SchoolYear::activeYear();
        if (!$activeSy) {
            abort(403, 'No active school year.');
        }

        $orgId = (int) $request->session()->get('active_org_id', 0);
        if (!$orgId) {
            return redirect()
                ->route('org.home')
                ->with('status', 'Please select an organization first.');
        }

   
        $encodeSyId = (int) $request->session()->get('encode_sy_id', 0);
        if ($encodeSyId <= 0) {
            $encodeSyId = (int) $activeSy->id;
            $request->session()->put('encode_sy_id', $encodeSyId);
        }
        if ($encodeSyId !== (int) $activeSy->id) {
            return redirect()
                ->route('org.home')
                ->with('status', 'You can only encode for the active school year.');
        }

        
        $encodeSyExists = SchoolYear::query()->whereKey($encodeSyId)->exists();
        if (!$encodeSyExists) {
            return redirect()
                ->route('org.home')
                ->with('status', 'Selected target school year is invalid. Please select again.');
        }

        $alreadyActivated = OrganizationSchoolYear::query()
            ->where('organization_id', $orgId)
            ->where('school_year_id', $encodeSyId)
            ->exists();

        if ($alreadyActivated) {
            return redirect()
                ->route('org.rereg.index')
                ->with('status', 'This school year is already activated and can no longer be edited.');
        }

     
        $isPresidentForEncodeSy = OrgMembership::query()
            ->where('user_id', $user->id)
            ->where('organization_id', $orgId)
            ->where('school_year_id', $encodeSyId)
            ->whereNull('archived_at')
            ->where('role', 'president')
            ->exists();

        if (!$isPresidentForEncodeSy) {
           
            return redirect()
                ->route('org.home')
                ->with('status', 'You can only encode for school years where you are the President.');
        }

        /* 
        dd([
            'user_id' => $user->id,
            'org_id' => $orgId,
            'active_sy_id' => $activeSy?->id,
            'encode_sy_id' => $encodeSyId,
            'already_activated' => $alreadyActivated,
        ]);
        */
        return $next($request);
    }
}
