<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\OfficerEntry;
use App\Models\OrgMembership;
use App\Models\ProjectAssignment;
use App\Http\Controllers\Controller;

class AdminOfficerController extends Controller
{

    public function index(Request $request)
    {
        $orgId = $request->query('organization_id');
        $syId  = $request->query('school_year_id');

        $officers = OfficerEntry::with([
            'organization',
            'membership' => function ($q) use ($orgId, $syId) {
                $q->whereNull('archived_at')
                  ->when($orgId, fn($qq) => $qq->where('organization_id', $orgId))
                  ->when($syId, fn($qq) => $qq->where('school_year_id', $syId));
            }
        ])
        ->when($orgId, fn($q) => $q->where('organization_id', $orgId))
        ->when($syId, fn($q) => $q->where('school_year_id', $syId))
        ->orderBy('sort_order')
        ->get();

  
        $majorRoles = [
            'president',
            'vice_president',
            'treasurer',
            'finance_officer',
            'moderator',
        ];

        foreach ($officers as $o) {

            $membership = $o->membership;

            $role = $membership->role ?? null;

            // Major Officer
            $o->is_major_officer = in_array($role, $majorRoles);

            // Project Head
            $o->is_project_head = ProjectAssignment::where('user_id', $o->user_id)
                ->where('assignment_role', 'project_head')
                ->whereNull('archived_at')
                ->exists();

            // Status flags
            $o->is_suspended = $membership->is_suspended ?? false;
            $o->is_under_probation = $membership->is_under_probation ?? false;
        }

        return view('admin.officers.index', compact('officers'));
    }


    public function suspend(OfficerEntry $officer)
    {
        $membership = OrgMembership::where('officer_entry_id', $officer->id)
            ->whereNull('archived_at')
            ->first();

        if ($membership) {
            $membership->update([
                'is_suspended' => 1
            ]);
        }

        return back()->with('status', 'Officer marked as suspended.');
    }


    public function overrideSuspension(OfficerEntry $officer)
    {
        $membership = OrgMembership::where('officer_entry_id', $officer->id)
            ->whereNull('archived_at')
            ->first();

        if ($membership) {
            $membership->update([
                'is_suspended' => 0,
                'is_under_probation' => 1,
            ]);
        }

        return back()->with('status', 'Suspension overridden. Officer is now under probation.');
    }
}