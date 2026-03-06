<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectDocument;

class AdminProjectDocumentController extends Controller
{

    public function hub(Project $project)
    {
        $documents = ProjectDocument::query()
            ->with(['formType', 'signatures.user'])
            ->where('project_id', $project->id)
            ->get()
            ->keyBy(fn($d) => $d->formType->code);

        return view('admin.projects.documents.hub', [
            'project'   => $project,
            'documents' => $documents,
        ]);
    }


    public function open(Project $project, $formType)
    {
        $document = ProjectDocument::query()
            ->with(['signatures.user','formType'])
            ->where('project_id', $project->id)
            ->whereHas('formType', fn($q) => $q->where('code', $formType))
            ->firstOrFail();

        $viewMap = [
            'PROJECT_PROPOSAL'       => 'org.projects.documents.project-proposal.create',
            'BUDGET_PROPOSAL'        => 'org.projects.documents.budget-proposal.create',
            'OFF_CAMPUS_APPLICATION' => 'org.projects.documents.off-campus.create',
        ];

        $view = $viewMap[$formType] ?? abort(404);

        $proposal = null;
        $budget = null;
        $activity = null;
        $participants = collect();

        if ($formType === 'PROJECT_PROPOSAL') {

            $proposal = $document->proposalData;

        }

        if ($formType === 'BUDGET_PROPOSAL') {

            $budget = $document->budgetProposal()->with('items')->first();

        }

        if ($formType === 'OFF_CAMPUS_APPLICATION') {

            $activity = \App\Models\OffCampusActivityData::with('participants')
                ->where('project_document_id', $document->id)
                ->first();

            if ($activity) {
                $participants = $activity->participants;
            }

        }

        return view($view, [
            'project' => $project,
            'document' => $document,

            'proposal' => $proposal,
            'budget' => $budget,
            'activity' => $activity,
            'participants' => $participants,

            'isReadOnly' => true,
            'isProjectHead' => false,
            'currentSignature' => null
        ]);
    }

}