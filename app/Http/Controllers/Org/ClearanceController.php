<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

        $verificationUrl = route(
            'clearance.verify',
            $project->clearance_reference
        );

        return view(
            'org.projects.clearance.print',
            compact(
                'project',
                'organization',
                'proposal',
                'activity',
                'participants',
                'hash',
                'verificationUrl'
            )
        );
    }

    public function verify($reference)
    {

        $project = Project::where(
            'clearance_reference',
            $reference
        )->firstOrFail();

        return view(
            'public.clearance.verify',
            compact('project')
        );

    }

    public function upload(Request $request, Project $project)
    {

        if (!$project->requires_clearance) {
            abort(404);
        }

        if ($project->clearance_status === 'verified') {
            return back()->with(
                'error',
                'Clearance already verified. Upload is locked.'
            );
        }

        $request->validate([
            'clearance_file' => [
                'required',
                'file',
                'mimes:pdf',
                'max:10240'
            ]
        ]);

        if ($project->clearance_file) {
            Storage::disk('public')->delete($project->clearance_file);
        }

        $path = $request->file('clearance_file')->store(
            'clearances',
            'public'
        );

        $project->update([
            'clearance_file_path' => $path,
            'clearance_status' => 'uploaded'
        ]);

        return back()->with(
            'success',
            'Clearance uploaded successfully.'
        );

    }

}