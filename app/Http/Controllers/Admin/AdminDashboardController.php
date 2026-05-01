<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ModeratorSubmission;
use App\Models\OfficerSubmission;
use App\Models\Organization;
use App\Models\OrgConstitutionSubmission;
use App\Models\OrgMembership;
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
        $scope = $this->dashboardScope();

        $activeSy = $scope['activeSy'];
        $activeSyId = $scope['activeSyId'];
        $isCoa = $scope['isCoa'];

        $projectApprovals = $this->buildProjectWorkflowQueue($scope);

        if ($isCoa) {
            return view('admin.dashboard', [
                'activeSy' => $activeSy,

                'orgCount' => 0,
                'syCount' => SchoolYear::count(),

                'pendingCases' => collect(),
                'pendingCaseCount' => 0,

                'readyForActivation' => collect(),
                'readyForActivationCount' => 0,

                'projectApprovals' => $projectApprovals,
                'projectApprovalsCount' => $projectApprovals->count(),

                'projectsReadyForCompletion' => collect(),
                'projectsReadyForCompletionCount' => 0,

                'calendarProjects' => collect(),

                'preImplementationCompleteCount' => 0,
                'upcomingProjectsCount' => 0,
                'offCampusProjectsCount' => 0,
                'activatedOrgCount' => 0,
                'completedProjectsCount' => 0,
                'projectCount' => 0,
            ]);
        }

        $pendingCases = $this->buildPendingCases($scope);
        $readyForActivation = $this->buildReadyForActivation($scope);
        $projectsReadyForCompletion = $this->buildProjectsReadyForCompletion($scope);
        $calendarProjects = $this->buildCalendarProjects($scope);

        $counts = $this->buildDashboardCounts($scope);

        return view('admin.dashboard', [
            'activeSy' => $activeSy,

            'orgCount' => $counts['orgCount'],
            'syCount' => $counts['syCount'],

            'pendingCases' => $pendingCases,
            'pendingCaseCount' => $pendingCases->count(),

            'readyForActivation' => $readyForActivation,
            'readyForActivationCount' => $readyForActivation->count(),

            'projectApprovals' => $projectApprovals,
            'projectApprovalsCount' => $projectApprovals->count(),

            'projectsReadyForCompletion' => $projectsReadyForCompletion,
            'projectsReadyForCompletionCount' => $projectsReadyForCompletion->count(),

            'calendarProjects' => $calendarProjects,

            'preImplementationCompleteCount' => $counts['preImplementationCompleteCount'],
            'upcomingProjectsCount' => $counts['upcomingProjectsCount'],
            'offCampusProjectsCount' => $counts['offCampusProjectsCount'],
            'activatedOrgCount' => $counts['activatedOrgCount'],
            'completedProjectsCount' => $counts['completedProjectsCount'],
            'projectCount' => $counts['projectCount'],
        ]);
    }

    

    public function projectApprovalsPartial()
    {
        $scope = $this->dashboardScope();

        $projectApprovals = $this->buildProjectWorkflowQueue($scope);

        return view('admin.dashboard._project-approvals', compact('projectApprovals'));
    }

    private function dashboardScope(): array
    {
        $user = auth()->user();

        $user->load('clusters');

        $activeSy = SchoolYear::activeYear();
        $activeSyId = $activeSy?->id;

        $nextSy = $activeSyId
            ? SchoolYear::where('id', '>', $activeSyId)->orderBy('id')->first()
            : null;

        $targetSyIds = collect([$activeSyId, $nextSy?->id])
            ->filter()
            ->unique()
            ->values();

        return [
            'user' => $user,
            'isCoa' => (bool) $user->is_coa_officer,
            'clusterIds' => $user->clusters->pluck('id'),
            'activeSy' => $activeSy,
            'activeSyId' => $activeSyId,
            'targetSyIds' => $targetSyIds,
        ];
    }

    private function applyOrganizationScope($query, array $scope)
    {
        if ($scope['isCoa']) {
            return $query;
        }

        $clusterIds = $scope['clusterIds'];
        $user = $scope['user'];

        if ($clusterIds->isNotEmpty()) {
            return $query->whereIn('cluster_id', $clusterIds);
        }

        if ($user->cluster_id) {
            return $query->where('cluster_id', $user->cluster_id);
        }

        return $query->whereRaw('1 = 0');
    }

    private function applyProjectOrganizationScope($query, array $scope)
    {
        return $query->whereHas('organization', function ($org) use ($scope) {
            $this->applyOrganizationScope($org, $scope);
        });
    }

    private function buildPendingCases(array $scope)
    {
        $targetSyIds = $scope['targetSyIds'];
        $actionable = ['submitted_to_sacdev', 'forwarded_to_sacdev'];

        $strategicPlans = StrategicPlanSubmission::query()
            ->with('organization')
            ->whereIn('target_school_year_id', $targetSyIds)
            ->whereIn('status', $actionable)
            ->whereHas('organization', function ($q) use ($scope) {
                $this->applyOrganizationScope($q, $scope);
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
            });

        $officerSubmissions = OfficerSubmission::query()
            ->with('organization')
            ->whereIn('target_school_year_id', $targetSyIds)
            ->whereIn('status', $actionable)
            ->whereHas('organization', function ($q) use ($scope) {
                $this->applyOrganizationScope($q, $scope);
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
            });

        $officerEditRequests = OfficerSubmission::query()
            ->with('organization')
            ->whereIn('target_school_year_id', $targetSyIds)
            ->where('edit_requested', true)
            ->whereHas('organization', function ($q) use ($scope) {
                $this->applyOrganizationScope($q, $scope);
            })
            ->get()
            ->map(function ($r) {
                return (object) [
                    'type' => 'Officer Submission (Edit Request)',
                    'organization_id' => $r->organization_id,
                    'school_year_id' => $r->target_school_year_id,
                    'organization' => $r->organization,
                    'school_year' => SchoolYear::find($r->target_school_year_id),
                    'status' => 'edit_requested',
                    'created_at' => $r->edit_requested_at ?? $r->updated_at,
                    'route' => route('admin.officer_submissions.show', $r->id),
                    'edit_request_reason' => $r->edit_request_reason,
                ];
            });

        return collect()
            ->merge($strategicPlans)
            ->merge($officerSubmissions)
            ->merge($officerEditRequests)
            ->sortByDesc('created_at')
            ->values();
    }

    private function buildReadyForActivation(array $scope)
    {
        $syId = $scope['activeSyId'];

        if (!$syId) {
            return collect();
        }

        $activatedKeys = DB::table('organization_school_years')
            ->get()
            ->map(fn ($r) => (int) $r->organization_id . '|' . (int) $r->school_year_id)
            ->unique()
            ->values();

        $readyItems = collect();

        $organizations = Organization::query()
            ->tap(fn ($q) => $this->applyOrganizationScope($q, $scope))
            ->get();

        foreach ($organizations as $org) {
            $b1 = StrategicPlanSubmission::where('organization_id', $org->id)
                ->where('target_school_year_id', $syId)
                ->latest()
                ->first();

            $b3 = OfficerSubmission::where('organization_id', $org->id)
                ->where('target_school_year_id', $syId)
                ->latest()
                ->first();

            $b5 = ModeratorSubmission::where('organization_id', $org->id)
                ->where('target_school_year_id', $syId)
                ->latest()
                ->first();

            $b6 = OrgConstitutionSubmission::where('organization_id', $org->id)
                ->where('school_year_id', $syId)
                ->latest()
                ->first();

            $hasPresident = OrgMembership::where('organization_id', $org->id)
                ->where('school_year_id', $syId)
                ->where('role', 'president')
                ->whereNull('archived_at')
                ->exists();

            $allApproved =
                in_array($b1?->status, ['approved_by_sacdev', 'approved'], true)
                && in_array($b3?->status, ['approved_by_sacdev', 'approved'], true)
                && $b5 !== null
                && $b6 !== null
                && $hasPresident;

            $key = (int) $org->id . '|' . (int) $syId;

            if ($allApproved && !$activatedKeys->contains($key)) {
                $readyItems->push((object) [
                    'organization_id' => (int) $org->id,
                    'school_year_id' => (int) $syId,
                    'organization' => $org,
                    'school_year' => SchoolYear::find($syId),
                    'route' => route('admin.rereg.hub', $org->id),
                ]);
            }
        }

        return $readyItems->values();
    }

    private function buildProjectWorkflowQueue(array $scope)
    {
        $documentApprovals = $this->buildProjectDocumentApprovals($scope);

        if ($scope['isCoa']) {
            return $documentApprovals
                ->sortByDesc(fn ($item) => $item->project->updated_at ?? now())
                ->values();
        }

        return collect()
            ->merge($documentApprovals)
            ->merge($this->buildProjectsReadyForCompletion($scope))
            ->merge($this->buildProjectsForClearanceReview($scope))
            ->sortByDesc(fn ($item) => $item->project->updated_at ?? now())
            ->values();
    }

    private function buildProjectDocumentApprovals(array $scope)
    {
        $activeSyId = $scope['activeSyId'];
        $user = $scope['user'];

        return ProjectDocument::with([
                'project.organization',
                'formType',
                'signatures',
            ])
            ->whereNull('archived_at')
            ->whereDoesntHave('formType', function ($q) {
                $q->where('code', 'BUDGET_PROPOSAL');
            })
            ->whereHas('project', function ($q) use ($activeSyId, $scope) {
                if ($activeSyId) {
                    $q->where('school_year_id', $activeSyId);
                }

                $q->where('workflow_status', '!=', 'cancelled');

                $this->applyProjectOrganizationScope($q, $scope);
            })
            ->get()
            ->filter(function ($doc) use ($user, $scope) {
                if (!$doc->project || !$doc->project->organization) {
                    return false;
                }

                $pending = $doc->currentPendingSignature();

                if ($pending && (int) $pending->user_id === (int) $user->id) {
                    return true;
                }

            if ($doc->edit_requested) {
                return true;
            }

                return false;
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
                        ],
                    ]),

                    'edit_request_remarks' => $doc->edit_requested ? $doc->edit_request_remarks : null,
                    'status' => $doc->edit_requested ? 'edit_requested' : $doc->status,

                    'route' => route('admin.projects.documents.hub', $doc->project_id),

                    'form_route' => in_array($doc->formType?->code, ['PROJECT_PROPOSAL', 'BUDGET_PROPOSAL'], true)
                        ? route('admin.projects.documents.combined-proposal.open', $doc->project_id)
                        : route('admin.projects.documents.open', [
                            $doc->project_id,
                            $doc->formType->code,
                        ]),

                    'count' => 1,
                    'is_completion' => false,
                ];
            })
            ->values();
    }

    private function buildProjectsReadyForCompletion(array $scope)
    {
        $activeSyId = $scope['activeSyId'];
        $resolver = app(ProjectFormRequirementResolver::class);

        if (!$activeSyId) {
            return collect();
        }

        return Project::with([
                'organization',
                'documents.formType',
            ])
            ->where('school_year_id', $activeSyId)
            ->where('workflow_status', '!=', 'completed')
            ->where('workflow_status', '!=', 'cancelled')
            ->whereHas('organization', function ($q) use ($scope) {
                $this->applyOrganizationScope($q, $scope);
            })
            ->get()
            ->filter(function ($project) use ($resolver) {
                $requiredFormTypes = collect($resolver->resolve($project))->filter();

                if ($requiredFormTypes->isEmpty()) {
                    return false;
                }

                $documents = $project->documents
                    ->whereNull('archived_at')
                    ->filter(fn ($doc) => $doc->formType)
                    ->keyBy(fn ($doc) => $doc->formType->code);

                return $requiredFormTypes->every(function ($formType) use ($documents) {
                    $doc = $documents->get($formType->code);

                    return $doc && $doc->status === 'approved_by_sacdev';
                });
            })
            ->map(function ($project) {
                return (object) [
                    'project' => $project,
                    'organization' => $project->organization ?? null,

                    'forms' => collect([
                        [
                            'name' => 'Ready for Completion',
                            'code' => 'READY_FOR_COMPLETION',
                            'phase' => 'completion',
                        ],
                    ]),

                    'status' => 'ready_for_completion',
                    'route' => route('admin.projects.documents.hub', $project->id),
                    'form_route' => route('admin.projects.documents.hub', $project->id),
                    'count' => 1,
                    'is_completion' => true,
                ];
            })
            ->values();
    }

    private function buildProjectsForClearanceReview(array $scope)
    {
        $activeSyId = $scope['activeSyId'];

        if (!$activeSyId) {
            return collect();
        }

        return Project::with('organization')
            ->where('school_year_id', $activeSyId)
            ->where('clearance_status', 'uploaded')
            ->whereHas('organization', function ($q) use ($scope) {
                $this->applyOrganizationScope($q, $scope);
            })
            ->get()
            ->map(function ($project) {
                return (object) [
                    'project' => $project,
                    'organization' => $project->organization ?? null,

                    'forms' => collect([
                        [
                            'name' => 'Clearance Review Required',
                            'code' => 'CLEARANCE_REVIEW',
                            'phase' => 'off_campus',
                        ],
                    ]),

                    'status' => 'clearance_uploaded',
                    'route' => route('admin.projects.documents.hub', $project->id),
                    'form_route' => route('admin.projects.documents.hub', $project->id),
                    'count' => 1,
                    'is_completion' => false,
                ];
            })
            ->values();
    }

    private function buildCalendarProjects(array $scope)
    {
        $activeSyId = $scope['activeSyId'];

        if (!$activeSyId) {
            return collect();
        }

        return Project::query()
            ->with('organization')
            ->where('school_year_id', $activeSyId)
            ->whereNotNull('implementation_start_date')
            ->whereHas('organization', function ($q) use ($scope) {
                $this->applyOrganizationScope($q, $scope);
            })
            ->orderBy('implementation_start_date')
            ->get()
            ->map(function ($project) {
                return [
                    'title' => $project->title,
                    'start' => $project->implementation_start_date,
                    'url' => route('admin.projects.documents.hub', $project->id),
                    'organization' => $project->organization?->name,
                    'venue_type' => $project->implementation_venue_type,
                    'workflow_status' => $project->workflow_status,
                ];
            })
            ->values();
    }

    private function buildDashboardCounts(array $scope): array
    {
        $activeSyId = $scope['activeSyId'];

        $orgCount = Organization::count();

        $projectCount = 0;
        $preImplementationCompleteCount = 0;
        $upcomingProjectsCount = 0;
        $offCampusProjectsCount = 0;
        $completedProjectsCount = 0;
        $activatedOrgCount = 0;

        if ($activeSyId) {
            $projectCount = Project::query()
                ->where('school_year_id', $activeSyId)
                ->count();

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
                ->count();

            $offCampusProjectsCount = Project::query()
                ->where('school_year_id', $activeSyId)
                ->whereHas('documents', function ($q) {
                    $q->whereHas('formType', fn ($f) =>
                        $f->where('code', 'OFF_CAMPUS_APPLICATION')
                    );
                })
                ->count();

            $completedProjectsCount = Project::query()
                ->where('school_year_id', $activeSyId)
                ->where(function ($q) {
                    $q->where('status', 'completed')
                    ->orWhere('workflow_status', 'completed');
                })
                ->count();

            $activatedOrgCount = DB::table('organization_school_years')
                ->where('school_year_id', $activeSyId)
                ->count();
        }

        return [
            'orgCount' => $orgCount,
            'syCount' => SchoolYear::count(),
            'projectCount' => $projectCount,
            'preImplementationCompleteCount' => $preImplementationCompleteCount,
            'upcomingProjectsCount' => $upcomingProjectsCount,
            'offCampusProjectsCount' => $offCampusProjectsCount,
            'activatedOrgCount' => $activatedOrgCount,
            'completedProjectsCount' => $completedProjectsCount,
        ];
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