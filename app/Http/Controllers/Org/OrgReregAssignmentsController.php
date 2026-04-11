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

      
        $selectedSyId = (int) $targetSyId;

        $currentSy = SchoolYear::query()
            ->where('id', $selectedSyId) 
            ->first(['id','name']);

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

        $registered = DB::table('organization_school_years')
            ->where('organization_id', $orgId)
            ->where('school_year_id', $selectedSyId)
            ->exists();

        return view('org.rereg.assign_moderator', compact(
            'currentSy',
            'selectedSyId',
            'current',
            'suggested',
            'hasB5ForCurrentModerator',
            'registered'
        ));
    }



    public function storeModerator(Request $request)
    {
        $orgId = $this->orgId($request);
        $targetSyId = $this->targetSyId($request);

        $this->requireTargetSySelected($targetSyId);

        $registered = DB::table('organization_school_years')
            ->where('organization_id', $orgId)
            ->where('school_year_id', $targetSyId)
            ->exists();

        if ($registered) {
            abort(403, 'Moderator cannot be changed after organization is registered.');
        }


        $data = $request->validate([
            'prefix' => ['nullable','string','max:20','regex:/^[A-Za-z\.]+$/'],
            'first_name' => ['required','string','max:100','regex:/^[A-Za-z]+(?:\s[A-Za-z]+)*$/'],
            'middle_initial' => ['nullable','string','max:5','regex:/^[A-Za-z]?$/'],
            'last_name' => ['required','string','max:100','regex:/^[A-Za-z]+(?:[ \-][A-Za-z]+)*$/'],
            'email' => ['required','email','max:255'],
        ]);

        $fullName = trim(collect([
            $data['prefix'] ?? null,
            $data['first_name'],
            isset($data['middle_initial']) ? $data['middle_initial'] . '.' : null,
            $data['last_name'],
        ])->filter()->implode(' '));

        $currentModerator = OrgMembership::query()
            ->where('organization_id', $orgId)
            ->where('school_year_id', $targetSyId)
            ->where('role', 'moderator')
            ->whereNull('archived_at')
            ->with('user')
            ->first();
        


        [$user, $tempPassword, $didResetTemp] = DB::transaction(function () use ($currentModerator, $fullName, $data, $orgId, $targetSyId) {

         
            [$user, $tempPassword] = AccountProvisioner::findOrCreateUser($fullName, $data['email']);

       
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





       
            $user->first_name = $data['first_name'];
            $user->middle_initial = $data['middle_initial'] ?? null;
            $user->last_name = $data['last_name'];
            $user->prefix = $data['prefix'] ?? null;
            $user->name = $fullName;
            $user->email = $data['email'];
            $user->save();


            if ($currentModerator && $currentModerator->user) {
            ModeratorSubmission::query()
                ->where('organization_id', $orgId)
                ->where('target_school_year_id', $targetSyId)
                ->where('moderator_user_id', $currentModerator->user->id)
                ->delete();
        }
       
            OrgMembership::query()
                ->where('organization_id', $orgId)
                ->where('school_year_id', $targetSyId)
                ->where('role', 'moderator')
                ->whereNull('archived_at')
                ->update(['archived_at' => now()]);

            AccountProvisioner::ensureMembership($user->id, $orgId, $targetSyId, 'moderator');

            $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'prefix' => $data['prefix'] ?? null,
                    'first_name' => $data['first_name'],
                    'middle_initial' => $data['middle_initial'] ?? null,
                    'last_name' => $data['last_name'],
                    'full_name' => $fullName,
                ]
            );


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







 





}
