<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use App\Models\OrgMembership;
use App\Support\AccountProvisioner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ApproverAssignmentController extends Controller
{
    private function ctx(Request $request): array
    {
        return [
            'orgId' => (int) $request->session()->get('active_org_id'),
            'syId'  => (int) $request->session()->get('encode_sy_id'),
        ];
    }

    public function edit(Request $request)
    {
        ['orgId' => $orgId, 'syId' => $syId] = $this->ctx($request);

        $officers = OrgMembership::query()
            ->with('officerEntry')
            ->where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->whereNull('archived_at')
            ->whereNotNull('officer_entry_id')
            ->whereHas('officerEntry', function ($q) {
                $q->whereRaw('LOWER(TRIM(position)) != ?', ['President']);
            })
            ->get();

        $memberships = \App\Models\OrgMembership::query()
            ->where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->whereNull('archived_at')
            ->get();

        $current = [
            'treasurer' => $memberships->firstWhere('role', 'treasurer'),
            'finance_officer' => $memberships->firstWhere('role', 'finance_officer'),
        ];

        return view('org.approver_assignments.edit', compact(
            'officers',
            'current'
        ));
    }



    public function update(Request $request)
    {
        ['orgId' => $orgId, 'syId' => $syId] = $this->ctx($request);

        if ($orgId <= 0) {
            return redirect()->route('org.home')->with('error', 'Please select an organization first.');
        }

        if ($syId <= 0) {
            return redirect()->route('org.rereg.index')->with('error', 'Please select a school year first.');
        }

        $data = $request->validate([
            'treasurer_id' => [
                'required',
                'integer',
                'different:finance_officer_id',
                Rule::exists('org_memberships', 'id')->where(function ($query) use ($orgId, $syId) {
                    $query->where('organization_id', $orgId)
                        ->where('school_year_id', $syId)
                        ->whereNull('archived_at')
                        ->whereNotNull('officer_entry_id');
                }),
            ],

            'finance_officer_id' => [
                'required',
                'integer',
                'different:treasurer_id',
                Rule::exists('org_memberships', 'id')->where(function ($query) use ($orgId, $syId) {
                    $query->where('organization_id', $orgId)
                        ->where('school_year_id', $syId)
                        ->whereNull('archived_at')
                        ->whereNotNull('officer_entry_id');
                }),
            ],
        ], [
            'treasurer_id.different' => 'The Treasurer and Finance Officer must be different officers.',
            'finance_officer_id.different' => 'The Finance Officer and Treasurer must be different officers.',
        ]);

        DB::transaction(function () use ($data, $orgId, $syId) {
            $roles = [
                'treasurer' => (int) $data['treasurer_id'],
                'finance_officer' => (int) $data['finance_officer_id'],
            ];

            foreach ($roles as $role => $membershipId) {
                $membership = OrgMembership::query()
                    ->with('officerEntry')
                    ->where('id', $membershipId)
                    ->where('organization_id', $orgId)
                    ->where('school_year_id', $syId)
                    ->whereNull('archived_at')
                    ->whereNotNull('officer_entry_id')
                    ->firstOrFail();

                if (!in_array($membership->role, ['officer', 'treasurer', 'finance_officer'], true)) {
                    abort(422, 'Selected membership cannot be assigned as an approver.');
                }

                $entry = $membership->officerEntry;

                if (!$entry || !$entry->student_id_number) {
                    abort(422, 'Selected officer has no valid student ID.');
                }

                $email = $entry->student_id_number . '@my.xu.edu.ph';

                [$user, $tempPassword] = AccountProvisioner::findOrCreateUser(
                    $entry->full_name,
                    $email
                );

                $entry->user_id = $user->id;
                $entry->email = $email;
                $entry->save();

                $user->name = $entry->full_name;
                $user->save();

                OrgMembership::query()
                    ->where('organization_id', $orgId)
                    ->where('school_year_id', $syId)
                    ->where('role', $role)
                    ->whereNull('archived_at')
                    ->where('id', '!=', $membership->id)
                    ->update([
                        'role' => 'officer',
                    ]);

                $membership->role = $role;
                $membership->user_id = $user->id;
                $membership->save();

                Audit::log(
                    'approver_role_assigned',
                    ucfirst(str_replace('_', ' ', $role)) . ' assigned: ' . $entry->full_name,
                    [
                        'actor_user_id' => auth()->id(),
                        'organization_id' => $orgId,
                        'school_year_id' => $syId,
                        'meta' => [
                            'role' => $role,
                            'new_user_id' => $user->id,
                            'new_officer_name' => $entry->full_name,
                            'previous_user_id' => $previous?->user_id,
                        ]
                    ]
                );
                
            }
        });

        return back()->with('success', 'Approver roles assigned successfully.');
    }
}