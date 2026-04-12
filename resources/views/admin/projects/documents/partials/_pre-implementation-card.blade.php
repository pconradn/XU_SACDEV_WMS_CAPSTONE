<div class="w-full rounded-2xl border border-amber-200 bg-gradient-to-b from-amber-50 to-white shadow-sm">

    <div class="p-4 space-y-4">

        {{-- HEADER --}}
        <div class="flex items-start justify-between gap-3">

            <div class="flex items-start gap-3 min-w-0">

                <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-amber-100 text-amber-700 border border-amber-200">
                    <i data-lucide="clipboard-check" class="h-4 w-4"></i>
                </div>

                <div class="min-w-0">
                    <div class="text-xs font-semibold text-slate-900">
                        Pre-Implementation
                    </div>
                    <p class="text-[11px] text-slate-500 mt-0.5">
                        Proposal and budget must be approved before proceeding.
                    </p>
                </div>

            </div>

            <span class="shrink-0 rounded-full border border-amber-200 bg-amber-100 px-2.5 py-1 text-[10px] font-semibold text-amber-700">
                Required
            </span>

        </div>


        @if($combined['exists'])

            {{-- PROPOSAL STATUS --}}
            @if($proposalDoc)
                @php
                    $pending = $proposalDoc->signatures?->where('status', 'pending')->sortBy('id')->first();
                @endphp

                <div class="rounded-xl border border-slate-200 bg-white px-3 py-3">

                    <div class="flex items-center justify-between gap-2">

                        <div class="flex items-center gap-2 min-w-0">

                            <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-md bg-slate-100 text-slate-600 border border-slate-200">
                                <i data-lucide="file-text" class="h-3.5 w-3.5"></i>
                            </div>

                            <div class="min-w-0">
                                <div class="text-xs font-medium text-slate-800">
                                    Project Proposal
                                </div>

                                @if($pending)
                                <div class="flex items-center gap-1 text-[10px] text-slate-500 mt-0.5">
                                    <i data-lucide="clock-3" class="h-3 w-3"></i>
                                    <span>{{ ucfirst(str_replace('_',' ',$pending->role)) }}</span>
                                </div>
                                @endif
                            </div>

                        </div>

                        <span class="text-[10px] font-semibold px-2 py-1 rounded-full {{ $proposalDoc->status_badge_class }}">
                            {{ $proposalDoc->status_label }}
                        </span>

                    </div>

                </div>
            @endif


            {{-- MAIN ACTION --}}
            <a href="{{ $combined['view_url'] }}"
               class="flex items-center justify-between rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-xs font-semibold text-amber-800
                      hover:bg-amber-100 transition">

                <span class="flex items-center gap-2">
                    <i data-lucide="folder-open" class="h-4 w-4"></i>
                    Open Combined Proposal
                </span>

                <i data-lucide="arrow-right" class="h-4 w-4"></i>
            </a>


            {{-- PRINT ACTIONS --}}
            <div class="flex gap-2">

                @if($combined['proposal_print_url'])
                <a href="{{ $combined['proposal_print_url'] }}"
                   target="_blank"
                   class="flex-1 flex items-center justify-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-2 text-[11px] font-medium text-slate-700
                          hover:bg-slate-50 transition">
                    <i data-lucide="printer" class="h-3 w-3"></i>
                    Proposal
                </a>
                @endif

                @if($combined['budget_print_url'])
                <a href="{{ $combined['budget_print_url'] }}"
                   target="_blank"
                   class="flex-1 flex items-center justify-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-2 text-[11px] font-medium text-slate-700
                          hover:bg-slate-50 transition">
                    <i data-lucide="printer" class="h-3 w-3"></i>
                    Budget
                </a>
                @endif

            </div>


            {{-- PACKETS --}}
            <div class="pt-2 border-t border-amber-200">

                <a href="{{ route('admin.projects.packets.index', $project) }}"
                   class="flex items-center justify-center gap-2 rounded-xl border border-amber-200 bg-white px-4 py-2 text-[11px] font-medium text-amber-700
                          hover:bg-amber-50 transition">

                    <i data-lucide="package" class="h-4 w-4"></i>
                    Submission Packets

                </a>

            </div>

        @else

            {{-- EMPTY --}}
            <div class="rounded-xl border border-dashed border-amber-200 bg-white px-4 py-5 text-center">

                <div class="mx-auto mb-2 flex h-9 w-9 items-center justify-center rounded-full bg-amber-100 text-amber-700">
                    <i data-lucide="file-warning" class="h-4 w-4"></i>
                </div>

                <div class="text-xs font-medium text-slate-700">
                    No proposal yet
                </div>

                <div class="text-[11px] text-slate-500 mt-1">
                    Will activate once created
                </div>

            </div>

        @endif

    </div>

</div>