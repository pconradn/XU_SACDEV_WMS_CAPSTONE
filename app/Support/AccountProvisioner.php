<?php

namespace App\Support;

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

        Mail::raw(
            "Hello {$user->name},\n\n" .
            "You have been assigned a role in the SACDEV Workflow System.\n\n" .
            "Login email: {$user->email}\n" .
            "Temporary password: {$tempPassword}\n\n" .
            "You will be required to change your password on first login.\n\n" .
            "Thank you.",
            function ($message) use ($user) {
                $message->to($user->email)->subject('SACDEV System - Account Credentials (Resent)');
            }
        );

        return $tempPassword;
    }

 
    public static function findOrCreateUser(string $name, string $email): array
    {
        $user = User::query()->where('email', $email)->first();

        if ($user) {
            if (!$user->name || $user->name !== $name) {
                $user->name = $name;
                $user->save();
            }
            return [$user, null];
        }

        $tempPassword = Str::random(10) . '!' . rand(10, 99);

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($tempPassword),
            'system_role' => null,
            'must_change_password' => true,
            'password_changed_at' => null,
        ]);

        Mail::raw(
            "Hello {$user->name},\n\n" .
            "You have been assigned a role in the SACDEV Workflow System.\n\n" .
            "Login email: {$user->email}\n" .
            "Temporary password: {$tempPassword}\n\n" .
            "You will be required to change your password on first login.\n\n" .
            "Thank you.",
            function ($message) use ($user) {
                $message->to($user->email)->subject('SACDEV System - Account Credentials');
            }
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

        Mail::raw(
            "Hello {$user->name},\n\n" .
            "Your temporary credentials have been updated for the SACDEV Workflow System.\n\n" .
            "Login email: {$user->email}\n" .
            "Temporary password: {$tempPassword}\n\n" .
            "You will be required to change your password on first login.\n\n" .
            "Thank you.",
            function ($message) use ($user) {
                $message->to($user->email)->subject('SACDEV System - Account Credentials (Updated)');
            }
        );

        return $tempPassword;
    }


    public static function ensureMembership(int $userId, int $orgId, int $syId, string $role, ?int $officerEntryId = null): OrgMembership
    {
        $membership = OrgMembership::query()
            ->where('user_id', $userId)
            ->where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->where('role', $role)
            ->first();

        if ($membership) {
            $dirty = false;

            if ($membership->archived_at !== null) {
                $membership->archived_at = null;
                $dirty = true;
            }

            
            if ($officerEntryId && (int) ($membership->officer_entry_id ?? 0) !== (int) $officerEntryId) {
                $membership->officer_entry_id = $officerEntryId;
                $dirty = true;
            }

            if ($dirty) {
                $membership->save();
            }

            return $membership;
        }

        return OrgMembership::create([
            'user_id' => $userId,
            'organization_id' => $orgId,
            'school_year_id' => $syId,
            'role' => $role,
            'archived_at' => null,
            'officer_entry_id' => $officerEntryId, // nullable
        ]);
    }

   
    public static function ensureBasicOrgAccess(int $userId, int $orgId, int $syId, ?int $officerEntryId = null): OrgMembership
    {
        $role = 'member';

        $membership = OrgMembership::query()
            ->where('user_id', $userId)
            ->where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->where('role', $role)
            ->first();

        if ($membership) {
            $dirty = false;

            if ($membership->archived_at !== null) {
                $membership->archived_at = null;
                $dirty = true;
            }

            
            if ($officerEntryId && (int) ($membership->officer_entry_id ?? 0) !== (int) $officerEntryId) {
                $membership->officer_entry_id = $officerEntryId;
                $dirty = true;
            }

            if ($dirty) {
                $membership->save();
            }

            return $membership;
        }

        return OrgMembership::create([
            'user_id' => $userId,
            'organization_id' => $orgId,
            'school_year_id' => $syId,
            'role' => $role,
            'archived_at' => null,
            'officer_entry_id' => $officerEntryId, // nullable
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

        Mail::raw(
            "Hello {$user->name},\n\n" .
            "You have been assigned a role in the SACDEV Workflow System.\n\n" .
            "Login email: {$user->email}\n" .
            "Temporary password: {$tempPassword}\n\n" .
            "You will be required to change your password on first login.\n\n" .
            "Thank you.",
            function ($message) use ($user) {
                $message->to($user->email)->subject('SACDEV System - Account Credentials');
            }
        );

        return [$user, $tempPassword];
    }
}
