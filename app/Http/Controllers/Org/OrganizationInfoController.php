<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Organization;
use App\Models\OrganizationMemberRecord;
use App\Models\OfficerEntry;
use App\Models\Project;

class OrganizationInfoController extends Controller
{
    protected function ctx(Request $request)
    {
        return [
            'orgId' => session('active_org_id'),
            'targetSy' => session('encode_sy_id'),
            'userId' => auth()->id(),
        ];
    }

    public function show(Request $request)
    {
        ['orgId' => $orgId, 'targetSy' => $targetSyId] = $this->ctx($request);

        if (!$orgId || !$targetSyId) {
            abort(403, 'Organization context not set.');
        }

        $organization = Organization::findOrFail($orgId);

        // counts for dashboard cards
        $membersCount = OrganizationMemberRecord::where('organization_id', $orgId)
            ->where('school_year_id', $targetSyId)
            ->whereNull('archived_at')
            ->count();

        $officersCount = OfficerEntry::where('organization_id', $orgId)
            ->where('school_year_id', $targetSyId)
            ->count();

        $projectsCount = Project::where('organization_id', $orgId)
            ->where('school_year_id', $targetSyId)
            ->count();

        return view('org.organization-info.show', compact(
            'organization',
            'membersCount',
            'officersCount',
            'projectsCount'
        ));
    }
}