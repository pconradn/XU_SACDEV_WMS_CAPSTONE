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

<div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

    {{-- ACCENT --}}
    <div class="h-1 bg-blue-500"></div>

    <div class="p-5 space-y-6">

        {{-- ================= HEADER ================= --}}
        <div>
            <h3 class="text-sm font-semibold text-slate-900">
                Funding & Budget Summary
            </h3>
            <p class="text-xs text-blue-700">
                Unified funding inputs (auto-syncs proposal & budget)
            </p>
        </div>

        {{-- ================= CORE FUNDING ================= --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border border-slate-200 rounded-xl p-4">

            {{-- PTA --}}
            <div>
                <label class="block text-xs font-medium text-slate-700 mb-1">
                    PTA Contribution
                </label>
                <p class="text-xs text-blue-700 mb-2">
                    Direct funding provided by the organization
                </p>

                <input type="text"
                    inputmode="decimal"
                    id="pta_input"
                    value="0.00"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm 
                           focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition">
            </div>

            {{-- COUNTERPART --}}
            <div>
                <label class="block text-xs font-medium text-slate-700 mb-1">
                    Counterpart Funding
                </label>
                <p class="text-xs text-blue-700 mb-2">
                    Amount per participant multiplied by total participants
                </p>

                <div class="grid grid-cols-2 gap-2">

                    <input type="text"
                        inputmode="decimal"
                        id="counterpart_amount"
                        name="counterpart_amount_per_pax"
                        value="0.00"
                        placeholder="Amount per pax"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm 
                               focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition">

                    <input type="number"
                        id="counterpart_pax"
                        name="counterpart_pax"
                        value="0"
                        placeholder="Pax"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-center 
                               focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition">

                </div>
            </div>

        </div>

        {{-- ================= RAISED FUNDS ================= --}}
        <div class="border border-slate-200 rounded-xl p-4">

            <div class="mb-3">
                <h4 class="text-xs font-semibold text-slate-900 uppercase tracking-wide">
                    Raised Funds
                </h4>
                <p class="text-xs text-blue-700">
                    Enter estimated income from fundraising activities
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                @foreach(['Solicitation','Ticket-Selling','Others'] as $src)
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1">
                            {{ $src }}
                        </label>

                        <input type="text"
                            inputmode="decimal"
                            value="0.00"
                            data-raised="{{ $src }}"
                            class="raised-input w-full rounded-lg border border-slate-300 px-3 py-2 text-sm 
                                   focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition"
                            placeholder="0.00">
                    </div>
                @endforeach

            </div>

        </div>

        {{-- ================= TOTAL ================= --}}
        <div class="border-t pt-4 flex justify-between items-center">

            <div>
                <div class="text-sm font-medium text-slate-900">
                    Total Budget
                </div>
                <div class="text-xs text-blue-700">
                    Automatically calculated from all sources
                </div>
            </div>

            <div class="text-xl font-bold text-emerald-600">
                ₱ <span id="combined_total_display">0.00</span>
            </div>

        </div>

    </div>

</div>

{{-- ================= HIDDEN FIELDS (UNCHANGED) ================= --}}
<input type="hidden" name="fund_sources[PTA]" id="hidden_pta">
<input type="hidden" name="fund_sources[Counterpart]" id="hidden_counterpart">
<input type="hidden" name="fund_sources[Solicitation]" id="hidden_solicitation">
<input type="hidden" name="fund_sources[Ticket-Selling]" id="hidden_ticket">
<input type="hidden" name="fund_sources[Others]" id="hidden_others">

<input type="hidden" name="total_budget" id="hidden_total_budget">

<input type="hidden" name="pta_amount" id="hidden_budget_pta">
<input type="hidden" name="counterpart_total" id="hidden_budget_counterpart">
<input type="hidden" name="raised_funds" id="hidden_budget_raised">
<input type="hidden" name="org_total" id="hidden_budget_total">

{{-- ================= SCRIPT (UNCHANGED) ================= --}}
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

        document.getElementById('combined_total_display').innerText = formatNumber(total);

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

    document.querySelectorAll('#pta_input, #counterpart_amount, .raised-input')
        .forEach(input => {
            attachFormatting(input);

            if (!input.value) {
                input.value = '0.00';
            } else {
                input.value = formatNumber(parseNumber(input.value));
            }
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