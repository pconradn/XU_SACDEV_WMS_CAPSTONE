<?php

namespace Database\Seeders;

use App\Models\Organization;
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
        $password = Hash::make('paul1234');

        // ---------------------------
        // 1) Reset School Years
        // ---------------------------
        SchoolYear::query()->update(['is_active' => false]);

        $sy = SchoolYear::query()->updateOrCreate(
            ['name' => '2025-2026'],
            [
                'start_date' => '2025-08-01',
                'end_date' => '2026-05-31',
                'is_active' => true,
            ]
        );

        // ---------------------------
        // 2) SACDEV Admin
        // ---------------------------
        $admin = User::query()->updateOrCreate(
            ['email' => 'sacdev.admin@my.xu.edu.ph'],
            [
                'name' => 'SACDEV Admin',
                'password' => $password,
                'system_role' => 'sacdev_admin',
                'must_change_password' => false,
                'password_changed_at' => now(),
            ]
        );

        // ---------------------------
        // 3) Organizations
        // ---------------------------
        $org1 = Organization::query()->updateOrCreate(
            ['name' => 'XU Coding Society'],
            ['acronym' => 'XUCS']
        );

        $org2 = Organization::query()->updateOrCreate(
            ['name' => 'XU Tech Innovators'],
            ['acronym' => 'XUTI']
        );

        // ---------------------------
        // 4) Presidents 
        // ---------------------------
        $pres1 = User::query()->updateOrCreate(
            ['email' => 'president.xucs@my.xu.edu.ph'],
            [
                'name' => 'President XUCS',
                'password' => $password,
                'system_role' => null,
                'must_change_password' => false,
                'password_changed_at' => now(),
            ]
        );

        $pres2 = User::query()->updateOrCreate(
            ['email' => 'president.xuti@my.xu.edu.ph'],
            [
                'name' => 'President XUTI',
                'password' => $password,
                'system_role' => null,
                'must_change_password' => false,
                'password_changed_at' => now(),
            ]
        );

        // ---------------------------
        // 5) President memberships 
        // ---------------------------
        OrgMembership::query()->updateOrCreate(
            [
                'organization_id' => $org1->id,
                'school_year_id' => $sy->id,
                'role' => 'president',
            ],
            [
                'user_id' => $pres1->id,
                'archived_at' => null,
            ]
        );

        OrgMembership::query()->updateOrCreate(
            [
                'organization_id' => $org2->id,
                'school_year_id' => $sy->id,
                'role' => 'president',
            ],
            [
                'user_id' => $pres2->id,
                'archived_at' => null,
            ]
        );

        // ---------------------------
        // Helper to create officer user
        // ---------------------------
        $makeUser = function ($name, $email) use ($password) {
            return User::query()->updateOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'password' => $password,
                    'system_role' => null,
                    'must_change_password' => false,
                    'password_changed_at' => now(),
                ]
            );
        };

        // ---------------------------
        // 6) Officers XUCS
        // ---------------------------
        $xucsOfficers = [
            ['Treasurer XUCS', 'treasurer.xucs@my.xu.edu.ph', 'treasurer'],
            ['Moderator XUCS', 'moderator.xucs@my.xu.edu.ph', 'moderator'],
            ['Secretary XUCS', 'secretary.xucs@my.xu.edu.ph', 'secretary'],
            ['PRO XUCS', 'pro.xucs@my.xu.edu.ph', 'pro'],
            ['Auditor XUCS', 'auditor.xucs@my.xu.edu.ph', 'auditor'],
        ];

        foreach ($xucsOfficers as $i => $o)
        {
            [$name, $email, $role] = $o;

            $user = $makeUser($name, $email);

            $officer = OfficerEntry::query()->updateOrCreate(
                [
                    'organization_id' => $org1->id,
                    'school_year_id' => $sy->id,
                    'email' => $email,
                ],
                [
                    'full_name' => $name,
                    'position' => ucfirst($role),
                    'user_id' => $user->id,
                    'sort_order' => $i,
                ]
            );

            OrgMembership::query()->updateOrCreate(
                [
                    'organization_id' => $org1->id,
                    'school_year_id' => $sy->id,
                    'role' => $role,
                ],
                [
                    'user_id' => $user->id,
                    'officer_entry_id' => $officer->id,
                    'archived_at' => null,
                ]
            );
        }

        // ---------------------------
        // 7) Officers XUTI
        // ---------------------------
        $xutiOfficers = [
            ['Treasurer XUTI', 'treasurer.xuti@my.xu.edu.ph', 'treasurer'],
            ['Moderator XUTI', 'moderator.xuti@my.xu.edu.ph', 'moderator'],
            ['Secretary XUTI', 'secretary.xuti@my.xu.edu.ph', 'secretary'],
            ['PRO XUTI', 'pro.xuti@my.xu.edu.ph', 'pro'],
            ['Auditor XUTI', 'auditor.xuti@my.xu.edu.ph', 'auditor'],
        ];

        foreach ($xutiOfficers as $i => $o)
        {
            [$name, $email, $role] = $o;

            $user = $makeUser($name, $email);

            $officer = OfficerEntry::query()->updateOrCreate(
                [
                    'organization_id' => $org2->id,
                    'school_year_id' => $sy->id,
                    'email' => $email,
                ],
                [
                    'full_name' => $name,
                    'position' => ucfirst($role),
                    'user_id' => $user->id,
                    'sort_order' => $i,
                ]
            );

            OrgMembership::query()->updateOrCreate(
                [
                    'organization_id' => $org2->id,
                    'school_year_id' => $sy->id,
                    'role' => $role,
                ],
                [
                    'user_id' => $user->id,
                    'officer_entry_id' => $officer->id,
                    'archived_at' => null,
                ]
            );
        }

        // ---------------------------
        // 8) Projects 
        // ---------------------------
        Project::query()->updateOrCreate(
            [
                'organization_id' => $org1->id,
                'school_year_id' => $sy->id,
                'title' => 'Hackathon Training Week',
            ]
        );

        Project::query()->updateOrCreate(
            [
                'organization_id' => $org2->id,
                'school_year_id' => $sy->id,
                'title' => 'AI Awareness Seminar',
            ]
        );

        // ---------------------------
        // DONE
        // ---------------------------
        $this->command->info('Sprint1Seeder ready for full workflow testing.');

        $this->command->warn('ADMIN: sacdev.admin@my.xu.edu.ph / paul1234');
        $this->command->warn('PRESIDENT XUCS: president.xucs@my.xu.edu.ph / paul1234');
        $this->command->warn('PRESIDENT XUTI: president.xuti@my.xu.edu.ph / paul1234');
        $this->command->warn('MODERATOR XUCS: moderator.xucs@my.xu.edu.ph / paul1234');
        $this->command->warn('TREASURER XUCS: treasurer.xucs@my.xu.edu.ph / paul1234');
    }
}