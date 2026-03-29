<div class="hidden">
    bg-blue-50 text-blue-700 border-blue-200
    bg-emerald-50 text-emerald-700 border-emerald-200
    bg-amber-50 text-amber-700 border-amber-200
    bg-purple-50 text-purple-700 border-purple-200
    bg-slate-50 text-slate-700 border-slate-200
    bg-red-50 text-red-700 border-red-200
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-3">

    {{-- ACTIVE SY --}}
    <div class="bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 shadow-sm flex flex-col justify-center">
        <div class="text-[10px] uppercase text-slate-600 font-semibold tracking-wide">
            Active SY
        </div>
        <div class="text-lg font-bold text-slate-900 mt-1">
            {{ $activeSy?->name ?? 'None' }}
        </div>
    </div>

    {{-- PENDING REREG --}}
    <a href="{{ route('admin.rereg.index') }}"
       class="bg-red-50 border border-red-200 rounded-2xl px-4 py-3 shadow-sm hover:bg-red-100 transition flex flex-col justify-center">

        <div class="text-[10px] uppercase text-red-700 font-semibold tracking-wide">
            Pending ReReg Forms
        </div>

        <div class="flex items-center gap-2 mt-1">
            <div class="text-lg font-bold text-red-800">
                {{ $pendingCaseCount ?? 0 }}
            </div>

            @if(($pendingCaseCount ?? 0) > 0)
                <span class="text-[9px] px-2 py-0.5 rounded-full bg-white text-red-700 border border-red-200 font-semibold">
                    Needs review
                </span>
            @endif
        </div>
    </a>

    {{-- SMALL KPIs GRID --}}
    <div class="grid grid-cols-2 gap-2">

        <div class="bg-emerald-50 border border-emerald-200 rounded-xl px-3 py-2 flex items-center justify-between">
            <span class="text-[10px] text-emerald-700 font-semibold">
                Registered Orgs
            </span>
            <span class="text-sm font-bold text-emerald-900">
                {{ $activatedOrgCount ?? 0 }}
            </span>
        </div>

        <div class="bg-amber-50 border border-amber-200 rounded-xl px-3 py-2 flex items-center justify-between">
            <span class="text-[10px] text-amber-700 font-semibold">
                Pending Upcoming
            </span>
            <span class="text-sm font-bold text-amber-900">
                {{ $upcomingProjectsCount ?? 0 }}
            </span>
        </div>

        <div class="bg-purple-50 border border-purple-200 rounded-xl px-3 py-2 flex items-center justify-between">
            <span class="text-[10px] text-purple-700 font-semibold">
                Off-Campus Projects
            </span>
            <span class="text-sm font-bold text-purple-900">
                {{ $offCampusProjectsCount ?? 0 }}
            </span>
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-xl px-3 py-2 flex items-center justify-between">
            <span class="text-[10px] text-blue-700 font-semibold">
                Pre Complete
            </span>
            <span class="text-sm font-bold text-blue-900">
                {{ $preImplementationCompleteCount ?? 0 }}
            </span>
        </div>

        <div class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 flex items-center justify-between col-span-2">
            <span class="text-[10px] text-slate-700 font-semibold">
                Completed Projects
            </span>
            <span class="text-sm font-bold text-slate-900">
                {{ $completedProjectsCount ?? 0 }}
            </span>
        </div>

    </div>

</div>