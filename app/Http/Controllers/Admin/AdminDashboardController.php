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



            
                PresidentRegistration::query()
                    ->with('organization')
                    ->whereIn('target_school_year_id', $targetSyIds)
                    ->whereIn('status', $actionable)
                    ->whereHas('organization', function ($q) use ($user) {
                        $q->whereIn('cluster_id', $user->clusters->pluck('id'));
                    })
                    ->get()
                    ->map(function ($r) {
                        return (object) [
                            'type' => 'President Registration',
                            'organization_id' => $r->organization_id,
                            'school_year_id' => $r->target_school_year_id,
                            'organization' => $r->organization,
                            'school_year' => SchoolYear::find($r->target_school_year_id),
                            'status' => $r->status,
                            'created_at' => $r->created_at,
                            'route' => route('admin.b2.president.show', $r->id),
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
                ModeratorSubmission::query()
                    ->with('organization')
                    ->whereIn('target_school_year_id', $targetSyIds)
                    ->whereIn('status', $actionable)
                    ->whereHas('organization', function ($q) use ($user) {
                        $q->whereIn('cluster_id', $user->clusters->pluck('id'));
                    })
                    ->get()
                    ->map(function ($r) {
                        return (object) [
                            'type' => 'Moderator Submission',
                            'organization_id' => $r->organization_id,
                            'school_year_id' => $r->target_school_year_id,
                            'organization' => $r->organization,
                            'school_year' => SchoolYear::find($r->target_school_year_id),
                            'status' => $r->status,
                            'created_at' => $r->created_at,
                            'route' => route('admin.moderator_submissions.show', $r->id),
                        ];
                    })
            )
            ->sortByDesc('created_at')
            ->values();

        $b1Approved = StrategicPlanSubmission::query()
            ->whereIn('target_school_year_id', $targetSyIds)
            ->where('status', $approved)
            ->get(['organization_id', 'target_school_year_id'])
            ->map(fn ($r) => (int) $r->organization_id . '|' . (int) $r->target_school_year_id)
            ->unique()
            ->values();

        $b2Approved = PresidentRegistration::query()
            ->whereIn('target_school_year_id', $targetSyIds)
            ->where('status', $approved)
            ->get(['organization_id', 'target_school_year_id'])
            ->map(fn ($r) => (int) $r->organization_id . '|' . (int) $r->target_school_year_id)
            ->unique()
            ->values();

        $b3Approved = OfficerSubmission::query()
            ->whereIn('target_school_year_id', $targetSyIds)
            ->where('status', $approved)
            ->get(['organization_id', 'target_school_year_id'])
            ->map(fn ($r) => (int) $r->organization_id . '|' . (int) $r->target_school_year_id)
            ->unique()
            ->values();

        $b5Approved = ModeratorSubmission::query()
            ->whereIn('target_school_year_id', $targetSyIds)
            ->where('status', $approved)
            ->get(['organization_id', 'target_school_year_id'])
            ->map(fn ($r) => (int) $r->organization_id . '|' . (int) $r->target_school_year_id)
            ->unique()
            ->values();

        $readyKeys = collect(array_values(array_intersect(
            $b1Approved->toArray(),
            $b2Approved->toArray(),
            $b3Approved->toArray(),
            $b5Approved->toArray()
        )));

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
                    'route' => route('rereg.hub', $orgId),
                ];
            })
            ->values()
            ->filter(fn ($r) => $r->organization !== null);

        $projectApprovals = ProjectDocument::with([
                'project.organization',
                'formType',
                'signatures',
            ])
            ->whereHas('project', function ($q) use ($activeSyId) {
                if ($activeSyId) {
                    $q->where('school_year_id', $activeSyId);
                }

            
                $q->where('workflow_status', '!=', 'cancelled');
            })
            ->get()
            ->filter(function ($doc) {
                $pending = $doc->currentPendingSignature();
                return $pending && $pending->role === 'sacdev_admin';
            })
            ->map(function ($doc) {
                return (object) [
                    'project' => $doc->project,
                    'organization' => $doc->project->organization ?? null,
                    'form_name' => $doc->formType->name ?? 'Form',
                    'form_code' => $doc->formType->code ?? null,
                    'status' => $doc->status,

                    // main click → admin hub
                    'route' => route('admin.projects.documents.hub', $doc->project_id),

                    // secondary action → exact form
                    'form_route' => $this->resolveOrgDocumentRoute($doc),
                ];
            })
            ->values();

        $projectApprovals = $projectApprovals
            ->groupBy(fn ($task) => $task->project->id)
            ->map(function ($tasks) {
                return (object)[
                    'project' => $tasks->first()->project,
                    'organization' => $tasks->first()->organization,
                    'forms' => $tasks->map(fn ($t) => [
                        'name' => $t->form_name,
                        'code' => $t->form_code,
                        'phase' => $t->formType->phase ?? 'other',
                    ])->values(),
                    'route' => $tasks->first()->route,
                    'count' => $tasks->count(),
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

                $allApproved = $requiredDocs
                    ->every(fn($doc) => $doc && $doc->status === 'approved_by_sacdev');



                $debug[] = [
                    'project' => $project->title,
                    'required_forms' => collect($requiredFormTypes)->pluck('code'),
                    'existing_documents' => $documents->keys(),
                    'required_docs_status' => $requiredDocs->map(fn($doc) => $doc?->status),
                    'allApproved' => $allApproved,
                ];

                return $allApproved;
            })
            ->map(function ($project) {
                return (object)[
                    'type' => 'Project Ready for Completion',

                
                    'organization_id' => $project->organization_id,
                    'school_year_id' => $project->school_year_id,

                    'project' => $project,
                    'organization' => $project->organization ?? null,
                    'created_at' => $project->updated_at,
                    'route' => route('admin.projects.documents.hub', $project->id),
                ];
            })
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
            ->merge($projectsReadyForCompletion)
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
}