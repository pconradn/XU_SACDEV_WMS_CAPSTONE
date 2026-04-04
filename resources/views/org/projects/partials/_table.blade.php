<div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm">

    {{-- 🔥 SCROLL CONTAINER --}}
    <div class="overflow-x-auto">

        <table class="min-w-[900px] w-full text-sm">

            {{-- ================= HEADER ================= --}}
            <thead class="bg-white border-b border-slate-200">
                <tr class="text-left text-[11px] uppercase tracking-wide text-slate-500 whitespace-nowrap">
                    <th class="px-6 py-4">Project</th>
                    <th class="px-6 py-4 w-[220px]">Project Head</th>
                    <th class="px-6 py-4 w-[200px]">Documents</th>
                    <th class="px-6 py-4 w-[180px]">Workflow</th>
                    @if($isPresident)
                        <th class="px-6 py-4 w-[260px] text-right">Actions</th>
                    @endif
                </tr>
            </thead>

            {{-- ================= BODY ================= --}}
            <tbody class="divide-y divide-slate-200 bg-white">

            @forelse ($projects as $p)

                @php
                    $head = $p->assignments
                        ->where('assignment_role', 'project_head')
                        ->first();

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

                <tr class="hover:bg-slate-50 transition cursor-pointer whitespace-nowrap">

                    {{-- PROJECT --}}
                    <td class="px-6 py-5 align-top min-w-[220px]">
                        <div class="flex flex-col gap-1">
                            <div class="font-semibold text-slate-900 truncate max-w-[220px]">
                                {{ $p->title }}
                            </div>

                            <div class="text-[11px] text-slate-500">
                                @if($p->implementation_start_date)
                                    Target: {{ \Carbon\Carbon::parse($p->implementation_start_date)->format('M d, Y') }}
                                @else
                                    No target date set
                                @endif
                            </div>
                        </div>
                    </td>

                    {{-- PROJECT HEAD --}}
                    <td class="px-6 py-5 align-top min-w-[220px]">

                        @if($head && $head->officerEntry)

                            <div class="flex flex-col gap-1">
                                <span class="text-sm font-medium text-slate-900 truncate">
                                    {{ $head->officerEntry->full_name }}
                                </span>

                                <span class="text-[11px] text-slate-500 truncate">
                                    {{ $head->officerEntry->email }}
                                </span>

                                <span class="inline-flex w-fit items-center rounded-full bg-emerald-50 px-2 py-0.5 text-[10px] font-semibold text-emerald-700 ring-1 ring-emerald-200">
                                    Assigned
                                </span>
                            </div>

                        @else

                            <span class="inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-[10px] text-slate-500">
                                Not assigned
                            </span>

                        @endif

                    </td>

                    {{-- DOCUMENTS --}}
                    <td class="px-6 py-5 align-top min-w-[200px]">
                        <div class="flex flex-col gap-2">

                            <a href="{{ route('org.projects.documents.hub', $p) }}"
                               class="inline-flex items-center justify-center rounded-lg border border-blue-200 bg-blue-50 px-3 py-2 text-xs font-semibold text-blue-700 hover:bg-blue-100 transition">
                                Manage
                            </a>

                            <span class="text-[11px] text-slate-500">
                                {{ $p->documents_count }} document(s)
                            </span>

                            @if(($p->pending_approvals ?? 0) > 0)
                                <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-rose-700 bg-rose-50 px-2 py-1 rounded-md w-fit">
                                    ● {{ $p->pending_approvals }} pending
                                </span>
                            @endif

                        </div>
                    </td>

                    {{-- WORKFLOW --}}
                    <td class="px-6 py-5 align-top min-w-[180px]">
                        <div class="flex flex-col gap-2">

                            <span class="inline-flex items-center gap-1 text-[11px] font-semibold px-2 py-1 rounded-md w-fit
                                @if($wf['color'] === 'emerald') bg-emerald-50 text-emerald-700
                                @elseif($wf['color'] === 'amber') bg-amber-50 text-amber-700
                                @elseif($wf['color'] === 'rose') bg-rose-50 text-rose-700
                                @else bg-slate-100 text-slate-600
                                @endif
                            ">
                                ● {{ $wf['label'] }}
                            </span>

                            <span class="text-[10px] text-slate-400">
                                {{ ucfirst(str_replace('_', ' ', $workflow)) }}
                            </span>

                        </div>
                    </td>

                    {{-- ACTIONS --}}
                    @if($isPresident)
                    <td class="px-6 py-5 text-right align-top min-w-[240px]">
                        <div class="flex flex-col items-end gap-2">

                            <div class="flex gap-2">
                                <button
                                    @click='selectedProject = @json($p); openEditModal = true'
                                    class="inline-flex items-center rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition">
                                    Edit
                                </button>

                                <button
                                    type="button"
                                    @click="selectedProject = { id: {{ $p->id }} }; openAssignHeadModal = true"
                                    class="inline-flex items-center rounded-lg bg-slate-800 px-3 py-2 text-xs font-semibold text-white hover:bg-slate-700 transition">
                                    Assign
                                </button>
                            </div>

                        </div>
                    </td>
                    @endif

                </tr>

            @empty

                <tr>
                    <td colspan="{{ $isPresident ? 5 : 4 }}"
                        class="px-6 py-12 text-center text-slate-500 text-sm">
                        No projects created yet.
                    </td>
                </tr>

            @endforelse

            </tbody>
        </table>

    </div>
</div>