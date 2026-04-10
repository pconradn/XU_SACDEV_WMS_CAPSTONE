<div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm">

    <div class="overflow-x-auto md:overflow-visible">

        <table class="w-full text-[12px] leading-snug table-auto">

            {{-- HEADER --}}
            <thead class="bg-white border-b border-slate-200">
                <tr class="text-left text-[10px] uppercase tracking-wide text-slate-500">
                    <th class="px-4 py-3 w-[35%]">Project</th>
                    <th class="px-4 py-3 w-[25%]">Head</th>
                    <th class="px-4 py-3 w-[15%]">Docs</th>
                    <th class="px-4 py-3 w-[15%]">Workflow</th>
                    @if($isPresident)
                        <th class="px-4 py-3 w-[10%] text-right">Actions</th>
                    @endif
                </tr>
            </thead>

            {{-- BODY --}}
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

                <tr class="hover:bg-slate-50 transition">

                    {{-- PROJECT --}}
                    <td class="px-4 py-3 align-top">
                        <div class="flex flex-col gap-1">

                            <!-- ✅ WRAPPED TITLE -->
                            <div class="font-semibold text-slate-900 break-words">
                                {{ $p->title }}
                            </div>

                            <div class="text-[10px] text-slate-500">
                                @if($p->implementation_start_date)
                                    {{ \Carbon\Carbon::parse($p->implementation_start_date)->format('M d, Y') }}
                                @else
                                    No date
                                @endif
                            </div>

                        </div>
                    </td>

                    {{-- HEAD --}}
                    <td class="px-4 py-3 align-top">
                        @if($head && $head->officerEntry)
                            <div class="flex flex-col gap-1">

                                <span class="font-medium text-slate-900 break-words">
                                    {{ $head->officerEntry->full_name }}
                                </span>

                                <span class="text-[10px] text-slate-500 break-words">
                                    {{ $head->officerEntry->email }}
                                </span>

                                <span class="inline-flex w-fit rounded-full bg-emerald-50 px-2 py-0.5 text-[10px] font-semibold text-emerald-700">
                                    Assigned
                                </span>

                            </div>
                        @else
                            <span class="text-[10px] text-slate-500">Not assigned</span>
                        @endif
                    </td>

                    {{-- DOCS --}}
                    <td class="px-4 py-3 align-top">
                        <div class="flex flex-col gap-2">

                            <a href="{{ route('org.projects.documents.hub', $p) }}"
                               class="inline-flex items-center justify-center rounded-md border border-blue-200 bg-blue-50 px-2 py-1 text-[11px] font-semibold text-blue-700 hover:bg-blue-100 transition">
                                Manage
                            </a>

                            <span class="text-[10px] text-slate-500">
                                {{ $p->documents_count }} doc(s)
                            </span>

                            @if(($p->pending_approvals ?? 0) > 0)
                                <span class="text-[10px] font-semibold text-rose-600">
                                    ● {{ $p->pending_approvals }}
                                </span>
                            @endif

                        </div>
                    </td>

                    {{-- WORKFLOW --}}
                    <td class="px-4 py-3 align-top">
                        <span class="inline-flex text-[10px] font-semibold px-2 py-1 rounded-md
                            @if($wf['color'] === 'emerald') bg-emerald-50 text-emerald-700
                            @elseif($wf['color'] === 'amber') bg-amber-50 text-amber-700
                            @elseif($wf['color'] === 'rose') bg-rose-50 text-rose-700
                            @else bg-slate-100 text-slate-600
                            @endif
                        ">
                            {{ $wf['label'] }}
                        </span>
                    </td>

                    {{-- ACTIONS --}}
                    @if($isPresident)
                    <td class="px-4 py-3 text-right align-top">
                        <div class="flex justify-end gap-2">

                            <button
                                @click='selectedProject = @json($p); openEditModal = true'
                                class="text-[11px] px-3 py-1 rounded-md border border-slate-300 hover:bg-slate-50">
                                Edit
                            </button>
                            {{--
                            <button
                                @click="selectedProject = { id: {{ $p->id }} }; openAssignHeadModal = true"
                                class="text-[11px] px-3 py-1 rounded-md bg-slate-800 text-white hover:bg-slate-700">
                                Assign
                            </button>
                            --}}
                        </div>
                    </td>
                    @endif

                </tr>

            @empty
                <tr>
                    <td colspan="{{ $isPresident ? 5 : 4 }}"
                        class="px-6 py-10 text-center text-slate-500 text-sm">
                        No projects created yet.
                    </td>
                </tr>
            @endforelse

            </tbody>
        </table>

    </div>

</div>