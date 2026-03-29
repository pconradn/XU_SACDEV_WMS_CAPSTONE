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
            <input type="number" step="0.01" min="0"
                id="pta_input"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
        </div>

        {{-- COUNTERPART --}}
        <div>
            <label class="block text-xs font-medium text-slate-700 mb-1">
                Counterpart (Per Pax × Participants)
            </label>

            <div class="flex gap-2">
                <input type="number" step="0.01"
                    id="counterpart_amount"
                    placeholder="Amount"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">

                <input type="number"
                    id="counterpart_pax"
                    placeholder="Pax"
                    class="w-24 rounded-lg border border-slate-300 px-3 py-2 text-sm text-center">
            </div>
        </div>

    </div>

    <div>
        <label class="block text-xs font-medium text-slate-700 mb-2">
            Raised Funds (Auto-totaled)
        </label>

        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">

            @foreach(['Solicitation','Ticket-Selling','Others'] as $src)
                <input type="number"
                    step="0.01"
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

    function computeAll() {

        const pta = parseFloat(document.getElementById('pta_input').value) || 0;

        const cpAmount = parseFloat(document.getElementById('counterpart_amount').value) || 0;
        const cpPax = parseFloat(document.getElementById('counterpart_pax').value) || 0;
        const counterpartTotal = cpAmount * cpPax;

        let raised = 0;

        document.querySelectorAll('.raised-input').forEach(input => {
            raised += parseFloat(input.value) || 0;
        });

        const total = pta + counterpartTotal + raised;

        // DISPLAY
        document.getElementById('combined_total_display').innerText = total.toFixed(2);

        // PROPOSAL
        document.getElementById('hidden_pta').value = pta;
        document.getElementById('hidden_counterpart').value = counterpartTotal;

        document.getElementById('hidden_solicitation').value =
            document.querySelector('[data-raised="Solicitation"]').value || 0;

        document.getElementById('hidden_ticket').value =
            document.querySelector('[data-raised="Ticket-Selling"]').value || 0;

        document.getElementById('hidden_others').value =
            document.querySelector('[data-raised="Others"]').value || 0;

        document.getElementById('hidden_total_budget').value = total;

        // BUDGET
        document.getElementById('hidden_budget_pta').value = pta;
        document.getElementById('hidden_budget_counterpart').value = counterpartTotal;
        document.getElementById('hidden_budget_raised').value = raised;
        document.getElementById('hidden_budget_total').value = total;

    }

    document.addEventListener('input', computeAll);

    computeAll();

});
</script>