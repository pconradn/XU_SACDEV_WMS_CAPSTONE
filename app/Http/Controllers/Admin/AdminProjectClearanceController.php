<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class AdminProjectClearanceController extends Controller
{

    public function verify(Project $project)
    {

        if ($project->clearance_status !== 'uploaded') {
            return back()->with('error','Clearance is not awaiting verification.');
        }

        $project->update([
            'clearance_status' => 'approved',
            'clearance_verified_at' => now()
        ]);

        $this->notifyProjectHeadClearance(
            $project,
            'Your project clearance has been approved.'
        );

        \App\Support\Audit::log(
            'clearance.approved',
            'Project clearance approved by SACDEV',
            [
                'actor_user_id' => auth()->id(),
                'organization_id' => $project->organization_id,
                'school_year_id' => $project->school_year_id,
                'meta' => [
                    'project_id' => $project->id,
                ],
            ]
        );

        return back()->with('success','Clearance verified successfully.');

    }


    public function return(Request $request, Project $project)
    {
        $request->validate([
            'remarks' => ['required', 'string']
        ]);

        if ($project->clearance_status !== 'uploaded') {
            return back()->with('error', 'Clearance cannot be returned.');
        }

        $project->update([
            'clearance_status'   => 'rejected',
            'clearance_remarks'  => trim($request->remarks),
        ]);

        $this->notifyProjectHeadClearance(
            $project,
            'Your project clearance was returned for revision: ' . $request->remarks
        );

        \App\Support\Audit::log(
            'clearance.returned',
            'Project clearance returned for revision by SACDEV',
            [
                'actor_user_id' => auth()->id(),
                'organization_id' => $project->organization_id,
                'school_year_id' => $project->school_year_id,
                'meta' => [
                    'project_id' => $project->id,
                    'remarks' => $request->remarks,
                ],
            ]
        );

        return back()->with('success', 'Clearance returned for revision.');
    }

    protected function notifyProjectHeadClearance(Project $project, string $message)
    {
        $assignment = \App\Models\ProjectAssignment::where('project_id', $project->id)
            ->where('assignment_role', 'project_head')
            ->whereNull('archived_at')
            ->with('user')
            ->first();

        if (!$assignment || !$assignment->user) {
            return;
        }

        \App\Support\InAppNotifier::notifyOnce($assignment->user, [
            'title' => 'Project Clearance Update',
            'message' => $message,
            'action_url' => route('org.projects.documents.hub', $project), 
            'dedupe_key' => 'clearance_'.$project->id.'_status_update',
            'meta' => [
                'project_id' => $project->id,
            ]
        ]);
    }

}