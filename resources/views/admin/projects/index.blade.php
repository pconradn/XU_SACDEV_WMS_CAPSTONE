<x-app-layout>

<div class="bg-slate-50 py-6">
<div class="max-w-7xl mx-auto px-4 space-y-6">

    {{-- HEADER --}}
    <div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm">

        <div class="px-5 py-5 flex items-center justify-between">

            <div>
                <div class="text-xs uppercase tracking-wide text-slate-500 mb-1">
                    Projects
                </div>

                <h1 class="text-lg font-semibold text-slate-900">
                    {{ $organization->name }}
                </h1>

                <div class="text-[11px] text-slate-500 mt-1">
                    {{ $schoolYear->name }}
                </div>
            </div>

            <a href="{{ route('admin.orgs_by_sy.show', [$organization->id, $schoolYear->id]) }}"
               class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 transition">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Back
            </a>

        </div>

    </div>


    {{-- TABLE --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

        {{-- TABLE HEADER --}}
        <div class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">

            <div>
                <div class="text-sm font-semibold text-slate-900">
                    Project Tracker
                </div>
                <div class="text-xs text-slate-500">
                    Implementation, ownership, document progress, and budget
                </div>
            </div>

            <span class="text-[10px] px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 border">
                {{ $projects->count() }}
            </span>

        </div>


        <div class="overflow-x-auto">

        <table class="min-w-[1100px] w-full text-sm">

            {{-- HEADER --}}
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


            {{-- BODY --}}
            <tbody class="divide-y divide-slate-100 bg-white">

            @forelse ($projects as $p)

                @php
                    $projectHead = $p->assignments
                        ->first(fn($a) =>
                            ($a->role === 'project_head' || $a->assignment_role === 'project_head')
                            && is_null($a->archived_at)
                        )?->user?->name;

                    $docs = $p->documents->where('is_active', 1);
                    $approvedDocs = $docs->where('status', 'approved_by_sacdev')->count();
                    $totalDocs = $docs->count();

                    $proposalDoc = $p->documents
                        ->first(fn($d) =>
                            $d->formType?->code === 'PROJECT_PROPOSAL'
                            && $d->is_active
                        );

                    $proposalBudget = optional($proposalDoc?->proposalData)->total_budget;
                @endphp

                <tr class="hover:bg-slate-50 transition">

                    {{-- PROJECT --}}
                    <td class="px-5 py-4">

                        <div class="space-y-1 max-w-[260px]">

                            <div class="font-semibold text-slate-900 truncate">
                                {{ $p->title }}
                            </div>

                            @php
                                $workflowMap = [
                                    'planning' => ['label' => 'Planning', 'class' => 'bg-slate-100 text-slate-700'],
                                    'drafting' => ['label' => 'Drafting', 'class' => 'bg-slate-100 text-slate-700'],
                                    'pre_implementation' => ['label' => 'Pre-Implementation', 'class' => 'bg-blue-100 text-blue-700'],
                                    'post_implementation' => ['label' => 'Post-Implementation', 'class' => 'bg-indigo-100 text-indigo-700'],
                                    'postponed' => ['label' => 'Postponed', 'class' => 'bg-amber-100 text-amber-700'],
                                    'cancelled' => ['label' => 'Cancelled', 'class' => 'bg-rose-100 text-rose-700'],
                                    'completed' => ['label' => 'Completed', 'class' => 'bg-emerald-100 text-emerald-700'],
                                ];

                                $wf = $workflowMap[$p->workflow_status] ?? [
                                    'label' => ucfirst(str_replace('_',' ', $p->workflow_status ?? '—')),
                                    'class' => 'bg-slate-100 text-slate-600'
                                ];
                            @endphp

                            <div class="mt-1">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 text-[10px] font-semibold rounded-full {{ $wf['class'] }}">
                                    ● {{ $wf['label'] }}
                                </span>
                            </div>

                        </div>

                    </td>


                    {{-- IMPLEMENTATION --}}
                    <td class="px-5 py-4 text-[11px] text-slate-600">

                        @if($p->implementation_start_date)
                            <div class="flex items-center gap-1">
                                <i data-lucide="calendar" class="w-3 h-3 text-slate-400"></i>
                                {{ \Carbon\Carbon::parse($p->implementation_start_date)->format('M d') }}
                                –
                                {{ \Carbon\Carbon::parse($p->implementation_end_date ?? $p->implementation_start_date)->format('M d, Y') }}
                            </div>
                        @else
                            —
                        @endif

                    </td>


                    {{-- PROJECT HEAD --}}
                    <td class="px-5 py-4 text-[11px] text-slate-700">

                        @if($projectHead)
                            <div class="flex items-center gap-1">
                                <i data-lucide="user" class="w-3 h-3 text-slate-400"></i>
                                {{ $projectHead }}
                            </div>
                        @else
                            <span class="text-slate-400">—</span>
                        @endif

                    </td>


                    {{-- PROGRESS --}}
                    <td class="px-5 py-4">

                        @if($totalDocs > 0)
                            <div class="space-y-1">

                                <div class="flex items-center justify-between text-[11px]">
                                    <span class="text-slate-600">
                                        {{ $approvedDocs }} / {{ $totalDocs }}
                                    </span>

                                    <span class="text-slate-400">
                                        {{ round(($approvedDocs / $totalDocs) * 100) }}%
                                    </span>
                                </div>

                                <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-emerald-400 to-emerald-500"
                                         style="width: {{ ($approvedDocs / $totalDocs) * 100 }}%">
                                    </div>
                                </div>

                            </div>
                        @else
                            <span class="text-slate-400 text-xs">No documents</span>
                        @endif

                    </td>


                    {{-- BUDGET --}}
                    <td class="px-5 py-4 text-[11px]">

                        @if($proposalBudget)
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full
                                         bg-emerald-50 text-emerald-700 border border-emerald-200 font-semibold">
                                ₱ {{ number_format($proposalBudget, 2) }}
                            </span>
                        @else
                            <span class="text-slate-400">—</span>
                        @endif

                    </td>


                    {{-- ACTION --}}
                    <td class="px-5 py-4 text-right">

                        <a href="{{ route('admin.projects.documents.hub', $p) }}"
                           class="inline-flex items-center gap-1 px-3 py-1.5 text-[11px] font-medium rounded-lg
                                  border border-slate-200 bg-white text-slate-700
                                  hover:bg-slate-100 transition">

                            Open
                            <i data-lucide="arrow-right" class="w-3 h-3"></i>

                        </a>

                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="6" class="px-5 py-12 text-center text-sm text-slate-500">
                        No projects found
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