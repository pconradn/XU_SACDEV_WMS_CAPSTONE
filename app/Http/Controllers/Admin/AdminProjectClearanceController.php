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
            'clearance_status' => 'verified',
            'clearance_verified_at' => now()
        ]);

        return back()->with('success','Clearance verified successfully.');

    }


    public function return(Request $request, Project $project)
    {

        $request->validate([
            'remarks' => ['required','string']
        ]);

        if ($project->clearance_status !== 'uploaded') {
            return back()->with('error','Clearance cannot be returned.');
        }

        $project->update([
            'clearance_status' => 'rejected'
        ]);

        return back()->with('success','Clearance returned for revision.');

    }

}