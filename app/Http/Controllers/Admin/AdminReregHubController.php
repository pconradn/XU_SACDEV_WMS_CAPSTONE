<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ModeratorSubmission;
use App\Models\OfficerSubmission;
use App\Models\Organization;
use App\Models\OrganizationSchoolYear;
use App\Models\OrgConstitutionSubmission;
use App\Models\OrgMembership;
use App\Models\PresidentRegistration;
use App\Models\SchoolYear;
use App\Models\StrategicPlanSubmission;
use Illuminate\Http\Request;

class AdminReregHubController extends Controller
{

    private function ctx(Request $request): array
    {
        return [
            'orgId' => (int) $request->session()->get('active_org_id'),
            'syId'  => (int) $request->session()->get('encode_sy_id'), 
            'userId'=> (int) $request->user()->id,
        ];
    }


    public function hub(Request $request, Organization $organization)
    {
        ['orgId' => $orgId, 'syId' => $syId, 'userId' => $userId] = $this->ctx($request);

        $encodeSyId = (int) $request->session()->get('encode_sy_id');

        if (!$encodeSyId) {
            $encodeSyId = (int) SchoolYear::where('is_active', true)->value('id');

            if ($encodeSyId) {
                $request->session()->put('encode_sy_id', $encodeSyId);
            }
        }

        abort_if(!$encodeSyId, 403, 'No school year selected.');

        $schoolYears = SchoolYear::orderByDesc('id')->get();

        $b1 = StrategicPlanSubmission::where('organization_id', $organization->id)
            ->where('target_school_year_id', $encodeSyId)
            ->latest()->first();

        $b2 = PresidentRegistration::where('organization_id', $organization->id)
            ->where('target_school_year_id', $encodeSyId)
            ->latest()->first();

        $b3 = OfficerSubmission::where('organization_id', $organization->id)
            ->where('target_school_year_id', $encodeSyId)
            ->latest()->first();

        $b5 = ModeratorSubmission::where('organization_id', $organization->id)
            ->where('target_school_year_id', $encodeSyId)
            ->latest()->first();

        $b6 = OrgConstitutionSubmission::where('organization_id', $organization->id)
            ->where('school_year_id', $encodeSyId)
            ->latest()->first();

        $presidentMembership = OrgMembership::where('organization_id', $organization->id)
            ->where('school_year_id', $encodeSyId)
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

        $b5Moderator = OrgMembership::where('organization_id', $organization->id)
            ->where('school_year_id', $encodeSyId)
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
                'editRoute' => null,
                'submission' => $b1,
            ],

            'b2' => [
                'label' => 'President Profile',
                'badge' => $this->b2Badge($presidentUser, $isPresidentProfileComplete),
                'submitted_at' => $b2?->submitted_at,
                'reviewed_at'  => $b2?->reviewed_at,
                'approved_at'  => $b2?->approved_at,
                'editRoute' => null,
                'submission' => $b2,
            ],

            'b3' => [
                'label' => 'Officers List',
                'badge' => $this->statusBadge($b3?->status),
                'submitted_at' => $b3?->submitted_at,
                'reviewed_at'  => $b3?->sacdev_reviewed_at,
                'approved_at'  => $b3?->approved_at,
                'editRoute' => null,
                'submission' => $b3,
            ],

            'b5' => [
                'label' => 'Moderator Profile',
                'badge' => $this->b5Badge($b5, false, true),
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
            $this->isApproved($b1?->status)   // Strategic Plan approved
            && $this->isApproved($b3?->status) // Officers approved
            && $b5 !== null                   // Moderator submission exists
            && $presidentUser                 // President assigned
            && $b6 !== null;                  // Constitution exists

        $isActivated = false;

        if ($syId) {
            $isActivated = OrganizationSchoolYear::query()
                ->where('organization_id',  $organization->id)
                ->where('school_year_id', $syId)
                ->exists();
        }

        $isPresident = OrgMembership::where('user_id', $userId)
            ->where('organization_id', $organization->id)
            ->where('school_year_id', $encodeSyId)
            ->where('role', 'president')
            ->whereNull('archived_at')
            ->exists();

        return view('org.rereg.index', [
            'organization' => $organization,
            'schoolYears' => $schoolYears,
            'encodeSyId' => $encodeSyId,
            'forms' => $forms,
            'allApproved' => $allApproved,
            'isActivated' => $isActivated,
            'canAssignModerator' => false,
            'isModerator' => false,
            'isProfileComplete' => true,
            'b5Moderator' => $b5Moderator,
            'constitutionSubmission' => $b6,
            'presidentUser' => $presidentUser,
            'isPresidentProfileComplete' => $isPresidentProfileComplete,
            'isAdminReregHub' => true,
            'isPresident' => $isPresident,
            
        ]);
    }

    private function b5Badge($b5, bool $isModerator, bool $isProfileComplete): array {
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