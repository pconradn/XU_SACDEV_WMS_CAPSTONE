@php
    $isRequired = $clearance['required'] && !$clearance['has_file'];
    $isReturned = $clearance['status'] === 'returned';
    $isPending  = $clearance['status'] === 'pending';
    $isApproved = $clearance['status'] === 'approved';

    $isOutdated = $clearance['is_outdated'] ?? false;
@endphp

<div 
    x-data="{ 
        open: {{ ($isRequired || $isReturned || $isOutdated) ? 'true' : 'false' }} 
    }"
    class="rounded-2xl shadow-sm transition-all

    @if($isReturned)
        bg-rose-50 border border-rose-300
    @elseif($isApproved)
        bg-emerald-50 border border-emerald-300
    @elseif($isOutdated)
        bg-gradient-to-br from-amber-50 via-white to-amber-50 border-2 border-amber-400
    @else
        bg-gradient-to-b from-purple-50 to-white border border-purple-200
    @endif

    @if($isRequired)
        ring-2 ring-purple-300 animate-[pulse_2.5s_ease-in-out_infinite]
    @endif

    @if($isOutdated)
        animate-[pulse_1.5s_ease-in-out_infinite]
    @endif
">

    {{-- ================= HEADER (CLICKABLE) ================= --}}
    <div 
        @click="open = !open"
        class="flex items-start justify-between p-5 cursor-pointer lg:cursor-default"
    >

        <div class="flex items-center gap-2">

            <div class="w-8 h-8 flex items-center justify-center rounded-lg
                @if($isOutdated)
                    bg-amber-100 text-amber-700
                @elseif($isReturned)
                    bg-rose-100 text-rose-700
                @elseif($isApproved)
                    bg-emerald-100 text-emerald-700
                @else
                    bg-purple-100 text-purple-700
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

        {{-- RIGHT SIDE --}}
        <div class="flex items-center gap-2">

            {{-- STATUS --}}
            @if($isOutdated)
                <span class="px-2 py-0.5 text-[10px] rounded-md bg-amber-200 text-amber-800 font-bold animate-pulse">
                    OUTDATED
                </span>
            @elseif(!$clearance['has_file'])
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

            {{-- CHEVRON --}}
            <i 
                data-lucide="chevron-down"
                class="w-4 h-4 text-slate-400 transition-transform"
                :class="{ 'rotate-180': open }"
            ></i>

        </div>
    </div>


    {{-- ================= BODY ================= --}}
    <div 
        x-show="open"
        x-transition
        @click.stop
        class="px-5 pb-5 space-y-4"
    >

        {{-- OUTDATED WARNING --}}
        @if($isOutdated)
            <div class="rounded-xl border-2 border-amber-400 bg-amber-100 text-amber-800 px-3 py-2 text-xs font-semibold animate-[pulse_1.2s_ease-in-out_infinite]">
                This clearance is outdated. Project data has changed.
                You must reissue before generating or printing again.
            </div>
        @endif


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
        <div class="pt-2 space-y-2" style="padding-bottom: 10px">

            <div class="flex items-center gap-2">

                @if(!$isOutdated)

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

                @else

                    <button disabled
                        class="px-3 py-1 text-[11px] rounded-md bg-slate-200 text-slate-500 cursor-not-allowed">
                        Disabled (Outdated)
                    </button>

                @endif

                @if($isOutdated && !$clearance['is_locked'])
                    <form method="POST" action="{{ $clearance['reissue_url'] }}">
                        @csrf
                        <button type="submit"
                            class="px-3 py-1 text-[11px] rounded-md bg-amber-500 text-white hover:bg-amber-600 transition shadow-sm animate-pulse">
                            Reissue
                        </button>
                    </form>
                @endif

            </div>

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

</div>