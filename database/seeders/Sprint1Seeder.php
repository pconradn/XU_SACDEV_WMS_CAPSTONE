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

        SchoolYear::query()->update(['is_active' => false]);

        $sy = SchoolYear::query()->updateOrCreate(
            ['name' => '2025-2026'],
            [
                'start_date' => '2025-08-01',
                'end_date' => '2026-05-31',
                'is_active' => true,
            ]
        );


        $adminA = User::query()->updateOrCreate(
            ['email' => 'badaya@xu.eud.ph'],
            [
                'name' => 'Engr. Billy Jhones A. Adaya',
                'password' => $password,
                'system_role' => 'sacdev_admin',
                'must_change_password' => false,
                'password_changed_at' => now(),
            ]
        );

        
        $adminB = User::query()->updateOrCreate(
            ['email' => 'cmulat@xu.edu.ph'],
            [
                'name' => 'Mr Carl Ed A Mulat, LPT',
                'password' => $password,
                'system_role' => 'sacdev_admin',
                'must_change_password' => false,
                'password_changed_at' => now(),
            ]
        );

        
        $adminC = User::query()->updateOrCreate(
            ['email' => 'ncinco@xu.edu.ph'],
            [
                'name' => 'Ms Noemi Marla C Cinco',
                'password' => $password,
                'system_role' => 'sacdev_admin',
                'must_change_password' => false,
                'password_changed_at' => now(),
            ]
        );


    }
}