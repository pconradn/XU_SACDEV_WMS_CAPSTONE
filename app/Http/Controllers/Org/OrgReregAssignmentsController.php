<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use App\Models\OrgMembership;
use App\Models\SchoolYear;
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

    /**
     * The SY being re-registered (SY2) — this is encode_sy_id in your UI.
     */
    private function targetSyId(Request $request): int
    {
        return (int) $request->session()->get('encode_sy_id');
    }

    private function activeSyId(): int
    {
        // You already have SchoolYear::activeId() in your middleware code.
        // If not, replace with query: SchoolYear::where('is_active', true)->value('id')
        return (int) SchoolYear::activeId();
    }

    private function requireTargetSySelected(int $targetSyId): void
    {
        abort_unless($targetSyId > 0, 403, 'Please select a target school year first.');
    }

    /**
     * Must be president of ACTIVE SY for selected org (SY1 president).
     * Used only for provisioning the next SY president.
     */
    private function assertActiveSyPresident(Request $request, int $orgId): void
    {
        $userId = (int) $request->user()->id;
        $activeSyId = $this->activeSyId();

        abort_unless($activeSyId > 0, 403, 'No active school year.');

        $ok = OrgMembership::query()
            ->where('user_id', $userId)
            ->where('organization_id', $orgId)
            ->where('school_year_id', $activeSyId)
            ->where('role', 'president')
            ->whereNull('archived_at')
            ->exists();

        abort_unless($ok, 403, 'Only the ACTIVE SY president can assign the next SY president.');
    }

    /**
     * Must be president of TARGET/ENCODE SY for selected org (SY2 president).
     * Used for assigning moderator for SY2.
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

    public function editNextPresident(Request $request)
    {
        $orgId = $this->orgId($request);
        $targetSyId = $this->targetSyId($request);

        $this->requireTargetSySelected($targetSyId);
        $this->assertActiveSyPresident($request, $orgId);

        $current = OrgMembership::query()
            ->where('organization_id', $orgId)
            ->where('school_year_id', $targetSyId)
            ->where('role', 'president')
            ->whereNull('archived_at')
            ->with('user')
            ->first();

        return view('org.rereg.assign_next_president', compact('targetSyId', 'current'));
    }

    public function storeNextPresident(Request $request)
    {
        $orgId = $this->orgId($request);
        $targetSyId = $this->targetSyId($request);

        $this->requireTargetSySelected($targetSyId);
        $this->assertActiveSyPresident($request, $orgId);

        $data = $request->validate([
            'full_name'  => ['required', 'string', 'max:255'],
            'student_id' => ['required', 'string', 'max:50'], // NOTE: not yet stored
            'email'      => ['required', 'email', 'max:255'],
        ]);

        [$user, $tempPassword] = DB::transaction(function () use ($data, $orgId, $targetSyId) {
            [$user, $tempPassword] = AccountProvisioner::findOrCreateUser($data['full_name'], $data['email']);

            // allow them to see this org + SY in dashboard
            AccountProvisioner::ensureBasicOrgAccess($user->id, $orgId, $targetSyId);

            // archive existing president for target sy
            OrgMembership::query()
                ->where('organization_id', $orgId)
                ->where('school_year_id', $targetSyId)
                ->where('role', 'president')
                ->whereNull('archived_at')
                ->update(['archived_at' => now()]);

            AccountProvisioner::ensureMembership($user->id, $orgId, $targetSyId, 'president');

            return [$user, $tempPassword];
        });

        Log::info($tempPassword
            ? '[REREG] Created target SY president account'
            : '[REREG] Assigned existing user as target SY president', [
            'email' => $user->email,
            'name'  => $user->name,
            'org_id' => $orgId,
            'sy_id'  => $targetSyId,
            'student_id' => $data['student_id'],
            'temp_password' => $tempPassword, // will be null if existing
        ]);

        return redirect()
            ->route('org.rereg.assign.next_president.edit')
            ->with('status', $tempPassword
                ? 'Next SY president assigned. Temporary password logged.'
                : 'Next SY president assigned (existing user).'
            );
    }

    public function editModerator(Request $request)
    {
        $orgId = $this->orgId($request);
        $targetSyId = $this->targetSyId($request);

        $this->requireTargetSySelected($targetSyId);
        $this->assertTargetSyPresident($request, $orgId, $targetSyId);

        $current = OrgMembership::query()
            ->where('organization_id', $orgId)
            ->where('school_year_id', $targetSyId)
            ->where('role', 'moderator')
            ->whereNull('archived_at')
            ->with('user')
            ->first();

        return view('org.rereg.assign_moderator', compact('targetSyId', 'current'));
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

        [$user, $tempPassword] = DB::transaction(function () use ($data, $orgId, $targetSyId) {
            [$user, $tempPassword] = AccountProvisioner::findOrCreateUser($data['full_name'], $data['email']);

            AccountProvisioner::ensureBasicOrgAccess($user->id, $orgId, $targetSyId);

            OrgMembership::query()
                ->where('organization_id', $orgId)
                ->where('school_year_id', $targetSyId)
                ->where('role', 'moderator')
                ->whereNull('archived_at')
                ->update(['archived_at' => now()]);

            AccountProvisioner::ensureMembership($user->id, $orgId, $targetSyId, 'moderator');

            return [$user, $tempPassword];
        });

        Log::info($tempPassword
            ? '[REREG] Created moderator account for org+sy'
            : '[REREG] Assigned existing user as moderator for org+sy', [
            'email' => $user->email,
            'name'  => $user->name,
            'org_id' => $orgId,
            'sy_id'  => $targetSyId,
            'temp_password' => $tempPassword,
        ]);

        return redirect()
            ->route('org.rereg.assign.moderator.edit')
            ->with('status', $tempPassword
                ? 'Moderator assigned for target SY. Temporary password logged.'
                : 'Moderator assigned for target SY (existing user).'
            );
    }
}
