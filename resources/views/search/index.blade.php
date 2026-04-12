<x-app-layout>

<style>
    .sticky-col {
        position: sticky;
        left: 0;
        z-index: 10;
        background: white;
    }

    thead .sticky-col {
        z-index: 20;
        background: #f8fafc;
    }
</style>

<div class="bg-slate-50 py-6">
<div class="max-w-7xl mx-auto px-4 space-y-6">

    {{-- HEADER --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-5">

        <h1 class="text-lg font-semibold text-slate-900">
            Project Search
        </h1>

        @if($q)
            <p class="text-sm text-slate-500 mt-1">
                Results for 
                "<span class="font-medium text-slate-700">{{ $q }}</span>"
            </p>
        @endif

    </div>


    {{-- TABLE --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

        {{-- HEADER --}}
        <div class="px-5 py-4 border-b bg-slate-50 flex justify-between">

            <div>
                <div class="text-sm font-semibold text-slate-900">
                    Projects
                </div>
                <div class="text-xs text-slate-500">
                    Search across all projects
                </div>
            </div>

            <span class="text-xs bg-slate-100 px-2 py-0.5 rounded-full border">
                {{ $projects->total() }}
            </span>

        </div>


        <div class="overflow-x-auto">

        <table class="min-w-[1100px] w-full text-sm">

            {{-- HEADER --}}
            <thead class="bg-slate-50 border-b">
                <tr class="text-[11px] uppercase text-slate-500">

                    <th class="px-5 py-3 sticky-col">Project</th>
                    <th class="px-5 py-3">Implementation</th>
                    <th class="px-5 py-3">Project Head</th>
                    <th class="px-5 py-3">Progress</th>
                    <th class="px-5 py-3">Budget</th>
                    <th class="px-5 py-3">Workflow</th>

                </tr>
            </thead>


            {{-- BODY --}}
            <tbody class="divide-y">

            @forelse($projects as $p)

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

                    $budget = optional($proposalDoc?->proposalData)->total_budget;

                    $org = $p->organization;

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

                <tr class="hover:bg-slate-50 transition cursor-pointer"
                    onclick="window.location='{{ route('admin.projects.documents.hub', $p) }}'">

                    {{-- PROJECT (STICKY) --}}
                    <td class="px-5 py-4 sticky-col">

                        <div class="flex items-center gap-3">

                            {{-- LOGO --}}
                            @if($org?->logo_path)
                                <img src="{{ asset('storage/'.$org->logo_path) }}"
                                     class="w-8 h-8 rounded-lg object-cover border">
                            @else
                                <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-xs font-semibold">
                                    {{ strtoupper(substr($org->acronym ?? $org->name,0,2)) }}
                                </div>
                            @endif

                            <div class="min-w-0">
                                <div class="font-semibold text-slate-900 truncate">
                                    {{ $p->title }}
                                </div>
                                <div class="text-[11px] text-slate-400 truncate">
                                    {{ $org->name }}
                                </div>
                            </div>

                        </div>

                    </td>


                    {{-- IMPLEMENTATION --}}
                    <td class="px-5 py-4 text-xs text-slate-600">
                        @if($p->implementation_start_date)
                            {{ \Carbon\Carbon::parse($p->implementation_start_date)->format('M d, Y') }}
                        @else
                            —
                        @endif
                    </td>


                    {{-- PROJECT HEAD --}}
                    <td class="px-5 py-4 text-xs">
                        {{ $projectHead ?? '—' }}
                    </td>


                    {{-- PROGRESS --}}
                    <td class="px-5 py-4">

                        @if($totalDocs > 0)
                            <div class="w-28">

                                <div class="text-[10px] text-right text-slate-400">
                                    {{ round(($approvedDocs/$totalDocs)*100) }}%
                                </div>

                                <div class="h-1.5 bg-slate-100 rounded">
                                    <div class="h-full bg-emerald-500"
                                         style="width: {{ ($approvedDocs/$totalDocs)*100 }}%">
                                    </div>
                                </div>

                            </div>
                        @endif

                    </td>


                    {{-- BUDGET --}}
                    <td class="px-5 py-4 text-xs text-emerald-700 font-medium">
                        {{ $budget ? '₱ '.number_format($budget,2) : '—' }}
                    </td>


                    {{-- WORKFLOW --}}
                    <td class="px-5 py-4 text-xs">
                        <span class="px-2 py-0.5 rounded-full {{ $wf['class'] }}">
                            {{ $wf['label'] }}
                        </span>
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


        {{-- PAGINATION --}}
        <div class="px-5 py-4 border-t bg-slate-50">
            {{ $projects->links() }}
        </div>

    </div>

</div>
</div>

</x-app-layout>