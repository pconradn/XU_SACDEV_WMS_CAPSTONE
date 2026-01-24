<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use App\Models\OfficerEntry;
use App\Models\Project;
use App\Models\ProjectAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Support\AccountProvisioner;

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

        // load current head per project (assignment_role = project_head)
        $heads = ProjectAssignment::query()
            ->whereIn('project_id', $projects->pluck('id'))
            ->where('assignment_role', 'project_head')
            ->get()
            ->keyBy('project_id');

        return view('org.assignments.project-heads-index', compact('projects', 'heads', 'syId'));
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

    public function update(Request $request, Project $project)
    {
        $orgId = (int) $request->session()->get('active_org_id');
        $syId  = (int) $request->session()->get('encode_sy_id');

        abort_unless($project->organization_id === $orgId && $project->school_year_id === $syId, 404);

        $data = $request->validate([
            'officer_id' => ['required', 'integer', 'exists:officer_entries,id'],
        ]);

        $officer = OfficerEntry::findOrFail($data['officer_id']);
        abort_unless($officer->organization_id === $orgId && $officer->school_year_id === $syId, 403);

        DB::transaction(function () use ($project, $officer, $orgId, $syId) {

            // Create only if missing (no password reset if exists)
            [$user, $tempPassword] = AccountProvisioner::findOrCreateUser($officer->full_name, $officer->email);

            // Ensure org access membership exists & not archived
            AccountProvisioner::ensureBasicOrgAccess($user->id, $orgId, $syId);

            // If same head already, no-op
            $currentHead = ProjectAssignment::query()
                ->where('project_id', $project->id)
                ->where('assignment_role', 'project_head')
                ->first();

            if ($currentHead && (int)$currentHead->user_id === (int)$user->id) {
                return;
            }

            // Overwrite previous head
            ProjectAssignment::query()
                ->where('project_id', $project->id)
                ->where('assignment_role', 'project_head')
                ->delete();

            ProjectAssignment::query()->create([
                'project_id' => $project->id,
                'user_id' => $user->id,
                'assignment_role' => 'project_head',
                'archived_at' => null,
            ]);
        });

        return redirect()->route('org.assign-project-heads.index')
            ->with('status', 'Project head assigned (existing users keep their password).');
    }


}
