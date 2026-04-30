<x-app-layout>

@php
    $roleLabels = collect($roles ?? [])->map(fn ($role) => ucfirst(str_replace('_', ' ', $role)))->values();

    $hasOrg = filled($currentOrg);
    $isPresident = collect($roles ?? [])->contains('president');

    $attentionItems = collect();

    if (($pendingApprovalCount ?? 0) > 0) {
        $attentionItems->push([
            'theme' => 'indigo',
            'icon' => 'file-check-2',
            'title' => ($pendingApprovalCount ?? 0) . ' ' . Str::plural('document', $pendingApprovalCount ?? 0) . ' awaiting your review',
            'description' => 'You have documents that need your approval or action based on your assigned organization role.',
            'label' => 'Review Documents',
            'href' => route('org.projects.index'),
            'badge' => 'Approval Task',
        ]);
    }

    if (($projectHeadPendingCount ?? 0) > 0) {
        $attentionItems->push([
            'theme' => 'amber',
            'icon' => 'folder-kanban',
            'title' => ($projectHeadPendingCount ?? 0) . ' project ' . Str::plural('requirement', $projectHeadPendingCount ?? 0) . ' need attention',
            'description' => 'Some assigned project documents or requirements still need to be prepared, revised, or submitted.',
            'label' => 'Open Projects',
            'href' => route('org.projects.index'),
            'badge' => 'Project Work',
        ]);
    }

    if ($isPresident && ($projectsWithoutHeadCount ?? 0) > 0) {
        $attentionItems->push([
            'theme' => 'rose',
            'icon' => 'user-plus',
            'title' => ($projectsWithoutHeadCount ?? 0) . ' ' . Str::plural('project', $projectsWithoutHeadCount ?? 0) . ' need project heads',
            'description' => 'Assign project heads so responsible members can prepare documents and move projects forward.',
            'label' => 'Assign Project Heads',
            'href' => route('org.assign-project-heads.index'),
            'badge' => 'President Task',
        ]);
    }

    if (($pendingCount ?? 0) > 0 && $attentionItems->isEmpty()) {
        $attentionItems->push([
            'theme' => 'blue',
            'icon' => 'list-checks',
            'title' => ($pendingCount ?? 0) . ' pending ' . Str::plural('task', $pendingCount ?? 0),
            'description' => 'You have workflow tasks available. Open the projects area to continue the required actions.',
            'label' => 'Open Tasks',
            'href' => route('org.projects.index'),
            'badge' => 'Pending',
        ]);
    }

    $themeClasses = [
        'indigo' => [
            'card' => 'border-indigo-200 bg-indigo-50',
            'icon' => 'bg-indigo-100 text-indigo-700',
            'badge' => 'bg-indigo-100 text-indigo-700',
            'button' => 'bg-indigo-600 hover:bg-indigo-700 text-white',
            'link' => 'text-indigo-700',
        ],
        'amber' => [
            'card' => 'border-amber-200 bg-amber-50',
            'icon' => 'bg-amber-100 text-amber-700',
            'badge' => 'bg-amber-100 text-amber-700',
            'button' => 'bg-amber-600 hover:bg-amber-700 text-white',
            'link' => 'text-amber-700',
        ],
        'rose' => [
            'card' => 'border-rose-200 bg-rose-50',
            'icon' => 'bg-rose-100 text-rose-700',
            'badge' => 'bg-rose-100 text-rose-700',
            'button' => 'bg-rose-600 hover:bg-rose-700 text-white',
            'link' => 'text-rose-700',
        ],
        'blue' => [
            'card' => 'border-blue-200 bg-blue-50',
            'icon' => 'bg-blue-100 text-blue-700',
            'badge' => 'bg-blue-100 text-blue-700',
            'button' => 'bg-blue-600 hover:bg-blue-700 text-white',
            'link' => 'text-blue-700',
        ],
    ];

    $workflowMap = [
        'planning' => [
            'label' => 'Planning',
            'class' => 'bg-slate-50 text-slate-700 border-slate-200',
            'dot' => 'bg-slate-400',
        ],
        'drafting' => [
            'label' => 'Drafting',
            'class' => 'bg-slate-50 text-slate-700 border-slate-200',
            'dot' => 'bg-slate-400',
        ],
        'pre_implementation' => [
            'label' => 'Pre-Implementation',
            'class' => 'bg-blue-50 text-blue-700 border-blue-200',
            'dot' => 'bg-blue-500',
        ],
        'post_implementation' => [
            'label' => 'Post-Implementation',
            'class' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
            'dot' => 'bg-indigo-500',
        ],
        'postponed' => [
            'label' => 'Postponed',
            'class' => 'bg-amber-50 text-amber-700 border-amber-200',
            'dot' => 'bg-amber-500',
        ],
        'cancelled' => [
            'label' => 'Cancelled',
            'class' => 'bg-rose-50 text-rose-700 border-rose-200',
            'dot' => 'bg-rose-500',
        ],
        'completed' => [
            'label' => 'Completed',
            'class' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'dot' => 'bg-emerald-500',
        ],
    ];
@endphp

<div class="min-h-screen bg-slate-50 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <nav class="text-xs text-slate-500">
            <ol class="flex flex-wrap items-center gap-1.5">
                <li class="font-medium text-slate-600">
                    Dashboard
                </li>

                <li class="text-slate-300">/</li>

                <li class="font-medium text-indigo-700">
                    Workflow Home
                </li>
            </ol>
        </nav>

        @if(!$hasOrg)

            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-6 shadow-sm">
                <div class="flex items-start gap-4">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-amber-100 text-amber-700">
                        <i data-lucide="circle-alert" class="w-6 h-6"></i>
                    </div>

                    <div>
                        <h1 class="text-lg font-semibold text-slate-900">
                            No organization access found
                        </h1>

                        <p class="mt-1 text-sm leading-6 text-slate-600">
                            You are not currently connected to an organization for the selected school year.
                        </p>
                    </div>
                </div>
            </div>

        @else

            <div class="rounded-2xl border border-indigo-200 bg-gradient-to-br from-indigo-50 via-white to-blue-50 shadow-sm overflow-hidden">
                <div class="p-6 flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">

                    <div class="flex items-start gap-4">
                        <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-indigo-100 text-indigo-700 ring-1 ring-indigo-200 shadow-sm">
                            <i data-lucide="layout-dashboard" class="w-7 h-7"></i>
                        </div>

                        <div>
                            <div class="flex flex-wrap items-center gap-2 mb-2">
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-indigo-100 px-2.5 py-1 text-[11px] font-semibold text-indigo-700">
                                    <i data-lucide="sparkles" class="w-3 h-3"></i>
                                    Workflow Home
                                </span>

                                <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-100 px-2.5 py-1 text-[11px] font-semibold text-blue-700">
                                    <i data-lucide="calendar-range" class="w-3 h-3"></i>
                                    {{ $selectedSy?->name ?? 'Selected School Year' }}
                                </span>
                            </div>

                            <h1 class="text-2xl font-semibold text-slate-900 leading-tight">
                                Welcome to {{ $currentOrg->acronym ?: $currentOrg->name }}
                            </h1>

                            <p class="mt-1 text-sm leading-6 text-slate-600 max-w-3xl">
                                This dashboard shows where your organization is in the workflow, what needs attention, and where to go next.
                            </p>

                            <div class="mt-3 flex flex-wrap gap-2">
                                @forelse($roleLabels as $role)
                                    <span class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 bg-white px-2.5 py-1 text-[11px] font-semibold text-slate-600 shadow-sm">
                                        <i data-lucide="user-round-check" class="w-3 h-3"></i>
                                        {{ $role }}
                                    </span>
                                @empty
                                    <span class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 bg-white px-2.5 py-1 text-[11px] font-semibold text-slate-600 shadow-sm">
                                        <i data-lucide="user" class="w-3 h-3"></i>
                                        Member
                                    </span>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href="#next-actions"
                           class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700">
                            <i data-lucide="list-checks" class="w-4 h-4"></i>
                            View Next Actions
                        </a>

                        <a href="#workflow-guide"
                           class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                            <i data-lucide="map" class="w-4 h-4"></i>
                            System Guide
                        </a>
                    </div>

                </div>
            </div>

            <div id="next-actions" class="grid grid-cols-1 lg:grid-cols-3 gap-4">



            <div
                class="lg:col-span-2"
                x-data="{
                    loading: false,
                    intervalId: null,

                    renderIcons() {
                        this.$nextTick(() => {
                            setTimeout(() => {
                                if (window.renderLucideIcons) {
                                    window.renderLucideIcons();
                                }
                            }, 50);
                        });
                    },

                    reloadPendingTasks() {
                        if (this.loading) return;

                        this.loading = true;

                        fetch('{{ route('org.dashboard.pending-tasks.partial') }}', {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'text/html'
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Pending tasks reload failed: ' + response.status);
                            }

                            return response.text();
                        })
                        .then(html => {
                            this.$refs.container.innerHTML = html;
                            this.renderIcons();
                        })
                        .catch(error => {
                            console.error(error);
                        })
                        .finally(() => {
                            this.loading = false;
                        });
                    }
                }"
                x-init="
                    renderIcons();

                    intervalId = setInterval(() => {
                        reloadPendingTasks();
                    }, 10000);
                "
            >
                <div x-ref="container">
                    @include('portals.partials._org_dashboard_pending_tasks')
                </div>
            </div>




                <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                    <div class="border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white px-5 py-4">
                        <div class="flex items-center gap-2 text-sm font-semibold text-slate-900">
                            <i data-lucide="navigation" class="w-4 h-4 text-indigo-600"></i>
                            Start here
                        </div>

                        <div class="mt-1 text-xs text-slate-500">
                            Main areas for organization users.
                        </div>
                    </div>

                    <div class="p-5 space-y-3">

                        <a href="{{ route('org.organization-info.show') }}"
                           class="group block rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:border-indigo-200 hover:bg-indigo-50/30">
                            <div class="flex items-start gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                                    <i data-lucide="building-2" class="w-5 h-5"></i>
                                </div>

                                <div class="min-w-0">
                                    <div class="text-sm font-semibold text-slate-900">
                                        Organization Profile
                                    </div>

                                    <div class="mt-1 text-xs leading-5 text-slate-500">
                                        View organization information, members, officers, and setup modules.
                                    </div>
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('org.projects.index') }}"
                           class="group block rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:border-indigo-200 hover:bg-indigo-50/30">
                            <div class="flex items-start gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                                    <i data-lucide="folder-kanban" class="w-5 h-5"></i>
                                </div>

                                <div class="min-w-0">
                                    <div class="text-sm font-semibold text-slate-900">
                                        Projects Module
                                    </div>

                                    <div class="mt-1 text-xs leading-5 text-slate-500">
                                        Prepare documents, track approvals, and continue project requirements.
                                    </div>
                                </div>
                            </div>
                        </a>

                        @if($isPresident)
                            <a href="{{ route('org.assign-project-heads.index') }}"
                               class="group block rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:border-indigo-200 hover:bg-indigo-50/30">
                                <div class="flex items-start gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                                        <i data-lucide="user-plus" class="w-5 h-5"></i>
                                    </div>

                                    <div class="min-w-0">
                                        <div class="text-sm font-semibold text-slate-900">
                                            Assign Project Heads
                                        </div>

                                        <div class="mt-1 text-xs leading-5 text-slate-500">
                                            Assign responsible members to handle project document preparation.
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endif

                    </div>
                </div>

            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

                <div class="lg:col-span-2 rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                    <div class="border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white px-5 py-4">
                        <div class="flex items-center gap-2 text-sm font-semibold text-slate-900">
                            <i data-lucide="folder-kanban" class="w-4 h-4 text-indigo-600"></i>
                            Current project workflow
                        </div>

                        <div class="mt-1 text-xs text-slate-500">
                            Assigned projects are summarized using required workflow forms, not only existing documents.
                        </div>
                    </div>

                    <div class="p-5 space-y-3">

                        @forelse($assignedProjects->take(3) as $project)
                            @php
                                $resolver = app(\App\Services\ProjectFormRequirementResolver::class);

                                $project->loadMissing([
                                    'documents.formType',
                                    'documents.signatures.user',
                                ]);

                                $documents = collect($project->documents ?? [])
                                    ->filter(fn($doc) => is_null($doc->archived_at ?? null))
                                    ->values();

                                $documentsByType = $documents->groupBy('form_type_id');

                                $requiredFormTypes = collect($resolver->resolve($project))
                                    ->filter()
                                    ->values();

                                $requiredCount = $requiredFormTypes->count();

                                $completedRequired = $requiredFormTypes->filter(function ($formType) use ($documentsByType) {
                                    $doc = $documentsByType->get($formType->id)?->first();

                                    return $doc && $doc->status === 'approved_by_sacdev';
                                })->count();

                                $pendingRequired = max($requiredCount - $completedRequired, 0);

                                $progressPercent = $requiredCount > 0
                                    ? round(($completedRequired / $requiredCount) * 100)
                                    : 0;

                                $wf = $workflowMap[$project->workflow_status] ?? [
                                    'label' => ucfirst(str_replace('_', ' ', $project->workflow_status ?? 'Planning')),
                                    'class' => 'bg-slate-50 text-slate-700 border-slate-200',
                                    'dot' => 'bg-slate-400',
                                ];

                                $requiredPreview = $requiredFormTypes->take(5)->map(function ($formType) use ($documentsByType) {
                                    $doc = $documentsByType->get($formType->id)?->first();

                                    return [
                                        'name' => $formType->name,
                                        'code' => $formType->code,
                                        'phase' => $formType->phase,
                                        'status' => $doc?->status ?? 'not_started',
                                        'is_done' => $doc && $doc->status === 'approved_by_sacdev',
                                    ];
                                });

                                $hasClearanceRequirement = (int) ($project->requires_clearance ?? 0) === 1;

                                $clearanceDone = !$hasClearanceRequirement
                                    || (
                                        !empty($project->clearance_file_path)
                                        && !in_array($project->clearance_status, ['rejected', 'returned'], true)
                                    );

                                $clearanceStatusLabel = $hasClearanceRequirement
                                    ? ucfirst(str_replace('_', ' ', $project->clearance_status ?? 'not_submitted'))
                                    : null;
                            @endphp

                            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">

                                    <div class="flex items-start gap-3 min-w-0">
                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                                            <i data-lucide="folder-kanban" class="w-5 h-5"></i>
                                        </div>

                                        <div class="min-w-0">
                                            <div class="text-sm font-semibold text-slate-900 truncate">
                                                {{ $project->title }}
                                            </div>

                                            <div class="mt-1 flex flex-wrap items-center gap-2">
                                                <span class="inline-flex items-center gap-1.5 rounded-full border px-2 py-0.5 text-[10px] font-semibold {{ $wf['class'] }}">
                                                    <span class="h-1.5 w-1.5 rounded-full {{ $wf['dot'] }}"></span>
                                                    {{ $wf['label'] }}
                                                </span>

                                                <span class="rounded-full border border-indigo-200 bg-indigo-50 px-2 py-0.5 text-[10px] font-semibold text-indigo-700">
                                                    {{ $completedRequired }} / {{ $requiredCount }} required completed
                                                </span>

                                                @if($pendingRequired > 0)
                                                    <span class="rounded-full border border-amber-200 bg-amber-50 px-2 py-0.5 text-[10px] font-semibold text-amber-700">
                                                        {{ $pendingRequired }} remaining
                                                    </span>
                                                @else
                                                    <span class="rounded-full border border-emerald-200 bg-emerald-50 px-2 py-0.5 text-[10px] font-semibold text-emerald-700">
                                                        Requirements complete
                                                    </span>
                                                @endif

                                                @if($hasClearanceRequirement)
                                                    <span class="rounded-full border {{ $clearanceDone ? 'border-cyan-200 bg-cyan-50 text-cyan-700' : 'border-rose-200 bg-rose-50 text-rose-700' }} px-2 py-0.5 text-[10px] font-semibold">
                                                        Clearance: {{ $clearanceStatusLabel }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <a href="{{ route('org.projects.documents.hub', $project) }}"
                                    class="inline-flex w-full items-center justify-center gap-1.5 rounded-xl border border-indigo-200 bg-indigo-50 px-3 py-2 text-xs font-semibold text-indigo-700 transition hover:bg-indigo-100 sm:w-auto">
                                        Open Hub
                                        <i data-lucide="arrow-right" class="w-3 h-3"></i>
                                    </a>
                                </div>

                                <div class="mt-4">
                                    <div class="flex items-center justify-between text-[11px]">
                                        <span class="text-slate-600">
                                            Required workflow progress
                                        </span>

                                        <span class="font-semibold text-indigo-700">
                                            {{ $progressPercent }}%
                                        </span>
                                    </div>

                                    <div class="mt-1.5 h-2 rounded-full bg-slate-100 overflow-hidden">
                                        <div class="h-full rounded-full bg-gradient-to-r from-indigo-500 to-blue-500"
                                            style="width: {{ $progressPercent }}%">
                                        </div>
                                    </div>
                                </div>

                                @if($requiredPreview->count())
                                    <div class="mt-4 flex flex-wrap gap-2">
                                        @foreach($requiredPreview as $form)
                                            @php
                                                $statusClass = $form['is_done']
                                                    ? 'border-emerald-200 bg-emerald-50 text-emerald-700'
                                                    : 'border-slate-200 bg-slate-50 text-slate-600';
                                            @endphp

                                            <span class="inline-flex max-w-full items-center gap-1.5 rounded-full border px-2.5 py-1 text-[11px] font-semibold {{ $statusClass }}">
                                                <i data-lucide="{{ $form['is_done'] ? 'check-circle-2' : 'circle' }}" class="w-3 h-3 shrink-0"></i>
                                                <span class="truncate">
                                                    {{ $form['name'] }}
                                                </span>
                                            </span>
                                        @endforeach

                                        @if($requiredFormTypes->count() > 5)
                                            <span class="inline-flex items-center rounded-full border border-slate-200 bg-white px-2.5 py-1 text-[11px] font-semibold text-slate-600">
                                                + {{ $requiredFormTypes->count() - 5 }} more
                                            </span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @empty

                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-6 text-center">
                                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-slate-400 border border-slate-200">
                                    <i data-lucide="folder-kanban" class="w-6 h-6"></i>
                                </div>

                                <div class="mt-3 text-sm font-semibold text-slate-800">
                                    No assigned projects yet
                                </div>

                                <div class="mt-1 text-xs text-slate-500">
                                    Assigned projects will appear here when you are made a project head or draftee.
                                </div>

                                <div class="mt-4">
                                    <a href="{{ route('org.projects.index') }}"
                                    class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-50">
                                        View Projects
                                        <i data-lucide="arrow-right" class="w-3 h-3"></i>
                                    </a>
                                </div>
                            </div>

                        @endforelse

                        @if(($assignedProjects->count() ?? 0) > 3)
                            <div class="pt-1">
                                <a href="{{ route('org.projects.index') }}"
                                class="inline-flex items-center gap-1.5 text-xs font-semibold text-indigo-700 hover:text-indigo-900">
                                    View all assigned projects
                                    <i data-lucide="arrow-right" class="w-3 h-3"></i>
                                </a>
                            </div>
                        @endif

                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                    <div class="border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white px-5 py-4">
                        <div class="flex items-center gap-2 text-sm font-semibold text-slate-900">
                            <i data-lucide="book-open" class="w-4 h-4 text-indigo-600"></i>
                            System guide
                        </div>

                        <div class="mt-1 text-xs text-slate-500">
                            Important workflow terms and reminders for organization users.
                        </div>
                    </div>

                    <div class="p-5">
                        <div class="max-h-[520px] overflow-y-auto pr-1 space-y-3">

                            <div class="rounded-2xl border border-indigo-200 bg-indigo-50 p-4">
                                <div class="flex items-start gap-3">
                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-indigo-100 text-indigo-700">
                                        <i data-lucide="file-stack" class="w-4 h-4"></i>
                                    </div>

                                    <div>
                                        <div class="text-sm font-semibold text-slate-900">
                                            Combined Proposal Form
                                        </div>

                                        <div class="mt-1 text-xs leading-5 text-slate-600">
                                            The Project Proposal and Budget Proposal are handled together in one form. This avoids repeated encoding and lets the system compute required documents based on project details, budget, venue, and funding sources.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="rounded-2xl border border-blue-200 bg-blue-50 p-4">
                                <div class="flex items-start gap-3">
                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-700">
                                        <i data-lucide="folder-kanban" class="w-4 h-4"></i>
                                    </div>

                                    <div>
                                        <div class="text-sm font-semibold text-slate-900">
                                            Project Requirements
                                        </div>

                                        <div class="mt-1 text-xs leading-5 text-slate-600">
                                            Required documents are not manually listed. The system determines them from the project workflow, such as whether the project has a budget, off-campus venue, solicitation, selling activity, or post-implementation requirements.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="rounded-2xl border border-purple-200 bg-purple-50 p-4">
                                <div class="flex items-start gap-3">
                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-purple-100 text-purple-700">
                                        <i data-lucide="shield-alert" class="w-4 h-4"></i>
                                    </div>

                                    <div>
                                        <div class="text-sm font-semibold text-slate-900">
                                            Off-Campus Clearance
                                        </div>

                                        <div class="mt-1 text-xs leading-5 text-slate-600">
                                            If a project is marked as off-campus, the system treats clearance as a separate requirement. It may appear as a task only when no clearance file has been uploaded yet or when the uploaded clearance was rejected.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="rounded-2xl border border-orange-200 bg-orange-50 p-4">
                                <div class="flex items-start gap-3">
                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-orange-100 text-orange-700">
                                        <i data-lucide="package-check" class="w-4 h-4"></i>
                                    </div>

                                    <div>
                                        <div class="text-sm font-semibold text-slate-900">
                                            Org Packet Submission
                                        </div>

                                        <div class="mt-1 text-xs leading-5 text-slate-600">
                                            Some physical or external documents are tracked through packet submissions. This helps the organization and SACDEV monitor what was submitted physically, what was reviewed, and what still needs follow-up.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="rounded-2xl border border-green-200 bg-green-50 p-4">
                                <div class="flex items-start gap-3">
                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-green-100 text-green-700">
                                        <i data-lucide="receipt-text" class="w-4 h-4"></i>
                                    </div>

                                    <div>
                                        <div class="text-sm font-semibold text-slate-900">
                                            DV Generation
                                        </div>

                                        <div class="mt-1 text-xs leading-5 text-slate-600">
                                            The Disbursement Voucher is generated from the project budget data. It is not treated as a full approval document inside the system. After generating or accomplishing it outside the system, it can be included in the org packet submission for tracking.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="rounded-2xl border border-cyan-200 bg-cyan-50 p-4">
                                <div class="flex items-start gap-3">
                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-cyan-100 text-cyan-700">
                                        <i data-lucide="users-round" class="w-4 h-4"></i>
                                    </div>

                                    <div>
                                        <div class="text-sm font-semibold text-slate-900">
                                            Draftee Role
                                        </div>

                                        <div class="mt-1 text-xs leading-5 text-slate-600">
                                            A draftee can help prepare project documents, but approval responsibility still follows the assigned workflow roles. This allows collaboration without giving everyone the same authority.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4">
                                <div class="flex items-start gap-3">
                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">
                                        <i data-lucide="check-circle-2" class="w-4 h-4"></i>
                                    </div>

                                    <div>
                                        <div class="text-sm font-semibold text-slate-900">
                                            What Counts as Completed?
                                        </div>

                                        <div class="mt-1 text-xs leading-5 text-slate-600">
                                            A required document is counted as completed only when it is approved by SACDEV. Drafts, submitted documents, and documents waiting for approval are still part of the workflow, but they are not yet completed.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                <div class="flex items-start gap-3">
                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-white text-slate-600 border border-slate-200">
                                        <i data-lucide="layout-list" class="w-4 h-4"></i>
                                    </div>

                                    <div>
                                        <div class="text-sm font-semibold text-slate-900">
                                            Why Open the Project Hub?
                                        </div>

                                        <div class="mt-1 text-xs leading-5 text-slate-600">
                                            The Project Hub shows the complete workflow for one project, including required forms, optional actions, approval status, off-campus clearance, packet submissions, and available next steps.
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>


            <div id="workflow-guide" class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white px-5 py-4">
                    <div class="flex items-center gap-2 text-sm font-semibold text-slate-900">
                        <i data-lucide="map" class="w-4 h-4 text-indigo-600"></i>
                        System workflow guide
                    </div>

                    <div class="mt-1 text-xs text-slate-500">
                        A quick walkthrough of how organization setup, project documents, approvals, and packet submissions move through the system.
                    </div>
                </div>

                <div class="p-5 space-y-5">

                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                                <i data-lucide="workflow" class="w-4 h-4"></i>
                            </div>

                            <div>
                                <div class="text-sm font-semibold text-slate-900">
                                    Main organization and project workflow
                                </div>
                                <div class="text-xs text-slate-500">
                                    From re-registration to project completion.
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-4">

                            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4">
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-100 text-amber-700">
                                    <i data-lucide="clipboard-list" class="w-5 h-5"></i>
                                </div>

                                <div class="mt-4 text-sm font-semibold text-slate-900">
                                    1. Complete Setup
                                </div>

                                <div class="mt-1 text-xs leading-5 text-slate-600">
                                    The organization completes re-registration requirements such as profiles, officers, strategic plan, moderator details, and constitution.
                                </div>
                            </div>

                            <div class="rounded-2xl border border-blue-200 bg-blue-50 p-4">
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-100 text-blue-700">
                                    <i data-lucide="users" class="w-5 h-5"></i>
                                </div>

                                <div class="mt-4 text-sm font-semibold text-slate-900">
                                    2. Assign Roles
                                </div>

                                <div class="mt-1 text-xs leading-5 text-slate-600">
                                    Officers, project heads, draftees, moderator, and approval roles determine who can prepare, review, or approve each part of the workflow.
                                </div>
                            </div>

                            <div class="rounded-2xl border border-indigo-200 bg-indigo-50 p-4">
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-100 text-indigo-700">
                                    <i data-lucide="folder-kanban" class="w-5 h-5"></i>
                                </div>

                                <div class="mt-4 text-sm font-semibold text-slate-900">
                                    3. Prepare Project
                                </div>

                                <div class="mt-1 text-xs leading-5 text-slate-600">
                                    The project head opens the Project Hub, completes the combined proposal form, and prepares documents required by the system rules.
                                </div>
                            </div>

                            <div class="rounded-2xl border border-violet-200 bg-violet-50 p-4">
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-violet-100 text-violet-700">
                                    <i data-lucide="route" class="w-5 h-5"></i>
                                </div>

                                <div class="mt-4 text-sm font-semibold text-slate-900">
                                    4. Route Approvals
                                </div>

                                <div class="mt-1 text-xs leading-5 text-slate-600">
                                    Submitted documents move through the required approval sequence. Each approver only acts when the document is assigned to them.
                                </div>
                            </div>

                            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4">
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">
                                    <i data-lucide="check-circle-2" class="w-5 h-5"></i>
                                </div>

                                <div class="mt-4 text-sm font-semibold text-slate-900">
                                    5. Complete Requirements
                                </div>

                                <div class="mt-1 text-xs leading-5 text-slate-600">
                                    A requirement is considered completed only when it is approved by SACDEV. Post-implementation documents follow after the project is implemented.
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

                        <div class="rounded-2xl border border-orange-200 bg-orange-50 overflow-hidden">
                            <div class="border-b border-orange-200 bg-orange-100/60 px-4 py-3">
                                <div class="flex items-center gap-2 text-sm font-semibold text-slate-900">
                                    <i data-lucide="package-check" class="w-4 h-4 text-orange-700"></i>
                                    Org packet submission flow
                                </div>

                                <div class="mt-1 text-xs text-orange-800/80">
                                    Used for physical or external documents that need SACDEV tracking.
                                </div>
                            </div>

                            <div class="p-4 space-y-3">

                                <div class="flex items-start gap-3">
                                    <div class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-white border border-orange-200 text-[11px] font-bold text-orange-700">
                                        1
                                    </div>

                                    <div>
                                        <div class="text-xs font-semibold text-slate-900">
                                            Open the project
                                        </div>
                                        <div class="mt-0.5 text-xs leading-5 text-slate-600">
                                            Go to the Project Hub and check if physical or external items need to be submitted.
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-start gap-3">
                                    <div class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-white border border-orange-200 text-[11px] font-bold text-orange-700">
                                        2
                                    </div>

                                    <div>
                                        <div class="text-xs font-semibold text-slate-900">
                                            Create a project packet
                                        </div>
                                        <div class="mt-0.5 text-xs leading-5 text-slate-600">
                                            Start a packet and list the items included, such as DV, receipts, solicitation letters, or other supporting documents.
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-start gap-3">
                                    <div class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-white border border-orange-200 text-[11px] font-bold text-orange-700">
                                        3
                                    </div>

                                    <div>
                                        <div class="text-xs font-semibold text-slate-900">
                                            Compile and submit to SACDEV
                                        </div>
                                        <div class="mt-0.5 text-xs leading-5 text-slate-600">
                                            Prepare the physical documents and submit them to SACDEV. The system tracks the packet status after submission.
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-start gap-3">
                                    <div class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-white border border-orange-200 text-[11px] font-bold text-orange-700">
                                        4
                                    </div>

                                    <div>
                                        <div class="text-xs font-semibold text-slate-900">
                                            Monitor review status
                                        </div>
                                        <div class="mt-0.5 text-xs leading-5 text-slate-600">
                                            SACDEV can review packet items, mark them reviewed, return items, or indicate items ready for claiming.
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="rounded-2xl border border-purple-200 bg-purple-50 overflow-hidden">
                            <div class="border-b border-purple-200 bg-purple-100/60 px-4 py-3">
                                <div class="flex items-center gap-2 text-sm font-semibold text-slate-900">
                                    <i data-lucide="shield-alert" class="w-4 h-4 text-purple-700"></i>
                                    Off-campus clearance flow
                                </div>

                                <div class="mt-1 text-xs text-purple-800/80">
                                    Applies when a project activity is outside campus.
                                </div>
                            </div>

                            <div class="p-4 space-y-3">

                                <div class="flex items-start gap-3">
                                    <div class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-white border border-purple-200 text-[11px] font-bold text-purple-700">
                                        1
                                    </div>

                                    <div>
                                        <div class="text-xs font-semibold text-slate-900">
                                            Mark project as off-campus
                                        </div>
                                        <div class="mt-0.5 text-xs leading-5 text-slate-600">
                                            The system detects off-campus requirements from the project venue and related proposal information.
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-start gap-3">
                                    <div class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-white border border-purple-200 text-[11px] font-bold text-purple-700">
                                        2
                                    </div>

                                    <div>
                                        <div class="text-xs font-semibold text-slate-900">
                                            Generate and upload clearance
                                        </div>
                                        <div class="mt-0.5 text-xs leading-5 text-slate-600">
                                            The project head uploads the signed clearance file. Once uploaded, it is no longer treated as a missing task unless it is rejected.
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-start gap-3">
                                    <div class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-white border border-purple-200 text-[11px] font-bold text-purple-700">
                                        3
                                    </div>

                                    <div>
                                        <div class="text-xs font-semibold text-slate-900">
                                            Wait for review
                                        </div>
                                        <div class="mt-0.5 text-xs leading-5 text-slate-600">
                                            If clearance is already uploaded, the task should not keep asking the project head to submit it again.
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-start gap-3">
                                    <div class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-white border border-purple-200 text-[11px] font-bold text-purple-700">
                                        4
                                    </div>

                                    <div>
                                        <div class="text-xs font-semibold text-slate-900">
                                            Re-upload if rejected
                                        </div>
                                        <div class="mt-0.5 text-xs leading-5 text-slate-600">
                                            If SACDEV rejects the clearance, it returns as an action item so the project head can correct and upload again.
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">


                    </div>

                </div>
            </div>

        @endif

    </div>
</div>

</x-app-layout>