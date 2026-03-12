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

        /*
        |--------------------------------------------------------------------------
        | Require org context
        |--------------------------------------------------------------------------
        */

        $orgId = (int) $request->session()->get('active_org_id', 0);

        if (!$orgId) {
            return redirect()
                ->route('org.home')
                ->with('status', 'Please select an organization first.');
        }


        /*
        |--------------------------------------------------------------------------
        | Require encode school year context
        |--------------------------------------------------------------------------
        */

        $encodeSyId = (int) $request->session()->get('encode_sy_id', 0);

        if ($encodeSyId <= 0) {

            return redirect()
                ->route('org.encode-sy.show')
                ->with('status', 'Please select a school year to encode.');

        }


        /*
        |--------------------------------------------------------------------------
        | Validate school year exists
        |--------------------------------------------------------------------------
        */

        $encodeSyExists = SchoolYear::query()
            ->whereKey($encodeSyId)
            ->exists();

        if (!$encodeSyExists) {

            return redirect()
                ->route('org.encode-sy.show')
                ->with('status', 'Selected school year is invalid.');

        }


        /*
        |--------------------------------------------------------------------------
        | Prevent editing if already activated
        |--------------------------------------------------------------------------
        */
/*
        $alreadyActivated = OrganizationSchoolYear::query()
            ->where('organization_id', $orgId)
            ->where('school_year_id', $encodeSyId)
            ->exists();
 
        if ($alreadyActivated) {

            return redirect()
                ->route('org.projects.index')
                ->with('status', 'This school year is already activated and cannot be edited.');

        }  */



        /*
        |--------------------------------------------------------------------------
        | Ensure user is president for this org and SY
        |--------------------------------------------------------------------------
        */

        $isPresident = OrgMembership::query()
            ->where('user_id', $user->id)
            ->where('organization_id', $orgId)
            ->where('school_year_id', $encodeSyId)
            ->whereNull('archived_at')
            ->where('role', 'president')
            ->exists();

        if (!$isPresident) {

            return redirect()
                ->route('org.home')
                ->with('status', 'Only the President can perform this action.');

        }


        return $next($request);
    }
}