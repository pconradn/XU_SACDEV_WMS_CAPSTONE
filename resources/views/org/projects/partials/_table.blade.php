<div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm relative">


    <div id="scrollHint"
         class="absolute top-2 right-4 z-20 text-[10px] text-slate-400 flex items-center gap-1 pointer-events-none">
        <span>Scroll</span>
        <span>→</span>
    </div>

  
    <div id="leftFade"
         class="pointer-events-none absolute left-0 top-0 h-full w-6 bg-gradient-to-r from-white to-transparent opacity-0 transition"></div>

  
    <div id="rightFade"
         class="pointer-events-none absolute right-0 top-0 h-full w-6 bg-gradient-to-l from-white to-transparent opacity-100 transition"></div>


   
    <div id="tableScroll" class="overflow-x-auto">

        <div class="scale-[0.88] lg:scale-[0.92] origin-top-left w-[112%]">

            <table class="min-w-[820px] w-full text-[11px] leading-tight">

                {{-- HEADER --}}
                <thead class="bg-white border-b border-slate-200">
                    <tr class="text-left text-[9px] uppercase tracking-wide text-slate-500 whitespace-nowrap">
                        <th class="px-3 py-2">Project</th>
                        <th class="px-3 py-2 w-[180px]">Head</th>
                        <th class="px-3 py-2 w-[150px]">Docs</th>
                        <th class="px-3 py-2 w-[100px]">Workflow</th>
                        @if($isPresident)
                            <th class="px-3 py-2 w-[100px] text-right">Actions</th>
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

                    <tr class="hover:bg-slate-50 transition cursor-pointer whitespace-nowrap">

                        {{-- PROJECT --}}
                        <td class="px-3 py-2 min-w-[180px]">
                            <div class="flex flex-col gap-0.5">
                                <div class="font-semibold text-slate-900 truncate text-[11px] max-w-[180px]">
                                    {{ $p->title }}
                                </div>

                                <div class="text-[9px] text-slate-500">
                                    @if($p->implementation_start_date)
                                        {{ \Carbon\Carbon::parse($p->implementation_start_date)->format('M d, Y') }}
                                    @else
                                        No date
                                    @endif
                                </div>
                            </div>
                        </td>

                        {{-- HEAD --}}
                        <td class="px-3 py-2 min-w-[180px]">

                            @if($head && $head->officerEntry)
                                <div class="flex flex-col gap-0.5">
                                    <span class="text-[11px] font-medium text-slate-900 truncate">
                                        {{ $head->officerEntry->full_name }}
                                    </span>

                                    <span class="text-[9px] text-slate-500 truncate">
                                        {{ $head->officerEntry->email }}
                                    </span>

                                    <span class="inline-flex w-fit rounded-full bg-emerald-50 px-1.5 py-0.5 text-[9px] font-semibold text-emerald-700">
                                        Assigned
                                    </span>
                                </div>
                            @else
                                <span class="text-[9px] text-slate-500">Not assigned</span>
                            @endif

                        </td>

                        {{-- DOCS --}}
                        <td class="px-3 py-2 min-w-[150px]">
                            <div class="flex flex-col gap-1">

                                <a href="{{ route('org.projects.documents.hub', $p) }}"
                                class="inline-flex items-center justify-center rounded-md border border-blue-200 bg-blue-50 px-2 py-1 text-[10px] font-semibold text-blue-700 hover:bg-blue-100 transition">
                                    Manage
                                </a>

                                <span class="text-[9px] text-slate-500">
                                    {{ $p->documents_count }} doc(s)
                                </span>

                                @if(($p->pending_approvals ?? 0) > 0)
                                    <span class="text-[9px] font-semibold text-rose-600">
                                        ● {{ $p->pending_approvals }}
                                    </span>
                                @endif

                            </div>
                        </td>

                        {{-- WORKFLOW --}}
                        <td class="px-3 py-2 min-w-[100px]">
                            <span class="inline-flex text-[9px] font-semibold px-2 py-0.5 rounded-md
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
                        <td class="px-3 py-2 text-right min-w-[100px]">
                            <div class="flex justify-end gap-1">

                                <button
                                    @click='selectedProject = @json($p); openEditModal = true'
                                    class="text-[10px] px-2 py-1 rounded-md border border-slate-300 hover:bg-slate-50">
                                    Edit
                                </button>

                                <button
                                    @click="selectedProject = { id: {{ $p->id }} }; openAssignHeadModal = true"
                                    class="text-[10px] px-2 py-1 rounded-md bg-slate-800 text-white hover:bg-slate-700">
                                    Assign
                                </button>

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



    
</div>


{{-- ========================= --}}
{{-- SCROLL INDICATOR SCRIPT --}}
{{-- ========================= --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const container = document.getElementById('tableScroll');
    const leftFade = document.getElementById('leftFade');
    const rightFade = document.getElementById('rightFade');
    const hint = document.getElementById('scrollHint');

    function updateScrollIndicators() {
        const scrollLeft = container.scrollLeft;
        const maxScroll = container.scrollWidth - container.clientWidth;

        // Left fade
        leftFade.style.opacity = scrollLeft > 5 ? '1' : '0';

        // Right fade
        rightFade.style.opacity = scrollLeft < maxScroll - 5 ? '1' : '0';

        // Hint disappears after scrolling
        if (scrollLeft > 20) {
            hint.style.opacity = '0';
        }
    }

    container.addEventListener('scroll', updateScrollIndicators);

    // Initial check
    updateScrollIndicators();
});
</script>