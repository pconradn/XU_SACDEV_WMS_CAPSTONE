<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use App\Models\FormType;
use App\Models\Project;
use App\Models\ProjectAssignment;
use App\Models\ProjectDocument;
use App\Models\ProjectDocumentRequirement;
use Carbon\Carbon;

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

        $orgRole = \App\Models\OrgMembership::where('user_id', $user->id)
            ->where('organization_id', $project->organization_id)
            ->where('school_year_id', $project->school_year_id)
            ->whereNull('archived_at')
            ->value('role');

        $allDocuments = ProjectDocument::with(['signatures'])
            ->where('project_id', $project->id)
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

        $proposalType = $formTypes->firstWhere('code', 'PROJECT_PROPOSAL');
        $budgetType   = $formTypes->firstWhere('code', 'BUDGET_PROPOSAL');

        $proposalDoc = $proposalType ? ($documents[$proposalType->id] ?? null) : null;
        $budgetDoc   = $budgetType ? ($documents[$budgetType->id] ?? null) : null;

        $proposalData = $proposalDoc?->proposalData;

        if (!$proposalDoc) {
            $proposalAction = [
                'label' => 'Create Proposal',
                'type' => 'create',
                'url' => route('org.projects.project-proposal.create', $project),
            ];
        } elseif ($proposalDoc->isEditable() && $isProjectHead) {
            $proposalAction = [
                'label' => 'Continue Proposal',
                'type' => 'edit',
                'url' => route('org.projects.project-proposal.create', $project),
            ];
        } else {
            $proposalAction = [
                'label' => 'View Proposal',
                'type' => 'view',
                'url' => route('org.projects.project-proposal.create', $project),
            ];
        }


        $header = [
            'title' => $project->title,
            'org' => $project->organization->name ?? null,
            'school_year' => $project->schoolYear->name ?? null,
            'project_head' => $projectHead?->name ?? null,

            'status_label' => $project->workflow_status_label,
            'status_class' => $project->workflow_status_badge_class,

            'proposal_action' => $proposalAction,
        ];

        $snapshot = [
            'date' => $project->implementation_date_display,
            'time' => $project->implementation_time_display,
            'venue' => $project->implementation_venue, 

            'description' => $proposalData->description ?? $project->description,

            'status' => $proposalDoc?->status,
            'is_off_campus' => $project->implementation_venue_type === 'off_campus',
        ];

        $postponementType = $formTypes->firstWhere('code', 'POSTPONEMENT_NOTICE');
        $cancellationType = $formTypes->firstWhere('code', 'CANCELLATION_NOTICE');

        $postponements = $postponementType
            ? ($documentsByType[$postponementType->id] ?? collect())
            : collect();

        $cancellations = $cancellationType
            ? ($documentsByType[$cancellationType->id] ?? collect())
            : collect();

        $hasApprovedProposal = $proposalDoc && $proposalDoc->status === 'approved_by_sacdev';

        $actions = [
            'can_generate_dv' => $budgetDoc !== null,
            'dv_url' => route('org.projects.disbursement-voucher.create', $project),

            'can_postpone' => $hasApprovedProposal && $isProjectHead && (
                $postponements->isEmpty() ||
                $postponements->last()?->status === 'approved_by_sacdev'
            ),

            'can_cancel' => $hasApprovedProposal && $isProjectHead && $cancellations->isEmpty(),

            'can_packets' => $hasApprovedProposal,
            'packet_url' => route('org.projects.packets.index', $project),
        ];


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

        $buildForm = function ($formType) use (
            $documents,
            $user,
            $project,
            $formRoutes,
            $isProjectHead
        ) {

            $doc = $documents[$formType->id] ?? null;
            $routeName = $formRoutes[$formType->code] ?? null;

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

                
                'can_create' => !$doc && $routeName && $isProjectHead,
                'can_edit' => $doc?->isEditable() && $isProjectHead,
                'can_review' => $canReview,

                'create_url' => (!$doc && $routeName) ? route($routeName, $project) : null,
                'edit_url' => ($doc && $doc->isEditable() && $routeName) ? route($routeName, $project) : null,
                'view_url' => ($doc && $routeName) ? route($routeName, $project) : null,
            ];
        };

        $sections = [
            'pre' => $formTypes->where('phase', 'pre_implementation')->map($buildForm),
            'notices' => $formTypes->where('phase', 'notice')->map($buildForm),
            'other' => $formTypes->where('phase', 'other')->map($buildForm),
            'post' => $formTypes->where('phase', 'post_implementation')->map($buildForm),
        ];

        $clearance = [
            'required' => $project->requires_clearance,

            'reference' => $project->clearance_reference,
            'status' => $project->clearance_status,

            'is_project_head' => $isProjectHead,

            'print_url' => route('org.projects.clearance.print', $project),
            'upload_url' => route('org.projects.clearance.upload', $project),
        ];




        $today = Carbon::today();

        $currentStage = 'submitted'; // default


        $proposalApproved = $proposalDoc && $proposalDoc->status === 'approved_by_sacdev';
        $budgetApproved   = $budgetDoc && $budgetDoc->status === 'approved_by_sacdev';

        $hasBothApproved = $proposalApproved && $budgetApproved;

        $startDate = $project->implementation_start_date;
        $endDate   = $project->implementation_end_date;



        if ($project->workflow_status === 'completed') {
            $currentStage = 'completed';
        }

        elseif ($hasBothApproved) {

            if ($startDate && $endDate) {

                if ($today->between($startDate, $endDate)) {
                    $currentStage = 'implementation';
                }

                elseif ($today->gt($endDate)) {
                    $currentStage = 'post';
                }

                else {
                    $currentStage = 'pre';
                }

            } else {
                $currentStage = 'pre';
            }
        }


        $postFormsApproved = collect($sections['post'] ?? [])
            ->every(fn ($f) =>
                $f['document'] &&
                $f['document']->status === 'approved_by_sacdev'
            );

        if ($currentStage === 'post' && $postFormsApproved) {
            $currentStage = 'finance';
        }

        $milestones = [
            ['key' => 'submitted', 'label' => 'Submitted'],
            ['key' => 'pre', 'label' => 'Pre-'],
            ['key' => 'implementation', 'label' => 'Implementation'],
            ['key' => 'post', 'label' => 'Post-'],
            ['key' => 'finance', 'label' => 'Awaiting Finance'],
            ['key' => 'completed', 'label' => 'Completed'],
        ];

        return view('org.projects.documents.hub', compact(
            'project',
            'header',
            'snapshot',
            'actions',
            'sections',
            'clearance',
            'milestones',
            'currentStage',
        ));
    }


}