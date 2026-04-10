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


    private function b5Badge($b5, bool $isModerator, bool $isProfileComplete): array
    {
        if (!$b5) {

            if ($isModerator && !$isProfileComplete) {
                return [
                    'text' => 'Profile Incomplete',
                    'dot'  => 'bg-rose-500',
                ];
            }

            return [
                'text' => 'Not submitted',
                'dot'  => 'bg-slate-400',
            ];
        }

        return match ($b5->status) {

            'draft' => [
                'text' => 'Draft',
                'dot'  => 'bg-slate-400',
            ],

            'submitted' => [
                'text' => 'Submitted',
                'dot'  => 'bg-amber-500',
            ],

            'returned' => [
                'text' => 'Returned',
                'dot'  => 'bg-rose-500',
            ],

            'approved_by_sacdev' => [
                'text' => 'Approved',
                'dot'  => 'bg-emerald-500',
            ],

            default => [
                'text' => 'Not submitted',
                'dot'  => 'bg-slate-400',
            ],
        };
    }


    private function b2Badge($presidentUser, bool $isComplete): array
    {
        if (!$presidentUser) {
            return [
                'text' => 'Not assigned',
                'dot'  => 'bg-slate-400',
            ];
        }

        if (!$isComplete) {
            return [
                'text' => 'Profile Incomplete',
                'dot'  => 'bg-rose-500',
            ];
        }

        return [
            'text' => 'Complete',
            'dot'  => 'bg-emerald-500',
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

        $canAssignModerator = ($syId > 0)
            ? $this->hasRole($userId, $orgId, $syId, 'president')
            : false;

        $isModerator = ($syId > 0)
            ? $this->hasRole($userId, $orgId, $syId, 'moderator')
            : false;

        if ($syId <= 0 || !in_array($syId, $allowedSyIds, true)) {
            return view('org.rereg.index', [
                'schoolYears' => $schoolYears,
                'encodeSyId'  => null,
                'forms'       => [],
                'allApproved' => false,
                'isActivated' => false,
                'canAssignModerator' => false,
                'isModerator' => false,
                'constitutionSubmission' => null,
                'isAdminReregHub' => false,
            ]);
        }

        $b1 = StrategicPlan::where('organization_id', $orgId)
            ->where('target_school_year_id', $syId)
            ->latest()->first();

        $b2 = PresidentRegistration::where('organization_id', $orgId)
            ->where('target_school_year_id', $syId)
            ->latest()->first();

        $b3 = OfficerSubmission::where('organization_id', $orgId)
            ->where('target_school_year_id', $syId)
            ->latest()->first();

        $b5 = ModeratorSubmission::where('organization_id', $orgId)
            ->where('target_school_year_id', $syId)
            ->latest()->first();

        $b6 = OrgConstitutionSubmission::where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->latest()->first();

        $presidentMembership = OrgMembership::where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->where('role', 'president')
            ->whereNull('archived_at')
            ->with('user.profile')
            ->first();

        $presidentUser = $presidentMembership?->user;
        $presProfile = $presidentUser?->profile;

        $isPresidentProfileComplete =
            $presProfile
            && $presProfile->first_name
            && $presProfile->last_name
            && $presProfile->birthday
            && $presProfile->sex
            && $presProfile->mobile_number
            && $presProfile->email
            && $presProfile->home_address
            && $presProfile->city_address;

        $profile = auth()->user()->profile;

        $isProfileComplete =
            $profile
            && $profile->first_name
            && $profile->last_name
            && $profile->mobile_number
            && $profile->email
            && $profile->city_address
            && $profile->university_designation
            && $profile->unit_department
            && $profile->employment_status
            && $profile->years_of_service;

        $b5Moderator = OrgMembership::where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->where('role', 'moderator')
            ->whereNull('archived_at')
            ->with('user.profile')
            ->first();

        $forms = [

            'b1' => [
                'label' => 'Strategic Plan',
                'badge' => $this->statusBadge($b1?->status),
                'submitted_at' => $b1?->submitted_to_moderator_at,
                'reviewed_at'  => $b1?->moderator_reviewed_at,
                'approved_at'  => $b1?->approved_at,
                'editRoute' => 'org.rereg.b1.edit',
                'submission' => $b1,
            ],

            'b2' => [
                'label' => 'President Profile',
                'badge' => $this->b2Badge($presidentUser, $isPresidentProfileComplete),
                'submitted_at' => $b2?->submitted_at,
                'reviewed_at'  => $b2?->reviewed_at,
                'approved_at'  => $b2?->approved_at,
                'editRoute' => 'org.rereg.b2.president.edit',
                'submission' => $b2,
            ],

            'b3' => [
                'label' => 'Officers List',
                'badge' => $this->statusBadge($b3?->status),
                'submitted_at' => $b3?->submitted_at,
                'reviewed_at'  => $b3?->sacdev_reviewed_at,
                'approved_at'  => $b3?->approved_at,
                'editRoute' => 'org.rereg.b3.officers-list.edit',
                'submission' => $b3,
            ],

            'b5' => [
                'label' => 'Moderator Profile',
                'badge' => $this->b5Badge($b5, $isModerator, $isProfileComplete),
                'submitted_at' => $b5?->submitted_at,
                'reviewed_at'  => $b5?->reviewed_at,
                'approved_at'  => $b5?->approved_at,
                'editRoute' => null,
                'submission' => $b5,
            ],

            'b6' => [
                'label' => 'Organization Constitution',
                'badge' => $this->statusBadge($b6?->status),
                'submitted_at' => $b6?->created_at,
                'reviewed_at'  => $b6?->reviewed_at,
                'approved_at'  => $b6?->approved_at,
                'editRoute' => null,
                'submission' => $b6,
            ],
        ];

        $allApproved =
            $this->isApproved($b1?->status)
            && $presidentUser && $isPresidentProfileComplete
            && $this->isApproved($b3?->status)
            && $b5 && $this->isApproved($b5?->status)
            && $b6 !== null;

        return view('org.rereg.index', [
            'schoolYears' => $schoolYears,
            'encodeSyId'  => $syId,
            'forms'       => $forms,
            'allApproved' => $allApproved,
            'isActivated' => $isActivated,
            'canAssignModerator' => $canAssignModerator,
            'isModerator' => $isModerator,
            'isProfileComplete' => $isProfileComplete,
            'b5Moderator' => $b5Moderator,
            'constitutionSubmission' => $b6,
            'presidentUser' => $presidentUser,
            'isPresidentProfileComplete' => $isPresidentProfileComplete,
            'isAdminReregHub' => false,
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
