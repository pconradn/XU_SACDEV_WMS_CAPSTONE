<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use App\Models\ModeratorSubmission;
use App\Models\OrgMembership;
use Illuminate\Http\Request;

class ModeratorSubmissionController extends Controller
{
    private function ctx(Request $request): array
    {
        return [
            'user' => auth()->user(),
            'orgId' => (int) $request->session()->get('active_org_id'),
            'syId' => (int) $request->session()->get('encode_sy_id'),
        ];
    }

    public function edit(Request $request)
    {
        ['user' => $user, 'orgId' => $orgId, 'syId' => $syId] = $this->ctx($request);

        if (!$orgId || !$syId) {
            return redirect()->route('org.home')
                ->with('error', 'Missing organization or school year.');
        }

        $isModerator = OrgMembership::query()
            ->where('user_id', $user->id)
            ->where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->where('role', 'moderator')
            ->whereNull('archived_at')
            ->exists();

        $submission = ModeratorSubmission::query()
            ->where('organization_id', $orgId)
            ->where('target_school_year_id', $syId)
            ->first();

        $moderatorUser = $submission?->moderatorUser;

        if (!$moderatorUser && $isModerator) {
            $moderatorUser = $user;
        }

        $profile = $moderatorUser?->profile;

        $missingFields = [];

        if (!$profile?->first_name) $missingFields[] = 'First Name';
        if (!$profile?->last_name) $missingFields[] = 'Last Name';
        if (!$profile?->mobile_number) $missingFields[] = 'Mobile Number';
        if (!$profile?->email) $missingFields[] = 'Email';
        if (!$profile?->city_address) $missingFields[] = 'City Address';
        if (!$profile?->university_designation) $missingFields[] = 'University Designation';
        if (!$profile?->unit_department) $missingFields[] = 'Unit / Department';
        if (!$profile?->employment_status) $missingFields[] = 'Employment Status';
        if (!$profile?->years_of_service) $missingFields[] = 'Years of Service';

        $isProfileComplete = empty($missingFields);
        $isSubmissionComplete = $submission 
            && in_array($submission->status, [
                'submitted',
                'submitted_to_moderator',
                'submitted_to_sacdev',
                'approved',
                'approved_by_sacdev'
            ]);

        $submission = ModeratorSubmission::query()
            ->where('organization_id', $orgId)
            ->where('target_school_year_id', $syId)
            ->first();




        return view('org.rereg.moderator_submission', [
            'submission' => $submission,
            'profile' => $profile,
            'moderatorUser' => $moderatorUser,
            'isModerator' => $isModerator,
            'isProfileComplete' => $isProfileComplete,
            'missingFields' => $missingFields,
            'isSubmissionComplete' => $isSubmissionComplete,
        ]);
    }

    public function update(Request $request)
    {
        ['user' => $user, 'orgId' => $orgId, 'syId' => $syId] = $this->ctx($request);

        $isModerator = OrgMembership::query()
            ->where('user_id', $user->id)
            ->where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->where('role', 'moderator')
            ->whereNull('archived_at')
            ->exists();

        if (!$isModerator) {
            abort(403);
        }

        $profile = $user->profile;

        $required = [
            $profile?->first_name,
            $profile?->last_name,
            $profile?->mobile_number,
            $profile?->email,
            $profile?->city_address,
            $profile?->university_designation,
            $profile?->unit_department,
            $profile?->employment_status,
            $profile?->years_of_service,
        ];

        if (in_array(null, $required, true)) {
            return redirect()->route('org.profile.edit')
                ->with('error', 'Complete your profile first.');
        }

        $data = $request->validate([
            'was_moderator_before' => ['required','boolean'],
            'moderated_org_name' => ['nullable','string','max:255'],
            'served_nominating_org_before' => ['required','boolean'],
            'served_nominating_org_years' => ['nullable','integer','min:0'],
        ]);

        ModeratorSubmission::updateOrCreate(
            [
                'organization_id' => $orgId,
                'target_school_year_id' => $syId,
                'moderator_user_id' => $user->id,
            ],
            $data
        );

        return back()->with('success', 'Moderator submission saved.');
    }

    public function view(Request $request, $orgId, $syId)
    {
        $submission = ModeratorSubmission::query()
            ->where('organization_id', $orgId)
            ->where('target_school_year_id', $syId)
            ->latest('id')
            ->first();

        if (!$submission) {
            $moderatorUser = OrgMembership::query()
                ->where('organization_id', $orgId)
                ->where('school_year_id', $syId)
                ->where('role', 'moderator')
                ->with('user.profile')
                ->first()?->user;
        } else {
            $moderatorUser = $submission->moderatorUser;
        }

        


        $profile = $moderatorUser?->profile;

        $missingFields = [];

        if (!$profile?->first_name) $missingFields[] = 'First Name';
        if (!$profile?->last_name) $missingFields[] = 'Last Name';
        if (!$profile?->mobile_number) $missingFields[] = 'Mobile Number';
        if (!$profile?->email) $missingFields[] = 'Email';
        if (!$profile?->city_address) $missingFields[] = 'City Address';
        if (!$profile?->university_designation) $missingFields[] = 'University Designation';
        if (!$profile?->unit_department) $missingFields[] = 'Unit / Department';
        if (!$profile?->employment_status) $missingFields[] = 'Employment Status';
        if (!$profile?->years_of_service) $missingFields[] = 'Years of Service';

        $isProfileComplete = empty($missingFields);
        $isSubmissionComplete = $submission 
            && in_array($submission->status, [
                'submitted',
                'submitted_to_moderator',
                'submitted_to_sacdev',
                'approved',
                'approved_by_sacdev'
            ]);

        return view('org.rereg.moderator_submission', [
            'submission' => $submission,
            'profile' => $profile,
            'moderatorUser' => $moderatorUser,
            'isModerator' => false,
            'isProfileComplete' => $isProfileComplete,
            'missingFields' => $missingFields,
            'isAdminView' => true,
            'isSubmissionComplete' => $isSubmissionComplete,
        ]);
    }



}