<?php

namespace App\Services;

use App\Models\Project;

class ProjectFormRouteResolver
{
    public static function resolve(object $task): string
    {
        $project = $task->project;

        $formCode = $task->formType->code ?? $task->form_code ?? null;

        $type = $task->type ?? null;

        $formCode = $type === 'approval'
            ? ($task->formType->code ?? null)
            : ($task->form_code ?? $task->formType->code ?? null);

        return match ($formCode) {

            'PROJECT_PROPOSAL' => route('org.projects.documents.combined-proposal.create', $project),
            'BUDGET_PROPOSAL'
                => route('org.projects.documents.combined-proposal.create', $project),

            'OFF_CAMPUS_APPLICATION'
                => route('org.projects.documents.off-campus.create', $project),

            'SOLICITATION_APPLICATION'
                => route('org.projects.documents.solicitation.create', $project),
                       
            'SELLING_APPLICATION'
                => route('org.projects.documents.selling.create', $project),

            'REQUEST_TO_PURCHASE'
                => route('org.projects.documents.request-to-purchase.create', $project),

            'FEES_COLLECTION_REPORT'
                => route('org.projects.documents.fees-collection.create', $project),

            'SELLING_ACTIVITY_REPORT'
                => route('org.projects.documents.selling-activity-report.create', $project),

            'SOLICITATION_SPONSORSHIP_REPORT'
                => route('org.projects.documents.solicitation-sponsorship-report.create', $project),

            'TICKET_SELLING_REPORT'
                => route('org.projects.documents.ticket-selling-report.create', $project),

            'DOCUMENTATION_REPORT'
                => route('org.projects.documents.documentation-report.create', $project),

            'LIQUIDATION_REPORT'
                => route('org.projects.documents.liquidation-report.create', $project),

            default
                => route('org.projects.documents.hub', $project),
        };
    }
}