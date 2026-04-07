<div class="w-full rounded-2xl border border-slate-200 bg-gradient-to-br from-white to-slate-50 shadow-sm p-4 space-y-3">

    {{-- HEADER --}}
    <div class="flex items-center justify-between">
        <h2 class="text-xs font-semibold text-slate-700 tracking-wide">
            Project Snapshot
        </h2>
    </div>


    {{-- DESCRIPTION --}}
    @if(!empty($snapshot['description']))
        <div class="text-[11px] text-slate-600 leading-snug bg-slate-50 border border-slate-200 rounded-lg px-3 py-2">
            {{ $snapshot['description'] }}
        </div>
    @endif


    {{-- INFO GRID --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-[11px]">

        {{-- DATE --}}
        <div class="bg-slate-50 rounded-lg px-3 py-2 border border-slate-200 space-y-0.5">
            <div class="text-[10px] text-slate-400 flex items-center gap-1">
                <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                Date
            </div>
            <div class="font-semibold text-slate-800">
                {{ $snapshot['date'] ?? '—' }}
            </div>
        </div>

        {{-- TIME --}}
        <div class="bg-slate-50 rounded-lg px-3 py-2 border border-slate-200 space-y-0.5">
            <div class="text-[10px] text-slate-400 flex items-center gap-1">
                <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                Time
            </div>
            <div class="font-semibold text-slate-800">
                {{ $snapshot['time'] ?? '—' }}
            </div>
        </div>

        {{-- VENUE --}}
        <div class="bg-slate-50 rounded-lg px-3 py-2 border border-slate-200 col-span-2 sm:col-span-1 space-y-0.5">
            <div class="text-[10px] text-slate-400 flex items-center gap-1">
                <i data-lucide="map-pin" class="w-3.5 h-3.5"></i>
                Venue
            </div>
            <div class="font-semibold text-slate-800 truncate">
                {{ $snapshot['venue'] ?? '—' }}
            </div>
        </div>

        {{-- BUDGET --}}
        <div class="relative group bg-gradient-to-br from-amber-50 to-white rounded-lg px-3 py-2 border border-amber-200 space-y-0.5">

            <div class="flex items-center gap-1 text-[10px] text-amber-700">

                <i data-lucide="wallet" class="w-3.5 h-3.5"></i>
                Budget

                @if($snapshot['fund_sources'] && $snapshot['fund_sources']->count())
                    <div class="ml-1 w-4 h-4 flex items-center justify-center rounded-full bg-amber-200 text-[9px] text-amber-800 font-bold cursor-pointer">
                        !
                    </div>
                @endif

            </div>

            <div class="font-semibold text-slate-900">
                @if($snapshot['total_budget'])
                    ₱ {{ number_format($snapshot['total_budget'], 2) }}
                @else
                    —
                @endif
            </div>

            {{-- TOOLTIP --}}
            @if($snapshot['fund_sources'] && $snapshot['fund_sources']->count())
                <div class="absolute right-0 top-12 hidden group-hover:block z-30 w-60 bg-white border border-slate-200 rounded-xl shadow-xl p-3 space-y-2 text-[10px]">

                    <div class="text-slate-500 text-[10px] font-semibold">
                        Fund Sources
                    </div>

                    @foreach($snapshot['fund_sources'] as $source)
                        <div class="flex justify-between items-center border-b border-slate-100 pb-1 last:border-none">

                            <span class="text-slate-600 truncate">
                                {{ $source->source_name ?? 'Source' }}
                            </span>

                            @if(isset($source->amount))
                                <span class="font-medium text-slate-800">
                                    ₱ {{ number_format($source->amount, 2) }}
                                </span>
                            @endif

                        </div>
                    @endforeach

                </div>
            @endif

        </div>

    </div>

</div>