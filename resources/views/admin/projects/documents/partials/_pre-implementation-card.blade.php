<div class="w-full rounded-2xl border border-fuchsia-200 bg-gradient-to-br from-fuchsia-50 via-violet-50 to-white shadow-sm p-4 space-y-4">

    <div class="flex items-start justify-between gap-3">

        <div class="flex items-start gap-3 min-w-0">
            <div class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-fuchsia-100 text-fuchsia-700 border border-fuchsia-200">
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

        <span class="shrink-0 rounded-full border border-fuchsia-200 bg-white px-2.5 py-1 text-[10px] font-semibold text-fuchsia-700">
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
           class="group flex w-full items-center justify-between rounded-2xl bg-gradient-to-r from-fuchsia-600 to-violet-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:from-fuchsia-700 hover:to-violet-700 hover:shadow-md">

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
                   class="flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-[11px] font-medium text-slate-700 shadow-sm transition hover:border-fuchsia-200 hover:bg-fuchsia-50 hover:text-fuchsia-700">
                    <i data-lucide="printer" class="h-3.5 w-3.5"></i>
                    Proposal
                </a>
            @endif

            @if($combined['budget_print_url'])
                <a href="{{ $combined['budget_print_url'] }}"
                   target="_blank"
                   class="flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-[11px] font-medium text-slate-700 shadow-sm transition hover:border-fuchsia-200 hover:bg-fuchsia-50 hover:text-fuchsia-700">
                    <i data-lucide="printer" class="h-3.5 w-3.5"></i>
                    Budget
                </a>
            @endif

        </div>


        <div class="border-t border-fuchsia-200/70 pt-2">

            <a href="{{ route('admin.projects.packets.index', $project) }}"
               class="flex w-full items-center justify-center gap-2 rounded-xl border border-fuchsia-200 bg-fuchsia-100/70 px-4 py-2.5 text-[11px] font-semibold text-fuchsia-800 transition hover:bg-fuchsia-200/80 hover:border-fuchsia-300">

                <i data-lucide="package" class="h-4 w-4"></i>
                View Submission Packets
            </a>

        </div>

    @else

        <div class="rounded-2xl border border-dashed border-fuchsia-200 bg-white/80 px-4 py-5 text-center">
            <div class="mx-auto mb-2 flex h-10 w-10 items-center justify-center rounded-full bg-fuchsia-100 text-fuchsia-700">
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