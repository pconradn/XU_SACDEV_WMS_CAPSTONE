@php
    $isRequired = $clearance['required'] && !$clearance['has_file'];
    $isReturned = $clearance['status'] === 'returned';
    $isPending  = $clearance['status'] === 'pending';
    $isApproved = $clearance['status'] === 'approved';
@endphp

<div 
    class="rounded-2xl shadow-sm p-5 space-y-4 transition-all

    {{-- BASE PURPLE THEME --}}
    @if($isReturned)
        bg-rose-50 border border-rose-300
    @elseif($isApproved)
        bg-emerald-50 border border-emerald-300
    @else
        bg-gradient-to-b from-purple-50 to-white border border-purple-200
    @endif

    {{-- ATTENTION STATE --}}
    @if($isRequired)
        ring-2 ring-purple-300 animate-[pulse_2.5s_ease-in-out_infinite]
    @endif
">

    {{-- HEADER --}}
    <div class="flex items-start justify-between">

        <div class="flex items-center gap-2">

            <div class="w-8 h-8 flex items-center justify-center rounded-lg
                @if($isReturned) bg-rose-100 text-rose-700
                @elseif($isApproved) bg-emerald-100 text-emerald-700
                @else bg-purple-100 text-purple-700
                @endif
            ">
                <i data-lucide="shield" class="w-4 h-4"></i>
            </div>

            <div>
                <p class="text-xs font-semibold text-slate-900">
                    Off-Campus Clearance
                </p>
                <p class="text-[10px] text-slate-500">
                    Required for off-campus activities
                </p>
            </div>

        </div>

        {{-- STATUS BADGE --}}
        @if(!$clearance['has_file'])
            <span class="px-2 py-0.5 text-[10px] rounded-md bg-amber-100 text-amber-700 font-semibold">
                Required
            </span>
        @elseif($isPending)
            <span class="px-2 py-0.5 text-[10px] rounded-md bg-purple-100 text-purple-700 font-semibold">
                Pending
            </span>
        @elseif($isReturned)
            <span class="px-2 py-0.5 text-[10px] rounded-md bg-rose-100 text-rose-700 font-semibold">
                Returned
            </span>
        @elseif($isApproved)
            <span class="px-2 py-0.5 text-[10px] rounded-md bg-emerald-100 text-emerald-700 font-semibold">
                Approved
            </span>
        @endif

    </div>


    {{-- META --}}
    <div class="grid grid-cols-2 gap-x-4 gap-y-2 text-[11px]">

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


    {{-- SNAPSHOT --}}
    @if(!empty($clearance['snapshot']) && is_array($clearance['snapshot']))
        <div class="border border-purple-100 bg-white/70 backdrop-blur-sm rounded-lg p-3 text-[11px]">

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
                    <div class="text-slate-400">Budget</div>
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


    {{-- REMARKS --}}
    @if($clearance['remarks'] && $isReturned)
        <div class="flex items-start gap-2 text-[11px] text-rose-700 bg-rose-50 border border-rose-200 rounded-md px-3 py-2">
            <i data-lucide="alert-circle" class="w-4 h-4 mt-0.5"></i>
            <span>{{ $clearance['remarks'] }}</span>
        </div>
    @endif


    {{-- ACTIONS --}}
    <div class="pt-2 space-y-2">

        <div class="flex items-center gap-2">

            @if(!$clearance['has_file'])
                <a href="{{ $clearance['print_url'] }}"
                   target="_blank"
                   class="px-3 py-1 text-[11px] rounded-md bg-purple-600 text-white hover:bg-purple-700 transition shadow-sm">
                    Generate
                </a>
            @else
                <a href="{{ $clearance['print_url'] }}"
                   target="_blank"
                   class="px-3 py-1 text-[11px] rounded-md bg-purple-100 text-purple-700 hover:bg-purple-200 transition">
                    Regenerate
                </a>
            @endif

            @if($clearance['is_outdated'] && !$clearance['is_locked'])
                <form method="POST" action="{{ $clearance['reissue_url'] }}">
                    @csrf
                    <button type="submit"
                        class="px-3 py-1 text-[11px] rounded-md bg-amber-500 text-white hover:bg-amber-600 transition">
                        Reissue
                    </button>
                </form>
            @endif

        </div>


        {{-- UPLOAD --}}
        @if($clearance['can_upload'])
        <form method="POST"
              action="{{ $clearance['upload_url'] }}"
              enctype="multipart/form-data"
              class="flex items-center gap-2 bg-white/80 border border-purple-200 rounded-md px-2 py-1">

            @csrf

            <input type="file"
                   name="clearance_file"
                   accept="application/pdf"
                   required
                   class="text-[11px] w-full bg-transparent focus:outline-none">

            <button type="submit"
                    class="px-3 py-1 text-[11px] rounded-md bg-indigo-600 text-white hover:bg-indigo-700 transition">
                Upload
            </button>

        </form>
        @endif

    </div>

</div>