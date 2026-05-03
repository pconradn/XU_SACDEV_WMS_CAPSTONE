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
use App\Models\OrganizationMemberRecord;
use App\Models\Department;

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
            ->whereNull('archived_at')
            ->get()
            ->keyBy('project_id');

        $officers = \App\Models\OrgMembership::query()
            ->with('officerEntry')
            ->where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->whereNull('archived_at')
            ->whereNotNull('officer_entry_id')
            ->whereNotIn('role', ['president', 'treasurer', 'finance_officer'])
            ->get()
            ->pluck('officerEntry')
            ->filter()
            ->unique('id')
            ->sortBy('full_name')
            ->values();

        $members = OrganizationMemberRecord::query()
            ->where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->whereNull('archived_at')
            ->orderBy('last_name')
            ->get();

        $departments = Department::query()
            ->where('organization_id', $orgId)
            ->where('is_archived', false)
            ->orderBy('name')
            ->get();


        $hasApprovers = \App\Models\OrgMembership::query()
            ->where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->whereIn('role', ['treasurer', 'finance_officer'])
            ->whereNull('archived_at')
            ->pluck('role')
            ->unique();

        $missingRoles = collect(['treasurer', 'finance_officer'])
            ->diff($hasApprovers);            


        return view('org.assignments.project-heads-index', compact(
            
            'projects',
            'heads',
            'syId',
            'officers',
            'members',
            'departments',
            'missingRoles',

        ));
    }

    public function edit(Request $request, Project $project)
    {
        $orgId = (int) $request->session()->get('active_org_id');
        $syId  = (int) $request->session()->get('encode_sy_id');

        abort_unless($project->organization_id === $orgId && $project->school_year_id === $syId, 404);

        $officers = \App\Models\OrgMembership::query()
            ->with('officerEntry')
            ->where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->whereNull('archived_at')
            ->whereNotNull('officer_entry_id')
            ->get();

        $currentHead = ProjectAssignment::query()
            ->where('project_id', $project->id)
            ->where('assignment_role', 'project_head')
            ->first();

        return view('org.assignments.project-heads-edit', compact('project', 'officers', 'currentHead', 'syId'));
    }

    public function update(Request $request, $project)
    {
        $orgId = (int) $request->session()->get('active_org_id');
        $syId  = (int) $request->session()->get('encode_sy_id');

        $requiredRoles = ['treasurer', 'finance_officer'];

        $existingRoles = \App\Models\OrgMembership::query()
            ->where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->whereIn('role', $requiredRoles)
            ->whereNull('archived_at')
            ->pluck('role')
            ->unique();

        if (collect($requiredRoles)->diff($existingRoles)->isNotEmpty()) {
            return back()->with('error', 'Assign all approver roles before assigning project heads.');
        }


        if (!$project instanceof \App\Models\Project) {
            $project = \App\Models\Project::findOrFail($project);
        }

        $orgId = (int) $request->session()->get('active_org_id');
        $syId  = (int) $request->session()->get('encode_sy_id');





        abort_unless(
            (int) $project->organization_id === $orgId &&
            (int) $project->school_year_id === $syId,
            404
        );

        $data = $request->validate([
            'assignment_type' => ['required', 'in:officer,member'],

            'officer_id' => [
                'nullable',
                'required_if:assignment_type,officer',
                'exists:officer_entries,id'
            ],

            'member_id' => [
                'nullable',
                'required_if:assignment_type,member',
                'exists:organization_member_records,id'
            ],
        ]);


        $assignmentType = $data['assignment_type'];

        $officer = null;
        $member = null;

        $user = null;

        if ($assignmentType === 'officer') {

            $officer = OfficerEntry::findOrFail($data['officer_id']);

            abort_unless(
                (int) $officer->organization_id === $orgId &&
                (int) $officer->school_year_id === $syId,
                403
            );

            [$user, $tempPassword] =
                AccountProvisioner::findOrCreateUser(
                    $officer->full_name,
                    $officer->email ?? ('officer'.$officer->id.'@temp.local')
                );

            if ((int) $officer->user_id !== (int) $user->id) {
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

            $member = OrganizationMemberRecord::query()
                ->whereNull('archived_at')
                ->findOrFail($data['member_id']);

            abort_unless(
                (int) $member->organization_id === $orgId &&
                (int) $member->school_year_id === $syId,
                403
            );

            $email = $member->student_id_number
                ? $member->student_id_number . '@my.xu.edu.ph'
                : ($member->email ?? ('member'.$member->id.'@temp.local'));

            [$user, $tempPassword] =
                AccountProvisioner::findOrCreateUser(
                    $member->full_name,
                    $email
                );

            if ((int) $member->user_id !== (int) $user->id) {
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

        try {

            DB::transaction(function () use ($project, $user) {

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

            if ($e->getMessage() === 'SAME_USER') {
                return back()->with('error', 'This user is already assigned.');
            }

            throw $e; 
        }


        return redirect()
            ->route('org.assign-project-heads.index')
            ->with('success', 'Project head updated.');
    }



    
}
