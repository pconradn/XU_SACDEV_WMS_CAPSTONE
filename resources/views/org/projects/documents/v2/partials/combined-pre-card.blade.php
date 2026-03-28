@php
    $proposal = $project->proposalDocument;
    $status = $proposal?->status ?? 'draft';
    $currentApprover = $proposal?->currentPendingSignature();

    $budget = $proposal?->proposalData?->total_budget ?? 0;

    function peso($val) {
        return '₱ ' . number_format($val, 2);
    }

    $statusColor = match($status) {
        'approved_by_sacdev' => 'text-emerald-700 bg-emerald-50 border-emerald-200',
        'submitted' => 'text-blue-700 bg-blue-50 border-blue-200',
        'returned' => 'text-rose-700 bg-rose-50 border-rose-200',
        default => 'text-slate-600 bg-slate-50 border-slate-200',
    };
@endphp

<a href="{{ route('org.projects.documents.combined-proposal.create', $project) }}"
   class="block max-w-md border rounded-2xl p-5 hover:shadow-lg transition bg-white">

    <div class="space-y-4">

        {{-- HEADER --}}
        <div class="flex items-start justify-between">

            <div>
                <div class="text-sm font-semibold text-slate-900">
                    Project & Budget Proposal
                </div>

                <div class="text-[11px] text-slate-400">
                    Pre-implementation requirement
                </div>
            </div>

            {{-- STATUS BADGE --}}
            <div class="text-[10px] px-2 py-1 rounded-full border font-semibold {{ $statusColor }}">
                {{ strtoupper(str_replace('_',' ', $status)) }}
            </div>

        </div>


        {{-- BUDGET --}}
        <div class="bg-slate-50 border border-slate-200 rounded-lg px-3 py-2">

            <div class="text-[10px] uppercase text-slate-500 tracking-wide">
                Total Budget
            </div>

            <div class="text-sm font-semibold text-slate-800">
                {{ peso($budget) }}
            </div>

        </div>


        {{-- INFO --}}
        <div class="flex items-center justify-between text-xs">

            {{-- LEFT MESSAGE --}}
            <div class="text-slate-600">

                @if($status === 'approved_by_sacdev')
                    ✔ Fully approved

                @elseif($status === 'submitted')
                    Under review

                @elseif($status === 'draft')
                    Not yet submitted

                @elseif($status === 'returned')
                    Needs revision

                @endif

            </div>

            {{-- RIGHT AWAITING --}}
            @if($status === 'submitted' && $currentApprover)
                <div class="text-slate-500">
                    Awaiting
                    <span class="font-semibold text-slate-700 capitalize">
                        {{ str_replace('_',' ', $currentApprover->role) }}
                    </span>
                </div>
            @endif

        </div>

    </div>

</a>