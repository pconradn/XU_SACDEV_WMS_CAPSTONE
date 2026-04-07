<x-app-layout>

<div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm p-5">

    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

        {{-- LEFT --}}
        <div>
            <h2 class="text-lg font-semibold text-slate-900">
                {{ $organization->name }} — Projects
            </h2>

            <p class="text-xs text-slate-500 mt-1">
                School Year: {{ $schoolYear->name }}
            </p>
        </div>

        {{-- RIGHT ACTION --}}
        <div class="flex items-center gap-2">

            <a href="{{ route('admin.orgs_by_sy.show', [$organization->id, $schoolYear->id]) }}"
               class="inline-flex items-center gap-1 rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition">

                ← Back

            </a>

        </div>

    </div>

</div>


<div class="py-8">
<div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">

    {{-- ================= CONTAINER ================= --}}
    <div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm">

        <div class="px-6 py-4 border-b border-slate-200">
            <div class="text-sm font-semibold text-slate-800">
                Project List
            </div>
            <div class="text-xs text-slate-500">
                View and manage project documents and workflow status.
            </div>
        </div>

        {{-- ================= TABLE ================= --}}
        <div class="overflow-x-auto">

        <table class="min-w-[800px] w-full text-sm">

            {{-- HEADER --}}
            <thead class="bg-white border-b border-slate-200">
                <tr class="text-left text-[11px] uppercase tracking-wide text-slate-500 whitespace-nowrap">
                    <th class="px-6 py-4">Project</th>
                    <th class="px-6 py-4 w-[180px]">Workflow</th>
                    <th class="px-6 py-4 w-[220px]">Documents</th>
                </tr>
            </thead>

            {{-- BODY --}}
            <tbody class="divide-y divide-slate-200 bg-white">

            @forelse ($projects as $p)

                @php
                    $workflow = $p->workflow_status ?? 'draft';

                    $workflowMap = [
                        'draft' => ['label' => 'Draft', 'color' => 'slate'],
                        'pending' => ['label' => 'Pending', 'color' => 'amber'],
                        'approved' => ['label' => 'Approved', 'color' => 'emerald'],
                        'completed' => ['label' => 'Completed', 'color' => 'emerald'],
                        'cancelled' => ['label' => 'Cancelled', 'color' => 'rose'],
                    ];

                    $wf = $workflowMap[$workflow] ?? ['label' => ucfirst($workflow), 'color' => 'slate'];
                @endphp

                <tr class="hover:bg-slate-50 transition whitespace-nowrap">

                    {{-- PROJECT --}}
                    <td class="px-6 py-5 min-w-[260px]">

                        <div class="flex flex-col gap-1">

                            <div class="font-semibold text-slate-900 truncate max-w-[260px]">
                                {{ $p->title }}
                            </div>

                            <div class="text-xs text-slate-500">
                                @if($p->target_date)
                                    Target: {{ \Carbon\Carbon::parse($p->target_date)->format('M d, Y') }}
                                @else
                                    No target date set
                                @endif
                            </div>

                        </div>

                    </td>


                    {{-- WORKFLOW --}}
                    <td class="px-6 py-5 min-w-[180px]">

                        <span class="inline-flex items-center gap-1 text-xs font-semibold px-2 py-1 rounded-md
                            @if($wf['color'] === 'emerald') bg-emerald-50 text-emerald-700
                            @elseif($wf['color'] === 'amber') bg-amber-50 text-amber-700
                            @elseif($wf['color'] === 'rose') bg-rose-50 text-rose-700
                            @else bg-slate-100 text-slate-600
                            @endif
                        ">
                            ● {{ $wf['label'] }}
                        </span>

                        <div class="text-[10px] text-slate-400 mt-1">
                            {{ ucfirst(str_replace('_', ' ', $workflow)) }}
                        </div>

                    </td>


                    {{-- DOCUMENTS --}}
                    <td class="px-6 py-5 min-w-[220px]">

                        <div class="flex flex-col gap-2">

                            <a href="{{ route('admin.projects.documents.hub', $p) }}"
                               class="inline-flex items-center justify-center rounded-lg border border-blue-200 bg-blue-50 px-3 py-2 text-xs font-semibold text-blue-700 hover:bg-blue-100 transition">

                                View Documents

                            </a>

                            <div class="text-[10px] text-slate-400">
                                Open project document hub
                            </div>

                        </div>

                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="3"
                        class="px-6 py-12 text-center text-slate-500 text-sm">
                        No projects found for this organization and school year.
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