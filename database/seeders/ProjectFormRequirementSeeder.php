<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FormType;
use App\Models\ProjectFormRequirement;

class ProjectFormRequirementSeeder extends Seeder
{
    public function run(): void
    {
        $rules = [

            [
                'form_code' => 'PROJECT_PROPOSAL',
                'rule_key' => 'always_required',
                'label' => 'Project Proposal Required',
                'description' => 'Main proposal must always be submitted.',
                'sort_order' => 1,
            ],

            [
                'form_code' => 'BUDGET_PROPOSAL',
                'rule_key' => 'requires_budget',
                'label' => 'Budget Proposal Required if Budget Exists',
                'description' => 'Required only if total budget is greater than 0.',
                'sort_order' => 2,
            ],
            [
                'form_code' => 'DOCUMENTATION_REPORT',
                'rule_key' => 'always_required_after_approval',
                'label' => 'Documentation Report Required',
                'description' => 'Required after project proposal is approved.',
                'sort_order' => 100,
            ],

            [
                'form_code' => 'LIQUIDATION_REPORT',
                'rule_key' => 'requires_budget',
                'label' => 'Liquidation Required if Budget Exists',
                'description' => 'Required if total budget is greater than 0.',
                'sort_order' => 110,
            ],

  
            [
                'form_code' => 'OFF_CAMPUS_APPLICATION',
                'rule_key' => 'requires_off_campus',
                'label' => 'Off-Campus Form Required',
                'description' => 'Required if off-campus venue is specified.',
                'sort_order' => 200,
            ],

  
            [
                'form_code' => 'SOLICITATION_APPLICATION',
                'rule_key' => 'requires_solicitation',
                'label' => 'Solicitation Application Required',
                'description' => 'Required if solicitation fund source exists.',
                'sort_order' => 300,
            ],

            [
                'form_code' => 'SOLICITATION_SPONSORSHIP_REPORT',
                'rule_key' => 'requires_solicitation',
                'label' => 'Solicitation Report Required',
                'description' => 'Required if solicitation fund source exists.',
                'sort_order' => 310,
            ],

      
            [
                'form_code' => 'TICKET_SELLING_REPORT',
                'rule_key' => 'requires_ticket_selling',
                'label' => 'Ticket Selling Report Required',
                'description' => 'Required if ticket selling fund source exists.',
                'sort_order' => 400,
            ],


            [
                'form_code' => 'FEES_COLLECTION_REPORT',
                'rule_key' => 'requires_counterpart',
                'label' => 'Fees Collection Report Required',
                'description' => 'Required if counterpart fund source exists.',
                'sort_order' => 500,
            ],

        ];

        foreach ($rules as $rule) {

            $formType = FormType::where('code', $rule['form_code'])->first();

            if (!$formType) {
                continue; 
            }

            ProjectFormRequirement::updateOrCreate(
                [
                    'form_type_id' => $formType->id,
                    'rule_key' => $rule['rule_key'],
                ],
                [
                    'label' => $rule['label'],
                    'description' => $rule['description'],
                    'is_active' => true,
                    'sort_order' => $rule['sort_order'],
                ]
            );
        }
    }
}