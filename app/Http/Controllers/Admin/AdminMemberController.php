<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\OrganizationMemberRecord;
use App\Http\Controllers\Controller;

class AdminMemberController extends Controller
{
    public function index(Request $request)
    {
        $orgId = $request->query('organization_id');
        $syId  = $request->query('school_year_id');
        $search = trim((string) $request->query('search', ''));

        $membersQuery = OrganizationMemberRecord::with('organization')
            ->whereNull('archived_at')
            ->when($orgId, fn($q) => 
                $q->where('organization_id', $orgId)
            )

            ->when($syId, fn($q) => 
                $q->where('school_year_id', $syId)
            )

           
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('middle_initial', 'like', "%{$search}%")
                        ->orWhere('student_id_number', 'like', "%{$search}%")
                        ->orWhere('course_and_year', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })

            ->latest();

        $members = $membersQuery->paginate(20)->withQueryString();

        return view('admin.members.index', [
            'members' => $members,
            'orgId' => $orgId,
            'syId' => $syId,
            'search' => $search,
        ]);
    }
}