<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ProjectProposalFormSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('form_types')->updateOrInsert(
            ['code' => 'PROJECT_PROPOSAL'],
            [
                'name' => 'Project Proposal',
                'phase' => 'pre_implementation',
                'description' => 'Main project proposal form required before implementation.',
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        DB::table('form_types')->updateOrInsert(
            ['code' => 'BUDGET_PROPOSAL'],
            [
                'name' => 'Budget Proposal',
                'phase' => 'pre_implementation',
                'description' => 'Detailed financial breakdown of project expenses and funding sources.',
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        DB::table('form_types')->updateOrInsert(
            ['code' => 'OFF_CAMPUS_APPLICATION'],
            [
                'name' => 'Off-Campus Activity Form',
                'phase' => 'pre_implementation',
                'description' => 'Required form for student organization activities conducted outside the university campus.',
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        DB::table('form_types')->updateOrInsert(
            ['code' => 'SOLICITATION_APPLICATION'],
            [
                'name' => 'Application for Solicitation / Sponsorship',
                'phase' => 'pre_implementation',
                'description' => 'Application form required before student organizations conduct solicitation or sponsorship activities using the name of the university.',
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        DB::table('form_types')->updateOrInsert(
            ['code' => 'SELLING_APPLICATION'],
            [
                'name' => 'Application for Selling',
                'phase' => 'pre_implementation',
                'description' => 'Application form for student organization selling activities.',
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );



    }
}