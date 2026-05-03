@php
    $projectApprovals = collect($projectApprovals ?? []);

    $editRequestTasks = $projectApprovals
        ->filter(fn($task) => ($task->status ?? null) === 'edit_requested')
        ->values();

    $normalProjectApprovals = $projectApprovals
        ->reject(fn($task) => ($task->status ?? null) === 'edit_requested')
        ->values();


    $phaseConfig = [
        'pre_implementation' => [
            'label' => 'Pre-Implementation',
            'icon' => 'file-check-2',
            'class' => 'border-emerald-200 bg-emerald-50 text-emerald-700',
        ],
        'off-campus' => [
            'label' => 'Off-Campus',
            'icon' => 'map',
            'class' => 'border-purple-200 bg-purple-50 text-purple-700',
        ],
        'off_campus' => [
            'label' => 'Off-Campus',
            'icon' => 'map',
            'class' => 'border-purple-200 bg-purple-50 text-purple-700',
        ],
        'post_implementation' => [
            'label' => 'Post-Implementation',
            'icon' => 'clipboard-check',
            'class' => 'border-blue-200 bg-blue-50 text-blue-700',
        ],
        'notice' => [
            'label' => 'Notice',
            'icon' => 'calendar-clock',
            'class' => 'border-rose-200 bg-rose-50 text-rose-700',
        ],
        'completion' => [
            'label' => 'Completion',
            'icon' => 'check-circle-2',
            'class' => 'border-emerald-200 bg-emerald-50 text-emerald-700',
        ],
        'default' => [
            'label' => 'Requirement',
            'icon' => 'file-text',
            'class' => 'border-amber-200 bg-amber-50 text-amber-700',
        ],
    ];

    $statusConfig = [
        'submitted' => [
            'label' => 'Submitted',
            'icon' => 'send',
            'class' => 'border-blue-200 bg-blue-50 text-blue-700',
        ],
        'submitted_to_sacdev' => [
            'label' => 'Submitted to SACDEV',
            'icon' => 'send',
            'class' => 'border-indigo-200 bg-indigo-50 text-indigo-700',
        ],
        'forwarded_to_sacdev' => [
            'label' => 'Forwarded to SACDEV',
            'icon' => 'forward',
            'class' => 'border-violet-200 bg-violet-50 text-violet-700',
        ],
        'edit_requested' => [
            'label' => 'Edit Requested',
            'icon' => 'message-square-warning',
            'class' => 'border-amber-200 bg-amber-50 text-amber-700',
        ],
        'clearance_uploaded' => [
            'label' => 'Clearance Uploaded',
            'icon' => 'shield-alert',
            'class' => 'border-purple-200 bg-purple-50 text-purple-700',
        ],
        'ready_for_completion' => [
            'label' => 'Ready for Completion',
            'icon' => 'check-circle-2',
            'class' => 'border-emerald-200 bg-emerald-50 text-emerald-700',
        ],
        'default' => [
            'label' => 'Needs Review',
            'icon' => 'clock',
            'class' => 'border-slate-200 bg-slate-50 text-slate-700',
        ],
    ];
@endphp

<div class="space-y-3">

    @forelse($normalProjectApprovals as $task)

        @php
            $isCompletion = (bool) ($task->is_completion ?? false);
            $forms = collect($task->forms ?? []);

            $firstForm = $forms->first();
            $firstPhase = $firstForm['phase'] ?? 'default';
            $firstCode = $firstForm['code'] ?? null;

            $phaseData = $phaseConfig[$firstPhase] ?? $phaseConfig['default'];

            $status = $task->status ?? null;
            $statusData = $statusConfig[$status] ?? $statusConfig['default'];

            if ($isCompletion) {
                $statusData = $statusConfig['ready_for_completion'];
            }

            if ($firstCode === 'CLEARANCE_REVIEW') {
                $statusData = $statusConfig['clearance_uploaded'];
            }

            $project = $task->project ?? null;
            $organization = $task->organization ?? $project?->organization ?? null;

            $date = $project?->implementation_start_date
                ? \Carbon\Carbon::parse($project->implementation_start_date)->format('M d, Y')
                : null;

            $cardTheme = $isCompletion
                ? 'border-emerald-200 bg-emerald-50/70 hover:bg-emerald-50'
                : ($firstCode === 'CLEARANCE_REVIEW'
                    ? 'border-purple-200 bg-purple-50/60 hover:bg-purple-50'
                    : ($status === 'edit_requested'
                        ? 'border-amber-200 bg-amber-50/60 hover:bg-amber-50'
                        : 'border-slate-200 bg-white hover:bg-indigo-50/30 hover:border-indigo-200'));

            $mainUrl = $task->form_route ?? $task->route ?? '#';
        @endphp

        <a href="{{ $mainUrl }}"
           class="block rounded-2xl border {{ $cardTheme }} p-4 shadow-sm transition">

            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">

                <div class="flex items-start gap-3 min-w-0">

                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border {{ $phaseData['class'] }}">
                        <i data-lucide="{{ $phaseData['icon'] }}" class="w-5 h-5"></i>
                    </div>

                    <div class="min-w-0">

                        <div class="flex flex-wrap items-center gap-2">
                            <div class="text-sm font-semibold text-slate-900 truncate">
                                {{ $project->title ?? 'Untitled Project' }}
                            </div>

                            @if($task->count > 1)
                                <span class="inline-flex items-center rounded-full border border-slate-200 bg-white px-2 py-0.5 text-[10px] font-semibold text-slate-600">
                                    {{ $task->count }} items
                                </span>
                            @endif
                        </div>

                        <div class="mt-1 flex flex-wrap items-center gap-2 text-xs text-slate-500">
                            <span class="inline-flex items-center gap-1">
                                <i data-lucide="building-2" class="w-3 h-3"></i>
                                {{ $organization->name ?? 'Organization' }}
                            </span>

                            @if($date)
                                <span class="text-slate-300">•</span>

                                <span class="inline-flex items-center gap-1">
                                    <i data-lucide="calendar" class="w-3 h-3"></i>
                                    {{ $date }}
                                </span>
                            @endif
                        </div>

                        <div class="mt-3 flex flex-wrap gap-2">
                            @foreach($forms as $form)
                                @php
                                    $phase = $form['phase'] ?? 'default';
                                    $formPhaseData = $phaseConfig[$phase] ?? $phaseConfig['default'];
                                @endphp

                                <span class="inline-flex max-w-full items-center gap-1.5 rounded-full border px-2.5 py-1 text-[11px] font-semibold {{ $formPhaseData['class'] }}">
                                    <i data-lucide="{{ $formPhaseData['icon'] }}" class="w-3 h-3 shrink-0"></i>
                                    <span class="truncate">
                                        {{ $form['name'] ?? 'Form' }}
                                    </span>
                                </span>
                            @endforeach

                            <span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-[11px] font-semibold {{ $statusData['class'] }}">
                                <i data-lucide="{{ $statusData['icon'] }}" class="w-3 h-3"></i>
                                {{ $statusData['label'] }}
                            </span>
                        </div>

                        @if(!empty($task->edit_request_remarks))
                            <div class="mt-3 rounded-xl border border-amber-200 bg-white/80 px-3 py-2 text-xs leading-5 text-amber-800">
                                <div class="mb-1 flex items-center gap-1.5 font-semibold">
                                    <i data-lucide="message-square-warning" class="w-3.5 h-3.5"></i>
                                    Edit request remarks
                                </div>

                                <div class="whitespace-pre-line">
                                    {{ $task->edit_request_remarks }}
                                </div>
                            </div>
                        @endif

                    </div>

                </div>

                <div class="flex shrink-0 items-center justify-between gap-3 lg:block lg:text-right">

                    <div>
                        <div class="text-[11px] font-medium text-slate-500">
                            Workflow Action
                        </div>

                        <div class="mt-0.5 text-[10px] text-slate-400">
                            {{ $isCompletion ? 'Project lifecycle' : 'Review queue' }}
                        </div>
                    </div>

                    <div class="lg:mt-3">
                        <span class="inline-flex items-center justify-center gap-1.5 rounded-xl bg-indigo-600 px-3 py-2 text-xs font-semibold text-white transition">
                            {{ $isCompletion ? 'Open Project' : 'Review' }}
                            <i data-lucide="arrow-right" class="w-3 h-3"></i>
                        </span>
                    </div>

                </div>

            </div>

        </a>

    @empty

        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-8 text-center">
            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-400">
                <i data-lucide="inbox" class="w-6 h-6"></i>
            </div>

            <div class="mt-3 text-sm font-semibold text-slate-800">
                No project workflow items
            </div>

            <div class="mt-1 text-xs text-slate-500">
                Project approvals, edit requests, clearance reviews, and completion-ready projects will appear here.
            </div>
        </div>

    @endforelse

    @if($editRequestTasks->count())
        @php
            $firstEditTask = $editRequestTasks->first();
            $firstEditUrl = $firstEditTask->form_route ?? $firstEditTask->route ?? '#';

            $editProjectsCount = $editRequestTasks
                ->map(fn($task) => $task->project?->id)
                ->filter()
                ->unique()
                ->count();
        @endphp

        <a href="{{ $firstEditUrl }}"
        class="mt-3 block rounded-2xl border border-slate-200 bg-slate-50 p-4 shadow-sm transition hover:bg-slate-100">

            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">

                <div class="flex items-start gap-3 min-w-0">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500">
                        <i data-lucide="message-square-warning" class="w-5 h-5"></i>
                    </div>

                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            <div class="text-sm font-semibold text-slate-800">
                                {{ $editRequestTasks->count() }} request-to-edit {{ $editRequestTasks->count() > 1 ? 'items' : 'item' }} pending
                            </div>

                            <span class="inline-flex items-center rounded-full border border-slate-200 bg-white px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-slate-500">
                                Low Priority
                            </span>
                        </div>

                        <p class="mt-1 text-xs leading-5 text-slate-500">
                            These approved documents have edit requests. They are grouped here so they do not crowd the main review queue.
                        </p>

                        <div class="mt-3 flex flex-wrap gap-2">
                            <span class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 bg-white px-2.5 py-1 text-[11px] font-semibold text-slate-600">
                                <i data-lucide="folder-kanban" class="w-3 h-3"></i>
                                {{ $editProjectsCount }} {{ $editProjectsCount > 1 ? 'projects' : 'project' }}
                            </span>

                            @foreach($editRequestTasks->take(5) as $task)
                                @php
                                    $forms = collect($task->forms ?? []);
                                    $firstForm = $forms->first();
                                @endphp

                                <span class="inline-flex max-w-full items-center gap-1.5 rounded-full border border-slate-200 bg-white px-2.5 py-1 text-[11px] font-semibold text-slate-600">
                                    <i data-lucide="file-pen-line" class="w-3 h-3 shrink-0"></i>
                                    <span class="truncate">
                                        {{ $task->project->title ?? 'Project' }}
                                        @if($firstForm)
                                            — {{ $firstForm['name'] ?? 'Edit Request' }}
                                        @endif
                                    </span>
                                </span>
                            @endforeach

                            @if($editRequestTasks->count() > 5)
                                <span class="inline-flex items-center rounded-full border border-slate-200 bg-white px-2.5 py-1 text-[11px] font-semibold text-slate-500">
                                    + {{ $editRequestTasks->count() - 5 }} more
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="flex shrink-0 items-center justify-between gap-3 lg:block lg:text-right">
                    <div>
                        <div class="text-[11px] font-medium text-slate-500">
                            Edit Requests
                        </div>

                        <div class="mt-0.5 text-[10px] text-slate-400">
                            Grouped queue
                        </div>
                    </div>

                    <div class="lg:mt-3">
                        <span class="inline-flex items-center justify-center gap-1.5 rounded-xl border border-slate-300 bg-white px-3 py-2 text-xs font-semibold text-slate-700 transition">
                            Open One
                            <i data-lucide="arrow-right" class="w-3 h-3"></i>
                        </span>
                    </div>
                </div>

            </div>
        </a>
    @endif

</div>