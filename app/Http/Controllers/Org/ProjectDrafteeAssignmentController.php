<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectAssignment;
use App\Models\OfficerEntry;
use App\Models\OrganizationMemberRecord;
use App\Models\OrgMembership;
use App\Support\AccountProvisioner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectDrafteeAssignmentController extends Controller
{
    public function index(Request $request)
    {
        $orgId = (int) $request->session()->get('active_org_id');
        $syId  = (int) $request->session()->get('encode_sy_id');

        $projects = Project::where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->orderBy('title')
            ->get();

        $draftees = ProjectAssignment::whereIn('project_id', $projects->pluck('id'))
            ->where('assignment_role', 'draftee')
            ->whereNull('archived_at')
            ->get()
            ->groupBy('project_id');

        return view('org.assignments.project-draftees-index', compact(
            'projects',
            'draftees'
        ));
    }

    public function edit(Request $request, Project $project)
    {
        $orgId = (int) $request->session()->get('active_org_id');
        $syId  = (int) $request->session()->get('encode_sy_id');

        abort_unless(
            $project->organization_id === $orgId &&
            $project->school_year_id === $syId,
            404
        );

        $user = auth()->user();

        $orgRole = OrgMembership::where('user_id', $user->id)
            ->where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->whereNull('archived_at')
            ->value('role');

        $isProjectHead = ProjectAssignment::where('project_id', $project->id)
            ->where('user_id', $user->id)
            ->where('assignment_role', 'project_head')
            ->whereNull('archived_at')
            ->exists();

        abort_unless(
            $isProjectHead || $orgRole === 'president',
            403
        );

        $departments = \App\Models\Department::where('organization_id', $orgId)
            ->where('is_archived', false)
            ->orderBy('name')
            ->get();

        $officers = OrgMembership::with('officerEntry')
            ->where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->whereNull('archived_at')
            ->whereNotNull('officer_entry_id')
            ->get()
            ->pluck('officerEntry')
            ->filter();

        $members = OrganizationMemberRecord::where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->whereNull('archived_at')
            ->get();

        $currentDraftees = ProjectAssignment::where('project_id', $project->id)
            ->where('assignment_role', 'draftee')
            ->whereNull('archived_at')
            ->pluck('user_id')
            ->toArray();

        return view('org.assignments.project-draftees-edit', compact(
            'project',
            'officers',
            'members',
            'departments',
            'currentDraftees'
        ));
    }

    public function update(Request $request, Project $project)
    {
        $orgId = (int) $request->session()->get('active_org_id');
        $syId  = (int) $request->session()->get('encode_sy_id');

        abort_unless(
            $project->organization_id === $orgId &&
            $project->school_year_id === $syId,
            404
        );

        $user = auth()->user();

        $orgRole = OrgMembership::where('user_id', $user->id)
            ->where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->whereNull('archived_at')
            ->value('role');

        $isProjectHead = ProjectAssignment::where('project_id', $project->id)
            ->where('user_id', $user->id)
            ->where('assignment_role', 'project_head')
            ->whereNull('archived_at')
            ->exists();

        abort_unless(
            $isProjectHead || $orgRole === 'president',
            403
        );

        $data = $request->validate([
            'draftees' => ['required', 'array', 'min:1', 'max:3'],
            'draftees.*.type' => ['required', 'in:officer,member'],
            'draftees.*.id' => ['nullable', 'integer'],
        ]);

        $entries = collect($data['draftees'])
            ->filter(fn($e) => !empty($e['id']))
            ->values();

        if ($entries->isEmpty()) {
            return back()->withErrors([
                'draftees' => 'At least one draftee is required.'
            ]);
        }

        DB::transaction(function () use ($data, $project, $orgId, $syId, $entries) {

            ProjectAssignment::where('project_id', $project->id)
                ->where('assignment_role', 'draftee')
                ->whereNull('archived_at')
                ->update([
                    'archived_at' => now()
                ]);

                foreach ($entries as $entry) {

                $user = null;

                if ($entry['type'] === 'officer') {

                    $officer = OfficerEntry::findOrFail($entry['id']);

                    abort_unless(
                        $officer->organization_id == $orgId &&
                        $officer->school_year_id == $syId,
                        403
                    );

                    [$user] = AccountProvisioner::findOrCreateUser(
                        $officer->full_name,
                        $officer->email ?? ('officer'.$officer->id.'@temp.local')
                    );

                    if ($officer->user_id != $user->id) {
                        $officer->user_id = $user->id;
                        $officer->save();
                    }

                    AccountProvisioner::ensureBasicOrgAccess(
                        $user->id,
                        $orgId,
                        $syId,
                        $officer->id,
                        'officer',
                        $officer->id
                    );

                } else {

                    $member = OrganizationMemberRecord::whereNull('archived_at')
                        ->findOrFail($entry['id']);

                    abort_unless(
                        $member->organization_id == $orgId &&
                        $member->school_year_id == $syId,
                        403
                    );

                    $email = $member->student_id_number
                        ? $member->student_id_number.'@my.xu.edu.ph'
                        : ($member->email ?? ('member'.$member->id.'@temp.local'));

                    [$user] = AccountProvisioner::findOrCreateUser(
                        $member->full_name,
                        $email
                    );

                    if ($member->user_id != $user->id) {
                        $member->user_id = $user->id;
                        $member->save();
                    }

                    AccountProvisioner::ensureBasicOrgAccess(
                        $user->id,
                        $orgId,
                        $syId,
                        null,
                        'member',
                        $member->id
                    );
                }

                $existing = ProjectAssignment::where('project_id', $project->id)
                    ->where('user_id', $user->id)
                    ->where('assignment_role', 'draftee')
                    ->first();

                if ($existing) {
                    $existing->update([
                        'archived_at' => null
                    ]);
                } else {
                    ProjectAssignment::create([
                        'project_id' => $project->id,
                        'user_id' => $user->id,
                        'assignment_role' => 'draftee',
                        'archived_at' => null,
                    ]);
                }
            }
        });

        return redirect()
        ->route('org.projects.assign-draftees.edit', $project)
            ->with('success', 'Draftees updated.');
    }
}