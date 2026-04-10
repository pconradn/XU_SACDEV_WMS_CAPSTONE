<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrganizationMemberRecord;
use App\Models\OrgMembership;
use App\Models\Department;

class OrganizationMemberRecordController extends Controller
{
    protected function ctx(Request $request)
    {
        return [
            'orgId' => session('active_org_id'),
            'targetSy' => session('encode_sy_id'),
            'userId' => auth()->id(),
        ];
    }

    protected function isPresident($userId, $orgId, $syId)
    {
        return OrgMembership::where('user_id', $userId)
            ->where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->where('role', 'president')
            
            ->exists();
    }

    public function index(Request $request)
    {
        ['orgId' => $orgId, 'targetSy' => $targetSyId, 'userId' => $userId] = $this->ctx($request);

        $members = OrganizationMemberRecord::where('organization_id', $orgId)
            ->where('school_year_id', $targetSyId)
            
            ->latest()
            ->get();

        $departments = Department::where('organization_id', $orgId)
            ->where('is_archived', false)
            ->orderBy('name')
            ->get();

        $isPresident = $this->isPresident($userId, $orgId, $targetSyId);

        return view('org.organization-members.index', compact('departments','members', 'isPresident'));
    }

    public function store(Request $request)
    {
        ['orgId' => $orgId, 'targetSy' => $targetSyId, 'userId' => $userId] = $this->ctx($request);

        if (!$this->isPresident($userId, $orgId, $targetSyId)) {
            abort(403, 'Only president can add members.');
        }

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_initial' => 'nullable|string|max:5',

            'latest_qpi' => 'nullable|numeric|min:0|max:5',

            'email' => 'nullable|email',
            'student_id_number' => 'nullable|string|max:32',
            'course_and_year' => 'nullable|string|max:255',
            'mobile_number' => 'nullable|string|max:32',

            'department_id' => 'nullable|exists:departments,id',

        ]);

        OrganizationMemberRecord::create([
            'organization_id' => $orgId,
            'school_year_id' => $targetSyId,

            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'middle_initial' => $request->middle_initial,
            'latest_qpi' => $request->latest_qpi,

            'email' => $request->email,
            'student_id_number' => $request->student_id_number,
            'course_and_year' => $request->course_and_year,
            'mobile_number' => $request->mobile_number,

            'encoded_by' => $userId,
            'department_id' => $request->department_id,
        ]);

        return back()->with('success', 'Member added successfully.');
    }

    public function update(Request $request, $id)
    {
        ['orgId' => $orgId, 'targetSy' => $targetSyId, 'userId' => $userId] = $this->ctx($request);

        if (!$this->isPresident($userId, $orgId, $targetSyId)) {
            abort(403, 'Only president can edit members.');
        }

        $member = OrganizationMemberRecord::where('organization_id', $orgId)
            ->where('school_year_id', $targetSyId)
            ->findOrFail($id);

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_initial' => 'nullable|string|max:5',

            'latest_qpi' => 'nullable|numeric|min:0|max:5',

            'email' => 'nullable|email',
            'student_id_number' => 'nullable|string|max:32',
            'department_id' => 'nullable|exists:departments,id',
        ]);

        $member->update($request->only([
            'first_name',
            'last_name',
            'middle_initial',
            'latest_qpi',
            'email',
            'student_id_number',
            'course_and_year',
            'mobile_number',
            'department_id',
        ]));

        return back()->with('success', 'Member updated.');
    }

    public function destroy(Request $request, $id)
    {
        ['orgId' => $orgId, 'targetSy' => $targetSyId, 'userId' => $userId] = $this->ctx($request);

        if (!$this->isPresident($userId, $orgId, $targetSyId)) {
            abort(403, 'Only president can delete members.');
        }

        $member = OrganizationMemberRecord::where('organization_id', $orgId)
            ->where('school_year_id', $targetSyId)
            ->findOrFail($id);

        $member->delete();

        return back()->with('success', 'Member permanently deleted.');
    }

    //department classes

    public function storeDepartment(Request $request)
    {
        ['orgId' => $orgId, 'targetSy' => $targetSyId, 'userId' => $userId] = $this->ctx($request);

        if (!$this->isPresident($userId, $orgId, $targetSyId)) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Department::create([
            'organization_id' => $orgId,
            'name' => $request->name,
        ]);

        return back()->with('success', 'Department created.');
    }

    public function updateDepartment(Request $request, $id)
    {
        ['orgId' => $orgId, 'targetSy' => $targetSyId, 'userId' => $userId] = $this->ctx($request);

        if (!$this->isPresident($userId, $orgId, $targetSyId)) {
            abort(403);
        }

        $department = Department::where('organization_id', $orgId)
            ->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $department->update([
            'name' => $request->name
        ]);

        return back()->with('success', 'Department updated.');
    }

    public function destroyDepartment(Request $request, $id)
    {
        ['orgId' => $orgId, 'targetSy' => $targetSyId, 'userId' => $userId] = $this->ctx($request);

        if (!$this->isPresident($userId, $orgId, $targetSyId)) {
            abort(403);
        }

        $department = Department::where('organization_id', $orgId)
            ->findOrFail($id);

        $department->update([
            'is_archived' => true
        ]);

        return back()->with('success', 'Department archived.');
    }

    public function assignDepartment(Request $request, $memberId)
    {
        ['orgId' => $orgId, 'targetSy' => $targetSyId, 'userId' => $userId] = $this->ctx($request);

        if (!$this->isPresident($userId, $orgId, $targetSyId)) {
            abort(403);
        }

        $request->validate([
            'department_id' => 'nullable|exists:departments,id'
        ]);

        $member = OrganizationMemberRecord::where('organization_id', $orgId)
            ->where('school_year_id', $targetSyId)
            ->findOrFail($memberId);

        $member->update([
            'department_id' => $request->department_id
        ]);

        return back()->with('success', 'Member department updated.');
    }


}