<?php

namespace App\Http\Controllers\Org;

use App\Models\User;
use App\Models\SchoolYear;
use Illuminate\Http\Request;
use App\Models\OrgMembership;
use Illuminate\Support\Facades\DB;
use App\Models\ModeratorSubmission;
use App\Support\AccountProvisioner;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class OrgReregAssignmentsController extends Controller
{

    private function assertActiveSyPresident(Request $request, int $orgId): ?RedirectResponse{
        $userId = (int) $request->user()->id;
        $activeSyId = $this->activeSyId();

        if ($activeSyId <= 0) {
            return redirect()
                ->back()
                ->with('error', 'No active school year found. Please contact the administrator.');
        }

        $ok = OrgMembership::query()
            ->where('user_id', $userId)
            ->where('organization_id', $orgId)
            ->where('school_year_id', $activeSyId)
            ->where('role', 'president')
            ->whereNull('archived_at')
            ->exists();

        if (! $ok) {
            return redirect()
                ->back()
                ->with('error', 'President access only for the active school year.');
        }

        return null; 
    }


    private function orgId(Request $request): int
    {
        return (int) $request->session()->get('active_org_id');
    }

    private function targetSyId(Request $request): int
    {
        
        return (int) $request->session()->get('encode_sy_id');
    }

    private function activeSyId(): int
    {
        return (int) SchoolYear::activeId();
    }

    private function requireTargetSySelected(int $targetSyId): ?RedirectResponse
    {
        if ($targetSyId <= 0) {
            return redirect()
                ->route('org.rereg.index') 
                ->with('error', 'Please select a target school year first.');
        }

        return null;
    }



    /**

     */
    private function isActivated(User $user): bool
    {

        return property_exists($user, 'must_change_password')
            ? !(bool) $user->must_change_password
            : true; 
    }


    private function suggestedModeratorUser(int $orgId, int $targetSyId): ?User
    {
        $activeSyId = $this->activeSyId();

     
        if ($activeSyId && $activeSyId !== $targetSyId) {
            $m = OrgMembership::query()
                ->where('organization_id', $orgId)
                ->where('school_year_id', $activeSyId)
                ->where('role', 'moderator')
                ->whereNull('archived_at')
                ->with('user')
                ->first();

            if ($m?->user) return $m->user;
        }

     
        $m = OrgMembership::query()
            ->where('organization_id', $orgId)
            ->where('school_year_id', '!=', $targetSyId)
            ->where('role', 'moderator')
            ->whereNull('archived_at')
            ->orderByDesc('school_year_id')
            ->with('user')
            ->first();

        return $m?->user;
    }

    public function editModerator(Request $request)
    {
        $orgId = $this->orgId($request);
        $targetSyId = $this->targetSyId($request);

        $this->requireTargetSySelected($targetSyId);
        

        $schoolYears = SchoolYear::query()->orderByDesc('id')->get(['id','name']);
        $selectedSyId = (int) $request->query('target_sy_id', $targetSyId);

        $current = OrgMembership::query()
            ->with('user')
            ->where('organization_id', $orgId)
            ->where('school_year_id', $selectedSyId)
            ->where('role', 'moderator')
            ->whereNull('archived_at')
            ->first();

        $hasB5ForCurrentModerator = false;
        if ($current && $current->user) {
            $hasB5ForCurrentModerator = ModeratorSubmission::query()
                ->where('organization_id', $orgId)
                ->where('target_school_year_id', $selectedSyId)
                ->where('moderator_user_id', $current->user->id)  
                ->exists();
        }
        $suggested = $this->suggestedModeratorUser($orgId, $selectedSyId);


        return view('org.rereg.assign_moderator', compact(
            'schoolYears',
            'selectedSyId',
            'current',
            'suggested',
            'hasB5ForCurrentModerator'
        ));
    }


    public function storeModerator(Request $request)
    {
        $orgId = $this->orgId($request);
        $targetSyId = $this->targetSyId($request);

        $this->requireTargetSySelected($targetSyId);


        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email'     => ['required', 'email', 'max:255'],
        ]);

 

        [$user, $tempPassword, $didResetTemp] = DB::transaction(function () use ($data, $orgId, $targetSyId) {

         
            [$user, $tempPassword] = AccountProvisioner::findOrCreateUser($data['full_name'], $data['email']);

       
            $didResetTemp = false;

            if (!$tempPassword) {
     
                if (method_exists(AccountProvisioner::class, 'resetTempPasswordIfNotActivated') && !$this->isActivated($user)) {
                    $newTemp = AccountProvisioner::resetTempPasswordIfPending($user->id);
                    if ($newTemp) {
                        $tempPassword = $newTemp;
                        $didResetTemp = true;
                    }
                }
            }

       
            AccountProvisioner::ensureBasicOrgAccess($user->id, $orgId, $targetSyId);

       
            OrgMembership::query()
                ->where('organization_id', $orgId)
                ->where('school_year_id', $targetSyId)
                ->where('role', 'moderator')
                ->whereNull('archived_at')
                ->update(['archived_at' => now()]);

            AccountProvisioner::ensureMembership($user->id, $orgId, $targetSyId, 'moderator');

            return [$user, $tempPassword, $didResetTemp];
        });

        Log::info(
            $tempPassword
                ? ($didResetTemp ? '[REREG] Reset temp password and assigned moderator' : '[REREG] Created moderator account and assigned moderator')
                : '[REREG] Assigned existing activated user as moderator',
            [
                'email' => $user->email,
                'name'  => $user->name,
                'org_id' => $orgId,
                'sy_id'  => $targetSyId,
                'temp_password' => $tempPassword, 
            ]
        );

        return redirect()
            ->route('org.rereg.assign.moderator.edit')
            ->with('status', $tempPassword
                ? ($didResetTemp
                    ? 'Moderator assigned. Temp password was reset (account not yet activated).'
                    : 'Moderator assigned. Temporary password logged.')
                : 'Moderator assigned (existing activated user, no new temp password).'
            );
    }

    public function editNextPresident(Request $request)
    {
        $orgId = $this->orgId($request);

       
        $selectedSyId = (int) $request->query('target_sy_id', $this->targetSyId($request));
        $this->requireTargetSySelected($selectedSyId);

        $this->assertActiveSyPresident($request, $orgId);

        $schoolYears = \App\Models\SchoolYear::query()
            ->orderByDesc('id')
            ->get(['id', 'name']); 

        $current = OrgMembership::query()
            ->with('user')
            ->where('organization_id', $orgId)
            ->where('school_year_id', $selectedSyId)
            ->where('role', 'president')
            ->whereNull('archived_at')
            ->first();

        return view('org.rereg.assign_next_president', [
            'schoolYears'  => $schoolYears,
            'selectedSyId' => $selectedSyId,
            'current'      => $current,
        ]);
    }


    public function storeNextPresident(Request $request)
    {
        $orgId = $this->orgId($request);
        $targetSyId = $this->targetSyId($request);

        $this->requireTargetSySelected($targetSyId);

      
        $this->assertActiveSyPresident($request, $orgId);

        $data = $request->validate([
            'target_sy_id' => ['required', 'integer', 'exists:school_years,id'],
            'full_name'  => ['required', 'string', 'max:255'],
            'student_id' => ['required', 'string', 'max:50'],
            'email'      => ['required', 'email', 'max:255'],
        ]);

        $targetSyId = (int) $data['target_sy_id'];

        [$user, $tempPassword] = DB::transaction(function () use ($data, $orgId, $targetSyId) {

         
            [$user, $tempPassword] = AccountProvisioner::findOrCreateUser($data['full_name'], $data['email']);

         
            AccountProvisioner::ensureBasicOrgAccess($user->id, $orgId, $targetSyId);

      
            OrgMembership::query()
                ->where('organization_id', $orgId)
                ->where('school_year_id', $targetSyId)
                ->where('role', 'president')
                ->whereNull('archived_at')
                ->update(['archived_at' => now()]);

         
            AccountProvisioner::ensureMembership($user->id, $orgId, $targetSyId, 'president');

            return [$user, $tempPassword];
        });

        Log::info($tempPassword ? '[REREG] Created next SY president account' : '[REREG] Assigned existing user as next SY president', [
            'email' => $user->email,
            'name'  => $user->name,
            'org_id' => $orgId,
            'sy_id'  => $targetSyId,
            'temp_password' => $tempPassword,
        ]);

        return redirect()
            ->route('org.provision.next_president.edit')
            ->with('status', $tempPassword
                ? 'Next SY president assigned. Temporary password logged.'
                : 'Next SY president assigned (existing user).'
            );
    }





 





}
