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

            if (!empty(trim($proposalData->off_campus_venue ?? ''))) {
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

    public function showV2(Project $project)
    {
        $user = auth()->user();

        $documents = ProjectDocument::with('signatures')
            ->where('project_id', $project->id)
            ->whereNull('archived_at')
            ->get()
            ->keyBy('form_type_id');

        $formTypes = FormType::all()->keyBy('id');

        $proposal = $formTypes->firstWhere('code', 'PROJECT_PROPOSAL');
        $budget   = $formTypes->firstWhere('code', 'BUDGET_PROPOSAL');

        $proposalDoc = $proposal ? ($documents[$proposal->id] ?? null) : null;
        $budgetDoc   = $budget ? ($documents[$budget->id] ?? null) : null;

        $hasProposal = (bool) $proposalDoc;
        $proposalStatus = $proposalDoc?->status;

        $isApproved = $project->workflow_status === 'approved';
        $isCancelled = $project->workflow_status === 'cancelled';

        $header = [
            'title' => $project->title,
            'status_label' => $project->workflow_status_label,
            'status_class' => $project->workflow_status_badge_class,
        ];

        $snapshot = [
            'date' => $project->implementation_date_display,
            'time' => $project->implementation_time_display,
            'venue' => $project->implementation_venue_display,
        ];

        $actions = [];

        if (!$hasProposal) {
            $actions[] = [
                'label' => 'Create Project Proposal',
                'type' => 'primary',
                'action' => 'create_proposal',
            ];
        }

        elseif ($proposalDoc && $proposalDoc->isEditable()) {
            $actions[] = [
                'label' => 'Edit Project Proposal',
                'type' => 'primary',
                'action' => 'edit_proposal',
            ];

            $actions[] = [
                'label' => 'Submit Project Proposal',
                'type' => 'secondary',
                'action' => 'submit_proposal',
            ];
        }

        elseif ($proposalStatus === 'submitted') {

            $pending = $proposalDoc->currentPendingSignature();

            if ($pending && $pending->user_id === $user->id) {

                $actions[] = [
                    'label' => 'Review Project Proposal',
                    'type' => 'primary',
                    'action' => 'review_proposal',
                ];

            } else {

                $actions[] = [
                    'label' => 'Waiting for ' . ucfirst(str_replace('_',' ', $pending->role ?? 'review')),
                    'type' => 'info',
                    'action' => null,
                ];

            }
        }

        elseif ($proposalStatus === 'approved_by_sacdev') {

            if (!$isCancelled) {

                $actions[] = [
                    'label' => 'Create Packet Submission',
                    'type' => 'primary',
                    'action' => 'create_packet',
                ];

                $actions[] = [
                    'label' => 'Create Notice of Postponement',
                    'type' => 'warning',
                    'action' => 'create_postponement',
                ];

                $actions[] = [
                    'label' => 'Create Notice of Cancellation',
                    'type' => 'danger',
                    'action' => 'create_cancellation',
                ];
            }
        }

        $formRoutes = [
            'PROJECT_PROPOSAL' => 'org.projects.project-proposal.create',
            'BUDGET_PROPOSAL'  => 'org.projects.budget-proposal.create',
            'OFF_CAMPUS_APPLICATION' => 'org.projects.off-campus.guidelines',
            'SOLICITATION_APPLICATION' => 'org.projects.solicitation.create',
            'SELLING_APPLICATION' => 'org.projects.selling.create',
            'REQUEST_TO_PURCHASE' => 'org.projects.request-to-purchase.create',

            'FEES_COLLECTION_REPORT' => 'org.projects.fees-collection.create',
            'SELLING_ACTIVITY_REPORT' => 'org.projects.selling-activity-report.create',
            'SOLICITATION_SPONSORSHIP_REPORT' => 'org.projects.solicitation-sponsorship-report.create',
            'TICKET_SELLING_REPORT' => 'org.projects.ticket-selling-report.create',

            'DOCUMENTATION_REPORT' => 'org.projects.documentation-report.create',
            'LIQUIDATION_REPORT' => 'org.projects.liquidation-report.create',
        ];


        $buildForm = function ($formType) use ($documents, $user, $project, $formRoutes) {

            $doc = $documents[$formType->id] ?? null;

            $routeName = $formRoutes[$formType->code] ?? null;

            $createUrl = (!$doc && $routeName)
                ? route($routeName, $project)
                : null;

            $editUrl = ($doc && $doc->isEditable() && $routeName)
                ? route($routeName, $project)
                : null;

            $viewUrl = ($doc && $routeName)
                ? route($routeName, $project)
                : null;

            $pending = $doc?->currentPendingSignature();
            $nextRole = $doc?->nextPendingRole();

            $canReview = $pending && $pending->user_id === $user->id;

            return [
                'name' => $formType->name,
                'code' => $formType->code,

                'document' => $doc,

                'status_label' => $doc?->status_label ?? 'Not started',
                'status_class' => $doc?->status_badge_class ?? 'bg-slate-100 text-slate-600',

                'waiting_for' => $nextRole,

                'can_create' => !$doc && $routeName,
                'can_edit' => $doc?->isEditable() ?? false,
                'can_review' => $canReview,

                'create_url' => $createUrl,
                'edit_url' => $editUrl,
                'view_url' => $viewUrl,
            ];
        };


        $preForms = FormType::where('phase', 'pre_implementation')->get()
            ->map($buildForm);

        $noticeForms = FormType::where('phase', 'notice')->get()
            ->map($buildForm);

        $postForms = FormType::where('phase', 'post_implementation')->get()
            ->map($buildForm);

        $otherForms = FormType::where('phase', 'other')->get()
            ->map($buildForm);

        $sections = [
            'pre' => $preForms,
            'notices' => $noticeForms,
            'other' => $otherForms,
            'post' => $postForms,
        ];

        return view('org.projects.documents.hub', compact(
            'project',
            'header',
            'snapshot',
            'actions',
            'sections'
        ));
    }


}