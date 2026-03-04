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


@foreach($sections as $code => $label)

<div class="border border-slate-300 bg-white mb-6" data-budget-section="{{ $code }}">

    <div class="bg-slate-50 border-b border-slate-300 px-4 py-2">
        <div class="text-[12px] font-semibold text-slate-900 tracking-wide">
            {{ $label }}
        </div>
    </div>

    <div class="px-4 py-4">

        <div class="overflow-x-auto">
            <div class="min-w-[700px]">

                <div class="grid grid-cols-12 gap-2 text-[11px] font-semibold text-slate-600 uppercase tracking-wide border-b border-slate-200 pb-2 mb-2">
                    <div class="col-span-1 text-center">Qty</div>
                    <div class="col-span-2 text-center">Unit</div>
                    <div class="col-span-4">Particulars</div>
                    <div class="col-span-2 text-right">Price / Unit</div>
                    <div class="col-span-2 text-right">Amount</div>
                    <div class="col-span-1"></div>
                </div>

                <div class="space-y-2" id="{{ $code }}_container"></div>

                <div class="mt-3 border-t border-slate-200 pt-3">
                    <div class="flex justify-end items-center gap-3">
                        <div class="text-[11px] font-semibold text-slate-600 uppercase tracking-wide">
                            Section Total
                        </div>
                        <div class="text-[12px] font-semibold text-slate-900 tabular-nums">
                            ₱ <span id="{{ $code }}_total">0.00</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="mt-4">
            <button
                type="button"
                data-add-budget="{{ $code }}"
                class="border border-slate-300 bg-white px-3 py-1 text-[11px] font-medium text-slate-700 hover:bg-slate-50 rounded"
            >
                + Add Item
            </button>
        </div>

    </div>
</div>

@endforeach


<div class="border border-slate-300 bg-white">
    <div class="bg-slate-50 border-b border-slate-300 px-4 py-2 text-center">
        <div class="text-[12px] font-semibold text-slate-900 tracking-wide">
            GRAND TOTAL
        </div>
    </div>

    <div class="px-4 py-4">
        <div class="flex justify-end items-center gap-3">
            <div class="text-[11px] font-semibold text-slate-600 uppercase tracking-wide">
                Total Expenses
            </div>
            <div class="text-[14px] font-bold text-slate-900 tabular-nums">
                ₱ <span id="grand_total">0.00</span>
            </div>
        </div>
    </div>
</div>