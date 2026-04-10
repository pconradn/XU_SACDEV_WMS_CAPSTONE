<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserProfile;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        $authUser = $request->user();

        $targetUserId = $request->route('user');

        $profileUser = $targetUserId
            ? \App\Models\User::findOrFail($targetUserId)
            : $authUser;

        $orgId = (int) $request->session()->get('active_org_id');
        $syId  = (int) $request->session()->get('encode_sy_id');

        $isSameUser = $authUser->id === $profileUser->id;

        $isSameOrg = \App\Models\OrgMembership::query()
            ->where('user_id', $profileUser->id)
            ->where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->whereNull('archived_at')
            ->exists();

        if (!$isSameUser && !$isSameOrg) {
            abort(403);
        }

        $profile = $profileUser->profile;

        if (!$profile) {
            $profile = new \App\Models\UserProfile([
                'user_id' => $profileUser->id
            ]);
        }

        $isOwner = $authUser->id === $profileUser->id;

        return view('org.profile.edit', [
            'profile' => $profile,
            'user' => $profileUser,
            'isOwner' => $isOwner,
        ]);
    }

    protected function buildFullName(array $data): string
    {
        $parts = [];

        if (!empty($data['prefix'])) {
            $parts[] = trim($data['prefix']);
        }

        if (!empty($data['first_name'])) {
            $parts[] = trim($data['first_name']);
        }

        if (!empty($data['middle_initial'])) {
            $parts[] = trim($data['middle_initial']) . '.';
        }

        if (!empty($data['last_name'])) {
            $parts[] = trim($data['last_name']);
        }

        return implode(' ', $parts);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $profile = $user->profile ?? new UserProfile([
            'user_id' => $user->id
        ]);

        $isModerator = $user->isModerator();

        $data = $request->validate([

            'photo_id' => ['nullable','image','mimes:jpg,jpeg,png','max:2048'],
            'skills_and_interests' => ['nullable','string'],

            'prefix' => ['nullable','string','max:50'],
            'first_name' => ['required','string','max:100','regex:/^[A-Za-z\s]+$/'],
            'middle_initial' => ['nullable','string','max:5','regex:/^[A-Za-z]?$/'],
            'last_name' => ['required','string','max:100','regex:/^[A-Za-z\s]+$/'],
            'birthday' => ['nullable','date'],
            'sex' => ['nullable','string','max:20'],
            'religion' => ['nullable','string','max:100'],

            'mobile_number' => ['required','string','max:20'],
            'email' => ['nullable','email','max:255'],
            'landline' => ['nullable','string','max:20'],
            'facebook_url' => ['nullable','url'],

            'home_address' => ['required','string'],
            'city_address' => ['nullable','string'],

            'skills_and_interests' => ['nullable','string'],

            'university_designation' => [$isModerator ? 'required' : 'nullable','string','max:255'],
            'unit_department' => [$isModerator ? 'required' : 'nullable','string','max:255'],
            'employment_status' => [$isModerator ? 'required' : 'nullable','string','max:100'],
            'years_of_service' => [$isModerator ? 'required' : 'nullable','integer','min:0'],

     
            'leaderships' => ['nullable','array'],
            'leaderships.*.organization_name' => ['required_with:leaderships','string'],
            'leaderships.*.position' => ['nullable','string'],
            'leaderships.*.organization_address' => ['nullable','string'],
            'leaderships.*.inclusive_years' => ['nullable','string'],

        
            'trainings' => ['nullable','array'],
            'trainings.*.seminar_title' => ['required_with:trainings','string'],
            'trainings.*.organizer' => ['nullable','string'],
            'trainings.*.venue' => ['nullable','string'],
            'trainings.*.date_from' => ['nullable','date'],
            'trainings.*.date_to' => ['nullable','date'],

        
            'awards' => ['nullable','array'],
            'awards.*.award_name' => ['required_with:awards','string'],
            'awards.*.award_description' => ['nullable','string'],
            'awards.*.conferred_by' => ['nullable','string'],
            'awards.*.date_received' => ['nullable','date'],
        ]);
        
        if ($request->hasFile('photo_id')) {
            $path = $request->file('photo_id')->store('profile_ids', 'public');
            $data['photo_id_path'] = $path;
        }

        $data['full_name'] = $this->buildFullName($data);
        $profile->fill($data);
        $profile->user_id = $user->id;
        
        $profile->save();

        $user->name = $data['full_name'];
        $user->first_name = $data['first_name'];
        $user->middle_initial = $data['middle_initial'];
        $user->last_name = $data['last_name'];
        $user->prefix = $data['prefix'];
        $user->save();


        $profile->leaderships()->delete();

        if (!empty($data['leaderships'])) {
            foreach ($data['leaderships'] as $index => $item) {
                $profile->leaderships()->create([
                    'organization_name' => $item['organization_name'],
                    'position' => $item['position'] ?? null,
                    'organization_address' => $item['organization_address'] ?? null,
                    'inclusive_years' => $item['inclusive_years'] ?? null,
                    'sort_order' => $index,
                ]);
            }
        }

    
        $profile->trainings()->delete();

        if (!empty($data['trainings'])) {
            foreach ($data['trainings'] as $index => $item) {
                $profile->trainings()->create([
                    'seminar_title' => $item['seminar_title'],
                    'organizer' => $item['organizer'] ?? null,
                    'venue' => $item['venue'] ?? null,
                    'date_from' => $item['date_from'] ?? null,
                    'date_to' => $item['date_to'] ?? null,
                    'sort_order' => $index,
                ]);
            }
        }


        $profile->awards()->delete();

        if (!empty($data['awards'])) {
            foreach ($data['awards'] as $index => $item) {
                $profile->awards()->create([
                    'award_name' => $item['award_name'],
                    'award_description' => $item['award_description'] ?? null,
                    'conferred_by' => $item['conferred_by'] ?? null,
                    'date_received' => $item['date_received'] ?? null,
                    'sort_order' => $index,
                ]);
            }
        }

        return back()->with('success', 'Profile updated successfully.');
    }

    //admin
    public function view($userId)
    {
        $profileUser = \App\Models\User::findOrFail($userId);

        $profile = $profileUser->profile;

        if (!$profile) {
            $profile = new \App\Models\UserProfile([
                'user_id' => $profileUser->id
            ]);
        }

        return view('org.profile.edit', [
            'profile' => $profile,
            'user' => $profileUser,
            'isOwner' => false,
        ]);
    }


}