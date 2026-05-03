@php
    $proposalDoc = $project->documents->where('form_type_id', 1)->first();
    $solicitationDoc = $project->documents->where('form_type_id', 7)->first();
    $ticketDoc = $project->documents->where('form_type_id', 8)->first();
    $feesDoc = $project->documents->where('form_type_id', 10)->first();
    $sellingDoc = $project->documents->where('form_type_id', 9)->first();

    $solicitationTotal = collect($solicitationDoc?->solicitationSponsorshipReport?->items ?? [])
        ->sum('amount_given');

    $counterpartTotal = collect($feesDoc?->feesCollectionReport?->items ?? [])
        ->sum(fn ($i) => ($i->number_of_payers ?? 0) * ($i->amount_paid ?? 0));

    $ticketTotal = collect($ticketDoc?->ticketSellingReport?->items ?? [])
        ->sum('amount');

    $sellingTotal = collect($sellingDoc?->sellingActivityReport?->items ?? [])
        ->sum('amount');

    $fundraisingTotal = $solicitationTotal + $counterpartTotal + $ticketTotal + $sellingTotal;

    $proposalBudget = $proposalDoc?->proposalData?->total_budget ?? 0;

    $financeAmount = old('finance_amount', $report->finance_amount ?? '');
    $fundRaisingAmount = old('fund_raising_amount', $report->fund_raising_amount ?? $fundraisingTotal);
    $sacdevAmount = old('sacdev_amount', $report->sacdev_amount ?? '');
    $ptaAmount = old('pta_amount', $report->pta_amount ?? '');

    function peso($value) {
        return number_format((float) ($value ?? 0), 2);
    }
@endphp

<div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-blue-50 to-white shadow-sm overflow-hidden">



    <div class="p-4 space-y-5">

        <div class="flex items-start gap-3">
            <div class="w-9 h-9 rounded-xl bg-blue-50 border border-blue-200 flex items-center justify-center">
                <i data-lucide="bar-chart-3" class="w-4 h-4 text-blue-600"></i>
            </div>

            <div>
                <div class="text-sm font-semibold text-slate-900">
                    Fundraising Summary from Submitted Reports
                </div>
                <p class="text-xs text-blue-700 mt-1">
                    Auto-calculated totals based on submitted project reports.
                </p>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-5 gap-3 text-center">

            <div class="rounded-xl border border-slate-200 bg-white px-3 py-3">
                <div class="text-[11px] text-slate-500">Solicitation</div>
                <div class="text-sm font-semibold text-slate-900">₱ {{ peso($solicitationTotal) }}</div>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white px-3 py-3">
                <div class="text-[11px] text-slate-500">Counterpart</div>
                <div class="text-sm font-semibold text-slate-900">₱ {{ peso($counterpartTotal) }}</div>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white px-3 py-3">
                <div class="text-[11px] text-slate-500">Ticket Selling</div>
                <div class="text-sm font-semibold text-slate-900">₱ {{ peso($ticketTotal) }}</div>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white px-3 py-3">
                <div class="text-[11px] text-slate-500">Selling</div>
                <div class="text-sm font-semibold text-slate-900">₱ {{ peso($sellingTotal) }}</div>
            </div>

            <div class="rounded-xl border border-blue-200 bg-blue-50 px-3 py-3">
                <div class="text-[11px] text-blue-700 font-semibold">Fundraising Total</div>
                <div class="text-base font-bold text-blue-900">₱ {{ peso($fundraisingTotal) }}</div>
            </div>

        </div>

        <div class="border-t border-slate-200 pt-3 text-center">
            <div class="text-xs text-slate-500">Proposed Project Budget</div>
            <div class="text-base font-bold text-slate-900">₱ {{ peso($proposalBudget) }}</div>
        </div>

    </div>

</div>

<div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

    <div class="h-1 bg-blue-500"></div>

    <div class="px-6 py-5 border-b border-slate-200">
        <h2 class="text-base font-semibold text-slate-900">
            Cash Received (Source of Funds)
        </h2>
        <p class="text-sm text-slate-500 mt-1">
            Review all financial sources that contributed to the project. Fund Raising is auto-filled from related report totals.
        </p>
    </div>

    <div class="px-6 py-6 space-y-6">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div class="rounded-xl border border-slate-200 p-5 bg-slate-50/40">

                <div class="text-xs font-semibold text-slate-600 uppercase tracking-wide mb-4 text-center">
                    Cluster A (Internal Sources)
                </div>

                <div class="space-y-4">

                    <div>
                        <label class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                            XU Finance
                        </label>

                        <div class="relative mt-2">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-slate-400">₱</span>
                            <input
                                type="text"
                                name="finance_amount"
                                data-money
                                value="{{ $financeAmount !== '' ? number_format((float)$financeAmount, 2) : '' }}"
                                placeholder="0.00"
                                class="w-full rounded-lg border border-slate-300 pl-7 pr-3 py-2 text-sm
                                    {{ $errors->has('finance_amount') ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500' : 'focus:ring-2 focus:ring-blue-500 focus:border-blue-500' }}"
                            >
                        </div>

                        <p class="text-[11px] text-slate-400 mt-1">
                            Funds directly allocated by university finance.
                        </p>
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                            Fund Raising
                        </label>

                        <div class="relative mt-2">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-slate-400">₱</span>

                            @php
                               $hasExisting = optional(
                                    $project->documents->where('form_type_id',12)->first()?->liquidationData
                                )->fund_raising_amount;
                                $suggested = $fundraisingTotal ?? 0;
                            @endphp

                            <input
                                type="text"
                                name="fund_raising_amount"
                                id="fundRaisingAmount"
                                data-money
                                value="{{ old(
                                    'fund_raising_amount',
                                    $hasExisting !== null
                                        ? number_format($hasExisting, 2)
                                        : number_format($suggested, 2)
                                ) }}"
                                placeholder="0.00"
                                data-suggested="{{ $suggested }}"
                                class="w-full rounded-lg border border-slate-300 pl-7 pr-3 py-2 text-sm
                                    {{ $hasExisting ? 'bg-white text-slate-900' : 'bg-blue-50 text-blue-900 font-semibold' }}"
                            >
                        </div>

                        {{-- helper --}}
                        @if(!$hasExisting)
                        <p class="text-[11px] text-blue-700 mt-1">
                            Suggested from reports: ₱ {{ number_format($suggested, 2) }}
                        </p>
                        @endif

                        <p class="text-[11px] text-blue-700 mt-1">
                            Auto-filled from Solicitation, Counterpart, Ticket Selling, and Selling reports.
                        </p>
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                            SACDEV Support
                        </label>

                        <div class="relative mt-2">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-slate-400">₱</span>
                            <input
                                type="text"
                                name="sacdev_amount"
                                data-money
                                value="{{ $sacdevAmount !== '' ? number_format((float)$sacdevAmount, 2) : '' }}"
                                placeholder="0.00"
                                class="w-full rounded-lg border border-slate-300 pl-7 pr-3 py-2 text-sm
                                    {{ $errors->has('sacdev_amount') ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500' : 'focus:ring-2 focus:ring-blue-500 focus:border-blue-500' }}"
                            >
                        </div>

                        <p class="text-[11px] text-slate-400 mt-1">
                            Financial assistance provided by SACDEV.
                        </p>
                    </div>

                </div>

            </div>

            <div class="rounded-xl border border-slate-200 p-5 bg-slate-50/40">

                <div class="text-xs font-semibold text-slate-600 uppercase tracking-wide mb-4 text-center">
                    Cluster B (External Sources)
                </div>

                <div>
                    <label class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                        PTA / College / Department
                    </label>

                    <div class="relative mt-2">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-slate-400">₱</span>
                        <input
                            type="text"
                            name="pta_amount"
                            data-money
                            value="{{ $ptaAmount !== '' ? number_format((float)$ptaAmount, 2) : '' }}"
                            placeholder="0.00"
                            class="w-full rounded-lg border border-slate-300 pl-7 pr-3 py-2 text-sm
                                {{ $errors->has('pta_amount') ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500' : 'focus:ring-2 focus:ring-blue-500 focus:border-blue-500' }}"
                        >
                    </div>

                    <p class="text-[11px] text-slate-400 mt-1">
                        Contributions from partner offices, departments, or external sponsors.
                    </p>
                </div>

            </div>

        </div>

        <div class="pt-4 border-t border-slate-200">

            <div class="text-sm font-semibold text-slate-900 mb-2">
                Fundraising Breakdown
            </div>

            @php
                $autoSources = [];

                if ($solicitationTotal > 0) $autoSources[] = 'solicitation';
                if ($counterpartTotal > 0) $autoSources[] = 'counterpart';
                if ($ticketTotal > 0) $autoSources[] = 'ticket_selling';
                if ($sellingTotal > 0) $autoSources[] = 'selling';

                $selected = old('fundraising_types', $report->fundraising_types ?? $autoSources);
            @endphp

<div class="flex flex-wrap gap-3">

    <label class="flex items-center gap-2 text-sm border border-slate-200 rounded-lg px-3 py-2 bg-slate-50">
        @if($solicitationTotal > 0)
            <input type="checkbox" checked disabled>
            <input type="hidden" name="fundraising_types[]" value="solicitation">
        @else
            <input type="checkbox" disabled>
        @endif
        Solicitation
    </label>

    <label class="flex items-center gap-2 text-sm border border-slate-200 rounded-lg px-3 py-2 bg-slate-50">
        @if($counterpartTotal > 0)
            <input type="checkbox" checked disabled>
            <input type="hidden" name="fundraising_types[]" value="counterpart">
        @else
            <input type="checkbox" disabled>
        @endif
        Counterpart
    </label>

    <label class="flex items-center gap-2 text-sm border border-slate-200 rounded-lg px-3 py-2 bg-slate-50">
        @if($ticketTotal > 0)
            <input type="checkbox" checked disabled>
            <input type="hidden" name="fundraising_types[]" value="ticket_selling">
        @else
            <input type="checkbox" disabled>
        @endif
        Ticket Selling
    </label>

    <label class="flex items-center gap-2 text-sm border border-slate-200 rounded-lg px-3 py-2 bg-slate-50">
        @if($sellingTotal > 0)
            <input type="checkbox" checked disabled>
            <input type="hidden" name="fundraising_types[]" value="selling">
        @else
            <input type="checkbox" disabled>
        @endif
        Selling
    </label>

</div>

        </div>

    </div>

</div>

<script>
    
document.querySelectorAll('[data-money]').forEach(input => {

    const clean = (value) => {
        return value
            .replace(/,/g, '')
            .replace(/[^\d.]/g, '')
            .replace(/(\..*)\./g, '$1');
    };

    const format = (value) => {
        value = clean(value);

        if (value === '' || value === '.') {
            return '';
        }

        const number = Number(value);

        if (Number.isNaN(number)) {
            return '';
        }

        return number.toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    };

    input.addEventListener('input', e => {
        const cursor = e.target.selectionStart;
        const oldLength = e.target.value.length;

        let value = clean(e.target.value);

        e.target.value = value;

        const newLength = e.target.value.length;
        const diff = newLength - oldLength;

        const newCursor = Math.max(0, cursor + diff);
        e.target.setSelectionRange(newCursor, newCursor);
    });

    input.addEventListener('blur', e => {
        e.target.value = format(e.target.value);
    });

});


</script>