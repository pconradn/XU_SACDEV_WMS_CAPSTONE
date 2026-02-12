<?php

namespace App\Http\Middleware;

use App\Models\OrgMembership;
use App\Models\OrganizationSchoolYear;
use App\Models\ProjectAssignment;
use App\Models\SchoolYear;
use Closure;
use Illuminate\Http\Request;

class OperationalAccess
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        $orgId = (int) $request->session()->get('active_org_id');
        if (!$orgId) {
            abort(403, 'Organization context required.');
        }

        $activeSyId = SchoolYear::activeYear()?->id;

  
        $doneStatuses = ['accomplished'];

        $hasActiveMembership = false;
        $orgActivatedInActiveSy = false;

        if ($activeSyId) {
            $hasActiveMembership = OrgMembership::query()
                ->where('user_id', $user->id)
                ->where('organization_id', $orgId)
                ->where('school_year_id', $activeSyId)
                ->whereNull('archived_at')
                ->exists();

            $orgActivatedInActiveSy = OrganizationSchoolYear::query()
                ->where('organization_id', $orgId)
                ->where('school_year_id', $activeSyId)
                ->exists();
        }

        $canOperationalByActiveSy = $hasActiveMembership && $orgActivatedInActiveSy;

        /*
        |---------------------------------------------------------
        | B) Carry-over access
        |   - user has an assignment in this org
        |   - project is not accomplished
        |---------------------------------------------------------
        */
        $hasUnfinishedAssignment = ProjectAssignment::query()
            ->where('user_id', $user->id)
            ->whereNull('archived_at')
            ->whereHas('project', function ($q) use ($orgId, $doneStatuses) {
                $q->where('organization_id', $orgId)
                  ->whereNotIn('status', $doneStatuses);
            })
            ->exists();

        $hasOfficerRoleWithPendingProjects = OrgMembership::query()
            ->where('user_id', $user->id)
            ->where('organization_id', $orgId)
            ->whereNull('archived_at')
            ->whereIn('role', ['president', 'treasurer', 'moderator'])
            ->whereHas('schoolYear') // optional if you have relation, not needed
            ->whereExists(function ($q) use ($orgId, $doneStatuses) {
                $q->selectRaw(1)
                ->from('projects')
                ->whereColumn('projects.school_year_id', 'org_memberships.school_year_id')
                ->where('projects.organization_id', $orgId)
                ->whereNotIn('projects.status', $doneStatuses);
            })
            ->exists();


        if (!($canOperationalByActiveSy || $hasUnfinishedAssignment || $hasOfficerRoleWithPendingProjects)) {
            abort(403, 'Operational access only.');
        }

        return $next($request);
    }
}
