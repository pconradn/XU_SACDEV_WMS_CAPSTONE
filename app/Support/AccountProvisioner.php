<?php

namespace App\Support;

use App\Mail\AccountCredentialsMail;
use App\Models\OrgMembership;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AccountProvisioner
{

    public static function resendInviteToPendingUser(User $user): string
    {
        if (!(bool) $user->must_change_password || $user->password_changed_at !== null) {
            throw new \RuntimeException('Cannot resend invite: user is already activated.');
        }

        $tempPassword = Str::random(10) . '!' . rand(10, 99);

        $user->password = Hash::make($tempPassword);
        $user->save();
        Mail::to($user->email)->queue(
            new AccountCredentialsMail(
                $user->name,
                $user->email,
                $tempPassword,
                'SACDEV System - Account Credentials'
            )
        );

        return $tempPassword;
    }

 
    public static function findOrCreateUser(
        string $name,
        string $email,
        ?string $first_name = null,
        ?string $middle_initial = null,
        ?string $last_name = null,
        ?string $prefix = null
    ): array
    {
        $user = User::query()->where('email', $email)->first();

        if ($user) {
            if (!$user->name || $user->name !== $name) {
                $user->name = $name;
            }

            
            if (!$user->first_name && $first_name) {
                $user->first_name = $first_name;
            }

            if (!$user->middle_initial && $middle_initial) {
                $user->middle_initial = $middle_initial;
            }

            if (!$user->last_name && $last_name) {
                $user->last_name = $last_name;
            }

            if (!$user->prefix && $prefix) {
                $user->prefix = $prefix;
            }

            $user->save();

            return [$user, null];
        }

        $tempPassword = Str::random(10) . '!' . rand(10, 99);

        $user = User::create([
            'name' => $name,
            'email' => $email,

      
            'first_name' => $first_name,
            'middle_initial' => $middle_initial,
            'last_name' => $last_name,
            'prefix' => $prefix,

            'password' => Hash::make($tempPassword),
            'system_role' => null,
            'must_change_password' => true,
            'password_changed_at' => null,
        ]);

        Mail::to($user->email)->queue(
            new AccountCredentialsMail(
                $user->name,
                $user->email,
                $tempPassword,
                'SACDEV System - Account Credentials'
            )
        );
        return [$user, $tempPassword];
    }

    public static function resetTempPasswordIfPending(User $user): ?string
    {
        $isPending = (bool) $user->must_change_password && $user->password_changed_at === null;

        if (!$isPending) {
            return null;
        }

        $tempPassword = Str::random(10) . '!' . rand(10, 99);

        $user->password = Hash::make($tempPassword);
        $user->must_change_password = true;
        $user->password_changed_at = null;
        $user->save();

        Mail::to($user->email)->queue(
            new AccountCredentialsMail(
                $user->name,
                $user->email,
                $tempPassword,
                'SACDEV System - Account Credentials'
            )
        );

        return $tempPassword;
    }


    public static function ensureMembership(
        int $userId,
        int $orgId,
        int $syId,
        string $role,
        ?int $officerEntryId = null,
        ?string $sourceType = null,
        ?int $sourceId = null
    ): OrgMembership {

        OrgMembership::where('user_id', $userId)
            ->where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->where('role', $role)
            ->delete();

        return OrgMembership::create([
            'user_id' => $userId,
            'organization_id' => $orgId,
            'school_year_id' => $syId,
            'role' => $role,
            'officer_entry_id' => $officerEntryId,
            
            'source_type' => $sourceType,
            'source_id' => $sourceId,
        ]);
    }
   
    public static function ensureBasicOrgAccess(
        int $userId,
        int $orgId,
        int $syId,
        ?int $officerEntryId = null,
        ?string $sourceType = null,
        ?int $sourceId = null
    ): OrgMembership {

        $role = $sourceType === 'member' ? 'member' : 'officer';

        OrgMembership::where('user_id', $userId)
            ->where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->where('role', $role)
            ->delete();

        return OrgMembership::create([
            'user_id' => $userId,
            'organization_id' => $orgId,
            'school_year_id' => $syId,
            'role' => $role,

        
            'officer_entry_id' => $officerEntryId,

            
            'source_type' => $sourceType,
            'source_id' => $sourceId,
        ]);
    }

 
    public static function provisionUser(string $name, string $email): array
    {
        $tempPassword = Str::random(10) . '!' . rand(10, 99);

        $user = User::query()->firstOrNew(['email' => $email]);
        $user->name = $name;
        $user->system_role = null;
        $user->password = Hash::make($tempPassword);
        $user->must_change_password = true;
        $user->password_changed_at = null;
        $user->save();

        Mail::to($user->email)->queue(
            new AccountCredentialsMail(
                $user->name,
                $user->email,
                $tempPassword,
                'SACDEV System - Account Credentials'
            )
        );

        return [$user, $tempPassword];
    }
}
