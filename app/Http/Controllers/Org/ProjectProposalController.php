<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use App\Models\FormType;
use App\Models\Project;
use App\Models\ProjectDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectProposalController extends Controller
{
    public function create(Request $request, Project $project)
    {
        $formType = FormType::where('code', 'project_proposal')->firstOrFail();

        $document = ProjectDocument::where('project_id', $project->id)
            ->where('form_type_id', $formType->id)
            ->first();

        return view('org.projects.documents.project-proposal.create', [
            'project' => $project,
            'document' => $document,
        ]);
    }


    public function store(Request $request, Project $project)
    {
        $formType = FormType::where('code', 'project_proposal')->firstOrFail();

        DB::transaction(function () use ($request, $project, $formType) {

            $document = ProjectDocument::updateOrCreate(
                [
                    'project_id' => $project->id,
                    'form_type_id' => $formType->id,
                ],
                [
                    'created_by_user_id' => auth()->id(),
                    'status' => 'draft',
                ]
            );

            \App\Models\ProjectProposalData::updateOrCreate(
                [
                    'project_document_id' => $document->id
                ],
                [
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    'venue_type' => $request->venue_type,
                    'venue_name' => $request->venue_name,
                    'description' => $request->description,
                    'org_link' => $request->org_link,
                ]
            );

        });

        return redirect()
            ->route('org.projects.documents.hub', $project)
            ->with('success', 'Project Proposal saved as draft.');
    }
}