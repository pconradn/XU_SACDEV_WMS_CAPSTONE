<div>

    @if($expectedBudget > 0 || $this->hasItems)

    <div class="text-sm font-semibold text-slate-900 mb-2">
        Budget Proposal Section
    </div>

    @foreach($sections as $code => $label)

    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden mb-6">

        <div class="h-1 bg-blue-500"></div>

        <div class="px-5 py-3 border-b border-slate-200 bg-slate-50">
            <h3 class="text-sm font-semibold text-slate-900">
                {{ $label }}
            </h3>
        </div>

        <div class="px-5 py-5 space-y-3">

            {{-- HEADER --}}
            <div class="grid grid-cols-12 gap-2 text-[11px] font-semibold text-slate-500 border-b pb-2">
                <div class="col-span-1 text-center">Qty</div>
                <div class="col-span-2 text-center">Unit</div>
                <div class="col-span-4">Particulars</div>
                <div class="col-span-2 text-right">Price</div>
                <div class="col-span-2 text-right">Amount</div>
                <div class="col-span-1"></div>
            </div>

            {{-- ROWS --}}
            @foreach($items[$code] as $i => $row)

            <div class="grid grid-cols-12 gap-2 items-center">

                <div class="col-span-1">
                    <input type="number"
                        wire:model.lazy="items.{{ $code }}.{{ $i }}.qty"
                        class="w-full text-center border rounded-lg text-sm">
                </div>

                <div class="col-span-2">
                    <input type="text"
                        wire:model.lazy="items.{{ $code }}.{{ $i }}.unit"
                        class="w-full border rounded-lg text-sm">
                </div>

                <div class="col-span-4">
                    <input type="text"
                        wire:model.lazy="items.{{ $code }}.{{ $i }}.particulars"
                        class="w-full border rounded-lg text-sm">
                </div>

                <div class="col-span-2">
                    <input type="text"
                        wire:model.lazy="items.{{ $code }}.{{ $i }}.price"
                        class="w-full text-right border rounded-lg text-sm"
                        inputmode="decimal"
                        oninput="this.value = this.value.replace(/[^0-9.,]/g, '')"
                        onblur="this.value = Number(this.value.replace(/,/g,'') || 0).toLocaleString('en-PH', {minimumFractionDigits:2, maximumFractionDigits:2})">
                </div>

                <div class="col-span-2 text-right font-semibold text-slate-900">
                    ₱ {{ number_format(
                        (int)($row['qty'] ?? 0) *
                        (float) str_replace(',', '', $row['price'] ?? 0),
                    2) }}
                </div>

                <div class="col-span-1 flex justify-end">
                    <button type="button"
                        wire:click="removeRow('{{ $code }}', {{ $i }})"
                        class="text-rose-500 text-xs hover:text-rose-700">
                        ✕
                    </button>
                </div>

            </div>

            @endforeach

            {{-- ADD --}}
            <button type="button"
                wire:click="addRow('{{ $code }}')"
                class="text-xs font-semibold text-blue-600 hover:text-blue-800">
                + Add Item
            </button>

            {{-- SECTION TOTAL --}}
            <div class="text-right font-semibold text-sm pt-2 border-t">
                ₱ {{ number_format($this->getSectionTotal($code), 2) }}
            </div>

        </div>

    </div>

    @endforeach

    {{-- GRAND TOTAL --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

        <div class="h-1 bg-emerald-500"></div>

        <div class="px-5 py-4 flex justify-between items-center">

            <div>
                <div class="text-sm font-medium text-slate-900">
                    Total Expenses
                </div>

                <div class="text-xs mt-1
                    {{ $this->isMatch ? 'text-emerald-600' : 'text-rose-600' }}">

                    {{ $this->isMatch ? 'Budget matched' : 'Mismatch with funding' }}

                    <div class="text-[10px] text-slate-400 mt-1">
                        Funding: ₱ {{ number_format($expectedBudget, 2) }} |
                        Expenses: ₱ {{ number_format($this->grandTotal, 2) }}
                    </div>

                </div>
            </div>

            <div class="text-xl font-bold text-emerald-600">
                ₱ {{ number_format($this->grandTotal, 2) }}
            </div>

        </div>

    </div>

    {{-- HIDDEN OUTPUT FOR CONTROLLER --}}
    @foreach($items as $section => $rows)
        @foreach($rows as $row)
            <input type="hidden" name="{{ $section }}[qty][]" value="{{ $row['qty'] }}">
            <input type="hidden" name="{{ $section }}[unit][]" value="{{ $row['unit'] }}">
            <input type="hidden" name="{{ $section }}[particulars][]" value="{{ $row['particulars'] }}">
            <input type="hidden" name="{{ $section }}[price][]" value="{{ str_replace(',', '', $row['price']) }}">
            <input type="hidden" name="{{ $section }}[amount][]" value="{{ 
                (int)($row['qty'] ?? 0) * (float) str_replace(',', '', $row['price'] ?? 0) 
            }}">
        @endforeach
    @endforeach

    <input type="hidden" name="total_expenses" value="{{ $this->grandTotal }}">

    @endif

</div>