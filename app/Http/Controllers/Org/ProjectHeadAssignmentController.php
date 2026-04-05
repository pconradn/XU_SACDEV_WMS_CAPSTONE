<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use App\Models\OfficerEntry;
use App\Models\Project;
use App\Models\ProjectAssignment;
use App\Models\ProjectDocument;
use App\Models\ProjectDocumentSignature;
use App\Support\AccountProvisioner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectHeadAssignmentController extends Controller
{
    public function index(Request $request)
    {
        $orgId = (int) $request->session()->get('active_org_id');
        $syId  = (int) $request->session()->get('encode_sy_id');

        $projects = Project::query()
            ->where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->orderBy('title')
            ->get();

        $heads = ProjectAssignment::query()
            ->whereIn('project_id', $projects->pluck('id'))
            ->where('assignment_role', 'project_head')
            ->get()
            ->keyBy('project_id');

        $officers = OfficerEntry::query()
            ->where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->where('is_major_officer', 0)
            ->orderBy('full_name')
            ->get();


        return view('org.assignments.project-heads-index', compact('projects', 'heads', 'syId','officers'));
    }

    public function edit(Request $request, Project $project)
    {
        $orgId = (int) $request->session()->get('active_org_id');
        $syId  = (int) $request->session()->get('encode_sy_id');

        abort_unless($project->organization_id === $orgId && $project->school_year_id === $syId, 404);

        $officers = OfficerEntry::query()
            ->where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->orderBy('full_name')
            ->get();

        $currentHead = ProjectAssignment::query()
            ->where('project_id', $project->id)
            ->where('assignment_role', 'project_head')
            ->first();

        return view('org.assignments.project-heads-edit', compact('project', 'officers', 'currentHead', 'syId'));
    }

    public function update(Request $request, $project)
    {
        if (!$project instanceof \App\Models\Project) {
            $project = \App\Models\Project::findOrFail($project);
        }

        $orgId = (int) $request->session()->get('active_org_id');
        $syId  = (int) $request->session()->get('encode_sy_id');

        if (!$project instanceof Project) {
            $project = Project::find($project);
            dd('STEP 2 - after find', $project);
        }



        abort_unless(
            (int) $project->organization_id === $orgId &&
            (int) $project->school_year_id === $syId,
            404
        );

        $data = $request->validate([
            'officer_id' => ['required', 'integer', 'exists:officer_entries,id'],
        ]);



        $officer = OfficerEntry::find($data['officer_id']);


        abort_unless(
            (int) $officer->organization_id === $orgId &&
            (int) $officer->school_year_id === $syId,
            403
        );

        try {

            DB::transaction(function () use ($project, $officer, $orgId, $syId) {

                [$user, $tempPassword] =
                    AccountProvisioner::findOrCreateUser(
                        $officer->full_name,
                        $officer->email
                    );


                if ((int) $officer->user_id !== (int) $user->id) {
                    $officer->user_id = $user->id;
                    $officer->save();
                }

                AccountProvisioner::ensureBasicOrgAccess(
                    $user->id,
                    $orgId,
                    $syId,
                    $officer->id
                );

                $currentHead = ProjectAssignment::query()
                    ->where('project_id', $project->id)
                    ->where('assignment_role', 'project_head')
                    ->whereNull('archived_at')
                    ->first();


                if ($currentHead && (int) $currentHead->user_id === (int) $user->id) {
                    throw new \Exception('SAME_USER');
                }

                ProjectAssignment::query()
                    ->where('project_id', $project->id)
                    ->where('assignment_role', 'project_head')
                    ->whereNull('archived_at')
                    ->update([
                        'archived_at' => now()
                    ]);



                $existing = ProjectAssignment::query()
                    ->where('project_id', $project->id)
                    ->where('user_id', $user->id)
                    ->where('assignment_role', 'project_head')
                    ->first();



                if ($existing) {
                    $existing->update([
                        'archived_at' => null
                    ]);
                } else {
                    ProjectAssignment::create([
                        'project_id' => $project->id,
                        'user_id' => $user->id,
                        'assignment_role' => 'project_head',
                        'archived_at' => null,
                    ]);
                }

   

            });

        } catch (\Exception $e) {

        }


        return redirect()
            ->route('org.projects.index')
            ->with('success', 'Project head updated.');
    }



    
}
