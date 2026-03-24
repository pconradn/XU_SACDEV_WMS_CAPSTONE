<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Organization;
use App\Models\OrgMembership;
use App\Models\StrategicPlanSubmission;
use App\Models\StrategicPlanProject;
use App\Models\StrategicPlanObjective;
use App\Models\StrategicPlanDeliverable;
use App\Models\StrategicPlanBeneficiary;
use App\Models\StrategicPlanPartner;
use App\Models\SchoolYear;

class StrategicPlanSeeder extends Seeder
{
    public function run(): void
    {
        $schoolYear = SchoolYear::where('name', '2026-2027')->first();

        $organizations = Organization::all()->take(6);

        foreach ($organizations as $index => $org) {

            // ❌ Org 1 = NO submission
            if ($index === 0) {
                continue;
            }

            // =========================
            // GET PRESIDENT
            // =========================
            $presidentMembership = OrgMembership::where([
                'organization_id' => $org->id,
                'school_year_id' => $schoolYear->id,
                'role' => 'president'
            ])->first();

            if (!$presidentMembership) continue;

            $president = $presidentMembership->user;

            // =========================
            // STATUS LOGIC
            // =========================
            $status = match (true) {
                $index <= 2 => 'draft',
                default => 'submitted_to_moderator',
            };

            // =========================
            // CREATE SUBMISSION
            // =========================
            $submission = StrategicPlanSubmission::updateOrCreate(
                [
                    'organization_id' => $org->id,
                    'target_school_year_id' => $schoolYear->id,
                ],
                [
                    'submitted_by_user_id' => $president->id,
                    'status' => $status,
                    'org_acronym' => $org->acronym,
                    'org_name' => $org->name,
                    'mission' => 'To empower students through technology and innovation.',
                    'vision' => 'To be a leading tech-driven student organization.',
                    'submitted_to_moderator_at' => $status === 'submitted_to_moderator' ? now() : null,
                ]
            );

            // =========================
            // PROJECTS (2–4 per org)
            // =========================
            $projectCount = rand(2, 4);

            for ($i = 1; $i <= $projectCount; $i++) {

                $budget = rand(5000, 20000);

                $project = StrategicPlanProject::create([
                    'submission_id' => $submission->id,
                    'category' => collect([
                        'org_dev',
                        'student_services',
                        'community_involvement'
                    ])->random(),
                    'target_date' => now()->addMonths(rand(1, 10)),
                    'title' => $this->generateProjectTitle(),
                    'implementing_body' => 'Project Committee',
                    'budget' => $budget,
                ]);

                // =========================
                // OBJECTIVES (required)
                // =========================
                for ($j = 1; $j <= rand(1, 3); $j++) {
                    StrategicPlanObjective::create([
                        'project_id' => $project->id,
                        'text' => $this->generateSentence(),
                    ]);
                }

                // =========================
                // DELIVERABLES (required)
                // =========================
                for ($j = 1; $j <= rand(1, 3); $j++) {
                    StrategicPlanDeliverable::create([
                        'project_id' => $project->id,
                        'text' => $this->generateSentence(),
                    ]);
                }

                // =========================
                // BENEFICIARIES (required)
                // =========================
                for ($j = 1; $j <= rand(1, 2); $j++) {
                    StrategicPlanBeneficiary::create([
                        'project_id' => $project->id,
                        'text' => collect([
                            'IT Students',
                            'University Community',
                            'Local Community',
                            'Partner Organizations'
                        ])->random(),
                    ]);
                }

                // =========================
                // PARTNERS (optional)
                // =========================
                if (rand(0, 1)) {
                    StrategicPlanPartner::create([
                        'project_id' => $project->id,
                        'text' => collect([
                            'DICT',
                            'Local NGOs',
                            'Tech Companies',
                            'Student Affairs Office'
                        ])->random(),
                    ]);
                }
            }
        }
    }

    private function generateProjectTitle(): string
    {
        return collect([
            'Tech Workshop Series',
            'Community Outreach Program',
            'Hackathon Event',
            'Digital Literacy Campaign',
            'Leadership Training Seminar',
            'System Development Project'
        ])->random();
    }

    private function generateSentence(): string
    {
        return collect([
            'Enhance student technical skills.',
            'Promote collaboration among members.',
            'Provide real-world experience.',
            'Develop innovative solutions.',
            'Support community engagement.',
        ])->random();
    }
}