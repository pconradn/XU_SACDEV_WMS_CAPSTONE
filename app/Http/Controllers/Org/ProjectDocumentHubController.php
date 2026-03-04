<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use App\Models\FormType;
use App\Models\Project;
use App\Models\ProjectAssignment;
use App\Models\ProjectDocument;
use App\Models\ProjectDocumentRequirement;

class ProjectDocumentHubController extends Controller
{
    public function show(Project $project)
    {
        $activeOrgId = (int) session('active_org_id');
        $encodeSyId  = (int) session('encode_sy_id');

        if (
            $project->organization_id !== $activeOrgId ||
            $project->school_year_id !== $encodeSyId
        ) {
            abort(403);
        }

        $user = auth()->user();

        $projectHeadAssignment = ProjectAssignment::with('user')
            ->where('project_id', $project->id)
            ->where('assignment_role', 'project_head')
            ->whereNull('archived_at')
            ->first();

        $projectHead = $projectHeadAssignment?->user;
        $isProjectHead = $projectHead?->id === $user->id;

        $requirements = ProjectDocumentRequirement::with('formType')
            ->where('project_id', $project->id)
            ->get()
            ->keyBy('form_type_id');

        $documents = ProjectDocument::where('project_id', $project->id)
            ->get()
            ->keyBy('form_type_id');

        $formTypes = FormType::orderBy('name')->get();

        $preImplementationForms = $formTypes
            ->where('phase', 'pre_implementation')
            ->map(function ($formType) use ($requirements, $documents) {
                return (object)[
                    'formType' => $formType,
                    'required' => isset($requirements[$formType->id]),
                    'document' => $documents[$formType->id] ?? null,
                ];
            });

        $postImplementationForms = $formTypes
            ->where('phase', 'post_implementation')
            ->map(function ($formType) use ($requirements, $documents) {
                return (object)[
                    'formType' => $formType,
                    'required' => isset($requirements[$formType->id]),
                    'document' => $documents[$formType->id] ?? null,
                ];
            });

        $budgetFormType = $formTypes->firstWhere('code', 'BUDGET_PROPOSAL');
        $budgetDocument = $budgetFormType
            ? ($documents[$budgetFormType->id] ?? null)
            : null;

        return view('org.projects.documents.hub', [
            'project' => $project,

            'projectHead' => $projectHead,
            'isProjectHead' => $isProjectHead,

            'preForms' => $preImplementationForms,
            'postForms' => $postImplementationForms,

            'budgetDocument' => $budgetDocument,
        ]);
    }
}