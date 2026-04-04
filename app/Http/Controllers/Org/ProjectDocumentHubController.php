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

        $project->loadMissing([
            'organization',
            'schoolYear',
        ]);

        $assignment = ProjectAssignment::with('user')
            ->where('project_id', $project->id)
            ->where('user_id', $user->id)
            ->where('assignment_role', 'project_head')
            ->whereNull('archived_at')
            ->first();

        $projectHeadAssignment = ProjectAssignment::with('user')
            ->where('project_id', $project->id)
            ->where('assignment_role', 'project_head')
            ->whereNull('archived_at')
            ->first();

        $projectHead = $projectHeadAssignment?->user;
        $isProjectHead = (bool) $assignment;
        $needsAgreement = $isProjectHead && !$assignment?->agreement_accepted_at;

        $allDocuments = ProjectDocument::with([
            'signatures',
            'formType',
            'proposalData',
            'budgetProposalData',
            'offCampusActivity.participants',
        ])
            ->where('project_id', $project->id)
            ->whereNull('archived_at')
            ->get();

        $documentsByType = $allDocuments->groupBy('form_type_id');

        $documentsByCode = $allDocuments->filter(fn ($doc) => $doc->formType)
            ->keyBy(fn ($doc) => $doc->formType->code);

        $formTypes = FormType::orderByRaw("
            CASE
                WHEN code = 'PROJECT_PROPOSAL' THEN 1
                WHEN code = 'BUDGET_PROPOSAL' THEN 2
                ELSE 3
            END
        ")
            ->orderBy('name')
            ->get()
            ->keyBy('code');

        $proposalType = $formTypes->get('PROJECT_PROPOSAL');
        $budgetType   = $formTypes->get('BUDGET_PROPOSAL');

        $proposalDoc = $proposalType
            ? ($documentsByType->get($proposalType->id)?->first())
            : null;

        $budgetDoc = $budgetType
            ? ($documentsByType->get($budgetType->id)?->first())
            : null;

        $proposalData = $proposalDoc?->proposalData;

        $requiredFormTypes = collect($resolver->resolve($project))->filter();

        $projectStatus = strtolower((string) ($project->status ?? ''));
        $workflowStatus = strtolower((string) ($project->workflow_status ?? ''));

        $isCancelled = in_array($projectStatus, ['cancelled', 'canceled'], true)
            || in_array($workflowStatus, ['cancelled', 'canceled'], true);

        $isCompleted = in_array($projectStatus, ['completed'], true)
            || in_array($workflowStatus, ['completed'], true);



        $snapshot = [
            'date' => $project->implementation_date_display,
            'time' => $project->implementation_time_display,
            'venue' => $project->implementation_venue,
            'description' => optional($proposalData)->description ?? $project->description,
            'status' => $proposalDoc?->status,
            'is_off_campus' => $project->implementation_venue_type === 'off_campus',
            'project_status' => $project->status,
            'workflow_status' => $project->workflow_status,
            'is_cancelled' => $isCancelled,
            'is_completed' => $isCompleted,
            'total_budget' => optional($proposalData)->total_budget ?? 0,
        ];

        $hasApprovedProposal = $proposalDoc && $proposalDoc->status === 'approved_by_sacdev';
        $hasApprovedBudget = $budgetDoc && $budgetDoc->status === 'approved_by_sacdev';
        $hasBothApproved = $hasApprovedProposal && $hasApprovedBudget;

        $postponementType = $formTypes->get('POSTPONEMENT_NOTICE');
        $cancellationType = $formTypes->get('CANCELLATION_NOTICE');

        $postponementDocs = $postponementType
            ? ($documentsByType->get($postponementType->id) ?? collect())
            : collect();

        $postponementDoc = $postponementDocs->sortByDesc('created_at')->first();

        $cancellationDocs = $cancellationType
            ? ($documentsByType->get($cancellationType->id) ?? collect())
            : collect();

        $cancellationDoc = $cancellationDocs->sortByDesc('created_at')->first();

        $isCancellationApproved = $cancellationDoc?->status === 'approved_by_sacdev';

        $isLocked = $isCancelled || $isCompleted || $isCancellationApproved;

        if (!$proposalDoc) {
            $proposalAction = [
                'label' => 'Create Proposal',
                'type' => 'create',
                'url' => !$isLocked
                    ? route('org.projects.documents.combined-proposal.create', $project)
                    : null,
            ];
        } elseif ($proposalDoc->isEditable() && $isProjectHead && !$isLocked) {
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
            'project_status' => $project->status,
            'workflow_status' => $project->workflow_status,
            'is_cancelled' => $isCancelled,
            'is_completed' => $isCompleted,
            'proposal_action' => $proposalAction,
        ];



        $actions = [
            'can_generate_dv' => !$isLocked && $budgetDoc !== null,
            'dv_url' => route('org.projects.documents.disbursement-voucher.create', $project),

            'postponement' => [
                'exists' => (bool) $postponementDoc,

                'is_approved' => $postponementDoc?->status === 'approved_by_sacdev',

                'can_create' => !$isLocked
                    && ($cancellationDoc?->status !== 'approved_by_sacdev')
                    && $hasApprovedProposal
                    && (
                        !$postponementDoc ||
                        $postponementDoc->status === 'approved_by_sacdev'
                    ),

                'create_url' => route('org.projects.documents.postponement.create', $project),

                'view_url' => $postponementDoc
                    ? route('org.projects.documents.postponement.edit', [
                        'project' => $project,
                        'document' => $postponementDoc->id,
                    ])
                    : null,
            ],

            'cancellation' => [
                'exists' => (bool) $cancellationDoc,

                'is_approved' => $cancellationDoc?->status === 'approved_by_sacdev',

                'can_create' => !$isLocked
                    && $hasApprovedProposal
                    && (
                        !$cancellationDoc ||
                        $cancellationDoc->status !== 'approved_by_sacdev'
                    ),

                'create_url' => route('org.projects.documents.cancellation.create', $project),

                'view_url' => $cancellationDoc
                    ? route('org.projects.documents.cancellation.edit', [
                        'project' => $project,
                        'document' => $cancellationDoc->id,
                    ])
                    : null,
            ],

            'can_packets' => !$isLocked && !$needsAgreement && $hasApprovedProposal,
            'packet_url' => route('org.projects.packets.index', $project),

            'travel_form' => [
                'can_create' => !$isLocked && $hasApprovedProposal,
                'create_url' => route('org.projects.documents.off-campus.travel-form.create', $project),
            ],

            'is_cancelled' => $isCancelled,
            'is_completed' => $isCompleted,
            'is_locked' => $isLocked,
        ];

        $formRoutes = [
            'PROJECT_PROPOSAL' => 'org.projects.documents.combined-proposal.create',
            'BUDGET_PROPOSAL' => 'org.projects.documents.combined-proposal.create',
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
            'POSTPONEMENT_NOTICE' => 'org.projects.documents.postponement.edit',
            'CANCELLATION_NOTICE' => 'org.projects.documents.cancellation.edit',
        ];

        $requiredCodes = $requiredFormTypes
            ->map(fn ($ft) => $ft->code)
            ->toArray();

        $buildForm = function ($formType) use (
            $documentsByType,
            $user,
            $project,
            $formRoutes,
            $isProjectHead,
            $needsAgreement,
            $isLocked,
            $requiredCodes 
        ) {
            $doc = $documentsByType->get($formType->id)?->first();
            $routeName = $formRoutes[$formType->code] ?? null;

            $pending = $doc?->currentPendingSignature();
            $nextRole = $doc?->nextPendingRole();
            $isMine = $pending && $pending->user_id === $user->id;

            $canCreate = !$isLocked && !$needsAgreement && !$doc && $routeName && $isProjectHead;
            $canEdit = !$isLocked && !$needsAgreement && $doc?->isEditable() && $isProjectHead;

   
            if ($formType->code === 'POSTPONEMENT_NOTICE') {

                if ($doc) {
                    $canCreate = $doc->status === 'approved_by_sacdev' && !$isLocked;
                    $canEdit = !$isLocked && $isProjectHead;
                } else {
                    $canCreate = !$isLocked && $isProjectHead;
                    $canEdit = false;
                }
            }

            if ($formType->code === 'CANCELLATION_NOTICE') {

                if ($doc) {
                    $canCreate = false;
                    $canEdit = !$isLocked && $isProjectHead;
                } else {
                    $canCreate = !$isLocked && $isProjectHead;
                    $canEdit = false;
                }
            }

            return [
                'name' => $formType->name,
                'code' => $formType->code,
                'phase' => $formType->phase,
                'is_required' => in_array($formType->code, $requiredCodes),
                'document' => $doc,
                'status_label' => $doc?->status_label ?? 'Not started',
                'status_class' => $doc?->status_badge_class ?? 'bg-slate-100 text-slate-600',
                'waiting_for' => $nextRole,
                'is_waiting_for_me' => (bool) $isMine,
                'is_waiting_for_others' => (bool) ($doc && !$isMine && $nextRole),
                'can_create' => $canCreate,
                'can_edit' => $canEdit,
                'can_review' => (bool) $isMine,
                'create_url' => $canCreate
                    ? (
                        in_array($formType->code, ['POSTPONEMENT_NOTICE', 'CANCELLATION_NOTICE'])
                            ? route(
                                str_replace('.edit', '.create', $routeName),
                                $project->id
                            )
                            : route($routeName, $project->id)
                    )
                    : null,
                'edit_url' => ($doc && $routeName)
                    ? (
                        in_array($formType->code, ['POSTPONEMENT_NOTICE', 'CANCELLATION_NOTICE'])
                            ? route($routeName, [
                                'project' => $project->id,
                                'document' => $doc->id
                            ])
                            : route($routeName, $project->id)
                    )
                    : null,

                'view_url' => ($doc && $routeName)
                    ? (
                        in_array($formType->code, ['POSTPONEMENT_NOTICE', 'CANCELLATION_NOTICE'])
                            ? route($routeName, [
                                'project' => $project->id,
                                'document' => $doc->id
                            ])
                            : route($routeName, $project->id)
                    )
                    : null,
                'is_locked' => $isLocked,
            ];
        };

        $requiredForms = $requiredFormTypes
            ->map($buildForm)
            ->filter()
            ->values();

        $alwaysAvailableCodes = [
            'POSTPONEMENT_NOTICE',
            'CANCELLATION_NOTICE',
            'REQUEST_TO_PURCHASE',
            'SELLING_APPLICATION','SELLING_ACTIVITY_REPORT',
        ];

        $alwaysAvailableForms = collect($alwaysAvailableCodes)
            ->map(function ($code) use ($formTypes, $documentsByCode, $buildForm) {

                $formType = $formTypes->get($code);

                if (!$formType) return null;

                $doc = $documentsByCode->get($code);

        
                if (in_array($code, ['POSTPONEMENT_NOTICE', 'CANCELLATION_NOTICE'])) {
                    if (!$doc) return null;
                }

                return $buildForm($formType);
            })
            ->filter()
            ->values();

        $workflowForms = collect();

        $sellingApproved = optional($documentsByCode->get('SELLING_APPLICATION'))->status === 'approved_by_sacdev';



        $combinedPreForm = null;

        if ($proposalType) {

            $combinedDoc = $proposalDoc ?? $budgetDoc;

            // determine status
            $status = $combinedDoc?->status;

            if ($proposalDoc && $budgetDoc) {
                if (
                    $proposalDoc->status === 'approved_by_sacdev' &&
                    $budgetDoc->status === 'approved_by_sacdev'
                ) {
                    $status = 'approved_by_sacdev';
                } elseif (
                    in_array('returned', [$proposalDoc->status, $budgetDoc->status])
                ) {
                    $status = 'returned';
                } elseif (
                    in_array('submitted', [$proposalDoc->status, $budgetDoc->status])
                ) {
                    $status = 'submitted';
                } else {
                    $status = 'draft';
                }
            }

            $combinedPreForm = [
                'name' => 'Project Proposal',
                'code' => 'PROJECT_PROPOSAL',
                'phase' => 'pre_implementation',

                'is_required' => true,

                'document' => $combinedDoc,

                'status_label' => $combinedDoc?->status_label ?? 'Not started',
                'status_class' => $combinedDoc?->status_badge_class ?? 'bg-slate-100 text-slate-600',

                'status_raw' => $status,

                'waiting_for' => $combinedDoc?->nextPendingRole(),

                'is_waiting_for_me' => (bool) optional($combinedDoc?->currentPendingSignature())->user_id === $user->id,
                'is_waiting_for_others' => (bool) ($combinedDoc && $combinedDoc->nextPendingRole()),

                'can_create' => !$isLocked && !$needsAgreement && !$proposalDoc && $isProjectHead,
                'can_edit' => !$isLocked && !$needsAgreement && $proposalDoc?->isEditable() && $isProjectHead,
                'can_review' => (bool) optional($combinedDoc?->currentPendingSignature())->user_id === $user->id,

                'create_url' => route('org.projects.documents.combined-proposal.create', $project),
                'edit_url' => route('org.projects.documents.combined-proposal.create', $project),
                'view_url' => route('org.projects.documents.combined-proposal.create', $project),

                'is_locked' => $isLocked,
            ];
        }

        $sections = [
            'pre' => $combinedPreForm ? collect([$combinedPreForm]) : collect(),
            'required' => $requiredForms,
            'optional' => $alwaysAvailableForms,
            'workflow' => $workflowForms->values(),
        ];
        $allFormsFlat = collect($sections)
            ->flatMap(fn ($group) => $group)
            ->filter(function ($form) {

                if (in_array($form['code'], ['PROJECT_PROPOSAL', 'BUDGET_PROPOSAL'])) {
                    return false;
                }

                if (in_array($form['code'], ['POSTPONEMENT_NOTICE', 'CANCELLATION_NOTICE'])) {
                    return !empty($form['document']);
                }

                return true;
            })
            ->values();
                        
        $documentsMapped = $allFormsFlat->map(function ($form) use ($user, $isProjectHead) {

            $doc = $form['document'] ?? null;
            $status = $doc?->status;

            $isActionRequired = false;

            // ================= PROJECT HEAD =================
            if ($isProjectHead) {
                if (in_array($status, ['draft', 'returned'])) {
                    $isActionRequired = true;
                }
            }

            // ================= APPROVER =================
            if (!empty($form['is_waiting_for_me'])) {
                $isActionRequired = true;
            }

            return array_merge($form, [
                'is_action_required' => $isActionRequired,
                'status_raw' => $status,
            ]);
        });

        $documentsActionRequired = $documentsMapped->filter(fn ($f) => $f['is_action_required']);

        $documentsCompleted = $documentsMapped->filter(function ($f) {
            return isset($f['document']) &&
                $f['document'] &&
                $f['document']->status === 'approved_by_sacdev';
        });

        $documentsInProgress = $documentsMapped->filter(function ($f) {
            return !$f['is_action_required']
                && (!isset($f['document']) || $f['document']->status !== 'approved_by_sacdev');
        });


        $phaseOrder = [
            'notice' => 1,
            'off-campus' => 2,
            'other' => 3,
            'post_implementation' => 4,
            'pre_implementation' => 5,
        ];


        $sortByPhase = function ($collection) use ($phaseOrder) {
            return $collection->sortBy(function ($f) use ($phaseOrder) {
                return $phaseOrder[$f['phase']] ?? 99;
            })->values();
        };

        $documentsActionRequired = $sortByPhase($documentsActionRequired);
        $documentsInProgress = $sortByPhase($documentsInProgress);
        $documentsCompleted = $sortByPhase($documentsCompleted);

        $requiredCollection = collect($sections['required'] ?? []);

        $requiredCount = $requiredCollection->count();

        $completedRequired = $requiredCollection->filter(function ($form) {
            return isset($form['document']) &&
                $form['document'] &&
                $form['document']->status === 'approved_by_sacdev';
        })->count();

        $progressPercent = $requiredCount > 0
            ? ($completedRequired / $requiredCount) * 100
            : 0;



        $tips = [];

        // ================= PROPOSAL =================
        if (!$proposalDoc) {
            $tips[] = [
                'text' => '📄 Start your project by creating a proposal.',
                'url' => route('org.projects.documents.combined-proposal.create', $project),
            ];
        }

        if ($proposalDoc && $proposalDoc->status === 'returned') {
            $tips[] = [
                'text' => '⚠ Proposal returned. Review remarks and resubmit.',
                'url' => route('org.projects.documents.combined-proposal.create', $project),
            ];
        }

        if ($proposalDoc) {
            $tips[] = [
                'text' => '👁 Want to review your proposal? Open it in the Action Center.',
                'url' => route('org.projects.documents.combined-proposal.create', $project),
            ];
        }

        // ================= AGREEMENT =================
        if ($needsAgreement) {
            $tips[] = [
                'text' => '📝 Complete the Student Agreement to unlock submissions.',
                'url' => null,
            ];
        } else {
            $tips[] = [
                'text' => '📜 You can review your agreement anytime in the Action Center.',
                'url' => null,
            ];
        }

        // ================= CLEARANCE =================
        if ($project->requires_clearance) {

            if (empty($project->clearance_file_path)) {
                $tips[] = [
                    'text' => '⚠ Off-campus activity detected. Generate and upload clearance.',
                    'url' => route('org.projects.clearance.print', $project),
                ];
            } elseif ($project->clearance_status === 'pending') {
                $tips[] = [
                    'text' => '⏳ Clearance uploaded. Awaiting approval.',
                    'url' => null,
                ];
            } elseif ($project->clearance_status === 'returned') {
                $tips[] = [
                    'text' => '❌ Clearance returned. Check remarks and re-upload.',
                    'url' => route('org.projects.clearance.upload', $project),
                ];
            }
        }

        // ================= PACKETS =================
        if ($actions['can_packets']) {
            $tips[] = [
                'text' => '📦 Submit physical documents via Packet Submissions.',
                'url' => route('org.projects.packets.index', $project),
            ];
        }

        // ================= TRAVEL =================
        if ($project->requires_clearance) {
            $tips[] = [
                'text' => '✈ Generate Travel Consent Form for off-campus participants.',
                'url' => route('org.projects.documents.off-campus.travel-form.create', $project),
            ];
        }

        // ================= PROGRESS =================
        if ($progressPercent == 100) {
            $tips[] = [
                'text' => '✅ All required documents are completed.',
                'url' => null,
            ];
        }

        // ================= FALLBACK =================
        if (empty($tips)) {
            $tips[] = [
                'text' => '➡ Continue completing your project requirements.',
                'url' => null,
            ];
        }







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

        $participants = $allDocuments
            ->first(fn ($doc) => optional($doc->formType)->code === 'OFF_CAMPUS_APPLICATION')
            ?->offCampusActivity
            ?->participants ?? collect();

        $clearance = [
            'required' => $project->requires_clearance,

            'reference' => $project->clearance_reference,
            'status' => $project->clearance_status,
            'issued_at' => $project->clearance_issued_at,
            'snapshot' => $project->clearance_snapshot,

            'participants_count' => $participants->count(),

            'is_project_head' => $isProjectHead,

            'is_outdated' => app(\App\Http\Controllers\Org\ClearanceController::class)
                ->isSnapshotOutdated($project),

            'print_url' => route('org.projects.clearance.print', $project),
            'upload_url' => route('org.projects.clearance.upload', $project),
            'reissue_url' => route('org.projects.clearance.reissue', $project),

            'is_cancelled' => $isCancelled,
            'is_completed' => $isCompleted,
            'is_locked' => $isLocked,

      
            'has_file' => !empty($project->clearance_file_path),
            'remarks' => $project->clearance_remarks,
            'can_upload' => $isProjectHead && !$isLocked && !$isCompleted,
        ];

        $today = Carbon::today();
        $currentStage = 'submitted';

        $startDate = $project->implementation_start_date;
        $endDate = $project->implementation_end_date;

        if ($isCancelled) {
            $currentStage = 'cancelled';
        } elseif ($isCompleted) {
            $currentStage = 'completed';
        } elseif ($hasBothApproved) {
            if ($startDate && $endDate) {
                if ($today->between($startDate, $endDate)) {
                    $currentStage = 'implementation';
                } elseif ($today->gt($endDate)) {
                    $currentStage = 'post';
                } else {
                    $currentStage = 'pre';
                }
            } else {
                $currentStage = 'pre';
            }
        }

        $postFormsApproved = collect($sections['required'] ?? [])
            ->filter(fn ($f) => in_array($f['code'], [
                'DOCUMENTATION_REPORT',
                'LIQUIDATION_REPORT',
            ], true))
            ->every(fn ($f) =>
                $f['document'] &&
                $f['document']->status === 'approved_by_sacdev'
            );

        if ($currentStage === 'post' && $postFormsApproved) {
            $currentStage = 'finance';
        }
        $noticeForms = $documentsMapped->filter(function ($f) {
            return in_array($f['phase'], ['notice']);
        });

        return view('org.projects.documents.hub', compact(
            'project',
            'header',
            'snapshot',
            'actions',
            'sections',
            'clearance',
            'needsAgreement',
            'proposalDoc',
            'budgetDoc',
            'pendingCount',
            'sectionCounts',
            'isProjectHead',
            'requiredCount',
            'completedRequired',
            'progressPercent',
            'tips',
            
            'documentsActionRequired',
            'documentsInProgress',
            'documentsCompleted','noticeForms','combinedPreForm',
        ));
    }
}