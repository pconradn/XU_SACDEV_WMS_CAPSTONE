<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

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

        $projects = Project::query()
            ->where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->orderBy('title')
            ->get();

        return view('org.projects.index', compact('projects', 'syId'));
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
