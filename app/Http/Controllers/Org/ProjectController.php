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
        $isfinance_officer   = $orgRole === 'finance_officer';

        $isProjectHead = \App\Models\ProjectAssignment::query()
            ->where('user_id', $user->id)
            ->where('assignment_role', 'project_head')
            ->whereNull('archived_at')
            ->exists();

        $query = Project::query()
            ->where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->orderBy('title');

        $isPrivileged = $isPresident || $isTreasurer || $isModerator || $isfinance_officer;

        $isProjectHead = \App\Models\ProjectAssignment::query()
            ->where('user_id', $user->id)
            ->where('assignment_role', 'project_head')
            ->whereNull('archived_at')
            ->exists();

        if (!$isPrivileged) {

            $isDraftee = \App\Models\ProjectAssignment::query()
                ->where('user_id', $user->id)
                ->where('assignment_role', 'draftee')
                ->whereNull('archived_at')
                ->exists();

            if ($isProjectHead || $isDraftee) {
                $query->whereHas('assignments', function ($q) use ($user) {
                    $q->where('user_id', $user->id)
                    ->whereIn('assignment_role', ['project_head', 'draftee'])
                    ->whereNull('archived_at');
                });
            } else {
                $query->whereRaw('0 = 1');
            }
        }

        $projects = $query
            ->with([
                'documents',
                    'assignments' => function ($q) use ($user) {
                        $q->where('user_id', $user->id)
                        ->whereIn('assignment_role', ['project_head', 'draftee'])
                        ->whereNull('archived_at');
                    },
                'documents.signatures',
            ])
            ->withCount('documents')
            ->get();

        $projects->transform(function ($project) use ($user) {

            $pendingCount = $project->documents->filter(function ($doc) use ($user) {
                $pending = $doc->currentPendingSignature();
                return $pending && $pending->user_id === $user->id;
            })->count();

            $project->pending_approvals = $pendingCount;

            return $project;
        });

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
            'isfinance_officer'    => $isfinance_officer,
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
