<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use App\Models\OfficerEntry;
use App\Models\OrgMembership;
use App\Models\Project;
use Illuminate\Http\Request;

//PROJECTS CRUD

class ProjectController extends Controller
{
    //IDENTIFY CONTEXT
    private function ctx(Request $request): array
    {
        return [
            'orgId' => (int) $request->session()->get('active_org_id'),
            'syId'  => (int) $request->session()->get('encode_sy_id'),
        ];
    }

    public function index(Request $request)
    {
        ['orgId' => $orgId, 'syId' => $syId] = $this->ctx($request);

        $user = auth()->user();

        $orgRole = \App\Models\OrgMembership::query()
            ->where('user_id', $user->id)
            ->where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->whereNull('archived_at')
            ->value('role');

        $isPresident = $orgRole === 'president';
        $isTreasurer = $orgRole === 'treasurer';
        $isModerator = $orgRole === 'moderator';


        $query = Project::query()
            ->where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->orderBy('title');

        if (!$isPresident && !$isTreasurer && !$isModerator) {
            $query->whereHas('assignments', function ($q) use ($user) {
                $q->where('user_id', $user->id)
                ->where('assignment_role', 'project_head')
                ->whereNull('archived_at');
            });
        }


        $projects = $query
            ->with([
                'documents',
                'assignments' => function ($q) {
                    $q->where('assignment_role', 'project_head')
                    ->whereNull('archived_at')
                    ->with('officerEntry');
                }
            ])
            ->withCount('documents')
            ->get();


        $officers = OfficerEntry::query()
            ->where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->where('is_major_officer', 0)
            ->orderBy('full_name')
            ->get();

        return view('org.projects.index', [
            'projects'     => $projects,
            'officers'     => $officers,
            'syId'         => $syId,
            'isPresident'  => $isPresident,
            'isTreasurer'  => $isTreasurer,
            'isModerator'  => $isModerator,
        ]);
    }

    public function create(Request $request)
    {
        ['syId' => $syId] = $this->ctx($request);
        return view('org.projects.create', compact('syId'));
    }

    public function store(Request $request)
    {
        ['orgId' => $orgId, 'syId' => $syId] = $this->ctx($request);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
        ]);

        Project::create([
            'organization_id' => $orgId,
            'school_year_id' => $syId,
            ...$data,
        ]);

        return redirect()->route('org.projects.index')
            ->with('status', 'Project added.');
    }

    public function edit(Request $request, Project $project)
    {
        ['orgId' => $orgId, 'syId' => $syId] = $this->ctx($request);

        abort_unless($project->organization_id === $orgId && $project->school_year_id === $syId, 404);

        return view('org.projects.edit', compact('project', 'syId'));
    }

    public function update(Request $request, Project $project)
    {
        ['orgId' => $orgId, 'syId' => $syId] = $this->ctx($request);

        abort_unless($project->organization_id === $orgId && $project->school_year_id === $syId, 404);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
        ]);

        $project->update($data);

        return redirect()->route('org.projects.index')
            ->with('status', 'Project updated.');
    }

    public function destroy(Request $request, Project $project)
    {
        ['orgId' => $orgId, 'syId' => $syId] = $this->ctx($request);

        abort_unless($project->organization_id === $orgId && $project->school_year_id === $syId, 404);

        $project->delete();

        return redirect()->route('org.projects.index')
            ->with('status', 'Project deleted.');
    }
}
