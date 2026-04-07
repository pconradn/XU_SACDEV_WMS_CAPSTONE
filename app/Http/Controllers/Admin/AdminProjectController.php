<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\Project;
use App\Models\SchoolYear;
use Illuminate\Support\Facades\DB;

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

    public function retractClearance(Project $project)
    {
        if (auth()->user()?->is_coa_officer) {
            abort(403, 'COA officers cannot modify clearance requirements.');
        }

        if (!$project->requires_clearance) {
            return back()->with('error', 'This project does not require clearance.');
        }

        DB::transaction(function () use ($project) {

            $project->update([
                'requires_clearance' => false,
                'clearance_status' => null,
                'clearance_reference' => null,
                'clearance_required_at' => null,
            ]);

            \App\Support\Audit::log(
                'project.clearance_retracted',
                'Clearance requirement removed from project',
                [
                    'actor_user_id' => auth()->id(),
                    'organization_id' => $project->organization_id,
                    'school_year_id' => $project->school_year_id,
                    'meta' => [
                        'project_id' => $project->id,
                        'title' => $project->title,
                    ]
                ]
            );

        });

        return back()->with('success', 'Off-campus clearance requirement has been removed.');
    }


}