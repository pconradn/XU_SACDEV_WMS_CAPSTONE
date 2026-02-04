<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use App\Models\OrgMembership;
use App\Models\SchoolYear;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Support\AccountProvisioner;

class OrgReregAssignmentsController extends Controller
{
    private function orgId(Request $request): int
    {
        return (int) $request->session()->get('active_org_id');
    }

    private function targetSyId(Request $request): int
    {
        // SY being encoded (re-reg target)
        return (int) $request->session()->get('encode_sy_id');
    }

    private function activeSyId(): int
    {
        return (int) SchoolYear::activeId();
    }

    private function requireTargetSySelected(int $targetSyId): void
    {
        abort_unless($targetSyId > 0, 403, 'Please select a target school year first.');
    }

    /**
     * President of TARGET/ENCODE SY only (SY2 president).
     * This is what you want for assigning moderator within re-reg workflow.
     */
    private function assertTargetSyPresident(Request $request, int $orgId, int $targetSyId): void
    {
        $userId = (int) $request->user()->id;

        $ok = OrgMembership::query()
            ->where('user_id', $userId)
            ->where('organization_id', $orgId)
            ->where('school_year_id', $targetSyId)
            ->where('role', 'president')
            ->whereNull('archived_at')
            ->exists();

        abort_unless($ok, 403, 'Only the TARGET SY president can assign the moderator for this SY.');
    }

    /**
     * If you have "must_change_password" logic, this is the simplest signal:
     * - false => already activated (changed password)
     * - true  => still on temp password
     *
     * Adjust if your column name differs.
     */
    private function isActivated(User $user): bool
    {
        // Example column:
        // return !$user->must_change_password;

        // If you store it differently, update here
        return property_exists($user, 'must_change_password')
            ? !(bool) $user->must_change_password
            : true; // fallback: treat as activated if unknown
    }

    /**
     * Find a suggested moderator user to prefill the form:
     * 1) Moderator in ACTIVE SY (same org)
     * 2) Most recent moderator membership (same org, any SY, excluding target)
     */
    private function suggestedModeratorUser(int $orgId, int $targetSyId): ?User
    {
        $activeSyId = $this->activeSyId();

        // Prefer active SY moderator (if target is not the active year)
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

        // Otherwise, most recent moderator (excluding target)
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
        $this->assertTargetSyPresident($request, $orgId, $targetSyId);

        // Current moderator for TARGET SY (if already assigned)
        $current = OrgMembership::query()
            ->where('organization_id', $orgId)
            ->where('school_year_id', $targetSyId)
            ->where('role', 'moderator')
            ->whereNull('archived_at')
            ->with('user')
            ->first();

        // Suggested (prefill) moderator based on previous/active SY
        // returns User|null
        $suggested = $this->suggestedModeratorUser($orgId, $targetSyId);

        return view('org.rereg.assign_moderator', [
            'targetSyId' => $targetSyId,
            'current'    => $current,
            'suggested'  => $suggested,   // <-- IMPORTANT: blade expects $suggested
        ]);
    }


    public function storeModerator(Request $request)
    {
        $orgId = $this->orgId($request);
        $targetSyId = $this->targetSyId($request);

        $this->requireTargetSySelected($targetSyId);
        $this->assertTargetSyPresident($request, $orgId, $targetSyId);

        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email'     => ['required', 'email', 'max:255'],
        ]);

        /**
         * Behavior:
         * - Assign whatever email/name was entered as moderator for target SY.
         * - If user does not exist: create + temp password (logged).
         * - If exists and NOT activated: do NOT force reset by default, unless you want it.
         *   (If you want “reset temp password whenever they change moderator”, we can add a checkbox.)
         * - If exists and activated: no temp password.
         *
         * Optional: if you want to "correct previous moderator email" instead of creating a new account,
         * we can add a separate "update_email_of_suggested" toggle later.
         */

        [$user, $tempPassword, $didResetTemp] = DB::transaction(function () use ($data, $orgId, $targetSyId) {

            // create/find by email
            [$user, $tempPassword] = AccountProvisioner::findOrCreateUser($data['full_name'], $data['email']);

            // If this is an existing user (no temp password returned), optionally reset temp password ONLY if not activated
            $didResetTemp = false;

            if (!$tempPassword) {
                // if user still hasn't activated, you can choose to reset temp password
                // NOTE: implement this method in AccountProvisioner if you don't have it yet
                if (method_exists(AccountProvisioner::class, 'resetTempPasswordIfNotActivated') && !$this->isActivated($user)) {
                    $newTemp = AccountProvisioner::resetTempPasswordIfNotActivated($user->id);
                    if ($newTemp) {
                        $tempPassword = $newTemp;
                        $didResetTemp = true;
                    }
                }
            }

            // Ensure they can see org+SY
            AccountProvisioner::ensureBasicOrgAccess($user->id, $orgId, $targetSyId);

            // Archive any existing moderator for org+targetSY
            OrgMembership::query()
                ->where('organization_id', $orgId)
                ->where('school_year_id', $targetSyId)
                ->where('role', 'moderator')
                ->whereNull('archived_at')
                ->update(['archived_at' => now()]);

            // Assign moderator membership
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
                'temp_password' => $tempPassword, // will be null if activated existing user
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
