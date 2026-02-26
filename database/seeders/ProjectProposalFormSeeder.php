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

        /*
        |--------------------------------------------------------------------------
        | Insert Project Proposal Form Type
        |--------------------------------------------------------------------------
        */

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

        $formTypeId = DB::table('form_types')
            ->where('code', 'PROJECT_PROPOSAL')
            ->value('id');

        if (!$formTypeId) {
            throw new \RuntimeException('PROJECT_PROPOSAL form type not found.');
        }


        $roles = [
            'project_head',
            'finance_officer',
            'treasurer',
            'president',
            'moderator',
            'sacdev_admin',
            'osa_admin',
        ];

        foreach ($roles as $role) {

            DB::table('form_type_required_roles')->updateOrInsert(
                [
                    'form_type_id' => $formTypeId,
                    'role' => $role,
                ],
                [
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }
}