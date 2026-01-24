<?php

namespace App\Support;

use App\Models\OrgMembership;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AccountProvisioner
{
    /**
     * Find user by email. If missing, create with temp password & must-change gate.
     * If exists, DO NOT reset password.
     *
     * @return array{0: User, 1: ?string} temp password string only when created
     */
    public static function findOrCreateUser(string $name, string $email): array
    {
        $user = User::query()->where('email', $email)->first();

        // If exists, don't touch password
        if ($user) {
            // Update name if blank/outdated (optional)
            if (!$user->name || $user->name !== $name) {
                $user->name = $name;
                $user->save();
            }
            return [$user, null];
        }

        // Create new user
        $tempPassword = Str::random(10) . '!' . rand(10, 99);

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($tempPassword),
            'system_role' => null,
            'must_change_password' => true,
            'password_changed_at' => null,
            // add 'is_archived' only if you use it on users (optional)
        ]);

        // Send credentials (MAIL_MAILER=log is fine)
        Mail::raw(
            "Hello {$user->name},\n\n" .
            "You have been assigned a role in the SAcDev Workflow System.\n\n" .
            "Login email: {$user->email}\n" .
            "Temporary password: {$tempPassword}\n\n" .
            "You will be required to change your password on first login.\n\n" .
            "Thank you.",
            function ($message) use ($user) {
                $message->to($user->email)->subject('SAcDev System - Account Credentials');
            }
        );

        return [$user, $tempPassword];
    }

    /**
     * Ensure user has an active OrgMembership for org + schoolyear + role.
     * If archived exists, revive it.
     */
    public static function ensureMembership(int $userId, int $orgId, int $syId, string $role): OrgMembership
    {
        $membership = OrgMembership::query()
            ->where('user_id', $userId)
            ->where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->where('role', $role)
            ->first();

        if ($membership) {
            if ($membership->archived_at !== null) {
                $membership->archived_at = null;
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
        ]);
    }

    /**
     * Ensure user is at least a member (basic access) in org+sy.
     * Useful when assigning project head so user can login to org portal.
     */
    public static function ensureBasicOrgAccess(int $userId, int $orgId, int $syId): OrgMembership
    {
        // choose role name for general access. Use 'member' if that’s your default.
        $role = 'member';

        $membership = OrgMembership::query()
            ->where('user_id', $userId)
            ->where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->where('role', $role)
            ->first();

        if ($membership) {
            if ($membership->archived_at !== null) {
                $membership->archived_at = null;
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
        ]);
    }
}
