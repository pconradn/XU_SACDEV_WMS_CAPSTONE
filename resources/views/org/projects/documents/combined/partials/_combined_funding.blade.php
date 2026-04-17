@php
    use Illuminate\Support\Str;

    $sources = [
        'OSA-SACDEV',
        'Finance Office',
        'PTA',
        'Counterpart',
        'Solicitation',
        'Ticket-Selling',
        'Others',
    ];
@endphp

@php
    $proposalData = $project->proposalDocument?->proposalData;

    $fundSources = $proposalData?->fundSources
        ? $proposalData->fundSources->pluck('amount', 'source_name')->toArray()
        : [];
@endphp

<div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm overflow-hidden">

    <div class="h-1 bg-gradient-to-r from-emerald-500 to-blue-500"></div>

    <div class="p-4 space-y-5">

        <div class="flex items-center gap-3">
            <div class="p-2 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-600">
                <i data-lucide="wallet" class="w-4 h-4"></i>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-slate-900">
                    Funding & Budget Summary
                </h3>
                <p class="text-[11px] text-slate-500">
                    Unified funding inputs (auto-syncs proposal & budget)
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 rounded-xl border border-slate-200 bg-white p-4">

            <div>
                <label class="block text-[11px] font-medium text-slate-700 mb-1">
                    Finance Office (₱)
                </label>
                <p class="text-[11px] text-emerald-600 mb-2">
                    Allocated support from finance unit
                </p>

            <input type="text"
                inputmode="decimal"
                data-name="finance_amount"
                id="finance_input"
                value="{{ old('fund_sources.Finance Office', $fundSources['Finance Office'] ?? 0) }}"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-xs 
                focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition">
            </div>

            <div>
                <label class="block text-[11px] font-medium text-slate-700 mb-1">
                    OSA-SACDEV (₱)
                </label>
                <p class="text-[11px] text-emerald-600 mb-2">
                    Institutional funding allocation
                </p>

                <input type="text"
                    inputmode="decimal"
                    data-name="osa_amount"
                    id="osa_input"
                    value="{{ old('fund_sources.OSA-SACDEV', $fundSources['OSA-SACDEV'] ?? 0) }}"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-xs 
                    focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition">
            </div>

            <div>
                <label class="block text-[11px] font-medium text-slate-700 mb-1">
                    PTA Contribution (₱)
                </label>
                <p class="text-[11px] text-emerald-600 mb-2">
                    Direct organizational funding support
                </p>

                <input type="text"
                    inputmode="decimal"
                    data-name="pta_amount"
                    id="pta_input"
                    value="{{ old('pta_amount', $budget?->pta_amount ?? 0) }}"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-xs 
                    focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition">
            </div>

            <div class="space-y-2">
                <label class="block text-[11px] font-medium text-slate-700">
                    Counterpart Funding (₱)
                </label>
                <p class="text-[11px] text-emerald-600">
                    Amount per participant × total participants
                </p>

                <div class="grid grid-cols-2 gap-2">

                    <input type="text"
                        inputmode="decimal"
                        id="counterpart_amount"
                        name="counterpart_amount_per_pax"
                        value="{{ old('counterpart_amount_per_pax', $budget?->counterpart_amount_per_pax ?? 0) }}"
                        placeholder="Amount"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-xs 
                        focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition">

                    <input type="number"
                        id="counterpart_pax"
                        name="counterpart_pax"
                        value="{{ old('counterpart_pax', $budget?->counterpart_pax ?? 0) }}"
                        placeholder="Participants"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-xs text-center 
                        focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition">

                </div>
            </div>

        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-4 space-y-3">

            <div class="flex items-center gap-2">
                <i data-lucide="trending-up" class="w-3.5 h-3.5 text-emerald-600"></i>
                <span class="text-[11px] font-semibold text-slate-700 uppercase tracking-wide">
                    Raised Funds 
                </span>
            </div>

            <p class="text-[11px] text-emerald-600">
                Estimated income from fundraising activities
            </p>



            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">

                @foreach(['Solicitation','Ticket-Selling','Others'] as $src)
                    <div>
                        <label class="block text-[11px] font-medium text-slate-700 mb-1">
                            {{ $src }} (₱)
                        </label>

                        <input type="text"
                            inputmode="decimal"
                            value="{{ old('fund_sources.'.$src, $fundSources[$src] ?? 0) }}"
                            data-raised="{{ $src }}"
                            class="raised-input w-full rounded-lg border border-slate-300 px-3 py-2 text-xs 
                            focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition"
                            placeholder="0.00">
                    </div>
                @endforeach

            </div>

        </div>

        <div class="flex items-center justify-between pt-4 border-t border-slate-200">

            <div>
                <div class="text-sm font-semibold text-slate-900">
                    Total Budget
                </div>
                <div class="text-[11px] text-emerald-600">
                    Automatically calculated from all sources
                </div>
            </div>
        <div class="bg-emerald-50 border border-emerald-200 rounded-xl px-4 py-2">
            <div class="flex items-center gap-1 text-emerald-700 tabular-nums">
                <span class="text-xs">₱</span> <span id="combined_total_display">0.00</span>
            </div>
        </div>
        </div>

    </div>

</div>

<input type="hidden" name="fund_sources[PTA]" id="hidden_pta">
<input type="hidden" name="fund_sources[Counterpart]" id="hidden_counterpart">
<input type="hidden" name="fund_sources[Solicitation]" id="hidden_solicitation">
<input type="hidden" name="fund_sources[Ticket-Selling]" id="hidden_ticket">
<input type="hidden" name="fund_sources[Others]" id="hidden_others">
<input type="hidden" name="fund_sources[OSA-SACDEV]" id="hidden_osa_sacdev">
<input type="hidden" name="fund_sources[Finance Office]" id="hidden_finance">

<input type="hidden" name="total_budget" id="hidden_total_budget">

<input type="hidden" name="pta_amount" id="hidden_budget_pta">
<input type="hidden" name="counterpart_total" id="hidden_budget_counterpart">
<input type="hidden" name="raised_funds" id="hidden_budget_raised">
<input type="hidden" name="org_total" id="hidden_budget_total">

<script>
document.addEventListener('DOMContentLoaded', () => {

    function parseNumber(value) {
        return Number((value || '').toString().replace(/,/g, '')) || 0;
    }

    function formatNumber(value) {
        const num = parseFloat(value) || 0;
        return num.toLocaleString('en-PH', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    function computeAll() {

        const finance = parseNumber(document.getElementById('finance_input').value);
        const osa = parseNumber(document.getElementById('osa_input').value);

        const pta = parseNumber(document.getElementById('pta_input').value);

        const cpAmount = parseNumber(document.getElementById('counterpart_amount').value);
        const cpPax = parseFloat(document.getElementById('counterpart_pax').value) || 0;
        const counterpartTotal = cpAmount * cpPax;

        let raised = 0;

        document.querySelectorAll('.raised-input').forEach(input => {
            raised += parseNumber(input.value);
        });

        const total = pta + finance + osa + counterpartTotal + raised;

        document.getElementById('combined_total_display').innerText = formatNumber(total);
        document.getElementById('hidden_finance').value = finance;
        document.getElementById('hidden_osa_sacdev').value = osa;
        document.getElementById('hidden_pta').value = pta;

        document.getElementById('hidden_counterpart').value = counterpartTotal;

        document.getElementById('hidden_solicitation').value =
            parseNumber(document.querySelector('[data-raised="Solicitation"]').value);

        document.getElementById('hidden_ticket').value =
            parseNumber(document.querySelector('[data-raised="Ticket-Selling"]').value);

        document.getElementById('hidden_others').value =
            parseNumber(document.querySelector('[data-raised="Others"]').value);

        document.getElementById('hidden_total_budget').value = total;

        document.getElementById('hidden_budget_pta').value = pta;
        document.getElementById('hidden_budget_counterpart').value = counterpartTotal;
        document.getElementById('hidden_budget_raised').value = raised;
        document.getElementById('hidden_budget_total').value = total;

    }

    function attachFormatting(input) {

        input.addEventListener('focus', () => {
            input.value = parseNumber(input.value) || 0;
        });

        input.addEventListener('blur', () => {
            input.value = formatNumber(parseNumber(input.value));
        });

    }

    document.querySelectorAll('#pta_input, #finance_input, #osa_input, #counterpart_amount, .raised-input')
        .forEach(input => {
            attachFormatting(input);

            if (!input.value) {
                input.value = '0.00';
            } else {
                input.value = formatNumber(parseNumber(input.value));
            }
        });

    document.querySelector('form').addEventListener('submit', () => {

        document.querySelectorAll('#pta_input, #finance_input, #osa_input, #counterpart_amount, .raised-input')
            .forEach(input => {
                input.value = parseNumber(input.value);
            });

        const cpAmountInput = document.getElementById('counterpart_amount');
        cpAmountInput.value = formatNumber(parseNumber(cpAmountInput.value));

        const cpPaxInput = document.getElementById('counterpart_pax');
        cpPaxInput.value = parseInt(cpPaxInput.value) || 0;

        computeAll();
    });

    document.addEventListener('input', () => {
        computeAll();
        setTimeout(checkBudgetMatch, 0);
        setTimeout(checkBudgetMatch, 0);
    });

    computeAll();
    setTimeout(checkBudgetMatch, 0);

});
</script>