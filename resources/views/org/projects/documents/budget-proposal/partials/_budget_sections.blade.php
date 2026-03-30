@php
$sections = [
    'fund_transfer'      => 'A. For Fund Transfer / Direct Payment to Supplier',
    'xucmpc'             => 'B. For XUCMPC',
    'bookstore'          => 'C. For Bookstore',
    'central_purchasing' => 'D. For Central Purchasing Unit',
    'cash_advance'       => 'E. For Cash Advance (Finance Office)',
];
@endphp

<div class="text-lg font-semibold text-slate-900 mb-2">
    Budget Proposal Section
</div>
<p class="text-xs text-blue-700 mb-4">
    Enter detailed expenses per section. Totals are calculated automatically.
</p>

<input type="hidden" id="proposal_total_budget"
    value="{{ $project->proposalDocument?->proposalData?->total_budget ?? 0 }}">

@foreach($sections as $code => $label)

<div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden mb-6" data-budget-section="{{ $code }}">

    {{-- ACCENT --}}
    <div class="h-1 bg-blue-500"></div>

    {{-- HEADER --}}
    <div class="px-6 py-3 border-b border-slate-200 bg-slate-50">
        <h3 class="text-sm font-semibold text-slate-900 tracking-wide">
            {{ $label }}
        </h3>
    </div>

    <div class="px-6 py-6 space-y-4">

        {{-- TABLE HEADER --}}
        <div class="grid grid-cols-12 gap-3 text-xs font-semibold text-slate-500 border-b border-slate-200 pb-3">
            <div class="col-span-1 text-center">Qty</div>
            <div class="col-span-2 text-center">Unit</div>
            <div class="col-span-4">Particulars</div>
            <div class="col-span-2 text-right">Price</div>
            <div class="col-span-2 text-right">Amount</div>
            <div class="col-span-1"></div>
        </div>

        {{-- ROWS --}}
        <div class="space-y-2" id="{{ $code }}_container">

            @php
                $items = $budget?->items?->where('section', $code) ?? collect();
            @endphp

            @foreach($items as $item)

            <div class="grid grid-cols-12 gap-3 items-center border border-slate-200 rounded-xl p-2 bg-white" data-budget-row>

                <input type="hidden" data-section="{{ $code }}">

                <div class="col-span-1">
                    <input type="number" step="1" value="{{ $item->qty }}"
                        name="{{ $code }}[qty][]"
                        class="w-full rounded-lg border border-slate-300 px-2 py-2 text-sm text-center 
                               focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="col-span-2">
                    <input type="text" value="{{ $item->unit }}"
                        name="{{ $code }}[unit][]"
                        class="w-full rounded-lg border border-slate-300 px-2 py-2 text-sm text-center 
                               focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="col-span-4">
                    <input type="text" value="{{ $item->particulars }}"
                        name="{{ $code }}[particulars][]"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm 
                               focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="col-span-2">
                    <input type="text"
                        inputmode="decimal"
                        value="{{ number_format($item->price_per_unit, 2) }}"
                        name="{{ $code }}[price][]"
                        class="money-input w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-right 
                               focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="col-span-2 text-right font-semibold tabular-nums text-slate-900">
                    ₱ <span data-amount>{{ number_format($item->amount, 2) }}</span>
                    <input type="hidden" name="{{ $code }}[amount][]" value="{{ $item->amount }}">
                </div>

                <div class="col-span-1 flex justify-end">
                    <button type="button" data-remove-budget
                        class="text-xs text-slate-400 hover:text-red-600 transition">
                        Remove
                    </button>
                </div>

            </div>

            @endforeach

        </div>

        {{-- SECTION TOTAL --}}
        <div class="border-t border-slate-200 pt-4 flex justify-end items-center gap-3">
            <div class="text-xs text-slate-500 uppercase">
                Section Total
            </div>
            <div class="font-semibold text-slate-900 tabular-nums">
                ₱ <span id="{{ $code }}_total" data-section-total>0.00</span>
            </div>
        </div>

        {{-- ADD BUTTON --}}
        <div>
            <button type="button"
                data-add-budget="{{ $code }}"
                class="text-xs font-semibold text-blue-600 hover:text-blue-800 transition">
                + Add Item
            </button>
        </div>

    </div>

</div>

@endforeach

{{-- GRAND TOTAL --}}
<div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden mt-6">

    <div class="h-1 bg-emerald-500"></div>

    <div class="px-6 py-3 border-b border-slate-200 bg-slate-50 text-center">
        <h3 class="text-sm font-semibold text-slate-900 uppercase">
            Grand Total
        </h3>
    </div>

    <div class="px-6 py-6 flex justify-between items-center">

        <div>
            <div class="text-sm font-medium text-slate-900">
                Total Expenses
            </div>
            <div id="budget_indicator" class="text-xs text-blue-700 mt-1 hidden"></div>
        </div>

        <div class="text-2xl font-bold text-emerald-600 tabular-nums">
            ₱ <span id="grand_total">0.00</span>
        </div>

    </div>

</div>

{{-- ================= MONEY FORMAT SCRIPT (SAFE ADDITION) ================= --}}
<script>
document.addEventListener('DOMContentLoaded', () => {

    function parse(val) {
        return parseFloat((val || '').replace(/,/g, '')) || 0;
    }

    function format(val) {
        return parse(val).toLocaleString('en-PH', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    document.querySelectorAll('.money-input').forEach(input => {

        input.addEventListener('focus', () => {
            input.value = parse(input.value);
        });

        input.addEventListener('blur', () => {
            input.value = format(input.value);
        });

    });

});
</script>