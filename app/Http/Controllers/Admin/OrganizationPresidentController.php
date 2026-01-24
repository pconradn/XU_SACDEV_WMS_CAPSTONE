<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\OrganizationSchoolYear;
use App\Models\OrgMembership;
use App\Models\SchoolYear;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class OrganizationPresidentController extends Controller
{
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
            'president_email' => ['required', 'email', 'max:255'],
        ]);

        DB::transaction(function () use ($data) {
            $tempPassword = Str::random(12) . '!';

            // Create or update president user
            $user = User::query()->firstOrNew(['email' => $data['president_email']]);
            $user->name = $data['president_name'];

            // If user is new OR you want to reset temp password each time provisioning is done:
            $user->password = Hash::make($tempPassword);
            $user->system_role = null;
            $user->must_change_password = true;
            $user->password_changed_at = null;
            $user->save();

            // Link org + SY to president
            $orgSy = OrganizationSchoolYear::query()->updateOrCreate(
                [
                    'organization_id' => $data['organization_id'],
                    'school_year_id' => $data['school_year_id'],
                ],
                [
                    'president_user_id' => $user->id,
                    'president_confirmed_at' => null,
                ]
            );

            // Ensure president membership exists (and unarchive if previously archived)
            $membership = OrgMembership::query()->firstOrNew([
                'organization_id' => $data['organization_id'],
                'school_year_id' => $data['school_year_id'],
                'user_id' => $user->id,
                'role' => 'president',
            ]);

            $membership->archived_at = null;
            $membership->save();

            // Send email (safe if MAIL_MAILER=log)
            Mail::raw(
                "Hello {$user->name},\n\n" .
                "You have been provisioned as the Organization President for the SAcDev Workflow System.\n\n" .
                "Login email: {$user->email}\n" .
                "Temporary password: {$tempPassword}\n\n" .
                "You will be required to change your password upon first login.\n\n" .
                "Thank you.",
                function ($message) use ($user) {
                    $message->to($user->email)
                        ->subject('SAcDev System - President Account Credentials');
                }
            );
        });

        return redirect()
            ->route('admin.organizations.index')
            ->with('status', 'President account provisioned and linked successfully (email sent/logged).');
    }
}
