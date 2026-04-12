@php
$sections = [
    'fund_transfer'      => 'Fund Transfer / Direct Payment',
    'xucmpc'             => 'XUCMPC',
    'bookstore'          => 'Bookstore',
    'central_purchasing' => 'Central Purchasing Unit',
    'cash_advance'       => 'Cash Advance (Finance Office)',
];
@endphp

<div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm overflow-hidden mb-4">
    <div class="px-5 py-4 flex items-center gap-3">
        <div class="p-2 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-600">
            <i data-lucide="clipboard-list" class="w-4 h-4"></i>
        </div>
        <div>
            <h2 class="text-sm font-semibold text-slate-900">Budget Proposal</h2>
            <p class="text-[11px] text-slate-500">Enter detailed expenses per section</p>
        </div>
    </div>
</div>

<input type="hidden" id="proposal_total_budget"
    value="{{ $project->proposalDocument?->proposalData?->total_budget ?? 0 }}">

@foreach($sections as $code => $label)

<div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden mb-5" data-budget-section="{{ $code }}">

    <div class="px-5 py-3 border-b border-slate-200 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <i data-lucide="folder" class="w-3.5 h-3.5 text-slate-500"></i>
            <h3 class="text-xs font-semibold text-slate-800">{{ $label }}</h3>
        </div>

        <button type="button"
            data-add-budget="{{ $code }}"
            class="text-[11px] font-semibold text-emerald-600 hover:text-emerald-800 transition">
            + Add Item
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-xs">
            <thead class="bg-slate-50 border-b border-slate-200 text-slate-500">
                <tr>
                    <th class="px-2 py-2 text-center w-16">Qty</th>
                    <th class="px-2 py-2 text-center w-24">Unit</th>
                    <th class="px-2 py-2 text-left">Particulars</th>
                    <th class="px-2 py-2 text-right w-28">Price</th>
                    <th class="px-2 py-2 text-right w-28">Amount</th>
                    <th class="px-2 py-2 w-10"></th>
                </tr>
            </thead>

            <tbody id="{{ $code }}_container" class="divide-y divide-slate-200">

                @php
                    $items = $budget?->items?->where('section', $code) ?? collect();
                @endphp

                @foreach($items as $item)

                <tr data-budget-row data-section="{{ $code }}" class="hover:bg-slate-50 transition">

                    <td class="px-1 py-2">
                        <input type="number" step="1" value="{{ $item->qty }}"
                            name="{{ $code }}[qty][]"
                            class="w-full rounded-lg border border-slate-300 px-2 py-1 text-xs text-center focus:ring-2 focus:ring-emerald-500">
                    </td>

                    <td class="px-2 py-2">
                        <input type="text" value="{{ $item->unit }}"
                            name="{{ $code }}[unit][]"
                            class="w-full rounded-lg border border-slate-300 px-2 py-1 text-xs text-center focus:ring-2 focus:ring-emerald-500">
                    </td>

                    <td class="px-2 py-2">
                        <input type="text" value="{{ $item->particulars }}"
                            name="{{ $code }}[particulars][]"
                            class="w-full rounded-lg border border-slate-300 px-2 py-1 text-xs focus:ring-2 focus:ring-emerald-500">
                    </td>

                    <td class="px-2 py-2">
                        <input type="text"
                            inputmode="decimal"
                            value="{{ number_format($item->price_per_unit, 2) }}"
                            name="{{ $code }}[price][]"
                            class="money-input w-full rounded-lg border border-slate-300 px-2 py-1 text-xs text-right focus:ring-2 focus:ring-emerald-500">
                    </td>

                    <td class="px-2 py-2 text-right">
                        <div class="flex items-center justify-end gap-1 font-semibold text-slate-900 tabular-nums text-sm">
                            <span class="text-[10px] text-slate-400">₱</span>
                            <span data-amount class="tracking-tight">
                                {{ number_format($item->amount, 2) }}
                            </span>
                        </div>
                        <input type="hidden" name="{{ $code }}[amount][]" value="{{ $item->amount }}">
                    </td>

                    <td class="px-2 py-2 text-right">
                        <button type="button" data-remove-budget
                            class="text-slate-400 hover:text-rose-600 transition">
                            <i data-lucide="x" class="w-3.5 h-3.5"></i>
                        </button>
                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>
    </div>

    <div class="px-5 py-3 border-t border-slate-200 flex justify-end">
        <div class="flex items-center gap-2 bg-slate-50 border border-slate-200 rounded-xl px-3 py-2">
            <span class="text-[10px] text-slate-500 uppercase tracking-wide">Total</span>
            <span class="text-sm font-semibold text-slate-900 tabular-nums tracking-tight">
                ₱ <span id="{{ $code }}_total" data-section-total>0.00</span>
            </span>
        </div>
    </div>

</div>

@endforeach

<div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden mt-6">

    <div class="px-5 py-4 flex justify-between items-center">

        <div class="flex items-center gap-2">
            <i data-lucide="calculator" class="w-4 h-4 text-emerald-600"></i>
            <div>
                <div class="text-sm font-semibold text-slate-900">Grand Total</div>
                <div id="budget_indicator" class="text-[11px] text-slate-500 hidden"></div>
            </div>
        </div>

        <div class="bg-emerald-50 border border-emerald-200 rounded-xl px-4 py-2">
            <div class="flex items-center gap-1 text-emerald-700 tabular-nums">
                <span class="text-xs">₱</span>
                <span id="grand_total" class="text-xl font-semibold tracking-tight">
                    0.00
                </span>
            </div>
        </div>

    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', () => {

    function parse(val) {
        return Number((val || '').replace(/,/g, '')) || 0;
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

document.querySelector('form').addEventListener('submit', () => {

    document.querySelectorAll('.money-input').forEach(input => {
        input.value = Number((input.value || '').replace(/,/g, '')) || 0;
    });

});
</script>