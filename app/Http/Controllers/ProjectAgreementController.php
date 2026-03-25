<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectAssignment;
use Illuminate\Http\Request;

class ProjectAgreementController extends Controller
{
    public function accept(Project $project)
    {
        $user = auth()->user();

        $assignment = ProjectAssignment::query()
            ->where('project_id', $project->id)
            ->where('user_id', $user->id)
            ->where('assignment_role', 'project_head')
            ->whereNull('archived_at')
            ->firstOrFail();

        $assignment->update([
            'agreement_accepted_at' => now(),
            'agreement_ip' => request()->ip(),
        ]);

        //dd($assignment);

        return back()->with('status', 'Agreement accepted.');
    }
}
