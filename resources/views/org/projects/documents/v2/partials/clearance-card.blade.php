<div 
    class="bg-white border rounded-xl p-4 shadow-sm space-y-3
    @if($clearance['required'] && !$clearance['has_file'])
        animate-pulse border-purple-300
    @elseif($clearance['status'] === 'returned')
        border-rose-300
    @elseif($clearance['status'] === 'pending')
        border-purple-300
    @else
        border-slate-200
    @endif
">

    <div class="flex items-center justify-between">

        <div class="flex items-center gap-2">

            <div class="w-6 h-6 flex items-center justify-center rounded-md bg-purple-100 text-purple-700 text-xs">
                🛂
            </div>

            <div>
                <p class="text-xs font-semibold text-slate-700">
                    Off-Campus Clearance
                </p>

                <p class="text-[10px] text-slate-500">
                    Required for off-campus activities
                </p>
            </div>

        </div>

        @if(!$clearance['has_file'])
            <span class="px-2 py-0.5 text-[10px] rounded-md bg-amber-100 text-amber-700">
                Required
            </span>

        @elseif($clearance['status'] === 'pending')
            <span class="px-2 py-0.5 text-[10px] rounded-md bg-purple-100 text-purple-700">
                Pending
            </span>

        @elseif($clearance['status'] === 'returned')
            <span class="px-2 py-0.5 text-[10px] rounded-md bg-rose-100 text-rose-700">
                Returned
            </span>

        @elseif($clearance['status'] === 'approved')
            <span class="px-2 py-0.5 text-[10px] rounded-md bg-emerald-100 text-emerald-700">
                Approved
            </span>
        @endif

    </div>


    <div class="grid grid-cols-2 gap-x-4 gap-y-1 text-[10px] text-slate-600">

        @if($clearance['reference'])
            <div>
                <span class="text-slate-400">Ref:</span>
                <span class="font-medium text-slate-700">
                    {{ $clearance['reference'] }}
                </span>
            </div>
        @endif

        <div>
            <span class="text-slate-400">Status:</span>
            <span class="font-medium text-slate-700 capitalize">
                {{ $clearance['status'] ?? '—' }}
            </span>
        </div>

        <div>
            <span class="text-slate-400">Participants:</span>
            <span class="font-medium text-slate-700">
                {{ $clearance['participants_count'] }}
            </span>
        </div>

        @if($clearance['issued_at'])
            <div>
                <span class="text-slate-400">Issued:</span>
                <span class="font-medium text-slate-700">
                    {{ \Carbon\Carbon::parse($clearance['issued_at'])->format('M d, Y') }}
                </span>
            </div>
        @endif

    </div>


    {{-- ================= SNAPSHOT (FIXED USING OLD METHOD) ================= --}}
    @if(!empty($clearance['snapshot']) && is_array($clearance['snapshot']))
        <div class="border border-slate-100 bg-slate-50 rounded-lg p-3 text-[10px]">

            <div class="grid grid-cols-2 gap-3">

                <div>
                    <div class="text-slate-400">Activity Dates</div>
                    <div class="font-medium text-slate-700">
                        {{ !empty($clearance['snapshot']['start_date'])
                            ? \Carbon\Carbon::parse($clearance['snapshot']['start_date'])->format('M d, Y')
                            : '—' }}
                        —
                        {{ !empty($clearance['snapshot']['end_date'])
                            ? \Carbon\Carbon::parse($clearance['snapshot']['end_date'])->format('M d, Y')
                            : '—' }}
                    </div>
                </div>

                <div>
                    <div class="text-slate-400">Venue</div>
                    <div class="font-medium text-slate-700">
                        {{ $clearance['snapshot']['off_campus_venue'] ?? '—' }}
                    </div>
                </div>

                <div>
                    <div class="text-slate-400">Total Budget</div>
                    <div class="font-medium text-slate-700">
                        ₱{{ number_format($clearance['snapshot']['total_budget'] ?? 0, 2) }}
                    </div>
                </div>

                <div>
                    <div class="text-slate-400">Participants</div>
                    <div class="font-medium text-slate-700">
                        {{ $clearance['participants_count'] ?? 0 }}
                    </div>
                </div>

            </div>

        </div>
    @endif


    @if($clearance['remarks'] && $clearance['status'] === 'returned')
        <div class="text-[11px] text-rose-700 bg-rose-50 border border-rose-200 rounded-md px-2 py-1">
            {{ $clearance['remarks'] }}
        </div>
    @endif


<div class="pt-2 space-y-2">

    {{-- TOP ACTIONS --}}
    <div class="flex items-center gap-2">

        @if(!$clearance['has_file'])
            <a href="{{ $clearance['print_url'] }}"
               target="_blank"
               class="px-3 py-1 text-[10px] rounded-md bg-purple-600 text-white hover:bg-purple-700">
                Generate Clearance
            </a>
        @else
            <a href="{{ $clearance['print_url'] }}"
               target="_blank"
               class="px-3 py-1 text-[10px] rounded-md bg-slate-100 hover:bg-slate-200 text-slate-700">
                Regenerate
            </a>
        @endif

        @if($clearance['is_outdated'] && !$clearance['is_locked'])
            <form method="POST" action="{{ $clearance['reissue_url'] }}">
                @csrf
                <button type="submit"
                        class="px-3 py-1 text-[10px] rounded-md bg-amber-500 text-white hover:bg-amber-600">
                    Reissue
                </button>
            </form>
        @endif

    </div>


    {{-- UPLOAD ROW (GROUPED) --}}
    @if($clearance['can_upload'])

    <form method="POST"
          action="{{ $clearance['upload_url'] }}"
          enctype="multipart/form-data"
          class="flex items-center gap-2 bg-slate-50 border border-slate-200 rounded-md px-2 py-1">

        @csrf

        {{-- FILE INPUT --}}
        <input type="file"
               name="clearance_file"
               accept="application/pdf"
               required
               class="text-[10px] w-full bg-transparent focus:outline-none">

        {{-- UPLOAD BUTTON --}}
        <button type="submit"
                class="px-3 py-1 text-[10px] rounded-md bg-indigo-600 text-white hover:bg-indigo-700">
            Upload
        </button>

    </form>

    @endif

</div>
</div>