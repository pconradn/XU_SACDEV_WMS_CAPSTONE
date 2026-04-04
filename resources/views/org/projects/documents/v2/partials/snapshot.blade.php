<div class="bg-white border rounded-xl p-4 shadow-sm space-y-3">

    {{-- HEADER --}}
    <div class="flex items-center justify-between">

        <h2 class="text-xs font-semibold text-slate-700">
            Project Snapshot
        </h2>

        <div class="flex items-center gap-1">

            {{-- STATUS BADGE --}}
            @if($snapshot['status'] === 'submitted')
                <span class="px-2 py-0.5 text-[10px] rounded-md bg-blue-100 text-blue-700">
                    Proposed
                </span>
            @elseif($snapshot['status'] === 'approved_by_sacdev')
                <span class="px-2 py-0.5 text-[10px] rounded-md bg-emerald-100 text-emerald-700">
                    Proposal Approved
                </span>
            @elseif($snapshot['status'] === 'draft')
                <span class="px-2 py-0.5 text-[10px] rounded-md bg-amber-100 text-amber-700">
                    Draft
                </span>
            @endif

            {{-- OFF-CAMPUS --}}
            @if($snapshot['is_off_campus'])
                <span class="px-2 py-0.5 text-[10px] rounded-md bg-purple-100 text-purple-700">
                    Off-Campus
                </span>
            @endif

        </div>

    </div>


    {{-- DESCRIPTION --}}
    @if(!empty($snapshot['description']))
        <p class="text-[11px] text-slate-600 leading-snug">
            {{ $snapshot['description'] }}
        </p>
    @endif


    {{-- DETAILS --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-[11px]">

        {{-- DATE --}}
        <div>
            <p class="text-slate-400">Date</p>
            <p class="font-medium text-slate-800">
                {{ $snapshot['date'] ?? '—' }}
            </p>
        </div>

        {{-- TIME --}}
        <div>
            <p class="text-slate-400">Time</p>
            <p class="font-medium text-slate-800">
                {{ $snapshot['time'] ?? '—' }}
            </p>
        </div>

        {{-- VENUE --}}
        <div>
            <p class="text-slate-400">Venue</p>
            <p class="font-medium text-slate-800">
                {{ $snapshot['venue'] ?? '—' }}
            </p>
        </div>

        {{-- BUDGET (NEW) --}}
        <div>
            <p class="text-slate-400">Budget</p>
            <p class="font-medium text-slate-800">
                ₱ {{ number_format($snapshot['total_budget'] ?? 0, 2) }}
            </p>
        </div>

    </div>

</div>