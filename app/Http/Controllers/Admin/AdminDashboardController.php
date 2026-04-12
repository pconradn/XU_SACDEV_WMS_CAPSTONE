<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ModeratorSubmission;
use App\Models\OfficerSubmission;
use App\Models\Organization;
use App\Models\PresidentRegistration;
use App\Models\Project;
use App\Models\ProjectDocument;
use App\Models\SchoolYear;
use App\Models\StrategicPlanSubmission;
use App\Services\ProjectFormRequirementResolver;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $user->load('clusters');
        
        $activeSy = SchoolYear::activeYear();
        $activeSyId = $activeSy?->id;

        $nextSy = SchoolYear::where('id', '>', $activeSyId)
            ->orderBy('id')
            ->first();

        $targetSyIds = collect([$activeSyId]);

        if ($nextSy) {
            $targetSyIds->push($nextSy->id);
        }

        $actionable = ['submitted_to_sacdev', 'forwarded_to_sacdev'];
        $approved = 'approved_by_sacdev';

        

        $pendingCases = collect()
            ->merge(
                StrategicPlanSubmission::query()
                    ->with('organization')
                    ->whereIn('target_school_year_id', $targetSyIds)
                    ->whereIn('status', $actionable)
                    ->whereHas('organization', function ($q) use ($user) {
                        $q->whereIn('cluster_id', $user->clusters->pluck('id'));
                    })
                    ->get()
                    ->map(function ($r) {
                        return (object) [
                            'type' => 'Strategic Plan',
                            'organization_id' => $r->organization_id,
                            'school_year_id' => $r->target_school_year_id,
                            'organization' => $r->organization,
                            'school_year' => SchoolYear::find($r->target_school_year_id),
                            'status' => $r->status,
                            'created_at' => $r->created_at,
                            'route' => route('admin.strategic_plans.show', $r->id),
                        ];
                    })
            )
            ->merge(
                OfficerSubmission::query()
                    ->with('organization')
                    ->whereIn('target_school_year_id', $targetSyIds)
                    ->whereIn('status', $actionable)
                    ->whereHas('organization', function ($q) use ($user) {
                        $q->whereIn('cluster_id', $user->clusters->pluck('id'));
                    })
                    ->get()
                    ->map(function ($r) {
                        return (object) [
                            'type' => 'Officer Submission',
                            'organization_id' => $r->organization_id,
                            'school_year_id' => $r->target_school_year_id,
                            'organization' => $r->organization,
                            'school_year' => SchoolYear::find($r->target_school_year_id),
                            'status' => $r->status,
                            'created_at' => $r->created_at,
                            'route' => route('admin.officer_submissions.show', $r->id),
                        ];
                    })
            )
            ->merge(
                OfficerSubmission::query()
                    ->with('organization')
                    ->whereIn('target_school_year_id', $targetSyIds)
                    ->where('edit_requested', true)
                    ->whereHas('organization', function ($q) use ($user) {
                        $q->whereIn('cluster_id', $user->clusters->pluck('id'));
                    })
                    ->get()
                    ->map(function ($r) {
                        return (object) [
                            'type' => 'Officer Submission (Edit Request)',
                            'organization_id' => $r->organization_id,
                            'school_year_id' => $r->target_school_year_id,
                            'organization' => $r->organization,
                            'school_year' => \App\Models\SchoolYear::find($r->target_school_year_id),

                            'status' => 'edit_requested',

                            'created_at' => $r->edit_requested_at ?? $r->updated_at,

                            'route' => route('admin.officer_submissions.show', $r->id),

                  
                            'edit_request_reason' => $r->edit_request_reason,
                        ];
                    })
            )
            ->sortByDesc('created_at')
            ->values();

            $readyKeys = Organization::query()
                ->whereIn('cluster_id', $user->clusters->pluck('id'))
                ->get()
                ->filter(function ($org) use ($targetSyIds) {

                    foreach ($targetSyIds as $syId) {

                        $b1 = StrategicPlanSubmission::where('organization_id', $org->id)
                            ->where('target_school_year_id', $syId)
                            ->latest()->first();

                        $b3 = OfficerSubmission::where('organization_id', $org->id)
                            ->where('target_school_year_id', $syId)
                            ->latest()->first();

                        $b5 = ModeratorSubmission::where('organization_id', $org->id)
                            ->where('target_school_year_id', $syId)
                            ->latest()->first();

                        $b6 = \App\Models\OrgConstitutionSubmission::where('organization_id', $org->id)
                            ->where('school_year_id', $syId)
                            ->latest()->first();

                        $presidentUser = \App\Models\OrgMembership::where('organization_id', $org->id)
                            ->where('school_year_id', $syId)
                            ->where('role', 'president')
                            ->whereNull('archived_at')
                            ->exists();

                        $allApproved =
                            in_array($b1?->status, ['approved_by_sacdev','approved'], true)
                            && in_array($b3?->status, ['approved_by_sacdev','approved'], true)
                            && $b5 !== null
                            && $presidentUser
                            && $b6 !== null;

                        if ($allApproved) {
                            return true;
                        }
                    }

                    return false;
                })
                ->map(function ($org) use ($activeSyId) {
                    return (int) $org->id . '|' . (int) $activeSyId;
                })
                ->values();

        $activatedKeys = collect(array_values(array_unique(
            DB::table('organization_school_years')
                ->get()
                ->map(fn ($r) => (int)$r->organization_id . '|' . (int)$r->school_year_id)
                ->toArray()
        )));

            $readyForActivation = $readyKeys
                ->diff($activatedKeys)
                ->map(function ($key) use ($user) {
                
                [$orgId, $syId] = explode('|', $key);

                return (object) [
                    'organization_id' => (int) $orgId,
                    'school_year_id' => (int) $syId,
                    'organization' => Organization::where('id', $orgId)
                        ->whereIn('cluster_id', $user->clusters->pluck('id'))
                        ->first(),
                    'school_year' => SchoolYear::find($syId),
                    'route' => route('admin.rereg.hub', $orgId),
                ];
            })
            ->values()
            ->filter(fn ($r) => $r->organization !== null);




            $projectApprovals = ProjectDocument::with([
                    'project.organization',
                    'formType',
                    'signatures',
                ])
                ->whereHas('project', function ($q) use ($activeSyId, $user) {
                    if ($activeSyId) {
                        $q->where('school_year_id', $activeSyId);
                    }

                    $q->where('workflow_status', '!=', 'cancelled');

                    $q->whereHas('organization', function ($org) use ($user) {
                        if ($user->clusters->isNotEmpty()) {
                            $org->whereIn('cluster_id', $user->clusters->pluck('id'));
                        }
                    });
                })
                ->get()
                //->tap(fn($docs) => dd($docs->count()))
                ->filter(function ($doc) {
                    $userId = auth()->id();

                if ($doc->edit_requested) {
                    return $doc->project && $doc->project->organization;
                }

                    $pending = $doc->currentPendingSignature();
             
                    return $pending && $pending->user_id === $userId;
                })
                ->map(function ($doc) {
                    return (object) [
                        'project' => $doc->project,
                        'organization' => $doc->project->organization ?? null,

                        'forms' => collect([
                            [
                                'name' => $doc->edit_requested
                                    ? ($doc->formType->name ?? 'Form') . ' (Edit Requested)'
                                    : ($doc->formType->name ?? 'Form'),
                                'code' => $doc->formType->code ?? null,
                                'phase' => $doc->formType->phase ?? 'default',
                            ]
                        ]),
                        'edit_request_remarks' => $doc->edit_requested ? $doc->edit_request_remarks : null,
                        'status' => $doc->edit_requested ? 'edit_requested' : $doc->status,
                        'route' => route('admin.projects.documents.hub', $doc->project_id),
                        'form_route' => in_array($doc->formType->code, ['PROJECT_PROPOSAL', 'BUDGET_PROPOSAL'])
                            ? route('admin.projects.documents.combined-proposal.open', $doc->project_id)
                            : route('admin.projects.documents.open', [
                                $doc->project_id,
                                $doc->formType->code
                            ]),

                        'count' => 1, // required
                        'is_completion' => false, // required
                    ];
                })
                ->values();      
            
            
            $debug = [];

            $resolver = app(ProjectFormRequirementResolver::class);

        $projectsReadyForCompletion = Project::with([
                'documents.formType'
            ])
            ->where('school_year_id', $activeSyId)
            ->where('workflow_status', '!=', 'completed')
            ->where('workflow_status', '!=', 'cancelled')
            ->whereHas('organization', function ($q) use ($user) {
                $q->whereIn('cluster_id', $user->clusters->pluck('id'));
            })
            ->get()
            ->filter(function ($project) use ($resolver, &$debug) {

                $requiredFormTypes = $resolver->resolve($project);

                $documents = $project->documents
                    ->whereNull('archived_at')
                    ->keyBy(fn($d) => $d->formType->code);

                $requiredDocs = collect($requiredFormTypes)->map(function (\App\Models\FormType $formType) use ($documents) {
                    return $documents[$formType->code] ?? null;
                });

                if ($requiredDocs->isEmpty()) {
                    return false;
                }

                return $requiredDocs
                    ->every(fn($doc) => $doc && $doc->status === 'approved_by_sacdev');
            })
            ->map(function ($project) {
                return (object)[
                    'project' => $project,
                    'organization' => $project->organization ?? null,

                    'forms' => collect([
                        [
                            'name' => 'Ready for Completion',
                            'code' => 'READY_FOR_COMPLETION',
                            'phase' => 'completion',
                        ]
                    ]),

                    'route' => route('admin.projects.documents.hub', $project->id),

                    'form_route' => route('admin.projects.documents.hub', $project->id),

                    'count' => 1,
                    'is_completion' => true,
                ];
            })
            ->values();


            $projectsForClearanceReview = Project::with('organization')
                    ->where('school_year_id', $activeSyId)
                    ->where('clearance_status', 'uploaded')
                    ->whereHas('organization', function ($q) use ($user) {
                        $q->whereIn('cluster_id', $user->clusters->pluck('id'));
                    })
                    ->get()
                    ->map(function ($project) {
                        return (object)[
                            'project' => $project,
                            'organization' => $project->organization ?? null,

                            'forms' => collect([
                                [
                                    'name' => 'Clearance Review Required',
                                    'code' => 'CLEARANCE_REVIEW',
                                    'phase' => 'off_campus',
                                ]
                            ]),

                            'route' => route('admin.projects.documents.hub', $project->id),

                            'form_route' => route('admin.projects.documents.hub', $project->id),

                            'count' => 1,
                            'is_completion' => false,
                        ];
                    })
                    ->values();     

            $projectApprovals = collect($projectApprovals)
                ->merge(collect($projectsReadyForCompletion))
                ->merge(collect($projectsForClearanceReview))
                ->sortByDesc(fn ($item) => $item->project->updated_at ?? now())
                ->values();


            $calendarProjects = Project::query()
                ->with('organization')
                ->when($activeSyId, fn ($q) => $q->where('school_year_id', $activeSyId))
                ->whereNotNull('implementation_start_date')
                ->orderBy('implementation_start_date')
                ->get()
                ->map(function ($project) {
                    return [
                        'title' => $project->title,
                        'start' => $project->implementation_start_date,
                        'url' => route('admin.projects.documents.hub', $project->id),
                    ];
                })
                ->values();


            $preImplementationCompleteCount = Project::query()
                ->where('school_year_id', $activeSyId)
                ->whereHas('documents', function ($q) {
                    $q->whereHas('formType', fn ($f) =>
                        $f->whereIn('code', ['PROJECT_PROPOSAL', 'BUDGET_PROPOSAL'])
                    )->where('status', 'approved_by_sacdev');
                }, '=', 2)
                ->count();


            $upcomingProjectsCount = Project::query()
                ->where('school_year_id', $activeSyId)
                ->whereNotNull('implementation_start_date')
                ->whereBetween('implementation_start_date', [now(), now()->addDays(30)])
                ->whereDoesntHave('documents', function ($q) {
                    $q->whereHas('formType', fn ($f) =>
                        $f->whereIn('code', ['PROJECT_PROPOSAL', 'BUDGET_PROPOSAL'])
                    )->where('status', 'approved_by_sacdev');
                })
                ->count();


            $offCampusProjectsCount = Project::query()
                ->where('school_year_id', $activeSyId)
                ->whereHas('documents', function ($q) {
                    $q->whereHas('formType', fn ($f) =>
                        $f->where('code', 'OFF_CAMPUS_APPLICATION')
                    );
                })
                ->count();


            $activatedOrgCount = DB::table('organization_school_years')
                ->where('school_year_id', $activeSyId)
                ->count();


            $completedProjectsCount = Project::query()
                ->where('school_year_id', $activeSyId)
                ->where('status', 'completed') // future-ready
                ->count();

            $pendingCases = $pendingCases

                ->sortByDesc('created_at')
                ->values();


            return view('admin.dashboard', [
                'activeSy' => $activeSy,
                'orgCount' => Organization::count(),
                'syCount' => SchoolYear::count(),
                'pendingCaseCount' => $pendingCases->count(),
                'readyForActivationCount' => $readyForActivation->count(),
                'pendingCases' => $pendingCases,

                'projectsReadyForCompletion' => $projectsReadyForCompletion,
                'projectsReadyForCompletionCount' => $projectsReadyForCompletion->count(),

                'readyForActivation' => $readyForActivation,
                'projectApprovals' => $projectApprovals,
                'calendarProjects' => $calendarProjects,

                'preImplementationCompleteCount' => $preImplementationCompleteCount,
                'upcomingProjectsCount' => $upcomingProjectsCount,
                'offCampusProjectsCount' => $offCampusProjectsCount,
                'activatedOrgCount' => $activatedOrgCount,
                'completedProjectsCount' => $completedProjectsCount,




        ]);










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


    protected function resolveAdminDocumentRoute(ProjectDocument $document): string
    {
        return route('admin.projects.documents.hub', [
            'project' => $document->project_id,
            'focus' => $document->id,
        ]);
    }
 





}