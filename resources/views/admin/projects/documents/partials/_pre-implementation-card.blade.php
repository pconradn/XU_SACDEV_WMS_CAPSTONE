<div class="w-full rounded-2xl border border-amber-200 bg-gradient-to-br from-amber-50 via-yellow-50 to-white shadow-sm p-4 space-y-4">

    <div class="flex items-start justify-between gap-3">

        <div class="flex items-start gap-3 min-w-0">
            <div class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-amber-700 border border-amber-200">
                <i data-lucide="clipboard-check" class="h-4 w-4"></i>
            </div>

            <div class="min-w-0">
                <h3 class="text-sm font-semibold text-slate-900">
                    Pre-Implementation
                </h3>
                <p class="mt-0.5 text-[11px] text-slate-600">
                    Proposal and budget review must be cleared before the project proceeds.
                </p>
            </div>
        </div>

        <span class="shrink-0 rounded-full border border-amber-200 bg-white px-2.5 py-1 text-[10px] font-semibold text-amber-700">
            Required
        </span>

    </div>


    @if($combined['exists'])

        @if($proposalDoc)
            @php
                $pending = $proposalDoc->signatures?->where('status', 'pending')->sortBy('id')->first();
            @endphp

            <div class="rounded-2xl border border-white/80 bg-white/90 backdrop-blur px-3.5 py-3 shadow-sm">

                <div class="flex items-start justify-between gap-3">

                    <div class="flex items-center gap-2 min-w-0">
                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-slate-600 border border-slate-200">
                            <i data-lucide="file-text" class="h-4 w-4"></i>
                        </div>

                        <div class="min-w-0">
                            <div class="text-xs font-semibold text-slate-800">
                                Project Proposal
                            </div>

                            @if($pending)
                                <div class="mt-0.5 flex flex-wrap items-center gap-1 text-[10px] text-slate-500">
                                    <i data-lucide="clock-3" class="h-3.5 w-3.5"></i>
                                    <span>Awaiting</span>
                                    <span class="font-medium text-slate-700">
                                        {{ ucfirst(str_replace('_', ' ', $pending->role)) }}
                                    </span>
                                    @if($pending->user)
                                        <span class="text-slate-400">• {{ $pending->user->name }}</span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    <span class="shrink-0 rounded-full px-2.5 py-1 text-[10px] font-semibold {{ $proposalDoc->status_badge_class }}">
                        {{ $proposalDoc->status_label }}
                    </span>

                </div>

            </div>
        @endif


        <a href="{{ $combined['view_url'] }}"
        class="group flex w-full items-center justify-between rounded-2xl 
                bg-gradient-to-r from-amber-500 to-yellow-500 
                border border-amber-400
                px-4 py-3 text-sm font-semibold text-white 
                shadow-sm ring-1 ring-amber-300/40
                transition hover:from-amber-600 hover:to-yellow-600 hover:shadow-md">

            <span class="flex items-center gap-2">
                <i data-lucide="folder-open" class="h-4 w-4"></i>
                Open Combined Proposal
            </span>

            <i data-lucide="arrow-right" class="h-4 w-4 transition group-hover:translate-x-0.5"></i>
        </a>

        <div class="grid grid-cols-2 gap-2">

            @if($combined['proposal_print_url'])
                <a href="{{ $combined['proposal_print_url'] }}"
                   target="_blank"
                   class="flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-[11px] font-medium text-slate-700 shadow-sm transition hover:border-amber-200 hover:bg-amber-50 hover:text-amber-700">
                    <i data-lucide="printer" class="h-3.5 w-3.5"></i>
                    Proposal
                </a>
            @endif

            @if($combined['budget_print_url'])
                <a href="{{ $combined['budget_print_url'] }}"
                   target="_blank"
                   class="flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-[11px] font-medium text-slate-700 shadow-sm transition hover:border-amber-200 hover:bg-amber-50 hover:text-amber-700">
                    <i data-lucide="printer" class="h-3.5 w-3.5"></i>
                    Budget
                </a>
            @endif

        </div>


        <div class="border-t border-amber-200/70 pt-2">

            <a href="{{ route('admin.projects.packets.index', $project) }}"
               class="flex w-full items-center justify-center gap-2 rounded-xl border border-amber-200 bg-amber-100/70 px-4 py-2.5 text-[11px] font-semibold text-amber-800 transition hover:bg-amber-200/80 hover:border-amber-300">

                <i data-lucide="package" class="h-4 w-4"></i>
                View Org Submission Packets
            </a>

        </div>

    @else

        <div class="rounded-2xl border border-dashed border-amber-200 bg-white/80 px-4 py-5 text-center">
            <div class="mx-auto mb-2 flex h-10 w-10 items-center justify-center rounded-full bg-amber-100 text-amber-700">
                <i data-lucide="file-warning" class="h-5 w-5"></i>
            </div>
            <div class="text-xs font-semibold text-slate-700">
                No proposal submitted yet
            </div>
            <div class="mt-1 text-[11px] text-slate-500">
                This section will become active once the combined proposal is created.
            </div>
        </div>

    @endif

</div>