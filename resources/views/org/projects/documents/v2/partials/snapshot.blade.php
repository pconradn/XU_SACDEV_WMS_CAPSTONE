<div class="lg:col-span-2 bg-white border rounded-2xl p-6 shadow-sm space-y-5">

    {{-- HEADER --}}
    <div class="flex items-start justify-between">

        <h2 class="text-sm font-semibold text-slate-700">
            Project Snapshot
        </h2>

        <div class="flex items-center gap-2">

            {{-- STATUS BADGE --}}
            @if($snapshot['status'] === 'submitted')
                <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-700">
                    Proposed
                </span>
            @elseif($snapshot['status'] === 'approved_by_sacdev')
                <span class="px-2 py-1 text-xs rounded-full bg-emerald-100 text-emerald-700">
                    Approved
                </span>
            @elseif($snapshot['status'] === 'draft')
                <span class="px-2 py-1 text-xs rounded-full bg-amber-100 text-amber-700">
                    Draft
                </span>
            @endif

            {{-- OFF-CAMPUS INDICATOR --}}
            @if($snapshot['is_off_campus'])
                <span class="px-2 py-1 text-xs rounded-full bg-purple-100 text-purple-700">
                    Off-Campus
                </span>
            @endif

        </div>

    </div>


    {{-- DESCRIPTION --}}
    @if(!empty($snapshot['description']))
        <div class="text-sm text-slate-600 leading-relaxed">
            {{ $snapshot['description'] }}
        </div>
    @endif


    {{-- DETAILS GRID --}}
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">

        {{-- DATE --}}
        <div>
            <p class="text-slate-500">Date</p>
            <p class="font-medium text-slate-800">
                {{ $snapshot['date'] ?? '—' }}
            </p>
        </div>

        {{-- TIME --}}
        <div>
            <p class="text-slate-500">Time</p>
            <p class="font-medium text-slate-800">
                {{ $snapshot['time'] ?? '—' }}
            </p>
        </div>

        {{-- VENUE --}}
        <div>
            <p class="text-slate-500">Venue</p>
            <p class="font-medium text-slate-800">
                {{ $snapshot['venue'] ?? '—' }}
            </p>
        </div>

    </div>

</div>