<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use App\Models\FormType;
use App\Models\Project;
use App\Models\ProjectAssignment;
use App\Models\ProjectDocument;
use App\Services\ProjectFormRequirementResolver;
use Carbon\Carbon;

class ProjectDocumentHubController extends Controller
{


    public function showV2(Project $project, ProjectFormRequirementResolver $resolver)
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

        $assignment = ProjectAssignment::query()
            ->where('project_id', $project->id)
            ->where('user_id', $user->id)
            ->where('assignment_role', 'project_head')
            ->whereNull('archived_at')
            ->first();

        $needsAgreement = $assignment && !$assignment->agreement_accepted_at;




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


        //form dependencies

        $solicitationApp = $allDocuments->first(function ($doc) {
            return $doc->formType?->code === 'SOLICITATION_APPLICATION';
        });

        $sellingApp = $allDocuments->first(function ($doc) {
            return $doc->formType?->code === 'SELLING_APPLICATION';
        });

        $solicitationApproved = $solicitationApp?->status === 'approved_by_sacdev';
        $sellingApproved = $sellingApp?->status === 'approved_by_sacdev';


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

        $requiredFormTypes = $resolver->resolve($project);

        if (!$proposalDoc) {
            $proposalAction = [
                'label' => 'Create Proposal',
                'type' => 'create',
                'url' => route('org.projects.documents.combined-proposal.create', $project),
            ];
        } elseif ($proposalDoc->isEditable() && $isProjectHead) {
            $proposalAction = [
                'label' => 'Continue Proposal',
                'type' => 'edit',
                'url' => route('org.projects.documents.combined-proposal.create', $project),
            ];
        } else {
            $proposalAction = [
                'label' => 'View Proposal',
                'type' => 'view',
                'url' => route('org.projects.documents.combined-proposal.create', $project),
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

        $hasApprovedProposal = $proposalDoc && $proposalDoc->status === 'approved_by_sacdev';

        $postponementType = FormType::where('code', 'POSTPONEMENT_NOTICE')->first();
        $cancellationType = FormType::where('code', 'CANCELLATION_NOTICE')->first();

        $postponementDoc = $postponementType
            ? ($documents[$postponementType->id] ?? null)
            : null;

        $cancellationDoc = $cancellationType
            ? ($documents[$cancellationType->id] ?? null)
            : null;

        

        $actions = [
            'can_generate_dv' => $budgetDoc !== null,
            'dv_url' => route('org.projects.documents.disbursement-voucher.create', $project),

           
            'postponement' => [
                'exists' => (bool) $postponementDoc,
                'can_create' => $hasApprovedProposal && (
                    !$postponementDoc ||
                    $postponementDoc->status === 'approved_by_sacdev'
                ),
                'create_url' => route('org.projects.documents.postponement.create', $project),
                'view_url' => $postponementDoc
                    ? route('org.projects.documents.postponement.edit', [
                        'project' => $project,
                        'document' => $postponementDoc->id
                    ])
                    : null,
            ],

            
            'cancellation' => [
                'exists' => (bool) $cancellationDoc,
                'can_create' => $hasApprovedProposal && !$cancellationDoc,
                'create_url' => route('org.projects.documents.cancellation.create', $project),
                'view_url' => $cancellationDoc
                    ? route('org.projects.documents.cancellation.edit', [
                        'project' => $project,
                        'document' => $cancellationDoc->id
                    ])
                    : null,
            ],


            'can_packets' => !$needsAgreement && $hasApprovedProposal,
            'packet_url' => route('org.projects.packets.index', $project),



        ];


        $actions['travel_form'] = [
            'can_create' => $proposalDoc && $proposalDoc->status === 'approved_by_sacdev',

            'create_url' => route('org.projects.documents.off-campus.travel-form.create', $project),
        ];


        $formRoutes = [
            'PROJECT_PROPOSAL' => 'org.projects.documents.project-proposal.create',
            'BUDGET_PROPOSAL'  => 'org.projects.documents.budget-proposal.create',
            'OFF_CAMPUS_APPLICATION' => 'org.projects.documents.off-campus.guidelines',
            'SOLICITATION_APPLICATION' => 'org.projects.documents.solicitation.create',
            'SELLING_APPLICATION' => 'org.projects.documents.selling.create',
            'REQUEST_TO_PURCHASE' => 'org.projects.documents.request-to-purchase.create',

            'FEES_COLLECTION_REPORT' => 'org.projects.documents.fees-collection.create',
            'SELLING_ACTIVITY_REPORT' => 'org.projects.documents.selling-activity-report.create',
            'SOLICITATION_SPONSORSHIP_REPORT' => 'org.projects.documents.solicitation-sponsorship-report.create',
            'TICKET_SELLING_REPORT' => 'org.projects.documents.ticket-selling-report.create',

            'DOCUMENTATION_REPORT' => 'org.projects.documents.documentation-report.create',
            'LIQUIDATION_REPORT' => 'org.projects.documents.liquidation-report.create',
        ];

        $buildForm = function ($formType) use (
            $documents,
            $user,
            $project,
            $formRoutes,
            $isProjectHead,
            $needsAgreement,
        ) {

            $doc = $documents[$formType->id] ?? null;
            $routeName = $formRoutes[$formType->code] ?? null;

            $pending = $doc?->currentPendingSignature();
            $nextRole = $doc?->nextPendingRole();

            $isMine = $pending && $pending->user_id === $user->id;

            return [
                'name' => $formType->name,
                'code' => $formType->code,

                'phase' => $formType->phase,

                'document' => $doc,

                'status_label' => $doc?->status_label ?? 'Not started',
                'status_class' => $doc?->status_badge_class ?? 'bg-slate-100 text-slate-600',

                'waiting_for' => $nextRole,

                'is_waiting_for_me' => $isMine,
                'is_waiting_for_others' => $doc && !$isMine && $nextRole,

                'can_create' => !$needsAgreement && !$doc && $routeName && $isProjectHead,
                'can_edit'   => !$needsAgreement && $doc?->isEditable() && $isProjectHead,
                'can_review' => $isMine,

                'create_url' => (!$doc && $routeName) ? route($routeName, $project) : null,
                'edit_url' => ($doc && $doc->isEditable() && $routeName) ? route($routeName, $project) : null,
                'view_url' => ($doc && $routeName) ? route($routeName, $project) : null,
            ];
        };


        
        // dd(FormType::where('phase', 'off-campus')->get());

        $requiredForms = collect($requiredFormTypes)->map($buildForm);

        $alwaysAvailableCodes = [
            'POSTPONEMENT_NOTICE',
            'CANCELLATION_NOTICE',
            'REQUEST_TO_PURCHASE',
            'SELLING_APPLICATION',
        ];

        $alwaysAvailableForms = FormType::whereIn('code', $alwaysAvailableCodes)
            ->get()
            ->map($buildForm);

        $workflowForms = collect();

        if ($sellingApproved) {
            $sellingReport = FormType::where('code', 'SELLING_ACTIVITY_REPORT')->first();

            if ($sellingReport) {
                $workflowForms->push($buildForm($sellingReport));
            }
        }

        $sections = [
            'pre' => $formTypes->where('phase', 'pre_implementation')->map($buildForm),
            'required' => $requiredForms,
            'optional' => $alwaysAvailableForms,
            'workflow' => $workflowForms,
        ];

        // dd(FormType::where('phase', 'off-campus')->get());

        $pendingCount = $allDocuments->filter(function ($doc) use ($user) {
            $pending = $doc->currentPendingSignature();
            return $pending && $pending->user_id === $user->id;
        })->count();

        $sectionCounts = [];

        foreach ($sections as $key => $forms) {
            $sectionCounts[$key] = collect($forms)->filter(function ($form) {
                return $form['is_waiting_for_me'] ?? false;
            })->count();
        }




        $participants = $project->documents()
            ->whereHas('formType', fn($q) => $q->where('code','OFF_CAMPUS_APPLICATION'))
            ->first()?->offCampus?->participants ?? collect();

        $clearance = [
            'required' => $project->requires_clearance,

            'reference' => $project->clearance_reference,
            'status' => $project->clearance_status,
            'issued_at' => $project->clearance_issued_at,

            'snapshot' => $project->clearance_snapshot,

            'participants_count' => $project->documents()
                ->where('form_type_id', 3)
                ->with('offCampusActivity.participants')
                ->get()
                ->pluck('offCampusActivity')
                ->filter()
                ->flatMap->participants
                ->count(),

            'is_project_head' => $isProjectHead,

            'is_outdated' => app(\App\Http\Controllers\Org\ClearanceController::class)
                ->isSnapshotOutdated($project),

            'print_url' => route('org.projects.clearance.print', $project),
            'upload_url' => route('org.projects.clearance.upload', $project),
            'reissue_url' => route('org.projects.clearance.reissue', $project),
        ];

        $today = Carbon::today();

        $currentStage = 'submitted'; 

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


        $postFormsApproved = collect($sections['required'] ?? [])
            ->filter(fn($f) => in_array($f['code'], [
                'DOCUMENTATION_REPORT',
                'LIQUIDATION_REPORT'
            ]))
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
            'needsAgreement',

            'proposalDoc',
            'budgetDoc',

            'pendingCount',
            'sectionCounts',

            'isProjectHead'
        ));
    }


}