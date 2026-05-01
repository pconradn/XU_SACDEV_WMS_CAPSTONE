<x-app-layout>

    <style>
        .content-frame.soft {
            background: #ffffff !important;
        }
    </style>

    @php
        $isCoa = auth()->user()?->is_coa_officer ?? false;

        $projectQueueCount = $projectApprovalsCount ?? collect($projectApprovals ?? [])->count();

        $calendarPreview = collect($calendarProjects ?? [])
            ->take(3)
            ->values();

        $activeSyName = $activeSy?->name ?? 'No active school year';

        $summaryCards = [
            [
                'label' => 'Orgs Registered',
                'value' => $activatedOrgCount ?? 0,
                'hint' => 'Organizations activated for the active SY',
                'icon' => 'building-2',
                'theme' => 'indigo',
                'show' => !$isCoa,
            ],
            [
                'label' => 'Projects Total',
                'value' => $projectCount ?? 0,
                'hint' => 'Projects recorded this active SY',
                'icon' => 'folder-kanban',
                'theme' => 'blue',
                'show' => !$isCoa,
            ],
            [
                'label' => 'Workflow Queue',
                'value' => $projectQueueCount,
                'hint' => $isCoa ? 'Document approvals and edit requests' : 'Approvals, clearance, edit requests, and completion',
                'icon' => 'file-check-2',
                'theme' => 'amber',
                'show' => true,
            ],
            [
                'label' => 'Projects Completed',
                'value' => $completedProjectsCount ?? 0,
                'hint' => 'Projects marked completed',
                'icon' => 'clipboard-check',
                'theme' => 'emerald',
                'show' => !$isCoa,
            ],
        ];

        $workflowStats = [
            [
                'label' => 'Activated Orgs',
                'value' => $activatedOrgCount ?? 0,
                'icon' => 'building-2',
                'theme' => 'indigo',
                'show' => !$isCoa,
            ],
            [
                'label' => 'Pre-Implementation Complete',
                'value' => $preImplementationCompleteCount ?? 0,
                'icon' => 'file-check-2',
                'theme' => 'blue',
                'show' => !$isCoa,
            ],
            [
                'label' => 'Upcoming Projects',
                'value' => $upcomingProjectsCount ?? 0,
                'icon' => 'calendar-clock',
                'theme' => 'amber',
                'show' => !$isCoa,
            ],
            [
                'label' => 'Off-Campus Projects',
                'value' => $offCampusProjectsCount ?? 0,
                'icon' => 'map',
                'theme' => 'purple',
                'show' => !$isCoa,
            ],
            [
                'label' => 'Completed Projects',
                'value' => $completedProjectsCount ?? 0,
                'icon' => 'clipboard-check',
                'theme' => 'emerald',
                'show' => !$isCoa,
            ],
        ];
    @endphp

    <div class="min-h-screen bg-slate-50">

        <div class="px-3 sm:px-4 lg:px-5 pt-4 space-y-4">

            <div class="rounded-2xl border border-indigo-200 bg-gradient-to-br from-indigo-50 via-white to-slate-50 shadow-sm overflow-hidden">
                <div class="px-5 py-5 flex flex-col gap-5 xl:flex-row xl:items-center xl:justify-between">

                    <div class="flex items-start gap-4">
                        <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-indigo-100 text-indigo-700 ring-1 ring-indigo-200 shadow-sm">
                            <i data-lucide="shield-check" class="w-7 h-7"></i>
                        </div>

                        <div>
                            <div class="flex flex-wrap items-center gap-2 mb-2">
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-indigo-100 px-2.5 py-1 text-[11px] font-semibold text-indigo-700">
                                    <i data-lucide="layout-dashboard" class="w-3 h-3"></i>
                                    {{ $isCoa ? 'COA Review Panel' : 'SACDEV Admin Panel' }}
                                </span>

                                <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-2.5 py-1 text-[11px] font-semibold text-slate-600">
                                    <i data-lucide="calendar-range" class="w-3 h-3"></i>
                                    {{ $activeSyName }}
                                </span>
                            </div>

                            <h1 class="text-2xl font-semibold text-slate-900 leading-tight">
                                Admin Workflow Dashboard
                            </h1>

                            <p class="mt-1 text-sm leading-6 text-slate-600 max-w-3xl">
                                @if($isCoa)
                                    Review assigned document approvals and edit requests for project workflows.
                                @else
                                    Monitor organization registration, project document approvals, clearance reviews, completion readiness, and scheduled activities.
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                        @foreach($summaryCards as $card)
                            @if($card['show'])
                                <div class="rounded-2xl border border-white/70 bg-white/85 px-4 py-3 shadow-sm">
                                    <div class="flex items-center gap-2 text-[11px] font-medium text-slate-500">
                                        <i data-lucide="{{ $card['icon'] }}" class="w-3.5 h-3.5 text-{{ $card['theme'] }}-600"></i>
                                        {{ $card['label'] }}
                                    </div>

                                    <div class="mt-1 text-xl font-semibold text-slate-900">
                                        {{ $card['value'] }}
                                    </div>

                                    <div class="mt-0.5 text-[10px] text-slate-500">
                                        {{ $card['hint'] }}
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                </div>
            </div>

        </div>

        <div class="px-3 sm:px-4 lg:px-5 py-4 space-y-4">

            <div class="grid grid-cols-1 xl:grid-cols-4 gap-4">

                <div class="xl:col-span-3 space-y-4">


                    <div class="rounded-2xl border border-indigo-200 bg-white shadow-sm overflow-hidden">
                        <div class="border-b border-indigo-200 bg-gradient-to-r from-indigo-50 to-white px-5 py-4">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <div class="flex items-center gap-2 text-sm font-semibold text-slate-900">
                                        <i data-lucide="file-check-2" class="w-4 h-4 text-indigo-600"></i>
                                        {{ $isCoa ? 'Document Review Queue' : 'Project Workflow Queue' }}
                                    </div>

                                    <div class="mt-1 text-xs text-slate-500">
                                        @if($isCoa)
                                            Documents and edit requests assigned for your review.
                                        @else
                                            Document approvals, edit requests, clearance reviews, and projects ready for completion.
                                        @endif
                                    </div>
                                </div>

                                <div class="flex flex-wrap gap-2">
                                    <span class="inline-flex items-center gap-1.5 rounded-full border border-indigo-200 bg-indigo-50 px-2.5 py-1 text-[11px] font-semibold text-indigo-700">
                                        <i data-lucide="inbox" class="w-3 h-3"></i>
                                        {{ $projectQueueCount }} items
                                    </span>

                                    <span class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 bg-white px-2.5 py-1 text-[11px] font-semibold text-slate-600">
                                        <i data-lucide="refresh-cw" class="w-3 h-3"></i>
                                        Auto-refresh
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div
                            x-data="{
                                loading: false,
                                intervalId: null,

                                renderIcons() {
                                    this.$nextTick(() => {
                                        setTimeout(() => {
                                            if (window.renderLucideIcons) {
                                                window.renderLucideIcons();
                                            } else if (window.lucide) {
                                                window.lucide.createIcons();
                                            }
                                        }, 50);
                                    });
                                },

                                reloadProjectQueue() {
                                    if (this.loading) return;

                                    this.loading = true;

                                    fetch('{{ route('admin.dashboard.project-approvals.partial') }}', {
                                        headers: {
                                            'X-Requested-With': 'XMLHttpRequest',
                                            'Accept': 'text/html'
                                        }
                                    })
                                    .then(response => {
                                        if (!response.ok) {
                                            throw new Error('Project workflow reload failed: ' + response.status);
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
                                    reloadProjectQueue();
                                }, 30000);
                            "
                            class="p-4"
                        >
                            <div x-ref="container">
                                @include('admin.dashboard._project-approvals')
                            </div>
                        </div>
                    </div>

                    @if(!$isCoa)
                        <div class="rounded-2xl border border-amber-200 bg-white shadow-sm overflow-hidden">
                            <div class="border-b border-amber-200 bg-gradient-to-r from-amber-50 to-white px-5 py-4">
                                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                    <div>
                                        <div class="flex items-center gap-2 text-sm font-semibold text-slate-900">
                                            <i data-lucide="building-2" class="w-4 h-4 text-amber-600"></i>
                                            Organization Registration Queue
                                        </div>

                                        <div class="mt-1 text-xs text-slate-500">
                                            Review submitted re-registration requirements and organizations ready for active school year activation.
                                        </div>
                                    </div>

                                    <div class="flex flex-wrap gap-2">
                                        <span class="inline-flex items-center gap-1.5 rounded-full border border-amber-200 bg-amber-50 px-2.5 py-1 text-[11px] font-semibold text-amber-700">
                                            <i data-lucide="clipboard-list" class="w-3 h-3"></i>
                                            {{ $pendingCaseCount ?? 0 }} pending
                                        </span>

                                        <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-[11px] font-semibold text-emerald-700">
                                            <i data-lucide="badge-check" class="w-3 h-3"></i>
                                            {{ $readyForActivationCount ?? 0 }} ready
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="p-4">
                                @include('admin.dashboard._pending-cases')
                            </div>
                        </div>
                    @endif

                    @if(!$isCoa)
                        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                            <div class="border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white px-5 py-4">
                                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                    <div>
                                        <div class="flex items-center gap-2 text-sm font-semibold text-slate-900">
                                            <i data-lucide="calendar-days" class="w-4 h-4 text-indigo-600"></i>
                                            Upcoming Activity Preview
                                        </div>

                                        <div class="mt-1 text-xs text-slate-500">
                                            Upcoming project implementation dates from the active school year.
                                        </div>
                                    </div>

                                    <span class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 bg-slate-50 px-2.5 py-1 text-[11px] font-semibold text-slate-600">
                                        <i data-lucide="calendar-range" class="w-3 h-3"></i>
                                        {{ collect($calendarProjects ?? [])->count() }} scheduled
                                    </span>
                                </div>
                            </div>

                            <div class="p-4">
                                @if($calendarPreview->count())
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                        @foreach($calendarPreview as $item)
                                            @php
                                                $startDate = $item['start'] ?? null;

                                                $venueType = $item['venue_type'] ?? null;
                                                $workflowStatus = $item['workflow_status'] ?? null;

                                                $theme = $venueType === 'off_campus'
                                                    ? 'purple'
                                                    : ($workflowStatus === 'completed' ? 'emerald' : 'indigo');

                                                $dateLabel = $startDate
                                                    ? \Carbon\Carbon::parse($startDate)->format('M d, Y')
                                                    : 'No date';
                                            @endphp

                                            <a href="{{ $item['url'] ?? '#' }}"
                                               class="block rounded-2xl border border-{{ $theme }}-200 bg-{{ $theme }}-50 p-4 transition hover:-translate-y-0.5 hover:shadow-sm">
                                                <div class="flex items-start gap-3">
                                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white text-{{ $theme }}-600 border border-{{ $theme }}-200">
                                                        <i data-lucide="calendar" class="w-5 h-5"></i>
                                                    </div>

                                                    <div class="min-w-0">
                                                        <div class="text-xs font-semibold text-{{ $theme }}-700">
                                                            {{ $dateLabel }}
                                                        </div>

                                                        <div class="mt-1 text-sm font-semibold text-slate-900 truncate">
                                                            {{ $item['title'] ?? 'Untitled Project' }}
                                                        </div>

                                                        <div class="mt-1 text-xs text-slate-600 truncate">
                                                            {{ $item['organization'] ?? 'Organization' }}
                                                        </div>

                                                        <div class="mt-2 inline-flex rounded-full border border-{{ $theme }}-200 bg-white px-2.5 py-1 text-[11px] font-semibold text-{{ $theme }}-700">
                                                            {{ ucwords(str_replace('_', ' ', $workflowStatus ?? 'scheduled')) }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>

                                    @if(collect($calendarProjects ?? [])->count() > 3)
                                        <div class="mt-3 text-xs text-slate-500">
                                            Showing 3 of {{ collect($calendarProjects ?? [])->count() }} scheduled projects.
                                        </div>
                                    @endif
                                @else
                                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-8 text-center">
                                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-slate-400 border border-slate-200">
                                            <i data-lucide="calendar-off" class="w-6 h-6"></i>
                                        </div>

                                        <div class="mt-3 text-sm font-semibold text-slate-800">
                                            No scheduled projects yet
                                        </div>

                                        <div class="mt-1 text-xs text-slate-500">
                                            Projects with implementation dates will appear here.
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                </div>

                <div class="space-y-4">

                    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                        <div class="border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white px-5 py-4">
                            <div class="flex items-center gap-2 text-sm font-semibold text-slate-900">
                                <i data-lucide="bolt" class="w-4 h-4 text-indigo-600"></i>
                                Admin Shortcuts
                            </div>

                            <div class="mt-1 text-xs text-slate-500">
                                Frequently used dashboard actions.
                            </div>
                        </div>

                        <div class="p-4">
                            @include('admin.dashboard._quick-links')
                        </div>
                    </div>

                    @if(!$isCoa)
                        <div class="rounded-2xl border border-emerald-200 bg-white shadow-sm overflow-hidden">
                            <div class="border-b border-emerald-200 bg-gradient-to-r from-emerald-50 to-white px-5 py-4">
                                <div class="flex items-center gap-2 text-sm font-semibold text-slate-900">
                                    <i data-lucide="badge-check" class="w-4 h-4 text-emerald-600"></i>
                                    Ready Organization Activation
                                </div>

                                <div class="mt-1 text-xs text-slate-500">
                                    Organizations that completed active school year requirements and are ready to be activated.
                                </div>
                            </div>

                            <div class="p-4">
                                @include('admin.dashboard._activation')
                            </div>
                        </div>
                    @endif


                    <div class="rounded-2xl border border-blue-200 bg-blue-50 shadow-sm overflow-hidden">
                        <div class="px-5 py-4 border-b border-blue-200 bg-blue-100/50">
                            <div class="flex items-center gap-2 text-sm font-semibold text-slate-900">
                                <i data-lucide="book-open" class="w-4 h-4 text-blue-700"></i>
                                {{ $isCoa ? 'COA Review Guide' : 'Admin Review Guide' }}
                            </div>

                            <div class="mt-1 text-xs text-blue-800/80">
                                What to prioritize first.
                            </div>
                        </div>

                        <div class="p-4 space-y-3">

                            @if($isCoa)
                                <div class="rounded-2xl border border-indigo-200 bg-white p-3">
                                    <div class="flex items-start gap-3">
                                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-indigo-100 text-indigo-700">
                                            <i data-lucide="file-check-2" class="w-4 h-4"></i>
                                        </div>

                                        <div>
                                            <div class="text-xs font-semibold text-slate-900">
                                                Review assigned documents
                                            </div>
                                            <div class="mt-1 text-xs leading-5 text-slate-600">
                                                Open the document queue and review items assigned to you or marked for edit request.
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="rounded-2xl border border-amber-200 bg-white p-3">
                                    <div class="flex items-start gap-3">
                                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-amber-700">
                                            <i data-lucide="message-square-warning" class="w-4 h-4"></i>
                                        </div>

                                        <div>
                                            <div class="text-xs font-semibold text-slate-900">
                                                Check edit requests
                                            </div>
                                            <div class="mt-1 text-xs leading-5 text-slate-600">
                                                Edit requests should be reviewed carefully before reopening an approved document.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="rounded-2xl border border-amber-200 bg-white p-3">
                                    <div class="flex items-start gap-3">
                                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-amber-700">
                                            <i data-lucide="clipboard-check" class="w-4 h-4"></i>
                                        </div>

                                        <div>
                                            <div class="text-xs font-semibold text-slate-900">
                                                Start with registration queue
                                            </div>
                                            <div class="mt-1 text-xs leading-5 text-slate-600">
                                                Review submitted re-registration forms and activate organizations that are ready for the active school year.
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="rounded-2xl border border-indigo-200 bg-white p-3">
                                    <div class="flex items-start gap-3">
                                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-indigo-100 text-indigo-700">
                                            <i data-lucide="file-check-2" class="w-4 h-4"></i>
                                        </div>

                                        <div>
                                            <div class="text-xs font-semibold text-slate-900">
                                                Review project workflow items
                                            </div>
                                            <div class="mt-1 text-xs leading-5 text-slate-600">
                                                Project queue items may include document approvals, clearance uploads, edit requests, and projects ready for completion.
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="rounded-2xl border border-emerald-200 bg-white p-3">
                                    <div class="flex items-start gap-3">
                                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">
                                            <i data-lucide="calendar-check" class="w-4 h-4"></i>
                                        </div>

                                        <div>
                                            <div class="text-xs font-semibold text-slate-900">
                                                Check upcoming activities
                                            </div>
                                            <div class="mt-1 text-xs leading-5 text-slate-600">
                                                Upcoming implementation dates help you anticipate review load and post-activity requirements.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>