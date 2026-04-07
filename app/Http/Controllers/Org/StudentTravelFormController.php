<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use Carbon\Carbon;

class StudentTravelFormController extends Controller
{
  
    public function create(Project $project)
    {
        
        $this->authorizeProject($project);

     
        $proposal = $project->documents()
            ->whereHas('formType', fn($q) => $q->where('code', 'PROJECT_PROPOSAL'))
            ->latest()
            ->first();

        $proposalData = optional($proposal)->proposalData;

        return view('org.projects.travel-form.create', [
            'project' => $project,
            'proposalData' => $proposalData,
        ]);
    }


    public function generate(Request $request, Project $project)
    {
        $this->authorizeProject($project);

     
        $validated = $request->validate([
            'activity_name' => 'required|string|max:255',
            'inclusive_start' => 'required|date',
            'inclusive_end' => 'required|date',
            'venue' => 'required|string|max:255',
            'accommodation' => 'nullable|string|max:255',
            'address' => 'required|string|max:255',

            'departure_date' => 'required|date',
            'departure_time' => 'required',
            'departure_mode' => 'nullable|string',
            'departure_plate' => 'nullable|string',
            'departure_flight' => 'nullable|string',

            'return_date' => 'required|date',
            'return_time' => 'required',
            'return_mode' => 'nullable|string',
            'return_plate' => 'nullable|string',
            'return_flight' => 'nullable|string',
        ]);

        $validated['inclusive_dates'] =
            Carbon::parse($validated['inclusive_start'])->format('F d, Y') .
            ' - ' .
            Carbon::parse($validated['inclusive_end'])->format('F d, Y');

        $validated['departure_date_formatted'] =
            Carbon::parse($validated['departure_date'])->format('F d, Y');

        $validated['return_date_formatted'] =
            Carbon::parse($validated['return_date'])->format('F d, Y');

        return view('org.projects.travel-form.print', [
            'project' => $project,
            'data' => $validated,
        ]);
    }

  
    private function authorizeProject(Project $project)
    {
       
        if ($project->organization_id !== session('active_org_id')) {
            abort(403, 'Unauthorized project access.');
        }
    }
}