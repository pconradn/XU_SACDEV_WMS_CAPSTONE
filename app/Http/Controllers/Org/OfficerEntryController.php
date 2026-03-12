<?php

namespace App\Http\Controllers\Org;

use App\Support\Audit;
use App\Models\Project;
use App\Models\SchoolYear;
use App\Models\OfficerEntry;
use Illuminate\Http\Request;
use App\Models\OrgMembership;
use Illuminate\Validation\Rule;
use App\Models\ProjectAssignment;
use App\Http\Controllers\Controller;

//OFFICER CRUD

class OfficerEntryController extends Controller
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

        $officers = OfficerEntry::query()
            ->where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->orderBy('full_name')
            ->get();

        return view('org.officers.index', compact('officers', 'syId'));
    }

    public function create(Request $request)
    {
        ['syId' => $syId] = $this->ctx($request);
        return view('org.officers.create', compact('syId'));
    }

    public function store(Request $request)
    {
        ['orgId' => $orgId, 'syId' => $syId] = $this->ctx($request);

        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 'email', 'max:255',
                Rule::unique('officer_entries')->where(fn ($q) => $q
                    ->where('organization_id', $orgId)
                    ->where('school_year_id', $syId)
                ),
            ],
            'position' => ['nullable', 'string', 'max:255'],
        ]);

        $officer=OfficerEntry::create([
            'organization_id' => $orgId,
            'school_year_id' => $syId,
            ...$data,
        ]);


        $this->logOfficerUpdateIfActiveSy($orgId, $syId, 'created', $officer);

       
        return redirect()->route('org.officers.index')
            ->with('status', 'Officer added.');
    }

    public function edit(Request $request, OfficerEntry $officer)
    {
        ['orgId' => $orgId, 'syId' => $syId] = $this->ctx($request);

        abort_unless($officer->organization_id === $orgId && $officer->school_year_id === $syId, 404);

        $presidentMembership = OrganizationMembership::query()
            ->where('organization_id', $orgId)
            ->where('school_year_id', $targetSyId)
            ->where('major_officer_role', 'president')
            ->with('user')
            ->first();

        $currentUser = auth()->user();



        return view('org.officers.edit', compact('officer', 'syId', 'presidentMembership'));
    }




    public function update(Request $request, OfficerEntry $officer)
    {
        ['orgId' => $orgId, 'syId' => $syId] = $this->ctx($request);

        abort_unless($officer->organization_id === $orgId && $officer->school_year_id === $syId, 404);

        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 'email',
                Rule::unique('officer_entries')
                    ->where(fn ($q) => $q->where('organization_id', $orgId)
                                    ->where('school_year_id', $syId))
                    ->ignore($officer->id),
            ],
            'position' => ['nullable', 'string', 'max:255'],
        ]);

        $oldEmail = strtolower(trim($officer->email));

        $officer->update($data);

        $newEmail = strtolower(trim($officer->email));
        $emailChanged = $oldEmail !== $newEmail;

        if (!$emailChanged) {
            return redirect()->route('org.officers.index')->with('status', 'Officer updated.');
        }

        
        if ($officer->user_id) {
            $user = \App\Models\User::find($officer->user_id);

            if ($user) {
                
                if ((int) $user->must_change_password === 1) {
                    return redirect()
                        ->route('org.officers.index')
                        ->with('warning', 'This officer has a pending invite. Resend invite to the corrected email?')
                        ->with('resend_invite_officer_id', $officer->id)
                        ->with('resend_invite_old_user_id', $user->id) // 
                        ->with('resend_invite_new_email', $newEmail);
                }

                
                $taken = \App\Models\User::query()
                    ->whereRaw('LOWER(email) = ?', [$newEmail])
                    ->where('id', '!=', $user->id)
                    ->exists();

                if ($taken) {
                    return redirect()
                        ->route('org.officers.index')
                        ->with('warning', "Officer email updated, but login email could not be updated because '{$newEmail}' is already used by another account.");
                }

                $user->email = $newEmail;
                $user->save();
            }
        }

        return redirect()->route('org.officers.index')->with('status', 'Officer updated.');
    }


    public function destroy(Request $request, OfficerEntry $officer)
    {
        ['orgId' => $orgId, 'syId' => $syId] = $this->ctx($request);

        abort_unless($officer->organization_id === $orgId && $officer->school_year_id === $syId, 404);

        
        if ($this->officerHasActiveAssignments($officer, $orgId, $syId)) {
            return redirect()
                ->route('org.officers.index')
                ->with('warning', 'Cannot delete this officer because they are still assigned as Treasurer/Moderator/Project Head. Please reassign first.');
        }


       
        OrgMembership::query()
            ->where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->where('officer_entry_id', $officer->id)
            ->whereNull('archived_at')
            ->update(['archived_at' => now()]);

        $this->logOfficerUpdateIfActiveSy($orgId, $syId, 'deleted', $officer);


        $officer->delete();

        return redirect()->route('org.officers.index')
            ->with('status', 'Officer deleted and access archived.');
    }


    private function officerHasActiveAssignments(OfficerEntry $officer, int $orgId, int $syId): bool
    {
        $userId = (int) ($officer->user_id ?? 0);

        
        if ($userId <= 0) {
            return false;
        }

        
        $hasOrgRole = OrgMembership::query()
            ->where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->where('user_id', $userId)
            ->whereIn('role', ['treasurer', 'moderator', 'president'])
            ->whereNull('archived_at')
            ->exists();

        if ($hasOrgRole) return true;

        
        $projectIds = Project::query()
            ->where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->pluck('id');

        if ($projectIds->isEmpty()) return false;

        return ProjectAssignment::query()
            ->whereIn('project_id', $projectIds)
            ->where('assignment_role', 'project_head')   
            ->where('user_id', $userId)
            ->whereNull('archived_at')
            ->exists();
    }




    private function logOfficerUpdateIfActiveSy(int $orgId, int $syId, string $action, $officer): void
    {
        $activeSy = SchoolYear::activeYear();
        if (!$activeSy) return;

       
        if ($syId !== (int) $activeSy->id) return;

        $name = $officer->full_name ?? 'Unknown';
        $email = $officer->email ?? '';

        $verb = match ($action) {
            'created' => 'Added',
            'updated' => 'Updated',
            'deleted' => 'Deleted',
            default => 'Changed',
        };

        Audit::log(
            'officers_updated',
            "{$verb} officer: {$name}" . ($email ? " ({$email})" : ''),
            [
                'actor_user_id' => auth()->id(),
                'organization_id' => $orgId,
                'school_year_id' => $syId,
                'meta' => [
                    'action' => $action,
                    'officer_entry_id' => $officer->id ?? null,
                    'email' => $email,
                ],
            ]
        );
    }

    //no longer used
    private function officerIsAssignedSomewhere($officer, int $orgId, int $syId): bool
    {


        $user = \App\Models\User::where('email', $officer->email)->first();
        if (!$user) {

            return true;
        }

        $hasOrgRole = \App\Models\OrgMembership::query()
            ->where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->whereIn('role', ['treasurer', 'moderator'])
            ->where('user_id', $user->id)
            ->whereNull('archived_at')
            ->exists();

        if ($hasOrgRole) return true;

        $projectIds = \App\Models\Project::query()
            ->where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->pluck('id');

        $hasHead = \App\Models\ProjectAssignment::query()
            ->whereIn('project_id', $projectIds)
            ->where('role', 'project_head')
            ->where('user_id', $user->id)
            ->whereNull('archived_at')
            ->exists();

        return $hasHead;
    }

}
