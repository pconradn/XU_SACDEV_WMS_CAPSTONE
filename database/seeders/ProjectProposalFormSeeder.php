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

        /*
        |--------------------------------------------------------------------------
        | Additional "Other" Phase Forms
        |--------------------------------------------------------------------------
        */

        DB::table('form_types')->updateOrInsert(
            ['code' => 'SOLICITATION_COLLECTION_REPORT'],
            [
                'name' => 'Solicitation Collection Report',
                'phase' => 'other',
                'description' => 'Report summarizing all funds collected from solicitation or sponsorship activities.',
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        DB::table('form_types')->updateOrInsert(
            ['code' => 'TICKET_SELLING_REPORT'],
            [
                'name' => 'Ticket Selling Report',
                'phase' => 'other',
                'description' => 'Report documenting ticket sales and total collections from ticket-based activities.',
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        DB::table('form_types')->updateOrInsert(
            ['code' => 'SELLING_ACTIVITY_REPORT'],
            [
                'name' => 'Selling Activity Report',
                'phase' => 'other',
                'description' => 'Summary report of goods sold during student organization selling activities.',
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        DB::table('form_types')->updateOrInsert(
            ['code' => 'FEES_COLLECTION_REPORT'],
            [
                'name' => 'Fees Collection Report',
                'phase' => 'other',
                'description' => 'Report detailing collections from membership fees or other organizational fees.',
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Post Implementation Forms
        |--------------------------------------------------------------------------
        */

        DB::table('form_types')->updateOrInsert(
            ['code' => 'DOCUMENTATION_REPORT'],
            [
                'name' => 'Documentation Report',
                'phase' => 'post_implementation',
                'description' => 'Report documenting the implementation of the activity including photos, narrative summary, and outcomes.',
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        DB::table('form_types')->updateOrInsert(
            ['code' => 'LIQUIDATION_REPORT'],
            [
                'name' => 'Liquidation Report',
                'phase' => 'post_implementation',
                'description' => 'Financial liquidation report showing actual expenses and supporting receipts after the activity.',
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Notices
        |--------------------------------------------------------------------------
        */

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