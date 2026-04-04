<div class="grid grid-cols-1 lg:grid-cols-3 gap-3">

    {{-- =========================
        TOP: KPI GRID (PRIMARY)
    ========================== --}}
    <div class="lg:col-span-3 grid grid-cols-2 md:grid-cols-4 gap-2">

        {{-- REGISTERED --}}
        <div class="rounded-xl border border-emerald-200 bg-gradient-to-b from-emerald-50 to-white px-3 py-2">
            <div class="text-[10px] text-emerald-700 font-semibold">Registered Orgs</div>
            <div class="text-[10px] text-emerald-600">Activated this SY</div>
            <div class="text-sm font-bold text-emerald-900 mt-1">
                {{ $activatedOrgCount ?? 0 }}
            </div>
        </div>

        {{-- UPCOMING --}}
        <div class="rounded-xl border border-amber-200 bg-gradient-to-b from-amber-50 to-white px-3 py-2">
            <div class="text-[10px] text-amber-700 font-semibold">Upcoming Projects</div>
            <div class="text-[10px] text-amber-600">Next 30 days</div>
            <div class="text-sm font-bold text-amber-900 mt-1">
                {{ $upcomingProjectsCount ?? 0 }}
            </div>
        </div>

        <div class="rounded-xl border border-blue-200 bg-gradient-to-b from-blue-50 to-white px-3 py-2">
            <div class="text-[10px] text-blue-700 font-semibold">Pre-Implementation</div>
            <div class="text-[10px] text-blue-600">Proposal approved</div>
            <div class="text-sm font-bold text-blue-900 mt-1">
                {{ $preImplementationCompleteCount ?? 0 }}
            </div>
        </div>

       
        <div class="rounded-xl border border-purple-200 bg-gradient-to-b from-purple-50 to-white px-3 py-2">
            <div class="text-[10px] text-slate-700 font-semibold">Completed Projects</div>
            <div class="text-[10px] text-slate-500">Fully closed workflows</div>
            <div class="text-sm font-bold text-slate-900 mt-1">
                {{ $completedProjectsCount ?? 0 }}
            </div>
        </div>

        {{-- PRE IMPLEMENTATION --}}




    </div>


    {{-- =========================
        LEFT: MAIN ACTION
    ========================== --}}
    <a href="{{ route('admin.rereg.index') }}"
       class="lg:col-span-2 rounded-2xl border border-rose-200 bg-gradient-to-b from-rose-50 to-white px-4 py-3 shadow-sm hover:bg-rose-50 transition">

        <div class="flex items-center justify-between">

            <div>
                <div class="text-xs font-semibold text-rose-700">
                    Re-Registration Queue
                </div>
                <div class="text-[11px] text-rose-600">
                    Forms awaiting SACDEV review
                </div>
            </div>

            <div class="text-lg font-bold text-rose-800">
                {{ $pendingCaseCount ?? 0 }}
            </div>
        </div>

        @if(($pendingCaseCount ?? 0) > 0)
            <div class="mt-2 text-[10px] inline-block px-2 py-0.5 rounded-full bg-white text-rose-700 border border-rose-200 font-semibold">
                Needs review
            </div>
        @endif
    </a>


    {{-- =========================
        RIGHT: CONTEXT
    ========================== --}}
    <div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white px-4 py-3 shadow-sm flex items-center justify-between">

        <div class="text-[10px] text-slate-500 font-semibold uppercase">
            Active SY
        </div>

        <div class="text-sm font-semibold text-slate-900">
            {{ $activeSy?->name ?? 'None' }}
        </div>

    </div>

</div>