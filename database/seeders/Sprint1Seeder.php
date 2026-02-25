<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\OrganizationSchoolYear;
use App\Models\OrgMembership;
use App\Models\OfficerEntry;
use App\Models\Project;
use App\Models\SchoolYear;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class Sprint1Seeder extends Seeder
{
    public function run(): void
    {
        // ---------------------------
        // 1) ACTIVE SCHOOL YEAR
        // ---------------------------
        SchoolYear::query()->update(['is_active' => false]);

        $sy = SchoolYear::query()->firstOrCreate(
            ['name' => '2025-2026'],
            [
                'start_date' => '2025-08-01',
                'end_date' => '2026-05-31',
                'is_active' => true,
            ]
        );

        $sy->update(['is_active' => true]);

        // ---------------------------
        // 2) SacDev Admin account
        // ---------------------------
        $admin = User::query()->firstOrCreate(
            ['email' => 'sacdev.admin@my.xu.edu.ph'],
            [
                'name' => 'SacDev Admin',
                'password' => Hash::make('Admin1234!'),
                'system_role' => 'sacdev_admin',
                'must_change_password' => false,
                'password_changed_at' => now(),
            ]
        );

        // ---------------------------
        // 3) Organizations
        // ---------------------------
        $org1 = Organization::query()->firstOrCreate(
            ['name' => 'XU Coding Society'],
            ['acronym' => 'XUCS']
        );

        $org2 = Organization::query()->firstOrCreate(
            ['name' => 'XU Tech Innovators'],
            ['acronym' => 'XUTI']
        );

        // ---------------------------
        // 4) Presidents
        // ---------------------------
        $pres1 = User::query()->firstOrCreate(
            ['email' => 'president.xucs@my.xu.edu.ph'],
            [
                'name' => 'President - XUCS',
                'password' => Hash::make('TempPass123!'),
                'system_role' => null,
                'must_change_password' => true,
                'password_changed_at' => null,
            ]
        );

        $pres2 = User::query()->firstOrCreate(
            ['email' => 'president.xuti@my.xu.edu.ph'],
            [
                'name' => 'President - XUTI',
                'password' => Hash::make('TempPass123!'),
                'system_role' => null,
                'must_change_password' => true,
                'password_changed_at' => null,
            ]
        );

        // ---------------------------
        // 5) organization_school_years
        // ---------------------------
        OrganizationSchoolYear::query()->updateOrCreate(
            [
                'organization_id' => $org1->id,
                'school_year_id' => $sy->id,
            ],
            [
                'president_user_id' => $pres1->id,
                'president_confirmed_at' => null,
            ]
        );

        OrganizationSchoolYear::query()->updateOrCreate(
            [
                'organization_id' => $org2->id,
                'school_year_id' => $sy->id,
            ],
            [
                'president_user_id' => $pres2->id,
                'president_confirmed_at' => null,
            ]
        );

        // ---------------------------
        // 6) President memberships
        // ---------------------------
        OrgMembership::query()->firstOrCreate([
            'organization_id' => $org1->id,
            'school_year_id' => $sy->id,
            'user_id' => $pres1->id,
            'role' => 'president',
        ]);

        OrgMembership::query()->firstOrCreate([
            'organization_id' => $org2->id,
            'school_year_id' => $sy->id,
            'user_id' => $pres2->id,
            'role' => 'president',
        ]);

        // ---------------------------
        // Helper: Create user for officer email (for Sprint1)
        // ---------------------------
        $makeOfficerUser = function (string $name, string $email) {
            return User::query()->firstOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'password' => Hash::make('TempPass123!'),
                    'system_role' => null,
                    'must_change_password' => true,
                    'password_changed_at' => null,
                ]
            );
        };

        // ---------------------------
        // 7) Officers list (5 per org) + link user_id
        // ---------------------------
        $xucsOfficers = [
            ['full_name' => 'Treasurer XUCS', 'email' => 'treasurer.xucs@my.xu.edu.ph', 'position' => 'Treasurer'],
            ['full_name' => 'Moderator XUCS', 'email' => 'moderator.xucs@my.xu.edu.ph', 'position' => 'Moderator'],
            ['full_name' => 'Officer A XUCS', 'email' => 'officerA.xucs@my.xu.edu.ph', 'position' => 'Secretary'],
            ['full_name' => 'Officer B XUCS', 'email' => 'officerB.xucs@my.xu.edu.ph', 'position' => 'PRO'],
            ['full_name' => 'Officer C XUCS', 'email' => 'officerC.xucs@my.xu.edu.ph', 'position' => 'Auditor'],
        ];

        foreach ($xucsOfficers as $o) {
            $user = $makeOfficerUser($o['full_name'], $o['email']);

            OfficerEntry::query()->updateOrCreate(
                [
                    'organization_id' => $org1->id,
                    'school_year_id' => $sy->id,
                    'email' => $o['email'],
                ],
                [
                    'user_id' => $user->id,
                    'full_name' => $o['full_name'],
                    'position' => $o['position'],
                ]
            );
        }

        $xutiOfficers = [
            ['full_name' => 'Treasurer XUTI', 'email' => 'treasurer.xuti@my.xu.edu.ph', 'position' => 'Treasurer'],
            ['full_name' => 'Moderator XUTI', 'email' => 'moderator.xuti@my.xu.edu.ph', 'position' => 'Moderator'],
            ['full_name' => 'Officer A XUTI', 'email' => 'officerA.xuti@my.xu.edu.ph', 'position' => 'Secretary'],
            ['full_name' => 'Officer B XUTI', 'email' => 'officerB.xuti@my.xu.edu.ph', 'position' => 'PRO'],
            ['full_name' => 'Officer C XUTI', 'email' => 'officerC.xuti@my.xu.edu.ph', 'position' => 'Auditor'],
        ];

        foreach ($xutiOfficers as $o) {
            $user = $makeOfficerUser($o['full_name'], $o['email']);

            OfficerEntry::query()->updateOrCreate(
                [
                    'organization_id' => $org2->id,
                    'school_year_id' => $sy->id,
                    'email' => $o['email'],
                ],
                [
                    'user_id' => $user->id, 
                    'full_name' => $o['full_name'],
                    'position' => $o['position'],
                ]
            );
        }

        // ---------------------------
        // 8) Assign Treasurer + Moderator memberships (optional but good)
        // ---------------------------
        $xucsTreas = User::where('email', 'treasurer.xucs@my.xu.edu.ph')->first();
        $xucsMod   = User::where('email', 'moderator.xucs@my.xu.edu.ph')->first();

        if ($xucsTreas) {
            OrgMembership::query()->updateOrCreate(
                [
                    'organization_id' => $org1->id,
                    'school_year_id' => $sy->id,
                    'role' => 'treasurer',
                ],
                [
                    'user_id' => $xucsTreas->id,
                    'archived_at' => null,
                ]
            );
        }

        if ($xucsMod) {
            OrgMembership::query()->updateOrCreate(
                [
                    'organization_id' => $org1->id,
                    'school_year_id' => $sy->id,
                    'role' => 'moderator',
                ],
                [
                    'user_id' => $xucsMod->id,
                    'archived_at' => null,
                ]
            );
        }

        $xutiTreas = User::where('email', 'treasurer.xuti@my.xu.edu.ph')->first();
        $xutiMod   = User::where('email', 'moderator.xuti@my.xu.edu.ph')->first();

        if ($xutiTreas) {
            OrgMembership::query()->updateOrCreate(
                [
                    'organization_id' => $org2->id,
                    'school_year_id' => $sy->id,
                    'role' => 'treasurer',
                ],
                [
                    'user_id' => $xutiTreas->id,
                    'archived_at' => null,
                ]
            );
        }

        if ($xutiMod) {
            OrgMembership::query()->updateOrCreate(
                [
                    'organization_id' => $org2->id,
                    'school_year_id' => $sy->id,
                    'role' => 'moderator',
                ],
                [
                    'user_id' => $xutiMod->id,
                    'archived_at' => null,
                ]
            );
        }

        // ---------------------------
        // 9) Projects (2 per org)
        // ---------------------------
        foreach (['Hackathon Training Week', 'Community Coding Workshop'] as $title) {
            Project::query()->firstOrCreate([
                'organization_id' => $org1->id,
                'school_year_id' => $sy->id,
                'title' => $title,
            ]);
        }

        foreach (['AI Awareness Seminar', 'Tech Expo Booth Setup'] as $title) {
            Project::query()->firstOrCreate([
                'organization_id' => $org2->id,
                'school_year_id' => $sy->id,
                'title' => $title,
            ]);
        }

        $this->command?->info('Sprint1Seeder completed successfully!');
        $this->command?->warn('Admin Login: sacdev.admin@my.xu.edu.ph / Admin1234!');
        $this->command?->warn('President Login XUCS: president.xucs@my.xu.edu.ph / TempPass123!');
        $this->command?->warn('President Login XUTI: president.xuti@my.xu.edu.ph / TempPass123!');
        $this->command?->warn('Officer Login Example: treasurer.xucs@my.xu.edu.ph / TempPass123!');
    }
}
