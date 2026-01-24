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
        // Make sure only one active SY
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
            ['email' => 'sacdev.admin@xu.edu.ph'],
            [
                'name' => 'SacDev Admin',
                'password' => Hash::make('Admin1234!'),
                'system_role' => 'sacdev_admin',
                'must_change_password' => false, // admin can already access right away
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
        // 4) Presidents (1 per org)
        // ---------------------------
        $pres1 = User::query()->firstOrCreate(
            ['email' => 'president.xucs@xu.edu.ph'],
            [
                'name' => 'President - XUCS',
                'password' => Hash::make('TempPass123!'),
                'system_role' => null,
                'must_change_password' => true,
                'password_changed_at' => null,
            ]
        );

        $pres2 = User::query()->firstOrCreate(
            ['email' => 'president.xuti@xu.edu.ph'],
            [
                'name' => 'President - XUTI',
                'password' => Hash::make('TempPass123!'),
                'system_role' => null,
                'must_change_password' => true,
                'password_changed_at' => null,
            ]
        );

        // ---------------------------
        // 5) organization_school_years setup
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
        // 7) Officers list (5 per org)
        // ---------------------------
        $xucsOfficers = [
            ['full_name' => 'Treasurer XUCS', 'email' => 'treasurer.xucs@xu.edu.ph', 'position' => 'Treasurer'],
            ['full_name' => 'Moderator XUCS', 'email' => 'moderator.xucs@xu.edu.ph', 'position' => 'Moderator'],
            ['full_name' => 'Officer A XUCS', 'email' => 'officerA.xucs@xu.edu.ph', 'position' => 'Secretary'],
            ['full_name' => 'Officer B XUCS', 'email' => 'officerB.xucs@xu.edu.ph', 'position' => 'PRO'],
            ['full_name' => 'Officer C XUCS', 'email' => 'officerC.xucs@xu.edu.ph', 'position' => 'Auditor'],
        ];

        foreach ($xucsOfficers as $o) {
            OfficerEntry::query()->updateOrCreate(
                [
                    'organization_id' => $org1->id,
                    'school_year_id' => $sy->id,
                    'email' => $o['email'],
                ],
                [
                    'full_name' => $o['full_name'],
                    'position' => $o['position'],
                ]
            );
        }

        $xutiOfficers = [
            ['full_name' => 'Treasurer XUTI', 'email' => 'treasurer.xuti@xu.edu.ph', 'position' => 'Treasurer'],
            ['full_name' => 'Moderator XUTI', 'email' => 'moderator.xuti@xu.edu.ph', 'position' => 'Moderator'],
            ['full_name' => 'Officer A XUTI', 'email' => 'officerA.xuti@xu.edu.ph', 'position' => 'Secretary'],
            ['full_name' => 'Officer B XUTI', 'email' => 'officerB.xuti@xu.edu.ph', 'position' => 'PRO'],
            ['full_name' => 'Officer C XUTI', 'email' => 'officerC.xuti@xu.edu.ph', 'position' => 'Auditor'],
        ];

        foreach ($xutiOfficers as $o) {
            OfficerEntry::query()->updateOrCreate(
                [
                    'organization_id' => $org2->id,
                    'school_year_id' => $sy->id,
                    'email' => $o['email'],
                ],
                [
                    'full_name' => $o['full_name'],
                    'position' => $o['position'],
                ]
            );
        }

        // ---------------------------
        // 8) Projects (2 per org)
        // ---------------------------
        $org1Projects = [
            'Hackathon Training Week',
            'Community Coding Workshop',
        ];

        foreach ($org1Projects as $title) {
            Project::query()->firstOrCreate([
                'organization_id' => $org1->id,
                'school_year_id' => $sy->id,
                'title' => $title,
            ]);
        }

        $org2Projects = [
            'AI Awareness Seminar',
            'Tech Expo Booth Setup',
        ];

        foreach ($org2Projects as $title) {
            Project::query()->firstOrCreate([
                'organization_id' => $org2->id,
                'school_year_id' => $sy->id,
                'title' => $title,
            ]);
        }

        $this->command?->info('✅ Sprint1Seeder completed successfully!');
        $this->command?->warn('Admin Login: sacdev.admin@xu.edu.ph / Admin1234!');
        $this->command?->warn('President Login XUCS: president.xucs@xu.edu.ph / TempPass123!');
        $this->command?->warn('President Login XUTI: president.xuti@xu.edu.ph / TempPass123!');
    }
}
