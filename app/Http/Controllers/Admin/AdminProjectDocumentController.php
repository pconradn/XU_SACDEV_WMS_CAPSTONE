<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FormType;
use App\Models\Project;
use App\Models\ProjectAssignment;
use App\Models\ProjectDocument;
use App\Notifications\ReregActionNotification;
use App\Services\ProjectFormRequirementResolver;
use App\Support\Audit;
use App\Support\InAppNotifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class AdminProjectDocumentController extends Controller
{

    public function hub(Project $project, \App\Services\ProjectFormRequirementResolver $resolver)
    {
        $focusDocId = request('focus');

        $documents = ProjectDocument::query()
            ->with(['formType', 'signatures.user', 'proposalData'])
            ->where('project_id', $project->id)
            ->whereNull('archived_at')
            ->get()
            ->keyBy(fn($d) => $d->formType->code);

        $project->load([
            'externalPackets.items',
            'submissionPackets'
        ]);

        $externalPackets = $project->externalPackets;
        $submissionPackets = $project->submissionPackets
            ->sortByDesc('created_at')
            ->take(3); 


        $projectHead = ProjectAssignment::with('user')
            ->where('project_id', $project->id)
            ->where('assignment_role', 'project_head')
            ->whereNull('archived_at')
            ->first()?->user;

        $header = [
            'title' => $project->title,
            'org' => $project->organization->name ?? null,
            'school_year' => $project->schoolYear->name ?? null,
            'project_head' => $projectHead?->name ?? null,

            'status' => $project->workflow_status,
            'status_label' => $project->workflow_status_label,
        ];


        $proposalDoc = $documents['PROJECT_PROPOSAL'] ?? null;
        $budgetDoc   = $documents['BUDGET_PROPOSAL'] ?? null;

        $proposalData = $proposalDoc?->proposalData;

        //dd($proposalData);

        $snapshot = [
            'date' => $project->implementation_date_display,
            'time' => $project->implementation_time_display,
            'venue' => $project->implementation_venue,

            'description' => $proposalData->description ?? $project->description,

          
            'status' => $proposalDoc?->status,
            'status_label' => $proposalDoc?->status_label,

            'is_off_campus' => $proposalData?->off_campus_venue,

            'total_budget' => $proposalData->total_budget ?? null,

            
            'fund_sources' => $proposalData?->fundSources ?? collect(),
        ];


        $hasProposal = isset($documents['PROJECT_PROPOSAL']);
        $hasBudget   = isset($documents['BUDGET_PROPOSAL']);

        $combined = [
            'exists' => $hasProposal || $hasBudget,

            'view_url' => ($hasProposal || $hasBudget)
                ? route('admin.projects.documents.combined-proposal.open', $project)
                : null,

            'proposal_print_url' => $hasProposal
                ? route('admin.projects.documents.print', [
                    'project' => $project,
                    'form' => 'PROJECT_PROPOSAL',
                    'document' => $proposalDoc->id ?? null
                ])
                : null,

            'budget_print_url' => $hasBudget
                ? route('admin.projects.documents.print', [
                    'project' => $project,
                    'form' => 'BUDGET_PROPOSAL',
                    'document' => $budgetDoc->id ?? null
                ])
                : null,
        ];


   
        $buildForm = function ($formType) use ($documents, $project) {

            $doc = $documents[$formType->code] ?? null;

            $pendingSignature = $doc?->signatures
                ?->where('status', 'pending')
                ->sortBy('id')
                ->first();

            $nextRole = $pendingSignature?->role;
            $pendingUser = $pendingSignature?->user;

            $pending = $pendingSignature !== null;

            $isMine = $pendingSignature && $pendingSignature->user_id === auth()->id();

            $isSacdevStep = $nextRole === 'sacdev_admin';

            $remainingRoles = $doc?->signatures
                ?->where('status', 'pending')
                ->pluck('role')
                ->unique()
                ->toArray() ?? [];

            $allowedRemainingRoles = ['sacdev_admin', 'coa_officer'];

            $isFinalStage =
                !empty($remainingRoles) &&
                count(array_diff($remainingRoles, $allowedRemainingRoles)) === 0;

            $canPrint =
                $doc &&
                (
                    $doc->status === 'approved_by_sacdev' ||
                    ($doc->status === 'submitted' && $isFinalStage)
                );

            return [
                'name' => $formType->name,
                'code' => $formType->code,
                'phase' => $formType->phase,

                'document' => $doc,

                'status_label' => $doc?->status_label ?? 'Not started',
                'status_class' => $doc?->status_badge_class ?? 'bg-slate-100 text-slate-600',

                'waiting_for' => $nextRole,

                'is_pending' => $pending,
                'is_approved' => $doc && $doc->status === 'approved_by_sacdev',

                'view_url' => $doc
                    ? route('admin.projects.documents.open', [$project, $formType->code])
                    : null,



                'print_url' => $canPrint
                    ? route('admin.projects.documents.print', [$project, $formType->code, $doc->id])
                    : null,

                'pending' => $pending,
                'is_pending_for_me' => $isMine,
                'is_sacdev_step' => $isSacdevStep,
                'pending_user_name' => $pendingUser?->name,
                'pending_user_id' => $pendingUser?->id,
            ];
        };


        $formTypes = FormType::whereIn('code', [
            'PROJECT_PROPOSAL',
            'BUDGET_PROPOSAL',
            'OFF_CAMPUS_APPLICATION',
            'SOLICITATION_APPLICATION',
            'SELLING_APPLICATION',
            'REQUEST_TO_PURCHASE',
            'FEES_COLLECTION_REPORT',
            'SELLING_ACTIVITY_REPORT',
            'SOLICITATION_SPONSORSHIP_REPORT',
            'TICKET_SELLING_REPORT',
            'DOCUMENTATION_REPORT',
            'LIQUIDATION_REPORT',
        ])->get();

        $forms = $formTypes
            ->reject(fn($f) => in_array($f->code, [
                'PROJECT_PROPOSAL',
                'BUDGET_PROPOSAL'
            ]))
            ->map($buildForm);

        $pendingForAdmin = $forms->filter(function ($f) {
            return $f['is_pending'] && $f['waiting_for'] === 'sacdev_admin';
        })->count();

        //$forms = $formTypes->map($buildForm);

        $groupedForms = $forms->groupBy('phase');

        /*
        |--------------------------------------------------------------------------
        | NEW: DOCUMENT GROUPING FOR DASHBOARD
        |--------------------------------------------------------------------------
        */

        $documentsGrouped = [
            'action_required' => collect(),
            'required' => collect(),
            'submitted_optional' => collect(),
            'approved' => collect(),
            'others' => collect(),
        ];
        $requiredFormTypes = $resolver->resolve($project);


        $requiredFormCodes = collect($requiredFormTypes)->pluck('code')->toArray();

        foreach ($forms as $form) {

            $doc = $form['document'];

            $isRequired = in_array($form['code'], $requiredFormCodes);
            $isSubmitted = $doc !== null;
            $isApproved = $doc && $doc->status === 'approved_by_sacdev';

            $isActionRequired =
                $form['is_pending'] &&
                $form['waiting_for'] === 'sacdev_admin';

            // 🔴 ACTION REQUIRED
            if ($isActionRequired || ($doc && $doc->status === 'returned')) {
                $documentsGrouped['action_required']->push($form);
                continue;
            }

            // 🟢 APPROVED
            if ($isApproved) {
                $documentsGrouped['approved']->push($form);
                continue;
            }

            // 🟡 REQUIRED
            if ($isRequired && !$isSubmitted) {
                $documentsGrouped['required']->push($form);
                continue;
            }

            // 🔵 SUBMITTED OPTIONAL
            if (!$isRequired && $isSubmitted) {
                $documentsGrouped['submitted_optional']->push($form);
                continue;
            }

            // ⚪ OTHERS
            $documentsGrouped['others']->push($form);
        }


        $totalForms = $forms->count();

        $approvedForms = $documents->filter(function ($doc) {
            return $doc->status === 'approved_by_sacdev';
        })->count();

        

        
        $requiredDocs = collect($requiredFormTypes)->map(function (FormType $formType) use ($documents) {
            return $documents[$formType->code] ?? null;
        });


        $totalRequired = $requiredDocs->count();

    
        $approvedRequired = $requiredDocs
            ->filter(fn($doc) => $doc && $doc->status === 'approved_by_sacdev')
            ->count();

     
        $percentage = $totalRequired > 0
            ? (int) round(($approvedRequired / $totalRequired) * 100)
            : 0;

    

        $progress = [
            'required' => $totalRequired,
            'approved' => $approvedRequired,
            'percentage' => $percentage,
        ];

        $postponementType = FormType::where('code','POSTPONEMENT_NOTICE')->first();

        $postponements = $postponementType
            ? ProjectDocument::where('project_id',$project->id)
                ->where('form_type_id',$postponementType->id)
                ->whereNull('archived_at')
                ->latest()
                ->get()
                ->map(fn($doc) => [
                    'id' => $doc->id,
                    'status' => $doc->status,
                    'status_label' => $doc->status_label,
                    'view_url' => route('admin.projects.documents.open', [
                        'project' => $project,
                        'formType' => 'POSTPONEMENT_NOTICE',
                    ]),
                ])
            : collect();


        $cancellationType = FormType::where('code','CANCELLATION_NOTICE')->first();

        $cancellations = $cancellationType
            ? ProjectDocument::where('project_id',$project->id)
                ->where('form_type_id',$cancellationType->id)
                ->whereNull('archived_at')
                ->latest()
                ->get()
                ->map(fn($doc) => [
                    'id' => $doc->id,
                    'status' => $doc->status,
                    'status_label' => $doc->status_label,
                    'view_url' => route('admin.projects.documents.open', [
                        'project' => $project,
                        'formType' => 'CANCELLATION_NOTICE',
                    ]),
                ])
            : collect();




        $allRequiredApproved = $requiredDocs
            ->every(fn($doc) => $doc && $doc->status === 'approved_by_sacdev');


        $isCoa = auth()->user()?->is_coa_officer;

        $canMarkComplete =
            !$isCoa &&
            $project->workflow_status !== 'completed' &&
            $requiredDocs->isNotEmpty() &&
            $allRequiredApproved;

        $isReadyForCompletion =
            $project->workflow_status !== 'completed' &&
            $requiredDocs->isNotEmpty() &&
            $allRequiredApproved;

        $actions = [
            'can_mark_complete' => $canMarkComplete,
            'is_ready' => $isReadyForCompletion,
            'mark_complete_url' => $canMarkComplete
                ? route('admin.projects.mark-complete', $project)
                : null,
        ];




        return view('admin.projects.documents.hub', [
            'project' => $project,

            'header' => $header,
            'snapshot' => $snapshot,
            'combined' => $combined,

            'forms' => $forms,
            'progress' => $progress,

            'postponements' => $postponements,
            'cancellations' => $cancellations,

            'actions' => $actions,
            'groupedForms' => $groupedForms,

            'proposalDoc' => $proposalDoc,
            'budgetDoc' => $budgetDoc,

            'pendingForAdmin' => $pendingForAdmin,
            
            'externalPackets' => $externalPackets,
            'submissionPackets' => $submissionPackets,
            'documentsGrouped' => $documentsGrouped,
            'focusDocId' => $focusDocId,
        ]);
    }



    public function retractComplete(Project $project)
    {
        if (auth()->user()?->is_coa_officer) {
            abort(403, 'COA officers cannot modify project completion.');
        }

        if ($project->workflow_status !== 'completed') {
            return back()->with('error', 'Only completed projects can be reverted.');
        }

        DB::transaction(function () use ($project) {

            $project->update([
                'workflow_status' => 'post_implementation',
                'status' => 'active',
                'completed_at' => null,
            ]);

            Audit::log(
                'project.completion_retracted',
                'Project completion was reverted',
                [
                    'actor_user_id' => auth()->id(),
                    'organization_id' => $project->organization_id,
                    'school_year_id' => $project->school_year_id,
                    'meta' => [
                        'project_id' => $project->id,
                        'title' => $project->title,
                    ]
                ]
            );

        });

        return back()->with('success', 'Project completion has been reverted.');
    }








    public function markComplete(Project $project, ProjectFormRequirementResolver $resolver)
    {
        $documents = ProjectDocument::query()
            ->with('formType')
            ->where('project_id', $project->id)
            ->whereNull('archived_at')
            ->get()
            ->keyBy(fn($d) => $d->formType->code);

        $requiredFormTypes = $resolver->resolve($project);

        $requiredDocs = collect($requiredFormTypes)->map(function (FormType $formType) use ($documents) {
            return $documents[$formType->code] ?? null;
        });

        $allApproved = $requiredDocs
            ->filter()
            ->every(fn($doc) => $doc->status === 'approved_by_sacdev');
        
        if (auth()->user()?->is_coa_officer) {
            abort(403, 'COA officers cannot mark projects as complete.');
        }

        if (!$allApproved) {
            return back()->with('error', 'Project cannot be marked as completed. Some required documents are not yet approved.');
        }

        if ($project->workflow_status === 'completed') {
            return back()->with('status', 'Project is already marked as completed.');
        }

        $project->update([
            'workflow_status' => 'completed',
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        $this->notifyProjectHeadCompleted($project);

        Audit::log(
            'project.completed',
            'Project marked as completed',
            [
                'actor_user_id' => auth()->id(),
                'organization_id' => $project->organization_id,
                'school_year_id' => $project->school_year_id,
                'meta' => [
                    'project_id' => $project->id,
                    'title' => $project->title,
                ]
            ]
        );

        return back()->with('success', 'Project marked as completed successfully.');
    }

    protected function notifyProjectHeadCompleted(Project $project)
    {
        $assignment = ProjectAssignment::where('project_id', $project->id)
            ->where('assignment_role', 'project_head')
            ->whereNull('archived_at')
            ->with('user')
            ->first();

        if (!$assignment || !$assignment->user) {
            return;
        }

        $assignment->user->notify(new ReregActionNotification([
            'title' => 'Project Completed',
            'message' => 'Your project "' . $project->title . '" has been marked as completed by SACDEV.',
            'action_url' => route('org.projects.documents.hub', $project),
            'meta' => [
                'project_id' => $project->id,
                'type' => 'project_completed'
            ]
        ]));
    }

    public function open(Project $project, $formType, $documentId = null)
    {

        $query = ProjectDocument::query()
            ->with([
                'signatures.user',
                'formType',
                'budgetProposal.items',
                'sellingApplication.items',
                'requestToPurchase.items',
                'feesCollectionReport.items',
                'sellingActivityReport.items',
                'solicitationSponsorshipReport.items',
                'ticketSellingReport.items',
                'liquidationData.items',

                'postponementNotice',
                'cancellationNotice',

                'documentationReport.objectives',
                'documentationReport.indicators',
                'documentationReport.partners',
                'documentationReport.attendees',
            ])
            ->where('project_id', $project->id)
            ->whereHas('formType', fn($q) => $q->where('code', $formType));

        if ($documentId !== null) {
            $document = $query->where('id', $documentId)->firstOrFail();
        } else {
            $document = $query->firstOrFail();
        }


        $viewMap = [

            'PROJECT_PROPOSAL' => 'org.projects.documents.combined.create',
            'BUDGET_PROPOSAL' => 'org.projects.documents.combined.create',

            'OFF_CAMPUS_APPLICATION' => 'org.projects.documents.off-campus.create',

            'SOLICITATION_APPLICATION' => 'org.projects.documents.solicitation.create',
            'SELLING_APPLICATION' => 'org.projects.documents.selling.create',

            'REQUEST_TO_PURCHASE' => 'org.projects.documents.request-to-purchase.create',

            'FEES_COLLECTION_REPORT' => 'org.projects.documents.fees-collection.create',
            'SELLING_ACTIVITY_REPORT' => 'org.projects.documents.selling-activity-report.create',
            'SOLICITATION_SPONSORSHIP_REPORT' => 'org.projects.documents.solicitation-sponsorship-report.create',
            'TICKET_SELLING_REPORT' => 'org.projects.documents.ticket-selling-report.create',

            'DOCUMENTATION_REPORT' => 'org.projects.documents.documentation-report.create',
            'LIQUIDATION_REPORT' => 'org.projects.documents.liquidation-report.create',
            
            
            'POSTPONEMENT_NOTICE' => 'org.projects.documents.postponement.create',
            'CANCELLATION_NOTICE' => 'org.projects.documents.cancellation.create',

        ];

        $view = $viewMap[$formType] ?? abort(404);


        $proposal = $document->proposalData;
        $budget = $document->budgetProposal;

        $activity = $document->offCampusActivity;
        $participants = $activity?->participants ?? collect();

        $solicitation = $document->solicitationData;
        $selling = $document->sellingApplication;
        $purchase = $document->requestToPurchase;

        $feesCollection = $document->feesCollectionReport;
        $sellingActivity = $document->sellingActivityReport;
        $solicitationReport = $document->solicitationSponsorshipReport;
        $ticketReport = $document->ticketSellingReport;


        $report = null;

        if ($formType === 'LIQUIDATION_REPORT') {
            $report = $document->liquidationData;
        }

        if ($formType === 'DOCUMENTATION_REPORT') {
            $report = $document->documentationReport;
        }


        $documentation = $document->documentationReport;

        $objectives = $documentation?->objectives ?? collect();
        $indicators = $documentation?->indicators ?? collect();
        $partners = $documentation?->partners ?? collect();
        $attendees = $documentation?->attendees ?? collect();


        $prefill = [];

        if ($documentation) {
            $prefill = [
                'implementation_start_date' => $documentation->implementation_start_date,
                'implementation_end_date'   => $documentation->implementation_end_date,
                'implementation_start_time' => $documentation->implementation_start_time,
                'implementation_end_time'   => $documentation->implementation_end_time,
                'on_campus_venue'           => $documentation->on_campus_venue,
                'off_campus_venue'          => $documentation->off_campus_venue,
                'description'               => $documentation->description,
            ];
        }


        $items =
            $purchase?->items ??
            $selling?->items ??
            $feesCollection?->items ??
            $sellingActivity?->items ??
            $solicitationReport?->items ??
            $ticketReport?->items ??
            $report?->items ??
            collect();

        



        $data = null;

        if ($formType === 'POSTPONEMENT_NOTICE') {
            $data = $document->postponementNotice;
        }

        if ($formType === 'CANCELLATION_NOTICE') {
            $data = $document->cancellationNotice;      
        }

        if ($formType === 'FEES_COLLECTION_REPORT') {
            $data = $feesCollection;
            $items = $feesCollection?->items ?? collect();
        }


        if ($formType === 'SELLING_ACTIVITY_REPORT') {
            $data = $sellingActivity;
            $items = $sellingActivity?->items ?? collect();
        }

        if ($formType === 'SOLICITATION_SPONSORSHIP_REPORT') {
            $data = $solicitationReport;
            $items = $solicitationReport?->items ?? collect();
        }

        if ($formType === 'TICKET_SELLING_REPORT') {
            $data = $ticketReport;
            $items = $ticketReport?->items ?? collect();
        }


        $user = auth()->user();
        $userId = $user->id;

        $isAdmin = $user->isSacdev();


        $currentSignature = $document->signatures
            ->where('user_id', $userId)
            ->first();


        $proposalDocument = ProjectDocument::where('project_id', $project->id)
            ->whereHas('formType', fn($q) => $q->where('code', 'PROJECT_PROPOSAL'))
            ->first();

        $proposal = $proposalDocument?->proposalData;

        //dd(
        //    $document->id,
        //    $document->postponementNotice
        //);

        return view($view, [

            'project' => $project,
            'document' => $document,

            'proposal' => $proposal,
            'budget' => $budget,

            'activity' => $activity,
            'participants' => $participants,

            'data' => $data ?? ($purchase ?? $solicitation ?? $selling),

            'feesCollection' => $feesCollection,
            'sellingActivity' => $sellingActivity,
            'solicitationReport' => $solicitationReport,
            'ticketReport' => $ticketReport,

            'documentation' => $documentation,
            'objectives' => $objectives,
            'indicators' => $indicators,
            'partners' => $partners,
            'attendees' => $attendees,

            'prefill' => $prefill,

            'report' => $report,

            'items' => $items,

            'isReadOnly' => true,
            'isProjectHead' => false,
            'currentSignature' => $currentSignature,
            'isAdmin' => $isAdmin,
        ]);
    }

    public function openCombined(Project $project)
    {
        //dd($project);
        $proposalDoc = ProjectDocument::with(['signatures.user', 'formType'])
            ->where('project_id', $project->id)
            ->whereHas('formType', fn($q) => $q->where('code', 'project_proposal'))
            ->first();

        $budgetDoc = ProjectDocument::with(['signatures.user', 'formType'])
            ->where('project_id', $project->id)
            ->whereHas('formType', fn($q) => $q->where('code', 'budget_proposal'))
            ->first();

        if (!$proposalDoc || !$budgetDoc) {
            abort(404, 'Combined documents not found.');
        }

        return view('org.projects.documents.combined.create', [
            'project' => $project,
            'proposalData' => [
                'document' => $proposalDoc,
                'proposal' => $proposalDoc->proposalData,
                'isProjectHead' => false,
            ],
            'budgetData' => [
                'document' => $budgetDoc,
                'budget' => $budgetDoc->budgetProposal,
            ],
            'isAdmin' => true,
        ]);
    }

    public function combinedApprove(Project $project)
    {
        DB::transaction(function () use ($project) {

            $this->approve(request(), $project, 'PROJECT_PROPOSAL');
            $this->approve(request(), $project, 'BUDGET_PROPOSAL');

        });

        return back()->with('success', 'Combined documents approved.');
    }

    public function combinedReturn(Request $request, Project $project)
    {
        DB::transaction(function () use ($request, $project) {

            $this->return($request, $project, 'PROJECT_PROPOSAL');
            $this->return($request, $project, 'BUDGET_PROPOSAL');

        });

        return back()->with('success', 'Combined documents returned.');
    }

    public function combinedRetract(Project $project)
    {
        DB::transaction(function () use ($project) {

            $this->retract($project, 'PROJECT_PROPOSAL');
            $this->retract($project, 'BUDGET_PROPOSAL');

        });

        return back()->with('success', 'Combined documents retracted.');
    }

    public function combinedAllowEdit(Project $project, Request $request)
    {
        DB::transaction(function () use ($project, $request) {

            $this->allowEdit($project, 'PROJECT_PROPOSAL', $request);
            $this->allowEdit($project, 'BUDGET_PROPOSAL', $request);

        });

        return back()->with('success', 'Edit allowed for combined documents.');
    }





    public function showPrint(Project $project, $formType, $documentId = null)
    {
        $query = ProjectDocument::query()
            ->with([
                'signatures.user',
                'formType',
                'proposalData.guests',
                'proposalData.planOfActions',
                'budgetProposal.items',
                'offCampusActivity.participants',
                'packetItems.packet',
            ])
            ->where('project_id', $project->id)
            ->whereHas('formType', fn($q) => $q->where('code', $formType));

            
        $document = $documentId
            ? $query->where('id', $documentId)->firstOrFail()
            : $query->firstOrFail();

        $remainingSignatures = $document->signatures
            ->where('status', 'pending');

        $remainingRoles = $remainingSignatures
            ->pluck('role')
            ->unique()
            ->values()
            ->toArray();

        $allowedRemainingRoles = ['sacdev_admin', 'coa_officer'];

        $isFinalStage =
            !empty($remainingRoles) &&
            count(array_diff($remainingRoles, $allowedRemainingRoles)) === 0;

        $canPrint =
            $document->status === 'approved_by_sacdev' ||
            ($document->status === 'submitted' && $isFinalStage);

        if (!$canPrint) {
            abort(403, 'Document not ready for printing.');
        }

        if (auth()->user()->system_role !== 'sacdev_admin') {
            abort(403);
        }

        $proposal = $document->proposalData;
        $budget = $document->budgetProposal;
        $offcampus = $document->offCampusActivity;
        $participants = $offcampus?->participants ?? collect();
        $solicitation = $document->solicitationData;
        $purchaseSourceOfFunds = $document->requestToPurchase;


        $packetItem = $document->packetItems
            ->sortByDesc('created_at')
            ->first();

        $packet = $packetItem?->packet;


        $view = match ($formType) {
            'PROJECT_PROPOSAL' => 'admin.projects.documents.project-proposal.print',
            'BUDGET_PROPOSAL'  => 'admin.projects.documents.budget-proposal.print',
            'OFF_CAMPUS_APPLICATION' => 'admin.projects.documents.off-campus.print',
            'SOLICITATION_APPLICATION' => 'admin.projects.documents.solicitation.print',
            'SELLING_APPLICATION' => 'admin.projects.documents.selling.print',
            'LIQUIDATION_REPORT' => 'admin.projects.documents.liquidation.print',
            'REQUEST_TO_PURCHASE' => 'admin.projects.documents.request-to-purchase.print',
            'SOLICITATION_SPONSORSHIP_REPORT' => 'admin.projects.documents.solicitation-report.print',
            'SELLING_ACTIVITY_REPORT' => 'admin.projects.documents.selling-report.print',
            'TICKET_SELLING_REPORT' => 'admin.projects.documents.ticket-report.print',
            'FEES_COLLECTION_REPORT' => 'admin.projects.documents.fees-report.print',
            'DOCUMENTATION_REPORT' => 'admin.projects.documents.docu.print',
            default => abort(404),
        };

        return view($view, [
            'project' => $project,
            'document' => $document,
            'proposal' => $proposal,
            'budget' => $budget,
            'offcampus'=> $offcampus,
            'participants'=> $participants,
            'solicitation' => $solicitation,
            'purchaseSourceOfFunds' => $purchaseSourceOfFunds,
            'packet' => $packet,

        ]);
    }
    

    public function approve(Request $request, Project $project, $formCode, $documentId = null)
    {

        $allowedForms = [

            'PROJECT_PROPOSAL',
            'BUDGET_PROPOSAL',

            'OFF_CAMPUS_APPLICATION',

            'SOLICITATION_APPLICATION',
            'SELLING_APPLICATION',

            'REQUEST_TO_PURCHASE',

            'FEES_COLLECTION_REPORT',
            'SELLING_ACTIVITY_REPORT',
            'SOLICITATION_SPONSORSHIP_REPORT',
            'TICKET_SELLING_REPORT',

            'DOCUMENTATION_REPORT',
            'LIQUIDATION_REPORT',

            
            'POSTPONEMENT_NOTICE',
            'CANCELLATION_NOTICE',

        ];

        if (!in_array($formCode, $allowedForms)) {
            abort(404);
        }

        $formType = FormType::where('code', $formCode)->firstOrFail();

        $query = ProjectDocument::with('signatures')
            ->where('project_id', $project->id)
            ->where('form_type_id', $formType->id);

        if ($documentId) {
            $document = $query->where('id', $documentId)->firstOrFail();
        } else {
            $document = $query->firstOrFail();
        }

        if ($document->status !== 'submitted') {
            return back()->with('error', 'This document is not awaiting approval.');
        }

        $userId = auth()->id();

        $userSignature = $document->signatures
            ->where('user_id', $userId)
            ->first();

        if (!$userSignature) {
            return back()->with('error', 'You are not part of the approval workflow.');
        }

        if ($userSignature->status === 'signed') {
            return back()->with('error', 'You have already approved this document.');
        }

        $currentPending = $document->signatures
            ->where('status', 'pending')
            ->sortBy('id')
            ->first();

        if (!$currentPending) {
            return back()->with('error', 'No pending approvals remain.');
        }

        if ($currentPending->user_id !== $userId) {
            return back()->with('error', 'It is not your turn to approve yet.');
        }


        DB::transaction(function () use ($request, $document, $currentPending, $formCode) {

            /*
            |------------------------------------------------------------------
            | Special logic for certain forms
            |------------------------------------------------------------------
            */

            if ($formCode === 'SOLICITATION_APPLICATION') {

                $request->validate([
                    'approved_letter_count' => ['required','integer','min:1'],
                    'control_series_start' => ['required','string','max:255'],
                    'control_series_end' => ['required','string','max:255'],
                ]);

                \App\Models\SolicitationLetterBatch::updateOrCreate(
                    [
                        'project_document_id' => $document->id
                    ],
                    [
                        'approved_letter_count' => $request->approved_letter_count,
                        'control_series_start' => $request->control_series_start,
                        'control_series_end' => $request->control_series_end,
                    ]
                );
            }


            if ($formCode === 'FEES_COLLECTION_REPORT') {

                $data = \App\Models\FeesCollectionReportData::where(
                    'project_document_id',
                    $document->id
                )->first();

                if ($data && $request->has('items')) {

                    foreach ($request->items as $itemId => $row) {

                        \App\Models\FeesCollectionItem::where('id', $itemId)
                            ->update([
                                'remarks' => $row['remarks'] ?? null
                            ]);

                    }

                }
            }


            if ($formCode === 'SELLING_APPLICATION') {

                $data = \App\Models\SellingApplicationData::where(
                    'project_document_id',
                    $document->id
                )->first();

                if ($data && $request->has('items')) {

                    foreach ($request->items as $itemId => $row) {

                        if (!empty($row['remarks'])) {

                            \App\Models\SellingApplicationItem::where('id', $itemId)
                                ->update([
                                    'remarks' => $row['remarks']
                                ]);

                        }

                    }

                }
            }


            $currentPending->update([
                'status' => 'signed',
                'signed_at' => now(),
            ]);


            $remaining = $document->signatures()
                ->where('status', 'pending')
                ->exists();


            if (!$remaining) {

                $document->update([
                    'status' => 'approved_by_sacdev',
                ]);

                $document->timelines()->create([
                    'user_id' => auth()->id(),
                    'action' => 'approved_by_sacdev',
                    'remarks' => null,
                    'old_status' => 'submitted',
                    'new_status' => 'approved_by_sacdev',
                ]);

                /*
                |----------------------------------------------------------
                | APPLY POSTPONEMENT TO PROJECT
                |----------------------------------------------------------
                */
                if ($formCode === 'POSTPONEMENT_NOTICE') {

                    $postponement = \App\Models\PostponementNoticeData::where(
                        'project_document_id',
                        $document->id
                    )->first();

                    if ($postponement) {

                        $project = $document->project;

                        $project->update([
                            'implementation_start_date' => $postponement->new_date,
                            'implementation_end_date'   => $postponement->new_date,

                            'implementation_start_time' => $postponement->new_start_time,
                            'implementation_end_time'   => $postponement->new_end_time,

                            'implementation_venue' => $postponement->venue,

                            'workflow_status' => 'postponed',

                            'approved_postponement_id' => $document->id,
                        ]);

                    }
                }

                if ($formCode === 'CANCELLATION_NOTICE') {

                    $project = $document->project;

                    $project->update([
                        'workflow_status' => 'cancelled',
                        'approved_cancellation_id' => $document->id,
                        'status' => 'cancelled',
                    ]);
                }

            }

        });

        $this->notifyProjectHead(
            $project,
            $document,
            $document->formType->name . ' was approved by SACDEV.'
        );

        Audit::log(
            'document.approved_by_sacdev',
            $document->formType->name . ' approved by SACDEV',
            [
                'actor_user_id' => auth()->id(),
                'organization_id' => $project->organization_id,
                'school_year_id' => $project->school_year_id,
                'meta' => [
                    'document_id' => $document->id,
                    'project_id' => $project->id,
                    'form_type' => $document->formType->code,
                ],
            ]
        );

        return back()->with('success', 'Document approved successfully.');
    }

    public function return(Request $request, Project $project, $formCode, $documentId = null)
    {

        $request->validate([
            'remarks' => ['required', 'string'],
        ]);

        $formType = FormType::where('code', $formCode)->firstOrFail();

        $query = ProjectDocument::with('signatures')
            ->where('project_id', $project->id)
            ->where('form_type_id', $formType->id);

        if ($documentId) {
            $document = $query->where('id', $documentId)->firstOrFail();
        } else {
            $document = $query->firstOrFail();
        }

        if ($document->status !== 'submitted') {
            return back()->with('error', 'This document cannot be returned.');
        }

        $userId = auth()->id();

        $userSignature = $document->signatures
            ->where('user_id', $userId)
            ->first();

        if (!$userSignature) {
            return back()->with('error', 'You are not part of the approval workflow.');
        }

        $currentPending = $document->signatures
            ->where('status', 'pending')
            ->sortBy('id')
            ->first();

        if (!$currentPending) {
            return back()->with('error', 'No pending approvals remain.');
        }

        if ($currentPending->user_id !== $userId) {
            return back()->with('error', 'It is not your turn to return this document yet.');
        }


        DB::transaction(function () use ($document, $request) {

            foreach ($document->signatures as $signature) {

                $signature->update([
                    'status' => 'pending',
                    'signed_at' => null,
                ]);

            }

            $document->update([
                'status' => 'draft',
                'remarks' => $request->remarks,
                'returned_by' => auth()->id(),
                'returned_at' => now(),
            ]);



        });
        $document->timelines()->create([
            'user_id' => auth()->id(),
            'action' => 'returned_by_sacdev',
            'remarks' => $request->remarks,
            'old_status' => 'submitted',
            'new_status' => 'draft',
        ]);

        $this->notifyProjectHead(
            $project,
            $document,
            'Your ' . $document->formType->name . ' was returned for revision by SACDEV.'
        );

        Audit::log(
            'document.returned_by_sacdev',
            $document->formType->name . ' returned for revision by SACDEV',
            [
                'actor_user_id' => auth()->id(),
                'organization_id' => $project->organization_id,
                'school_year_id' => $project->school_year_id,
                'meta' => [
                    'document_id' => $document->id,
                    'project_id' => $project->id,
                    'form_type' => $document->formType->code,
                    'remarks' => $request->remarks,
                ],
            ]
        );      

        return back()->with('success', 'Document returned for revision.');
    }

    public function allowEdit(Project $project, $formCode, Request $request)
    {
        $formType = FormType::where('code', $formCode)->firstOrFail();

        $document = ProjectDocument::where('project_id', $project->id)
            ->where('form_type_id', $formType->id)
            ->firstOrFail();

        $this->handleAllowEdit(
            $project,
            $document,
            $request->input('remarks')
        );

        return back()->with('success', 'Edit request granted.');
    }
    

    protected function handleAllowEdit(Project $project, ProjectDocument $document, ?string $remarks = null): void
    {
        if (!$document->edit_requested) {
            abort(403, 'No edit request pending.');
        }

      
        if (!in_array($document->status, ['approved_by_sacdev', 'submitted'], true)) {
            abort(403, 'Edit can only be granted on submitted or approved documents.');
        }

        DB::transaction(function () use ($project, $document, $remarks) {

            $oldStatus = $document->status;

            $document->update([
                'edit_mode' => true,
                'edit_requires_full_approval' => false, 


                'edit_requested' => false,
                'edit_requested_at' => null,
                'edit_requested_by' => null,
                'edit_request_remarks' => null,
            ]);

            $document->timelines()->create([
                'user_id' => auth()->id(),
                'action' => 'edit_granted',
                'remarks' => $remarks,
                'old_status' => $oldStatus,
                'new_status' => $oldStatus,
            ]);

        });

        DB::afterCommit(function () use ($project, $document) {

            $this->notifyProjectHead(
                $project,
                $document,
                'Edit request granted. You may update and resubmit. Only SACDEV approval will be required.'
            );

        });
    }



    protected function notifyProjectHead(Project $project, ProjectDocument $document, string $message){
        
        $assignment = ProjectAssignment::where('project_id', $project->id)
            ->where('assignment_role', 'project_head')
            ->whereNull('archived_at')
            ->with('user')
            ->first();

        if (!$assignment || !$assignment->user) {
            return;
        }

        InAppNotifier::notifyOnce($assignment->user, [
            'title' => 'Project Document Update',
            'message' => $message,
            'action_url' => $this->resolveOrgDocumentRoute($document),
            'dedupe_key' => 'doc_'.$document->id.'_status_update',
            'meta' => [
                'document_id' => $document->id,
                'form_type'   => $document->formType->code,
                'project_id'  => $project->id
            ]
        ]);
    }

    public function retract(Project $project, $formCode)
    {

        $formType = FormType::where('code', $formCode)->firstOrFail();

        $document = ProjectDocument::where('project_id', $project->id)
            ->where('form_type_id', $formType->id)
            ->firstOrFail();

        if ($document->status !== 'approved_by_sacdev') {
            return back()->with('error', 'Only approved documents can be retracted.');
        }

        $userId = auth()->id();

        if (!$project->isAssignedCoa($project)) {

            $userSignature = $document->signatures
                ->where('user_id', $userId)
                ->first();

            if (!$userSignature) {
                return back()->with('error', 'You are not part of the approval workflow.');
            }

        }

        DB::transaction(function () use ($document) {

            $lastSigned = $document->signatures
                ->where('status', 'signed')
                ->sortByDesc('id')
                ->first();

            if (!$lastSigned) {
                throw new \Exception('No signed approval to retract.');
            }

            if ($lastSigned->user_id !== auth()->id()) {
                return back()->with('error', 'You can only retract your own approval.');
            }

            $lastSigned->update([
                'status' => 'pending',
                'signed_at' => null
            ]);

            $document->update([
                'status' => 'submitted'
            ]);

            $document->timelines()->create([
                'user_id' => auth()->id(),
                'action' => 'approval_retracted',
                'remarks' => null,
                'old_status' => 'approved_by_sacdev',
                'new_status' => 'submitted',
            ]);

            if ($document->formType->code === 'SOLICITATION_APPLICATION') {

                $document->solicitationBatches()->delete();

            }

            if ($document->formType->code === 'SELLING_APPLICATION') {

                $data = \App\Models\SellingApplicationData::where(
                    'project_document_id',
                    $document->id
                )->first();

                if ($data) {

                    \App\Models\SellingApplicationItem::where(
                        'selling_application_data_id',
                        $data->id
                    )->update([
                        'remarks' => null
                    ]);

                }

            }

            if ($document->formType->code === 'FEES_COLLECTION_REPORT') {

                $data = \App\Models\FeesCollectionReportData::where(
                    'project_document_id',
                    $document->id
                )->first();

                if ($data) {

                    \App\Models\FeesCollectionItem::where(
                        'fees_collection_report_id',
                        $data->id
                    )->update([
                        'remarks' => null
                    ]);

                }

            }





        });


        $this->notifyProjectHead(
            $project,
            $document,
            $document->formType->name . ' approval was retracted by SACDEV and is now pending review again.'
        );

        Audit::log(
            'document.sacdev_approval_retracted',
            'SACDEV approval retracted for ' . $document->formType->name,
            [
                'actor_user_id' => auth()->id(),
                'organization_id' => $project->organization_id,
                'school_year_id' => $project->school_year_id,
                'meta' => [
                    'document_id' => $document->id,
                    'project_id' => $project->id,
                    'form_type' => $document->formType->code,
                ],
            ]
        );        

        return back()->with('success', 'SACDEV approval has been retracted.');

    }

    protected function resolveOrgDocumentRoute(ProjectDocument $document): string
    {
        $map = [
            'PROJECT_PROPOSAL' => 'org.projects.documents.combined-proposal.create',
            'BUDGET_PROPOSAL' => 'org.projects.documents.combined-proposal.create',

            'OFF_CAMPUS_APPLICATION' => 'org.projects.documents.off-campus.create',

            'SOLICITATION_APPLICATION' => 'org.projects.documents.solicitation.create',
            'SELLING_APPLICATION' => 'org.projects.documents.selling.create',

            'REQUEST_TO_PURCHASE' => 'org.projects.documents.request-to-purchase.create',

            'FEES_COLLECTION_REPORT' => 'org.projects.documents.fees-collection.create',
            'SELLING_ACTIVITY_REPORT' => 'org.projects.documents.selling-activity-report.create',
            'SOLICITATION_SPONSORSHIP_REPORT' => 'org.projects.documents.solicitation-sponsorship-report.create',
            'TICKET_SELLING_REPORT' => 'org.projects.documents.ticket-selling-report.create',

            'DOCUMENTATION_REPORT' => 'org.projects.documents.documentation-report.create',
            'LIQUIDATION_REPORT' => 'org.projects.documents.liquidation-report.create',
            
            
            'POSTPONEMENT_NOTICE' => 'org.projects.documents.postponement.create',
            'CANCELLATION_NOTICE' => 'org.projects.documents.cancellation.create',
        ];

        $routeName = $map[$document->formType->code] ?? 'org.projects.documents.hub';

        return route($routeName, $document->project);
    }


}