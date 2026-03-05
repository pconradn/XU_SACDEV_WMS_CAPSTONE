<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\Project;
use App\Models\SchoolYear;

class AdminProjectController extends Controller
{
    public function index($orgId, $syId)
    {
        $organization = Organization::findOrFail($orgId);

        $schoolYear = SchoolYear::findOrFail($syId);

        $projects = Project::query()
            ->where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->with(['documents'])
            ->orderByDesc('created_at')
            ->get();

        return view('admin.projects.index', [
            'organization' => $organization,
            'schoolYear'   => $schoolYear,
            'projects'     => $projects
        ]);
    }
}