<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use App\Models\OfficerEntry;
use App\Models\OrgMembership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Support\AccountProvisioner;

class OrgRoleAssignmentController extends Controller
{
    public function edit(Request $request)
    {
        $orgId = (int) $request->session()->get('active_org_id');
        $syId  = (int) $request->session()->get('encode_sy_id');

        $officers = OfficerEntry::query()
            ->where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->orderBy('full_name')
            ->get();

        $currentTreasurer = OrgMembership::query()
            ->where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->where('role', 'treasurer')
            ->whereNull('archived_at')
            ->first();

        $currentModerator = OrgMembership::query()
            ->where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->where('role', 'moderator')
            ->whereNull('archived_at')
            ->first();

        return view('org.assignments.roles', compact(
            'officers', 'currentTreasurer', 'currentModerator', 'syId'
        ));
    }

    public function update(Request $request)
    {
        $orgId = (int) $request->session()->get('active_org_id');
        $syId  = (int) $request->session()->get('encode_sy_id');

        $data = $request->validate([
            'treasurer_officer_id' => ['required', 'integer', 'exists:officer_entries,id'],
            'moderator_officer_id' => ['required', 'integer', 'exists:officer_entries,id'],
        ]);

        $treasurerOfficer = OfficerEntry::findOrFail($data['treasurer_officer_id']);
        $moderatorOfficer = OfficerEntry::findOrFail($data['moderator_officer_id']);

        // Security: officer must belong to the same org+sy being encoded
        abort_unless($treasurerOfficer->organization_id === $orgId && $treasurerOfficer->school_year_id === $syId, 403);
        abort_unless($moderatorOfficer->organization_id === $orgId && $moderatorOfficer->school_year_id === $syId, 403);

        DB::transaction(function () use ($orgId, $syId, $treasurerOfficer, $moderatorOfficer) {

            // -------------------
            // TREASURER
            // -------------------
            [$treasurerUser] = AccountProvisioner::findOrCreateUser($treasurerOfficer->full_name, $treasurerOfficer->email);

            // ✅ Link officer -> user
            if ((int) $treasurerOfficer->user_id !== (int) $treasurerUser->id) {
                $treasurerOfficer->user_id = $treasurerUser->id;
                $treasurerOfficer->save();
            }

            AccountProvisioner::ensureBasicOrgAccess($treasurerUser->id, $orgId, $syId);

            $currentTreasurer = OrgMembership::query()
                ->where('organization_id', $orgId)
                ->where('school_year_id', $syId)
                ->where('role', 'treasurer')
                ->whereNull('archived_at')
                ->first();

            if (!($currentTreasurer && (int) $currentTreasurer->user_id === (int) $treasurerUser->id)) {
                // archive existing active treasurer
                OrgMembership::query()
                    ->where('organization_id', $orgId)
                    ->where('school_year_id', $syId)
                    ->where('role', 'treasurer')
                    ->whereNull('archived_at')
                    ->update(['archived_at' => now()]);

                // upsert membership for selected treasurer user
                OrgMembership::query()->updateOrCreate(
                    [
                        'organization_id' => $orgId,
                        'school_year_id' => $syId,
                        'user_id' => $treasurerUser->id,
                        'role' => 'treasurer',
                    ],
                    [
                        'archived_at' => null,
                    ]
                );
            }

            // -------------------
            // MODERATOR
            // -------------------
            [$moderatorUser] = AccountProvisioner::findOrCreateUser($moderatorOfficer->full_name, $moderatorOfficer->email);

            // ✅ Link officer -> user
            if ((int) $moderatorOfficer->user_id !== (int) $moderatorUser->id) {
                $moderatorOfficer->user_id = $moderatorUser->id;
                $moderatorOfficer->save();
            }

            AccountProvisioner::ensureBasicOrgAccess($moderatorUser->id, $orgId, $syId);

            $currentModerator = OrgMembership::query()
                ->where('organization_id', $orgId)
                ->where('school_year_id', $syId)
                ->where('role', 'moderator')
                ->whereNull('archived_at')
                ->first();

            if (!($currentModerator && (int) $currentModerator->user_id === (int) $moderatorUser->id)) {
                // archive existing active moderator
                OrgMembership::query()
                    ->where('organization_id', $orgId)
                    ->where('school_year_id', $syId)
                    ->where('role', 'moderator')
                    ->whereNull('archived_at')
                    ->update(['archived_at' => now()]);

                // upsert membership for selected moderator user
                OrgMembership::query()->updateOrCreate(
                    [
                        'organization_id' => $orgId,
                        'school_year_id' => $syId,
                        'user_id' => $moderatorUser->id,
                        'role' => 'moderator',
                    ],
                    [
                        'archived_at' => null,
                    ]
                );
            }
        });

        return redirect()->route('org.assign-roles.edit')
            ->with('status', 'Treasurer and Moderator assigned.');
    }

}
