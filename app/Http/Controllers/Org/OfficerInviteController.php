<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use App\Models\OfficerEntry;
use App\Models\OrgMembership;
use App\Models\Project;
use App\Models\ProjectAssignment;
use App\Models\User;
use App\Support\AccountProvisioner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

//SEND ONBOARDING EMAIL

class OfficerInviteController extends Controller
{
    public function resend(Request $request, OfficerEntry $officer)
    {
        $orgId = (int) $request->session()->get('active_org_id');
        $syId  = (int) $request->session()->get('encode_sy_id');

        abort_unless($officer->organization_id === $orgId && $officer->school_year_id === $syId, 403);

        $data = $request->validate([
            'old_user_id' => ['required', 'integer', 'exists:users,id'],
        ]);

        $oldUser = User::findOrFail($data['old_user_id']);

  
        abort_unless((int) $oldUser->must_change_password === 1 && $oldUser->password_changed_at === null, 403);

        return DB::transaction(function () use ($officer, $orgId, $syId, $oldUser) {

            $newEmail = strtolower(trim($officer->email));

           
            $taken = User::query()
                ->whereRaw('LOWER(email) = ?', [$newEmail])
                ->where('id', '!=', $oldUser->id)
                ->exists();

            if ($taken) {
                return redirect()
                    ->route('org.officers.index')
                    ->with('warning', "Cannot resend to '{$officer->email}' because it is already used by another account. You'll need to do the re-link flow instead.");
            }

            
            $oldUser->email = $officer->email;
            $oldUser->name  = $officer->full_name; 
            $oldUser->save();
            if ((int) $officer->user_id !== (int) $oldUser->id) {
                $officer->user_id = $oldUser->id;
                $officer->save();
            }

        
            AccountProvisioner::ensureBasicOrgAccess($oldUser->id, $orgId, $syId);

           
            AccountProvisioner::resendInviteToPendingUser($oldUser);

            return redirect()
                ->route('org.officers.index')
                ->with('status', 'Invite resent to corrected email.');
        });
    }



}
