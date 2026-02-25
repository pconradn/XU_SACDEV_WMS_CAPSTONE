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

//ASSIGN PRESIDENT EMAIL TO ORG, TEMPORARY ORG ONBOARDING

class OrganizationPresidentController extends Controller
{

    public function index(Request $request)
    {
        $schoolYears = SchoolYear::query()
            ->orderByDesc('id')
            ->get(['id', 'name', 'is_active']);

        $selectedSyId = (int) $request->query('school_year_id', 0);

        $organizations = Organization::query()
            ->orderBy('name')
            ->get(['id', 'name', 'acronym']);

        $assignedMap = collect();
        if ($selectedSyId > 0) {
            $assignedMap = OfficerEntry::query()
                ->where('school_year_id', $selectedSyId)
                ->where('major_officer_role', 'president')
                ->where('is_major_officer', true)
                ->get(['organization_id', 'full_name', 'student_id_number', 'user_id'])
                ->keyBy('organization_id');
        }

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
            'president_name'    => ['required', 'string', 'max:255'],
            'student_id_number' => ['required', 'string', 'max:50'],
        ]);

        $orgId = (int) $data['organization_id'];
        $syId  = (int) $data['school_year_id'];
        $email = trim($data['student_id_number']) . '@my.xu.edu.ph';

        
        $alreadyPresidentElsewhere = OfficerEntry::query()
            ->where('school_year_id', $syId)
            ->where('major_officer_role', 'president')
            ->where('is_major_officer', true)
            ->where('student_id_number', $data['student_id_number'])
            ->where('organization_id', '!=', $orgId)
            ->exists();

        if ($alreadyPresidentElsewhere) {
            return back()->withErrors([
                'student_id_number' =>
                    'This student is already assigned as President in another organization for this school year.',
            ])->withInput();
        }

    
        $existingPresidentEntry = OfficerEntry::query()
            ->where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->where('major_officer_role', 'president')
            ->where('is_major_officer', true)
            ->first();

        if ($existingPresidentEntry && $existingPresidentEntry->user_id) {
            $existingUser = User::find($existingPresidentEntry->user_id);

            if ($existingUser && (int) $existingUser->must_change_password === 0) {
                return back()->withErrors([
                    'president_name' =>
                        'This organization already has an activated President account. ' .
                        'To change President due to suspension, use the Major Officer Roles page (Active SY only).',
                ])->withInput();
            }
        }

        try {
            DB::transaction(function () use ($data, $orgId, $syId, $email, $existingPresidentEntry) {

                $user = null;

    
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

                
                        $existingUser->name  = $data['president_name'];
                        $existingUser->email = $email;
                        $existingUser->save();

                    
                        AccountProvisioner::resetTempPasswordIfPending($existingUser);

                        $user = $existingUser;
                    }
                }

    
                if (!$user) {
                    [$user, $tempPassword] = AccountProvisioner::findOrCreateUser(
                        $data['president_name'],
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
                        'full_name'         => $data['president_name'],
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

            
                AccountProvisioner::ensureBasicOrgAccess(
                    $user->id,
                    $orgId,
                    $syId,
                    $officerEntry->id
                );

                $actor = Auth::user();
                $organization = Organization::find($orgId);
                $schoolYear = SchoolYear::find($syId);

                Audit::log(
                    'president_assigned',
                    "President {$data['president_name']} assigned to {$organization->name}",
                    [
                        'actor_user_id'   => $actor->id,
                        'organization_id' => $orgId,     
                        'school_year_id'  => $syId,      

                        'meta' => [
                            'actor_name'        => $actor->name,
                            'organization_name' => $organization->name,
                            'school_year_name'  => $schoolYear->name,
                            'assigned_email'       => $email,
                            'replaced_existing' => $existingPresidentEntry ? true : false,
                        ],
                    ]
                );


            });



        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        return back()->with('status', 'President assigned successfully.');
    }




    public function create()
    {
        $organizations = Organization::query()->orderBy('name')->get();
        $schoolYears = SchoolYear::query()->orderByDesc('start_date')->orderByDesc('id')->get();

        return view('admin.organizations.assign-president', compact('organizations', 'schoolYears'));
    }



    public function store(Request $request)
    {
        $data = $request->validate([
            'organization_id' => ['required', 'exists:organizations,id'],
            'school_year_id' => ['required', 'exists:school_years,id'],
            'president_name' => ['required', 'string', 'max:255'],
            'student_id_number' => ['required', 'string', 'max:50'],
        ]);

        $email = $data['student_id_number'] . '@my.xu.edu.ph';

        DB::transaction(function () use ($data, $email) {

            /*
            |--------------------------------------------------------------------------
            | RULE: President cannot be president of another org in same SY
            |--------------------------------------------------------------------------
            */

            $existingPresident = \App\Models\OfficerEntry::query()
                ->where('school_year_id', $data['school_year_id'])
                ->where('major_officer_role', 'president')
                ->where('student_id_number', $data['student_id_number'])
                ->exists();

            if ($existingPresident) {
                abort(422, 'This student is already a president of another organization in this school year.');
            }

            /*
            |--------------------------------------------------------------------------
            | STEP 1: Create or Update OfficerEntry (ACADEMIC RECORD)
            |--------------------------------------------------------------------------
            */

            $officerEntry = \App\Models\OfficerEntry::updateOrCreate(
                [
                    'organization_id' => $data['organization_id'],
                    'school_year_id' => $data['school_year_id'],
                    'student_id_number' => $data['student_id_number'],
                ],
                [
                    'full_name' => $data['president_name'],
                    'email' => $email,

                    'position' => 'President',

                    'major_officer_role' => 'president',
                    'is_major_officer' => true,

                    'is_under_probation' => false,
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | STEP 2: Create or Find User (LOGIN ACCOUNT)
            |--------------------------------------------------------------------------
            */

            [$user, $tempPassword] =
                \App\Support\AccountProvisioner::findOrCreateUser(
                    $data['president_name'],
                    $email
                );

            /*
            |--------------------------------------------------------------------------
            | STEP 3: Link OfficerEntry → User
            |--------------------------------------------------------------------------
            */

            $officerEntry->update([
                'user_id' => $user->id
            ]);

            /*
            |--------------------------------------------------------------------------
            | STEP 4: Archive Existing President Membership
            |--------------------------------------------------------------------------
            */

            \App\Models\OrgMembership::query()
                ->where('organization_id', $data['organization_id'])
                ->where('school_year_id', $data['school_year_id'])
                ->where('role', 'president')
                ->whereNull('archived_at')
                ->update([
                    'archived_at' => now(),
                ]);

            /*
            |--------------------------------------------------------------------------
            | STEP 5: Create New Membership
            |--------------------------------------------------------------------------
            */

            \App\Models\OrgMembership::create([
                'organization_id' => $data['organization_id'],
                'school_year_id' => $data['school_year_id'],

                'user_id' => $user->id,
                'officer_entry_id' => $officerEntry->id,

                'role' => 'president',
            ]);

        });

        return redirect()
            ->route('admin.organizations.index')
            ->with('status', 'President assigned successfully.');
    }



}
