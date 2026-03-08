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
                'phase' => 'off-campus',
                'description' => 'Required form for student organization activities conducted outside the university campus.',
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        DB::table('form_types')->updateOrInsert(
            ['code' => 'SOLICITATION_APPLICATION'],
            [
                'name' => 'Application for Solicitation / Sponsorship',
                'phase' => 'other',
                'description' => 'Application form required before student organizations conduct solicitation or sponsorship activities using the name of the university.',
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        DB::table('form_types')->updateOrInsert(
            ['code' => 'SELLING_APPLICATION'],
            [
                'name' => 'Application for Selling',
                'phase' => 'other',
                'description' => 'Application form for student organization selling activities.',
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        DB::table('form_types')->updateOrInsert(
            ['code' => 'REQUEST_TO_PURCHASE'],
            [
                'name' => 'Request to Purchase',
                'phase' => 'other',
                'description' => 'Form used by student organizations to request the purchase of equipment, materials, or other items needed for a project activity.',
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        DB::table('form_types')->updateOrInsert(
            ['code' => 'POSTPONEMENT_NOTICE'],
            [
                'name' => 'Notice of Postponement',
                'phase' => 'notice',
                'description' => 'Notice submitted when a project activity must be postponed.',
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        DB::table('form_types')->updateOrInsert(
            ['code' => 'CANCELLATION_NOTICE'],
            [
                'name' => 'Notice of Cancellation',
                'phase' => 'notice',
                'description' => 'Notice submitted when a project activity is cancelled.',
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );



    }
}