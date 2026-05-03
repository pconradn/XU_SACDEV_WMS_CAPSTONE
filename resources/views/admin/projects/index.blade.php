<x-app-layout>

<div class="min-h-screen bg-slate-50 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <nav class="text-xs text-slate-500">
            <ol class="flex items-center gap-1.5">
                <li>
                    <a href="{{ route('admin.orgs_by_sy.index') }}"
                    class="font-medium text-slate-600 hover:text-slate-900 transition">
                        Organizations by School Year
                    </a>
                </li>

                <li class="text-slate-300">/</li>

                <li>
                    <a href="{{ route('admin.orgs_by_sy.show', [$organization->id, $schoolYear->id]) }}"
                    class="font-medium text-slate-600 hover:text-slate-900 transition">
                        {{ $organization->acronym ?: $organization->name }}
                    </a>
                </li>

                <li class="text-slate-300">/</li>

                <li class="text-slate-400">
                    {{ $schoolYear->name }}
                </li>

                <li class="text-slate-300">/</li>

                <li class="font-medium text-indigo-700">
                    Projects
                </li>
            </ol>
        </nav>

        <div class="rounded-2xl border border-indigo-200 bg-gradient-to-br from-indigo-50 via-white to-blue-50 shadow-sm overflow-hidden">

            <div class="p-6 flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

                <div class="flex items-start gap-4">
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-indigo-100 text-indigo-700 ring-1 ring-indigo-200 shadow-sm">
                        <i data-lucide="folder-kanban" class="w-7 h-7"></i>
                    </div>

                    <div>
                        <div class="flex flex-wrap items-center gap-2 mb-2">
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-indigo-100 px-2.5 py-1 text-[11px] font-semibold text-indigo-700">
                                <i data-lucide="shield-check" class="w-3 h-3"></i>
                                Admin Monitoring
                            </span>

                            <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-100 px-2.5 py-1 text-[11px] font-semibold text-blue-700">
                                <i data-lucide="calendar-range" class="w-3 h-3"></i>
                                {{ $schoolYear->name }}
                            </span>
                        </div>

                        <h1 class="text-2xl font-semibold text-slate-900 leading-tight">
                            Projects Module
                        </h1>

                        <p class="mt-1 text-sm leading-6 text-slate-600 max-w-3xl">
                            Monitor {{ $organization->name }} projects, implementation schedules, project heads, document approval progress, and budget details.
                        </p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 sm:items-center">

                    <div class="rounded-2xl border border-indigo-200 bg-white/80 px-4 py-3 text-center shadow-sm">
                        <div class="text-2xl font-semibold text-indigo-800">
                            {{ $projects->count() }}
                        </div>
                        <div class="text-[10px] uppercase tracking-wide text-indigo-600">
                            Total Projects
                        </div>
                    </div>

                    <a href="{{ route('admin.orgs_by_sy.show', [$organization->id, $schoolYear->id]) }}"
                       class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm hover:bg-slate-50 transition">
                        <i data-lucide="arrow-left" class="w-4 h-4"></i>
                        Back to Organization
                    </a>

                </div>

            </div>

        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            @php
                $totalProjects = $projects->count();

                $completedProjects = $projects->filter(function ($p) {
                    return $p->workflow_status === 'completed';
                })->count();

                $activeProjects = $projects->filter(function ($p) {
                    return !in_array($p->workflow_status, ['completed', 'cancelled']);
                })->count();

                $totalDocuments = $projects->sum(function ($p) {
                    return $p->documents->where('is_active', 1)->count();
                });
            @endphp

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <div class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Active Projects
                        </div>
                        <div class="mt-2 text-2xl font-semibold text-slate-900">
                            {{ $activeProjects }}
                        </div>
                    </div>

                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                        <i data-lucide="activity" class="w-5 h-5"></i>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <div class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Completed
                        </div>
                        <div class="mt-2 text-2xl font-semibold text-slate-900">
                            {{ $completedProjects }}
                        </div>
                    </div>

                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                        <i data-lucide="check-circle-2" class="w-5 h-5"></i>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <div class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Active Documents
                        </div>
                        <div class="mt-2 text-2xl font-semibold text-slate-900">
                            {{ $totalDocuments }}
                        </div>
                    </div>

                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                        <i data-lucide="file-check-2" class="w-5 h-5"></i>
                    </div>
                </div>
            </div>

        </div>

        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

            <div class="px-5 py-4 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

                <div>
                    <div class="flex items-center gap-2 text-sm font-semibold text-slate-900">
                        <i data-lucide="folder-kanban" class="w-4 h-4 text-indigo-600"></i>
                        Project Tracker
                    </div>

                    <div class="mt-1 text-xs text-slate-500">
                        Implementation, ownership, document progress, and budget monitoring
                    </div>
                </div>

                <span class="inline-flex w-fit items-center gap-1.5 rounded-full border border-indigo-200 bg-indigo-50 px-2.5 py-1 text-[11px] font-semibold text-indigo-700">
                    <i data-lucide="list-checks" class="w-3 h-3"></i>
                    {{ $projects->count() }} Projects
                </span>

            </div>

            <div class="overflow-x-auto">

                <table class="min-w-[1100px] w-full text-sm">

                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr class="text-left text-[11px] uppercase tracking-wide text-slate-500">
                            <th class="px-5 py-3">Project</th>
                            <th class="px-5 py-3">Implementation</th>
                            <th class="px-5 py-3">Project Head</th>
                            <th class="px-5 py-3">Progress</th>
                            <th class="px-5 py-3">Budget</th>
                            <th class="px-5 py-3 text-right">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100 bg-white">

                    @forelse ($projects as $p)

                        @php
                            $projectHead = $p->assignments
                                ->first(fn($a) =>
                                    ($a->role === 'project_head' || $a->assignment_role === 'project_head')
                                    && is_null($a->archived_at)
                                )?->user?->name;

                                $resolver = app(\App\Services\ProjectFormRequirementResolver::class);

                                $p->loadMissing('documents.formType');

                                $docs = $p->documents
                                    ->filter(fn($doc) => (int) ($doc->is_active ?? 0) === 1)
                                    ->values();

                                $documentsByType = $docs->groupBy('form_type_id');

                                $requiredFormTypes = collect($resolver->resolve($p))
                                    ->filter()
                                    ->values();

                                $totalDocs = $requiredFormTypes->count();

                                $approvedDocs = $requiredFormTypes->filter(function ($formType) use ($documentsByType) {
                                    $doc = $documentsByType->get($formType->id)?->first();

                                    return $doc && $doc->status === 'approved_by_sacdev';
                                })->count();

                                $progressPercent = $totalDocs > 0
                                    ? round(($approvedDocs / $totalDocs) * 100)
                                    : 0;

                            $proposalDoc = $p->documents
                                ->first(fn($d) =>
                                    $d->formType?->code === 'PROJECT_PROPOSAL'
                                    && $d->is_active
                                );

                            $proposalBudget = optional($proposalDoc?->proposalData)->total_budget;

                            $workflowMap = [
                                'planning' => [
                                    'label' => 'Planning',
                                    'class' => 'bg-slate-100 text-slate-700 border-slate-200',
                                    'dot' => 'bg-slate-400',
                                ],
                                'drafting' => [
                                    'label' => 'Drafting',
                                    'class' => 'bg-slate-100 text-slate-700 border-slate-200',
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

                            $wf = $workflowMap[$p->workflow_status] ?? [
                                'label' => ucfirst(str_replace('_',' ', $p->workflow_status ?? '—')),
                                'class' => 'bg-slate-100 text-slate-600 border-slate-200',
                                'dot' => 'bg-slate-400',
                            ];
                        @endphp

                        <tr class="transition hover:bg-indigo-50/30">

                            <td class="px-5 py-4">
                                <div class="space-y-2 max-w-[300px]">

                                    <div class="flex items-start gap-2">
                                        <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                                            <i data-lucide="folder-kanban" class="w-4 h-4"></i>
                                        </div>

                                        <div class="min-w-0">
                                            <div class="font-semibold text-slate-900 truncate">
                                                {{ $p->title }}
                                            </div>

                                            <div class="mt-1">
                                                <span class="inline-flex items-center gap-1.5 rounded-full border px-2 py-0.5 text-[10px] font-semibold {{ $wf['class'] }}">
                                                    <span class="h-1.5 w-1.5 rounded-full {{ $wf['dot'] }}"></span>
                                                    {{ $wf['label'] }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </td>

                            <td class="px-5 py-4 text-[11px] text-slate-600">
                                @if($p->implementation_start_date)
                                    <div class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 bg-white px-2.5 py-1 text-slate-700">
                                        <i data-lucide="calendar" class="w-3 h-3 text-indigo-500"></i>
                                        {{ \Carbon\Carbon::parse($p->implementation_start_date)->format('M d') }}
                                        –
                                        {{ \Carbon\Carbon::parse($p->implementation_end_date ?? $p->implementation_start_date)->format('M d, Y') }}
                                    </div>
                                @else
                                    <span class="text-slate-400">—</span>
                                @endif
                            </td>

                            <td class="px-5 py-4 text-[11px] text-slate-700">
                                @if($projectHead)
                                    <div class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 bg-white px-2.5 py-1">
                                        <i data-lucide="user-round-check" class="w-3 h-3 text-indigo-500"></i>
                                        {{ $projectHead }}
                                    </div>
                                @else
                                    <span class="inline-flex items-center gap-1.5 rounded-full border border-amber-200 bg-amber-50 px-2.5 py-1 text-amber-700">
                                        <i data-lucide="circle-alert" class="w-3 h-3"></i>
                                        No Project Head
                                    </span>
                                @endif
                            </td>

                            <td class="px-5 py-4">
                                @if($totalDocs > 0)
                                    <div class="space-y-1.5 min-w-[160px]">

                                        <div class="flex items-center justify-between text-[11px]">
                                            <span class="font-medium text-slate-600">
                                                {{ $approvedDocs }} / {{ $totalDocs }} required approved
                                            </span>

                                            <span class="font-semibold text-indigo-700">
                                                {{ $progressPercent }}%
                                            </span>
                                        </div>

                                        <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                                            <div class="h-full rounded-full bg-gradient-to-r from-indigo-500 to-blue-500"
                                                 style="width: {{ $progressPercent }}%">
                                            </div>
                                        </div>

                                    </div>
                                @else
                                    <span class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 bg-slate-50 px-2.5 py-1 text-[11px] text-slate-500">
                                        <i data-lucide="file-x-2" class="w-3 h-3"></i>
                                        No documents
                                    </span>
                                @endif
                            </td>

                            <td class="px-5 py-4 text-[11px]">
                                @if($proposalBudget)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 font-semibold">
                                        <i data-lucide="philippine-peso" class="w-3 h-3"></i>
                                        {{ number_format($proposalBudget, 2) }}
                                    </span>
                                @else
                                    <span class="text-slate-400">—</span>
                                @endif
                            </td>

                            <td class="px-5 py-4 text-right">
                                <a href="{{ route('admin.projects.documents.hub', $p) }}"
                                   class="inline-flex items-center gap-1.5 rounded-xl border border-indigo-200 bg-indigo-50 px-3 py-2 text-[11px] font-semibold text-indigo-700 transition hover:bg-indigo-100">
                                    Open
                                    <i data-lucide="arrow-right" class="w-3 h-3"></i>
                                </a>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" class="px-5 py-16 text-center">
                                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                                    <i data-lucide="folder-kanban" class="w-7 h-7"></i>
                                </div>

                                <div class="mt-4 text-sm font-semibold text-slate-700">
                                    No projects found
                                </div>

                                <div class="mt-1 text-xs text-slate-500">
                                    This organization has no projects recorded for this school year.
                                </div>
                            </td>
                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>
</div>

</x-app-layout>