@php
    use Illuminate\Support\Str;

    $sources = [
        'PTA',
        'Counterpart',
        'Solicitation',
        'Ticket-Selling',
        'Others',
    ];
@endphp

<div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm space-y-6">

    <div>
        <h3 class="text-sm font-semibold text-slate-900">
            Funding & Budget Summary
        </h3>
        <p class="text-xs text-slate-500">
            Unified funding inputs (auto-syncs proposal & budget)
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- PTA --}}
        <div>
            <label class="block text-xs font-medium text-slate-700 mb-1">
                PTA Contribution
            </label>
            <input type="text"
                inputmode="decimal"
                id="pta_input"
                value="0.00"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
        </div>

        {{-- COUNTERPART --}}
        <div>
            <label class="block text-xs font-medium text-slate-700 mb-1">
                Counterpart (Per Pax × Participants)
            </label>

            <div class="flex gap-2">
                <input type="text"
                    inputmode="decimal"
                    id="counterpart_amount"
                    name="counterpart_amount_per_pax"
                    value="0.00"
                    placeholder="Amount"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">

                <input type="number"
                    id="counterpart_pax"
                    name="counterpart_pax"
                    value="0"
                    placeholder="Pax"
                    class="w-24 rounded-lg border border-slate-300 px-3 py-2 text-sm text-center">
            </div>
        </div>

    </div>

    {{-- RAISED FUNDS --}}
    <div>
        <label class="block text-xs font-medium text-slate-700 mb-2">
            Raised Funds (Auto-totaled)
        </label>

        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">

            @foreach(['Solicitation','Ticket-Selling','Others'] as $src)
                <input type="text"
                    inputmode="decimal"
                    value="0.00"
                    placeholder="{{ $src }}"
                    data-raised="{{ $src }}"
                    class="raised-input rounded-lg border border-slate-300 px-3 py-2 text-sm">
            @endforeach

        </div>
    </div>

    {{-- TOTAL --}}
    <div class="border-t pt-4 flex justify-between items-center">

        <div class="text-sm text-slate-500">
            Total Budget
        </div>

        <div class="text-lg font-bold text-slate-900">
            ₱ <span id="combined_total_display">0.00</span>
        </div>

    </div>

</div>

{{-- Proposal --}}
<input type="hidden" name="fund_sources[PTA]" id="hidden_pta">
<input type="hidden" name="fund_sources[Counterpart]" id="hidden_counterpart">
<input type="hidden" name="fund_sources[Solicitation]" id="hidden_solicitation">
<input type="hidden" name="fund_sources[Ticket-Selling]" id="hidden_ticket">
<input type="hidden" name="fund_sources[Others]" id="hidden_others">

<input type="hidden" name="total_budget" id="hidden_total_budget">

{{-- Budget --}}
<input type="hidden" name="pta_amount" id="hidden_budget_pta">
<input type="hidden" name="counterpart_total" id="hidden_budget_counterpart">
<input type="hidden" name="raised_funds" id="hidden_budget_raised">
<input type="hidden" name="org_total" id="hidden_budget_total">


<script>
document.addEventListener('DOMContentLoaded', () => {

    function parseNumber(value) {
        return parseFloat((value || '').toString().replace(/,/g, '')) || 0;
    }

    function formatNumber(value) {
        const num = parseFloat(value) || 0;
        return num.toLocaleString('en-PH', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    function computeAll() {

        const pta = parseNumber(document.getElementById('pta_input').value);

        const cpAmount = parseNumber(document.getElementById('counterpart_amount').value);
        const cpPax = parseFloat(document.getElementById('counterpart_pax').value) || 0;
        const counterpartTotal = cpAmount * cpPax;

        let raised = 0;

        document.querySelectorAll('.raised-input').forEach(input => {
            raised += parseNumber(input.value);
        });

        const total = pta + counterpartTotal + raised;

        // DISPLAY
        document.getElementById('combined_total_display').innerText = formatNumber(total);

        // PROPOSAL
        document.getElementById('hidden_pta').value = pta;
        document.getElementById('hidden_counterpart').value = counterpartTotal;

        document.getElementById('hidden_solicitation').value =
            parseNumber(document.querySelector('[data-raised="Solicitation"]').value);

        document.getElementById('hidden_ticket').value =
            parseNumber(document.querySelector('[data-raised="Ticket-Selling"]').value);

        document.getElementById('hidden_others').value =
            parseNumber(document.querySelector('[data-raised="Others"]').value);

        document.getElementById('hidden_total_budget').value = total;

        // BUDGET
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

    // Apply formatting
    document.querySelectorAll('#pta_input, #counterpart_amount, .raised-input')
        .forEach(input => {
            attachFormatting(input);

            // ensure default
            if (!input.value) {
                input.value = '0.00';
            } else {
                input.value = formatNumber(parseNumber(input.value));
            }
        });

    // Recompute on input
    document.addEventListener('input', computeAll);

    computeAll();

});
</script>