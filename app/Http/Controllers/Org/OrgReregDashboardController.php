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


use App\Models\OrgConstitutionSubmission;

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
                'isActivated' => false,

                'canAssignNextPresident' => $canAssignNextPresident,
                'canAssignModerator'     => false,
                'isTargetSyModerator'    => false,

                'constitutionSubmission' => null,
            ]);
        }

        $b1 = StrategicPlan::query()
            ->where('organization_id', $orgId)
            ->where('target_school_year_id', $syId)
            ->latest('id')
            ->first();


        $b2 = PresidentRegistration::query()
            ->where('organization_id', $orgId)
            ->where('target_school_year_id', $syId)
            ->latest('id')
            ->first();


        $b3 = OfficerSubmission::query()
            ->where('organization_id', $orgId)
            ->where('target_school_year_id', $syId)
            ->latest('id')
            ->first();


        $b5 = ModeratorSubmission::query()
            ->where('organization_id', $orgId)
            ->where('target_school_year_id', $syId)
            ->latest('id')
            ->first();


        

        $b6 = OrgConstitutionSubmission::query()
            ->where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->latest('id')
            ->first();

        $forms = [

            'b1' => [
                'label' => 'B-1 Strategic Plan',

                'badge' => $this->statusBadge($b1?->status),

                'submitted_at' => $b1?->submitted_to_moderator_at,
                'reviewed_at'  => $b1?->moderator_reviewed_at,
                'approved_at'  => $b1?->approved_at,

                'editRoute' => 'org.rereg.b1.edit',
                'viewRoute' => null,

                'submission' => $b1,
            ],


            'b2' => [
                'label' => 'B-2 President Registration',

                'badge' => $this->statusBadge($b2?->status),

                'submitted_at' => $b2?->submitted_at,
                'reviewed_at'  => $b2?->reviewed_at,
                'approved_at'  => $b2?->approved_at,

                'editRoute' => 'org.rereg.b2.president.edit',
                'viewRoute' => null,

                'submission' => $b2,
            ],


            'b3' => [
                'label' => 'B-3 Officers List',

                'badge' => $this->statusBadge($b3?->status),

                'submitted_at' => $b3?->submitted_at,
                'reviewed_at'  => $b3?->sacdev_reviewed_at,
                'approved_at'  => $b3?->approved_at,

                'editRoute' => 'org.rereg.b3.officers-list.edit',
                'viewRoute' => null,

                'submission' => $b3,
            ],


            'b5' => [
                'label' => 'B-5 Moderator Form',

                'badge' => $this->statusBadge($b5?->status),

                'submitted_at' => $b5?->submitted_at,
                'reviewed_at'  => $b5?->reviewed_at,
                'approved_at'  => $b5?->approved_at,

                'editRoute' => null,
                'viewRoute' => null,

                'submission' => $b5,
            ],


            'b6' => [
                'label' => 'B-6 Organization Constitution',

                'badge' => $this->statusBadge($b6?->status),

                'submitted_at' => $b6?->created_at,
                'reviewed_at'  => $b6?->reviewed_at,
                'approved_at'  => $b6?->approved_at,

                'editRoute' => null,
                'viewRoute' => null,

                'submission' => $b6,
            ],

        ];


        $allApproved =
            $this->isApproved($b1?->status)
            && $this->isApproved($b2?->status)
            && $this->isApproved($b3?->status)
            && $this->isApproved($b5?->status)
            && $b6 !== null;



        $b5Moderator = OrgMembership::query()
            ->where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->where('role', 'moderator')
            ->whereNull('archived_at')
            ->with('user')
            ->first();



        return view('org.rereg.index', [

            'schoolYears' => $schoolYears,

            'encodeSyId'  => $syId,

            'forms'       => $forms,

            'allApproved' => $allApproved,

            'isActivated' => $isActivated,

            'canAssignNextPresident' => $canAssignNextPresident,

            'canAssignModerator' => true,

            'b5Moderator' => $b5Moderator,

            'isTargetSyModerator' => $isTargetSyModerator,

            'constitutionSubmission' => $b6,
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

    private function statusBadge(?string $status): array
    {
        return match ($status) {

            // draft
            'draft' => [
                'text' => 'Draft',
                'dot'  => 'bg-slate-400',
            ],

            // submitted (officer_submissions)
            'submitted_to_sacdev' => [
                'text' => 'Submitted to SACDEV',
                'dot'  => 'bg-amber-500',
            ],

            // submitted_to_moderator (strategic plan)
            'submitted_to_moderator' => [
                'text' => 'Submitted to Moderator',
                'dot'  => 'bg-amber-500',
            ],

            'submitted' => [
                'text' => 'Submitted to SACDEV',
                'dot'  => 'bg-amber-500',
            ],

            // returned
            'returned',
            'returned_by_moderator' => [
                'text' => 'Returned',
                'dot'  => 'bg-rose-500',
            ],

            // approved
            'approved',
            'approved_by_sacdev' => [
                'text' => 'Approved',
                'dot'  => 'bg-emerald-500',
            ],

            // forwarded
            'forwarded_to_sacdev' => [
                'text' => 'Forwarded to SACDEV',
                'dot'  => 'bg-blue-500',
            ],

            default => [
                'text' => 'Not submitted',
                'dot'  => 'bg-slate-400',
            ],
        };
    }



}
