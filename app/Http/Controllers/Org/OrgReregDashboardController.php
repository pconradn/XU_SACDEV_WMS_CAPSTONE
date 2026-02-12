<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use App\Models\ModeratorSubmission;                     

use App\Models\OfficerSubmission;                       
use App\Models\OrganizationSchoolYear;

// Forms
use App\Models\OrgMembership;
use App\Models\PresidentRegistration;                   
use App\Models\SchoolYear;
use App\Models\StrategicPlanSubmission as StrategicPlan; 
use Illuminate\Http\Request;

class OrgReregDashboardController extends Controller
{
    private function ctx(Request $request): array
    {
        return [
            'orgId' => (int) $request->session()->get('active_org_id'),
            'syId'  => (int) $request->session()->get('encode_sy_id'), 
            'userId'=> (int) $request->user()->id,
        ];
    }

    public function index(Request $request)
    {
        ['orgId' => $orgId, 'syId' => $syId, 'userId' => $userId] = $this->ctx($request);

        $isActivated = false;

        if ($syId) {
            $isActivated = OrganizationSchoolYear::query()
                ->where('organization_id', $orgId)  
                ->where('school_year_id', $syId)
                ->exists();
        }

  
        $allowedSyIds = OrgMembership::query()
            ->where('user_id', $userId)
            ->where('organization_id', $orgId)
            ->whereNull('archived_at')
            ->distinct()
            ->pluck('school_year_id')
            ->map(fn ($v) => (int) $v)
            ->all();

        $schoolYears = SchoolYear::query()
            ->whereIn('id', $allowedSyIds ?: [-1]) 
            ->orderByDesc('id')
            ->get();

       
        $activeSyId = (int) SchoolYear::query()
            ->where('is_active', true)
            ->value('id');

        $canAssignNextPresident = $activeSyId > 0
            ? $this->hasRole($userId, $orgId, $activeSyId, 'president')
            : false;

        $canAssignModerator = ($syId > 0)
            ? $this->hasRole($userId, $orgId, $syId, 'president')
            : false;

        $isTargetSyModerator = ($syId > 0)
            ? $this->hasRole($userId, $orgId, $syId, 'moderator')
            : false;

        if ($syId <= 0 || !in_array($syId, $allowedSyIds, true)) {
            return view('org.rereg.index', [
                'schoolYears' => $schoolYears,
                'encodeSyId'  => null,
                'forms'       => [],
                'allApproved' => false,

                'canAssignNextPresident' => $canAssignNextPresident,
                'canAssignModerator'     => false,
                'isTargetSyModerator'    => false,
            ]);
        }

        // --- B1 ---
        $b1 = StrategicPlan::query()
            ->where('organization_id', $orgId)
            ->where('target_school_year_id', $syId)
            ->latest('id')
            ->first();

        // --- B2 ---
        $b2 = PresidentRegistration::query()
            ->where('organization_id', $orgId)
            ->where('target_school_year_id', $syId)
            ->latest('id')
            ->first();

        // --- B3 ---
        $b3 = OfficerSubmission::query()
            ->where('organization_id', $orgId)
            ->where('target_school_year_id', $syId)
            ->latest('id')
            ->first();

        // --- B5 ---
        $b5 = ModeratorSubmission::query()
            ->where('organization_id', $orgId)
            ->where('target_school_year_id', $syId)
            ->latest('id')
            ->first();

        $forms = [
            'b1' => $this->cardData(
                label: 'B-1 Strategic Plan',
                status: $b1?->status,
                editRoute: 'org.rereg.b1.edit',
                viewRoute: null
            ),
            'b2' => $this->cardData(
                label: 'B-2 President Registration',
                status: $b2?->status,
                editRoute: 'org.rereg.b2.president.edit',
                viewRoute: null
            ),
            'b3' => $this->cardData(
                label: 'B-3 Officers List',
                status: $b3?->status,
                editRoute: 'org.rereg.b3.officers-list.edit',
                viewRoute: null
            ),
            'b5' => $this->cardData(
                label: 'B-5 Moderator Form',
                status: $b5?->status,
                editRoute: null,
                viewRoute: null
            ),
        ];

        $allApproved = $this->isApproved($b1?->status)
            && $this->isApproved($b2?->status)
            && $this->isApproved($b3?->status)
            && $this->isApproved($b5?->status);

            $b5Moderator = OrgMembership::query()
                ->where('organization_id', $orgId)
                ->where('school_year_id', $syId)
                ->where('role', 'moderator')
                ->whereNull('archived_at')
                ->with('user')
                ->first();

            $canAssignModerator = true; 

        return view('org.rereg.index', [
            'schoolYears' => $schoolYears,
            'encodeSyId'  => $syId,
            'forms'       => $forms,
            'allApproved' => $allApproved,
            'isActivated' => $isActivated ,
          
            'canAssignNextPresident' => $canAssignNextPresident,
            'canAssignModerator'     => $canAssignModerator,
            'b5Moderator' => $b5Moderator,
            'isTargetSyModerator'    => $isTargetSyModerator,
            
        ]);
    }


    private function hasRole(int $userId, int $orgId, int $syId, string $role): bool
    {
        return OrgMembership::query()
            ->where('user_id', $userId)
            ->where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->where('role', $role)
            ->whereNull('archived_at')
            ->exists();
    }

    public function setSy(Request $request)
    {
        ['orgId' => $orgId, 'userId' => $userId] = $this->ctx($request);

        $data = $request->validate([
            'encode_school_year_id' => ['required', 'integer', 'exists:school_years,id'],
        ]);

        $targetSyId = (int) $data['encode_school_year_id'];

        $allowed = OrgMembership::query()
            ->where('user_id', $userId)
            ->where('organization_id', $orgId)
            ->where('school_year_id', $targetSyId)
            ->whereNull('archived_at')
            ->exists();

        if (!$allowed) {
            return back()->with('status', 'You do not have access to that school year for this organization.');
        }

        $request->session()->put('encode_sy_id', $targetSyId);

        return redirect()
            ->route('org.rereg.index')
            ->with('status', 'Re-registration school year updated.');
    }

    private function isApproved(?string $status): bool
    {
        return in_array($status, [
            'approved_by_sacdev',
            'approved', 
        ], true);
    }

    private function cardData(string $label, ?string $status, ?string $editRoute, ?string $viewRoute): array
    {
        return [
            'label'    => $label,
            'status'   => $status ?? 'not_started',
            'badge'    => $this->badge($status),
            'editRoute'=> $editRoute,
            'viewRoute'=> $viewRoute,
        ];
    }

    private function badge(?string $status): array
    {
        $s = $status ?? 'not_started';

        return match ($s) {
           
            'approved_by_sacdev', 'approved' => ['text' => 'Approved', 'class' => 'bg-emerald-100 text-emerald-800'],

            'submitted_to_sacdev', 'forwarded_to_sacdev' => ['text' => 'Under SACDEV Review', 'class' => 'bg-blue-100 text-blue-800'],

            'submitted_to_moderator' => ['text' => 'Under Moderator Review', 'class' => 'bg-indigo-100 text-indigo-800'],

    
            'returned_by_sacdev', 'returned_by_moderator' => ['text' => 'Returned', 'class' => 'bg-amber-100 text-amber-800'],

            'draft' => ['text' => 'Draft', 'class' => 'bg-slate-100 text-slate-800'],

            default => ['text' => 'Not Started', 'class' => 'bg-slate-100 text-slate-600'],
        };
    }
}
