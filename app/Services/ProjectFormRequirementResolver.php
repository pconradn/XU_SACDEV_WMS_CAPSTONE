<?php

namespace App\Services;

use App\Models\Project;
use App\Models\ProjectFormRequirement;
use Illuminate\Support\Collection;

class ProjectFormRequirementResolver
{
    public function resolve(Project $project): Collection
    {
        $project->loadMissing([
            'proposalDocument.proposalData.fundSources',
        ]);

        $proposalDoc = $project->proposalDocument;
        $proposalData = $proposalDoc?->proposalData;

        $status = $proposalDoc?->status;

        $isApproved = in_array($status, ['approved_by_sacdev', 'submitted']);

        $rules = ProjectFormRequirement::with('formType')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $requiredForms = collect();

        foreach ($rules as $rule) {

            if (!$rule->formType) {
                continue;
            }

            if ($this->passesRule($rule->rule_key, $proposalData, $isApproved)) {
                $requiredForms->push($rule->formType);
            }
        }

        // Always required
        $requiredForms->push(
            \App\Models\FormType::where('code', 'PROJECT_PROPOSAL')->first()
        );

        //dd($requiredForms);

        return $requiredForms->filter()->unique('id')->values();
    }

    protected function passesRule(string $ruleKey, $proposalData, bool $isApproved): bool
    {
        $fundSources = $proposalData?->fundSources ?? collect();

        return match ($ruleKey) {

            'always_required' => true,

            'always_required_after_approval' => $isApproved,

            'requires_off_campus' =>
                !empty($proposalData?->off_campus_venue),

            'requires_solicitation' =>
                $fundSources->contains(fn ($fs) =>
                    $fs->source_name === 'Solicitation' &&
                    (float) $fs->amount > 0
                ),

            'requires_ticket_selling' =>
                $fundSources->contains(fn ($fs) =>
                    $fs->source_name === 'Ticket-Selling' &&
                    (float) $fs->amount > 0
                ),

            'requires_counterpart' =>
                $fundSources->contains(fn ($fs) =>
                    $fs->source_name === 'Counterpart' &&
                    (float) $fs->amount > 0
                ),

            'requires_budget' =>
                $proposalData && (float) ($proposalData->total_budget ?? 0) > 0,

            default => false,
        };
    }

}