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


        $allDocuments = ProjectDocument::where('project_id', $project->id)
            ->whereNull('archived_at')
            ->get();


        $documents = $allDocuments->keyBy('form_type_id');


        $documentsByType = $allDocuments->groupBy('form_type_id');


        $formTypes = FormType::orderByRaw("
            CASE
                WHEN code = 'PROJECT_PROPOSAL' THEN 1
                WHEN code = 'BUDGET_PROPOSAL' THEN 2
                ELSE 3
            END
        ")->orderBy('name')->get();



        $proposalFormType = $formTypes->firstWhere('code', 'PROJECT_PROPOSAL');

        $proposalDocument = $proposalFormType
            ? ($documents[$proposalFormType->id] ?? null)
            : null;

        $proposalData = $proposalDocument
            ? $proposalDocument->proposalData
            : null;



        $budgetFormType = $formTypes->firstWhere('code', 'BUDGET_PROPOSAL');

        $budgetDocument = $budgetFormType
            ? ($documents[$budgetFormType->id] ?? null)
            : null;



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


        $showOffCampus = false;

        if ($proposalData) {

            if (
                strtolower($proposalData->venue ?? '') === 'off campus' ||
                strtolower($proposalData->venue_type ?? '') === 'off_campus'
            ) {
                $showOffCampus = true;
            }

        }



        $showOtherForms = $proposalDocument !== null;



        $postponementType = $formTypes->firstWhere('code', 'POSTPONEMENT_NOTICE');
        $cancellationType = $formTypes->firstWhere('code', 'CANCELLATION_NOTICE');

        $postponements = $postponementType
            ? ($documentsByType[$postponementType->id] ?? collect())
            : collect();

        $cancellations = $cancellationType
            ? ($documentsByType[$cancellationType->id] ?? collect())
            : collect();


        $projectStage = 'pre';

        if ($proposalDocument && $proposalDocument->status === 'approved_by_sacdev') {

            $projectStage = 'ready';

        }

        if ($postImplementationForms->contains(fn ($f) => $f->document)) {

            $projectStage = 'post_processing';

        }



        $offCampusForms = $formTypes
            ->where('phase', 'off-campus')
            ->map(function ($formType) use ($requirements, $documents) {

                return (object)[
                    'formType' => $formType,
                    'required' => isset($requirements[$formType->id]),
                    'document' => $documents[$formType->id] ?? null,
                ];

            });




        $solicitationForm = $formTypes->firstWhere('code','SOLICITATION_APPLICATION');
        $sellingForm = $formTypes->firstWhere('code','SELLING_APPLICATION');
        $proposalForm = $formTypes->firstWhere('code','PROJECT_PROPOSAL');

        $solicitationDoc = $solicitationForm ? ($documents[$solicitationForm->id] ?? null) : null;
        $sellingDoc = $sellingForm ? ($documents[$sellingForm->id] ?? null) : null;
        $proposalDoc = $proposalForm ? ($documents[$proposalForm->id] ?? null) : null;


        $otherForms = $formTypes
            ->where('phase','other')
            ->map(function($formType) use ($requirements,$documents,$solicitationDoc,$sellingDoc,$proposalDoc){

                $allowCreation = true;



                if($formType->code === 'SOLICITATION_COLLECTION_REPORT'){

                    $allowCreation =
                        $solicitationDoc &&
                        $solicitationDoc->status === 'approved_by_sacdev';

                }


                if($formType->code === 'TICKET_SELLING_REPORT'){

                    $allowCreation =
                        $sellingDoc &&
                        $sellingDoc->status === 'approved_by_sacdev';

                }


                if($formType->code === 'SELLING_ACTIVITY_REPORT'){

                    $allowCreation =
                        $sellingDoc &&
                        $sellingDoc->status === 'approved_by_sacdev';

                }



                if($formType->code === 'FEES_COLLECTION_REPORT'){

                    $allowCreation =
                        $proposalDoc &&
                        $proposalDoc->status === 'approved_by_sacdev';

                }

                return (object)[
                    'formType' => $formType,
                    'required' => isset($requirements[$formType->id]),
                    'document' => $documents[$formType->id] ?? null,
                    'allowed' => $allowCreation
                ];

            });



        return view('org.projects.documents.hub', [

            'project' => $project,

            'projectHead' => $projectHead,
            'isProjectHead' => $isProjectHead,

            'proposalDocument' => $proposalDocument,
            'proposalData' => $proposalData,

            'budgetDocument' => $budgetDocument,

            'preForms' => $preImplementationForms,
            'postForms' => $postImplementationForms,

            'offCampusForms' => $offCampusForms,
            'otherForms' => $otherForms,
            

            'showOffCampus' => $showOffCampus,
            'showOtherForms' => $showOtherForms,

            'postponements' => $postponements,
            'cancellations' => $cancellations,

            'projectStage' => $projectStage,

        ]);
    }


}