@php
$sections = [
    'cash_advance'       => 'A. For Cash Advance (Finance Office)',
    'fund_transfer'      => 'B. For Fund Transfer / Direct Payment to Supplier',
    'xucmpc'             => 'C. For XUCMPC',
    'bookstore'          => 'D. For Bookstore',
    'central_purchasing' => 'E. For Central Purchasing Unit',
    'counterpart'        => 'F. Counterpart'
];
@endphp

{{-- PROPOSAL BUDGET VALUE --}}
<input type="hidden" id="proposal_total_budget"
    value="{{ $project->proposalDocument?->proposalData?->total_budget ?? 0 }}">

@foreach($sections as $code => $label)

<div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden mb-6" data-budget-section="{{ $code }}">

    {{-- HEADER --}}
    <div class="bg-slate-50 border-b border-slate-200 px-6 py-3">
        <h3 class="text-sm font-semibold text-slate-800 tracking-wide uppercase text-center">
            {{ $label }}
        </h3>
    </div>

    <div class="px-6 py-6">

        {{-- TABLE HEADER --}}
        <div class="grid grid-cols-12 gap-3 text-xs font-semibold text-slate-500 border-b border-slate-200 pb-3 mb-4">
            <div class="col-span-1 text-center">Qty</div>
            <div class="col-span-2 text-center">Unit</div>
            <div class="col-span-4">Particulars</div>
            <div class="col-span-2 text-right">Price</div>
            <div class="col-span-2 text-right">Amount</div>
            <div class="col-span-1"></div>
        </div>

        {{-- ROWS --}}
        <div class="space-y-3" id="{{ $code }}_container">

            @php
                $items = $budget?->items?->where('section', $code) ?? collect();
            @endphp

            @foreach($items as $item)

            <div class="grid grid-cols-12 gap-3 items-center" data-budget-row>

                <input type="hidden" data-section="{{ $code }}">

                <div class="col-span-1">
                    <input type="number" step="1" value="{{ $item->qty }}"
                        name="{{ $code }}[qty][]"
                        class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm text-center">
                </div>

                <div class="col-span-2">
                    <input type="text" value="{{ $item->unit }}"
                        name="{{ $code }}[unit][]"
                        class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm text-center">
                </div>

                <div class="col-span-4">
                    <input type="text" value="{{ $item->particulars }}"
                        name="{{ $code }}[particulars][]"
                        class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm">
                </div>

                <div class="col-span-2">
                    <input type="number" step="0.01" value="{{ $item->price_per_unit }}"
                        name="{{ $code }}[price][]"
                        class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm text-right">
                </div>

                <div class="col-span-2 text-right font-semibold tabular-nums">
                    ₱ <span data-amount>{{ number_format($item->amount, 2) }}</span>
                    <input type="hidden" name="{{ $code }}[amount][]" value="{{ $item->amount }}">
                </div>

                <div class="col-span-1 text-center">
                    <button type="button" data-remove-budget class="text-red-500 text-xs hover:underline">
                        Remove
                    </button>
                </div>

            </div>

            @endforeach

        </div>

        {{-- SECTION TOTAL --}}
        <div class="border-t border-slate-200 mt-6 pt-4 flex justify-end gap-3">
            <div class="text-xs text-slate-500 uppercase">Section Total</div>
            <div class="font-semibold text-slate-900 tabular-nums">
                ₱ <span id="{{ $code }}_total" data-section-total>0.00</span>
            </div>
        </div>

        {{-- ADD BUTTON --}}
        <div class="mt-4">
            <button type="button"
                data-add-budget="{{ $code }}"
                class="text-xs px-3 py-2 border border-slate-200 rounded-lg hover:bg-slate-50">
                + Add Item
            </button>
        </div>

    </div>

</div>

@endforeach


{{-- GRAND TOTAL --}}
<div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden mt-6">

    <div class="bg-slate-50 border-b border-slate-200 px-6 py-3 text-center">
        <h3 class="text-sm font-semibold text-slate-800 uppercase">
            Grand Total
        </h3>
    </div>

    <div class="px-6 py-6 flex justify-between items-center">

        <div>
            <div class="text-xs text-slate-500">Total Expenses</div>

            {{-- INDICATOR --}}
            <div id="budget_indicator" class="text-xs mt-1 hidden"></div>
        </div>

        <div class="text-xl font-bold text-slate-900 tabular-nums">
            ₱ <span id="grand_total">0.00</span>
        </div>

    </div>

</div>