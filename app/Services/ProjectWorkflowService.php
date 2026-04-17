<?php

namespace App\Services;

use App\Models\Project;
use App\Models\ProjectDocument;

class ProjectWorkflowService
{
    public function updateFromDocument(ProjectDocument $document): void
    {
        $project = $document->project;

        if (!$project) return;

        
        if (
            $document->formType->code === 'PROJECT_PROPOSAL' &&
            $document->status === 'draft'
        ) {
            $project->update(['workflow_status' => 'drafting']);
            return;
        }

       
        if ($this->isPreImplementationReady($project)) {
            $project->update(['workflow_status' => 'pre_implementation']);
            return;
        }

     
        if ($this->hasApprovedPostImplementation($project)) {
            $project->update(['workflow_status' => 'post_implementation']);
            return;
        }
    }


    protected function isPreImplementationReady(Project $project): bool
    {
        $proposal = $project->documents()
            ->whereHas('formType', fn ($q) => $q->where('code', 'PROJECT_PROPOSAL'))
            ->latest()
            ->first();

        $budget = $project->documents()
            ->whereHas('formType', fn ($q) => $q->where('code', 'BUDGET_PROPOSAL'))
            ->latest()
            ->first();

        return 
            $proposal && method_exists($proposal, 'isApprovedBySacdev') && $proposal->isApprovedBySacdev()
            &&
            $budget && method_exists($budget, 'isApprovedBySacdev') && $budget->isApprovedBySacdev();
            }


    protected function hasApprovedPostImplementation(Project $project): bool
    {
        return $project->documents()
            ->whereHas('formType', fn ($q) => $q->where('phase', 'post_implementation'))
            ->get()
            ->contains(fn ($doc) => $doc->isApprovedBySacdev());
    }

    public function markCompleted(Project $project): void
    {
        $project->update(['workflow_status' => 'completed']);
    }

    public function markPostponed(Project $project): void
    {
        $project->update(['workflow_status' => 'postponed']);
    }

    public function markCancelled(Project $project): void
    {
        $project->update(['workflow_status' => 'cancelled']);
    }
}