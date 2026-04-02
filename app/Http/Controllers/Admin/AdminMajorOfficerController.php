<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OfficerEntry;
use App\Models\Organization;
use App\Models\OrgMembership;
use App\Models\SchoolYear;
use App\Support\AccountProvisioner;
use App\Support\Audit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminMajorOfficerController extends Controller
{
    private function activeSy(): ?SchoolYear
    {
        return SchoolYear::where('is_active', true)->first();
    }

    private function selectedSyId(Request $request): int
    {
        return (int) $request->session()->get('admin_orgs_sy_id', 0);
    }

    public function index(Request $request, Organization $organization)
    {
        $syId = $this->selectedSyId($request);

        if (!$syId) {
            return redirect()->route('admin.orgs_by_sy.index')
                ->with('error', 'Select school year first.');
        }

        $officers = OfficerEntry::query()
            ->where('organization_id', $organization->id)
            ->where('school_year_id', $syId)
            ->orderBy('sort_order')
            ->get();

       
        $currentByRole = [];

        foreach (['president','vice_president','treasurer','finance_officer'] as $role) {

            $membership = OrgMembership::query()
                ->where('organization_id', $organization->id)
                ->where('school_year_id', $syId)
                ->where('role', $role)
                ->whereNull('archived_at')
                ->latest('id')
                ->first();

            if ($membership && $membership->officer_entry_id) {

                $entry = $officers->firstWhere('id', $membership->officer_entry_id);

                if ($entry) {

                 
                    if (!$entry->is_major_officer || $entry->major_officer_role !== $role) {

                        $entry->update([
                            'is_major_officer' => true,
                            'major_officer_role' => $role,
                        ]);

                    }

                    $currentByRole[$role] = $entry;
                }
            }
        }

        $activeSy = $this->activeSy();

        //dd([
        //    'syId' => $syId,
        //    'organization_id' => $organization->id,

        //    'memberships' => \App\Models\OrgMembership::query()
        //        ->where('organization_id', $organization->id)
        //        ->where('school_year_id', $syId)
        //        ->get(),

        //    'officers' => $officers,

        //    'currentByRole' => $currentByRole ?? null,
        //]);

        return view('admin.orgs_by_sy.major_officers', [
            'organization' => $organization,
            'officers' => $officers,
            'currentByRole' => $currentByRole,
            'syId' => $syId,
            'activeSy' => $activeSy,
            'canEdit' => $activeSy && $activeSy->id == $syId,
        ]);
    }

    public function updateRole(Request $request, Organization $organization, string $role)
    {
        $syId = (int) $request->session()->get('admin_orgs_sy_id', 0);

        $activeSy = SchoolYear::where('is_active', true)->first();
        if (!$activeSy || $activeSy->id !== $syId) {
            return back()->withErrors(['general' => 'Major officers can only be edited for Active School Year.']);
        }

        $allowedRoles = ['president','vice_president','treasurer','finance_officer'];
        if (!in_array($role, $allowedRoles, true)) {
            return back()->withErrors(['general' => 'Invalid role.']);
        }

        $data = $request->validate([
            'officer_entry_id' => ['required', 'integer', 'exists:officer_entries,id'],
        ]);

        $newEntryId = (int) $data['officer_entry_id'];

        DB::transaction(function () use ($organization, $syId, $role, $newEntryId, $request) {

        
            $current = OfficerEntry::query()
                ->where('organization_id', $organization->id)
                ->where('school_year_id', $syId)
                ->where('major_officer_role', $role)
                ->where('is_major_officer', true)
                ->first();

            $newEntry = OfficerEntry::query()
                ->where('id', $newEntryId)
                ->where('organization_id', $organization->id)
                ->where('school_year_id', $syId)
                ->firstOrFail();

           
            $alreadyMajorInOrg = OfficerEntry::query()
                ->where('organization_id', $organization->id)
                ->where('school_year_id', $syId)
                ->where('is_major_officer', true)
                ->where('id', '!=', $newEntry->id)
                ->where('student_id_number', $newEntry->student_id_number)
                ->exists();

            if ($alreadyMajorInOrg) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'officer_entry_id' => 'This officer is already assigned as a major officer in this organization for this school year.'
                ]);
            }

           
            if ($role === 'president') {
                $alreadyPresidentElsewhere = OfficerEntry::query()
                    ->where('school_year_id', $syId)
                    ->where('major_officer_role', 'president')
                    ->where('is_major_officer', true)
                    ->where('student_id_number', $newEntry->student_id_number)
                    ->where('organization_id', '!=', $organization->id)
                    ->exists();

                if ($alreadyPresidentElsewhere) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'officer_entry_id' => 'This student is already assigned as President in another organization for this school year.'
                    ]);
                }
            }




           
            if ($current && $current->id && $current->id !== $newEntry->id) {
                $current->update([
                    'major_officer_role' => null,
                    'is_major_officer' => false,
                ]);

                OrgMembership::query()
                    ->where('organization_id', $organization->id)
                    ->where('school_year_id', $syId)
                    ->where('role', $role)
                    ->where('officer_entry_id', $current->id)
                    ->whereNull('archived_at')
                    ->update(['archived_at' => now()]);
            }

        
            $newEntry->update([
                'major_officer_role' => $role,
                'is_major_officer' => true,
            ]);

            //dd($newEntry->full_name);
          

            // Ensure the new officer has a user account
            if (!$newEntry->user_id) {

                [$user, $tempPassword] = AccountProvisioner::provisionUser(
                    $newEntry->full_name,
                    $newEntry->email
                );

                $newEntry->user_id = $user->id;
                $newEntry->save();
            }

            
            // Transfer pending signatures from old officer to new officer
            if ($current && $current->id !== $newEntry->id) {
                DB::table('project_document_signatures')
                    ->where('role', $role)
                    ->where('status', 'pending')
                    ->where('user_id', $current->user_id)
                    ->update([
                        'user_id' => $newEntry->user_id,
                        'updated_at' => now(),
                    ]);
            }
            
            OrgMembership::query()
                ->where('organization_id', $organization->id)
                ->where('school_year_id', $syId)
                ->where('role', $role)
                ->whereNull('archived_at')
                ->update(['archived_at' => now()]);

            AccountProvisioner::ensureMembership(
                (int) $newEntry->user_id,
                (int) $organization->id,
                (int) $syId,
                $role,
                (int) $newEntry->id
            );



            // AUDIT LOG
            Audit::log(
                'major_officer.updated',
                "Updated {$role} for org+SY",
                [
                    'actor_user_id' => (int) $request->user()->id,
                    'organization_id' => (int) $organization->id,
                    'school_year_id' => (int) $syId,
                    'meta' => [
                        'role' => $role,
                        'from_officer_entry_id' => $current?->id,
                        'to_officer_entry_id' => $newEntry->id,
                        'from_user_id' => $current?->user_id,
                        'to_user_id' => $newEntry->user_id,
                        'from_student_id' => $current?->student_id_number,
                        'to_student_id' => $newEntry->student_id_number,
                    ],
                ]
            );
        });

        return back()->with('status', ucfirst(str_replace('_',' ', $role)) . ' updated.');
    }




    public function update(Request $request, Organization $organization)
    {
        $syId = $this->selectedSyId($request);

        $activeSy = $this->activeSy();

        if (!$activeSy || $activeSy->id != $syId) {
            return back()->withErrors([
                'general' => 'Major officers can only be edited for Active School Year.'
            ]);
        }

        $data = $request->validate([
            'roles' => ['array']
        ]);

        DB::transaction(function () use ($data, $organization, $syId) {

            // clear all major roles first
            OfficerEntry::where('organization_id', $organization->id)
                ->where('school_year_id', $syId)
                ->whereIn('major_officer_role', ['president','vice_president','treasurer','finance_officer'])
                ->update([
                    'major_officer_role' => null,
                    'is_major_officer' => false,
                ]);

            OrgMembership::where('organization_id', $organization->id)
                ->where('school_year_id', $syId)
                ->whereIn('role', ['president','vice_president','treasurer','finance_officer'])
                ->whereNull('archived_at')
                ->update(['archived_at' => now()]);

            foreach ($data['roles'] ?? [] as $role => $officerId) {

                if (!$officerId) continue;

                $entry = OfficerEntry::find($officerId);

                if (!$entry) continue;

                // assign officer entry
                $entry->update([
                    'major_officer_role' => $role,
                    'is_major_officer' => true,
                ]);

                // sync membership
                if (!$entry->user_id) {
                    [$user, $tempPassword] = AccountProvisioner::provisionUser(
                        $entry->full_name,
                        $entry->email
                    );

                    $entry->user_id = $user->id;
                    $entry->save();
                }

                // sync membership
                AccountProvisioner::ensureMembership(
                    $entry->user_id,
                    $organization->id,
                    $syId,
                    $role,
                    $entry->id
                );
            }
        });

        return back()->with('status', 'Major officer roles updated.');
    }
}