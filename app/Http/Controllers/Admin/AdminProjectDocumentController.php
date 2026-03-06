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

}