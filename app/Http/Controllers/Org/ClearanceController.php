<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use App\Models\Project;

class ClearanceController extends Controller
{

    public function print(Project $project)
    {

        if (!$project->requires_clearance) {
            abort(404);
        }

        $organization = $project->organization;

        $proposalDoc = $project->documents()
            ->whereHas('formType', fn($q) => $q->where('code','PROJECT_PROPOSAL'))
            ->first();

        $proposal = $proposalDoc?->proposalData;

        $offCampusDoc = $project->documents()
            ->whereHas('formType', fn($q) => $q->where('code','OFF_CAMPUS_APPLICATION'))
            ->first();

        $activity = $offCampusDoc?->offCampus;

        $participants = $activity?->participants ?? collect();

        $hash = hash(
            'sha256',
            $project->clearance_reference .
            $project->id .
            $project->title
        );

        return view(
            'org.projects.clearance.print',
            compact(
                'project',
                'organization',
                'proposal',
                'activity',
                'participants',
                'hash'
            )
        );

    }

}