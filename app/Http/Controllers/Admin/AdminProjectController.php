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

    public function requireClearance(Project $project)
    {
        if ($project->requires_clearance) {
            return back()->with('error', 'Clearance already required for this project.');
        }

        $project->update([
            'requires_clearance' => true,
            'clearance_reference' => Project::generateClearanceReference(),
            'clearance_status' => 'required',
            'clearance_required_at' => now(),
        ]);

        return back()->with('success', 'Off-campus clearance requirement enabled.');
    }



}