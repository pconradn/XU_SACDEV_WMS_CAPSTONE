<div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm p-5 space-y-4">

    {{-- HEADER --}}
    <div class="flex items-start justify-between gap-3">

        <div class="flex items-center gap-2">
            <i data-lucide="layout-dashboard" class="w-4 h-4 text-slate-500"></i>

            <h2 class="text-xs font-semibold text-slate-700">
                Project Snapshot
            </h2>
        </div>

        {{-- BADGES --}}
        <div class="flex flex-wrap items-center gap-1.5">

            {{-- STATUS --}}
            @if($snapshot['status'] === 'submitted')
                <span class="px-2 py-0.5 text-[10px] rounded-md bg-blue-100 text-blue-700 font-semibold">
                    Proposed
                </span>
            @elseif($snapshot['status'] === 'approved_by_sacdev')
                <span class="px-2 py-0.5 text-[10px] rounded-md bg-emerald-100 text-emerald-700 font-semibold">
                    Approved
                </span>
            @elseif($snapshot['status'] === 'draft')
                <span class="px-2 py-0.5 text-[10px] rounded-md bg-amber-100 text-amber-700 font-semibold">
                    Draft
                </span>
            @endif

            {{-- OFF-CAMPUS --}}
            @if($snapshot['is_off_campus'])
                <span class="px-2 py-0.5 text-[10px] rounded-md bg-purple-100 text-purple-700 font-semibold">
                    Off-Campus
                </span>
            @endif

        </div>

    </div>


    {{-- DESCRIPTION --}}
    @if(!empty($snapshot['description']))
        <div class="text-[11px] text-slate-600 leading-relaxed border border-slate-200 rounded-lg px-3 py-2 bg-white">
            {{ $snapshot['description'] }}
        </div>
    @endif


    {{-- DETAILS --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-[11px]">

        {{-- DATE --}}
        <div class="space-y-0.5">
            <div class="flex items-center gap-1 text-slate-400">
                <i data-lucide="calendar" class="w-3 h-3"></i>
                <span>Date</span>
            </div>
            <div class="font-medium text-slate-800">
                {{ $snapshot['date'] ?? '—' }}
            </div>
        </div>

        {{-- TIME --}}
        <div class="space-y-0.5">
            <div class="flex items-center gap-1 text-slate-400">
                <i data-lucide="clock" class="w-3 h-3"></i>
                <span>Time</span>
            </div>
            <div class="font-medium text-slate-800">
                {{ $snapshot['time'] ?? '—' }}
            </div>
        </div>

        {{-- VENUE --}}
        <div class="space-y-0.5">
            <div class="flex items-center gap-1 text-slate-400">
                <i data-lucide="map-pin" class="w-3 h-3"></i>
                <span>Venue</span>
            </div>
            <div class="font-medium text-slate-800 truncate">
                {{ $snapshot['venue'] ?? '—' }}
            </div>
        </div>

        {{-- BUDGET --}}
        <div class="space-y-0.5">
            <div class="flex items-center gap-1 text-slate-400">
                <i data-lucide="wallet" class="w-3 h-3"></i>
                <span>Budget</span>
            </div>
            <div class="font-semibold text-slate-900">
                ₱ {{ number_format($snapshot['total_budget'] ?? 0, 2) }}
            </div>
        </div>

    </div>

</div>