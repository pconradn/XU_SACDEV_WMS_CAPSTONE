<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OfficerEntry;
use App\Models\Organization;
use App\Models\OrganizationSchoolYear;
use App\Models\OrgMembership;
use App\Models\SchoolYear;
use App\Models\User;
use App\Support\AccountProvisioner;
use App\Support\Audit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;



class OrganizationPresidentController extends Controller
{

    public function index(Request $request)
    {
        $schoolYears = SchoolYear::query()
            ->orderByDesc('id')
            ->get(['id', 'name', 'is_active']);

        $selectedSyId = (int) $request->query('school_year_id', 0);

        $organizations = Organization::query()
            ->whereNull('archived_at') 
            ->orderBy('name')
            ->get();

        $orgIds = $organizations->pluck('id');

        $assignedMap = \App\Models\OrgMembership::query()
            ->where('school_year_id', $selectedSyId)
            ->where('role', 'president')
            ->whereNull('archived_at')
            ->whereIn('organization_id', $orgIds) 
            ->with('officerEntry')
            ->get()
            ->keyBy('organization_id');



        return view('admin.president_assignments.index', [
            'schoolYears' => $schoolYears,
            'selectedSyId' => $selectedSyId,
            'organizations' => $organizations,
            'assignedMap' => $assignedMap,
        ]);
    }


    public function assign(Request $request)
    {
        $data = $request->validate([
            'organization_id'   => ['required', 'exists:organizations,id'],
            'school_year_id'    => ['required', 'exists:school_years,id'],
            'first_name' => ['required', 'string', 'max:100', 'regex:/^[A-Za-z]+(?:\s[A-Za-z]+)*$/'],
            'middle_initial' => ['required', 'string', 'max:100', 'regex:/^[A-Za-z]+(?:\s[A-Za-z]+)*$/'],
            'last_name'      => ['required', 'string', 'max:100', 'regex:/^[A-Za-z]+(?:[ \-][A-Za-z]+)*$/'],
            'prefix'         => ['nullable', 'string', 'max:20', 'regex:/^[A-Za-z\.]+$/'],
            'student_id_number' => ['required', 'string', 'max:50', 'regex:/^[0-9]{11}$/'],
        ]);

    

        $orgId = (int) $data['organization_id'];
        $syId  = (int) $data['school_year_id'];

        $fullName = trim(collect([
            $data['prefix'] ?? null,
            $data['first_name'],
            isset($data['middle_initial']) ? $data['middle_initial'] . '.' : null,
            $data['last_name'],
        ])->filter()->implode(' '));

        $email = trim($data['student_id_number']) . '@my.xu.edu.ph';

        
        $alreadyPresidentElsewhere = OrgMembership::query()
            ->where('school_year_id', $syId)
            ->where('role', 'president')
            ->whereNull('archived_at')
            ->whereHas('officerEntry', function ($q) use ($data) {
                $q->where('student_id_number', $data['student_id_number']);
            })
            ->where('organization_id', '!=', $orgId)
            ->exists();

        if ($alreadyPresidentElsewhere) {
            return back()->withErrors([
                'student_id_number' =>
                    'This student is already assigned as a major officer in another organization for this school year.',
            ])->withInput();
        }

            
            
        $existingMembership = OrgMembership::query()
            ->where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->where('role', 'president')
            ->whereNull('archived_at')
            ->with('officerEntry')
            ->first();

        $existingPresidentEntry = $existingMembership?->officerEntry;


        $hasOrgSyEntry = \App\Models\OrganizationSchoolYear::query()
            ->where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->exists();

        $forceReplace = $request->boolean('force_replace');

        if ($existingPresidentEntry && $existingPresidentEntry->user_id) {

            $existingUser = User::find($existingPresidentEntry->user_id);

            $isPending = $existingUser
                && $existingUser->must_change_password
                && $existingUser->password_changed_at === null;

            if (!$isPending && !$forceReplace) {
                return back()->withErrors([
                    'president_name' =>
                        'This organization already has an activated President. Use replace action if needed.',
                ])->withInput();
            }
        }

        try {
            DB::transaction(function () use ($fullName, $data, $orgId, $syId, $email, $existingPresidentEntry) {

                $user = null;

                $forceReplace = request()->boolean('force_replace');

                if ($existingPresidentEntry && $forceReplace) {
                    OfficerEntry::query()
                        ->where('organization_id', $orgId)
                        ->where('school_year_id', $syId)
                        ->where('major_officer_role', 'president')
                        ->update([
                            'is_major_officer' => false
                        ]);
                }
    
                if ($existingPresidentEntry && $existingPresidentEntry->user_id) {
                    $existingUser = User::find($existingPresidentEntry->user_id);

                    $isPending = $existingUser
                        && $existingUser->must_change_password
                        && $existingUser->password_changed_at === null;

                    if ($isPending) {

                
                        if ($existingUser->email !== $email) {
                            $emailTaken = User::query()
                                ->where('email', $email)
                                ->where('id', '!=', $existingUser->id)
                                ->exists();

                            if ($emailTaken) {
                                throw ValidationException::withMessages([
                                    'student_id_number' =>
                                        'Cannot assign this student ID because the email is already used by another account.',
                                ]);
                            }
                        }

                
                 
                        $existingUser->first_name = $data['first_name'];
                        $existingUser->middle_initial = $data['middle_initial'] ?? null;
                        $existingUser->last_name = $data['last_name'];
                        $existingUser->prefix = $data['prefix'] ?? null;
                        $existingUser->email = $email;
                        $existingUser->save();

                    
                        AccountProvisioner::resetTempPasswordIfPending($existingUser);

                        $user = $existingUser;
                    }
                }

    
                if (!$user) {
                    [$user, $tempPassword] = AccountProvisioner::findOrCreateUser(
                        $fullName,
                        $email
                    );

                
                    if ($tempPassword === null && $user->must_change_password && $user->password_changed_at === null) {
                        AccountProvisioner::resetTempPasswordIfPending($user);
                    }
                }

    
                $officerEntry = OfficerEntry::updateOrCreate(
                    [
                        'organization_id'   => $orgId,
                        'school_year_id'    => $syId,
                        'student_id_number' => $data['student_id_number'],
                    ],
                    [
                        'full_name'         => $fullName,
                        'email'             => $email,
                        'position'          => 'President',

                        'major_officer_role' => 'president',
                        'is_major_officer'   => true,

                        'user_id'           => $user->id,
                    ]
                );

            
                OrgMembership::query()
                    ->where('organization_id', $orgId)
                    ->where('school_year_id', $syId)
                    ->where('role', 'president')
                    ->whereNull('archived_at')
                    ->update(['archived_at' => now()]);

        
                AccountProvisioner::ensureMembership(
                    $user->id,
                    $orgId,
                    $syId,
                    'president',
                    $officerEntry->id
                );


                $user->profile()->updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'prefix' => $data['prefix'] ?? null,
                        'first_name' => $data['first_name'],
                        'middle_initial' => $data['middle_initial'] ?? null,
                        'last_name' => $data['last_name'],
                        'full_name' => $fullName,
                    ]
                );

            


                $actor = Auth::user();
                $organization = Organization::find($orgId);
                $schoolYear = SchoolYear::find($syId);

                Audit::log(
                    'president_assigned',
                    "President {$fullName} assigned to {$organization->name}",
                    [
                        'actor_user_id'   => $actor->id,
                        'organization_id' => $orgId,     
                        'school_year_id'  => $syId,      

                        'meta' => [
                            'actor_name'        => $actor->name,
                            'organization_name' => $organization->name,
                            'school_year_name'  => $schoolYear->name,
                            'assigned_email'       => $email,
                            'replaced_existing' => $existingPresidentEntry && $existingPresidentEntry->user_id ? true : false,
                        ],
                    ]
                );


            });



        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        return back()->with('success', 'President assigned successfully.');
    }



}
