<div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm overflow-hidden">

    {{-- ACCENT --}}
    <div class="h-1 bg-blue-500"></div>

    <div class="p-4 space-y-6">

        {{-- HEADER --}}
        <div class="flex items-start justify-between gap-3">
            <div class="flex items-start gap-3">
                <div class="w-9 h-9 rounded-xl bg-blue-50 border border-blue-200 flex items-center justify-center">
                    <i data-lucide="shopping-bag" class="w-4 h-4 text-blue-600"></i>
                </div>

                <div>
                    <h3 class="text-sm font-semibold text-slate-900 tracking-wide">
                        Items to be Purchased
                    </h3>
                    <p class="text-xs text-blue-700 mt-1">
                        List all requested items including quantity, estimated cost, and supplier details.
                    </p>
                </div>
            </div>

            @if(!$isReadOnly)
            <button
                type="button"
                onclick="addPurchaseItemRow()"
                class="flex items-center gap-1 text-xs bg-blue-600 text-white px-3 py-2 rounded-lg hover:bg-blue-700 shadow-sm transition">
                <i data-lucide="plus" class="w-3 h-3"></i>
                Add
            </button>
            @endif
        </div>

        {{-- TABLE WRAP (IMPORTANT: KEEP TABLE UNTOUCHED) --}}
        <div class="rounded-xl border border-slate-200 bg-white overflow-x-auto">

            <div class="min-w-[900px]">

                <table class="min-w-full text-sm">

                    <thead class="bg-slate-50 text-slate-600 text-xs uppercase tracking-wide">
                        <tr>
                            <th class="px-3 py-2 text-left w-[80px]">Qty</th>
                            <th class="px-3 py-2 text-left w-[90px]">Unit</th>
                            <th class="px-3 py-2 text-left">Particulars</th>
                            <th class="px-3 py-2 text-left w-[130px]">Unit Price</th>
                            <th class="px-3 py-2 text-left w-[130px]">Amount</th>
                            <th class="px-3 py-2 text-left">Vendor</th>
                            @if(!$isReadOnly)
                            <th class="px-3 py-2 w-[60px] text-center">Action</th>
                            @endif
                        </tr>
                    </thead>

                    <tbody id="purchaseItemsTable" class="divide-y divide-slate-200">

                        @php
                            $items = old('items', $items ?? []);
                        @endphp

                        @if(count($items))

                            @foreach($items as $i => $item)
                            <tr class="hover:bg-slate-50 transition">

                                <td class="px-2 py-2">
                                    <input type="number"
                                        name="items[{{ $i }}][quantity]"
                                        value="{{ $item['quantity'] ?? '' }}"
                                        oninput="updateAmount(this)"
                                        placeholder="0"
                                        class="w-full rounded-lg px-2 py-1 text-sm
                                            {{ $errors->has("items.$i.quantity")
                                                ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500'
                                                : 'border-slate-300 focus:ring-blue-500 focus:border-blue-500' }}
                                            focus:ring-2 focus:outline-none transition"
                                        @if($isReadOnly) disabled @endif>
                                </td>

                                <td class="px-2 py-2">
                                    <input type="text"
                                        name="items[{{ $i }}][unit]"
                                        value="{{ $item['unit'] ?? '' }}"
                                        placeholder="pcs"
                                        class="w-full rounded-lg px-2 py-1 text-sm
                                            {{ $errors->has("items.$i.unit")
                                                ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500'
                                                : 'border-slate-300 focus:ring-blue-500 focus:border-blue-500' }}
                                            focus:ring-2 focus:outline-none transition"
                                        @if($isReadOnly) disabled @endif>
                                </td>

                                <td class="px-2 py-2">
                                    <input type="text"
                                        name="items[{{ $i }}][particulars]"
                                        value="{{ $item['particulars'] ?? '' }}"
                                        placeholder="Item description"
                                        class="w-full rounded-lg px-2 py-1 text-sm
                                            {{ $errors->has("items.$i.particulars")
                                                ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500'
                                                : 'border-slate-300 focus:ring-blue-500 focus:border-blue-500' }}
                                            focus:ring-2 focus:outline-none transition"
                                        @if($isReadOnly) disabled @endif>
                                </td>

                                <td class="px-2 py-2">
                                    <input type="text"
                                        inputmode="decimal"
                                        name="items[{{ $i }}][unit_price]"
                                        value="{{ $item['unit_price'] ?? '' }}"
                                        oninput="formatCurrencyInput(this); updateAmount(this)"
                                        placeholder="0.00"
                                        class="w-full rounded-lg px-2 py-1 text-sm
                                            {{ $errors->has("items.$i.unit_price")
                                                ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500'
                                                : 'border-slate-300 focus:ring-blue-500 focus:border-blue-500' }}
                                            focus:ring-2 focus:outline-none transition"
                                        @if($isReadOnly) disabled @endif>
                                </td>

                                <td class="px-2 py-2">
                                    <input type="text" readonly
                                        class="w-full rounded-lg border border-slate-200 bg-slate-50 px-2 py-1 text-sm amount-field font-medium"
                                        value="{{ 
                                            (float) str_replace(',', '', $item['quantity'] ?? 0) *
                                            (float) str_replace(',', '', $item['unit_price'] ?? 0)
                                        }}">
                                </td>

                                <td class="px-2 py-2">
                                    <input type="text"
                                        name="items[{{ $i }}][vendor]"
                                        value="{{ $item['vendor'] ?? '' }}"
                                        placeholder="Optional"
                                        class="w-full rounded-lg px-2 py-1 text-sm border-slate-300 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                        @if($isReadOnly) disabled @endif>
                                </td>

                                @if(!$isReadOnly)
                                <td class="px-2 py-2 text-center">
                                    <button type="button"
                                        onclick="this.closest('tr').remove(); updateTotal();"
                                        class="text-rose-600 hover:text-rose-800 text-xs">
                                        Remove
                                    </button>
                                </td>
                                @endif

                            </tr>
                            @endforeach

                        @else

                            <tr>
                                <td class="px-2 py-2">
                                    <input type="number" name="items[0][quantity]"
                                        oninput="updateAmount(this)"
                                        class="w-full rounded-lg px-2 py-1 text-sm border-slate-300 focus:ring-2 focus:ring-blue-500">
                                </td>

                                <td class="px-2 py-2">
                                    <input type="text" name="items[0][unit]"
                                        class="w-full rounded-lg px-2 py-1 text-sm border-slate-300 focus:ring-2 focus:ring-blue-500">
                                </td>

                                <td class="px-2 py-2">
                                    <input type="text" name="items[0][particulars]"
                                        class="w-full rounded-lg px-2 py-1 text-sm border-slate-300 focus:ring-2 focus:ring-blue-500">
                                </td>

                                <td class="px-2 py-2">
                                    <input type="text" name="items[0][unit_price]"
                                        oninput="formatCurrencyInput(this); updateAmount(this)"
                                        class="w-full rounded-lg px-2 py-1 text-sm border-slate-300 focus:ring-2 focus:ring-blue-500">
                                </td>

                                <td class="px-2 py-2">
                                    <input type="text" readonly class="w-full rounded-lg border border-slate-200 bg-slate-50 px-2 py-1 text-sm amount-field">
                                </td>

                                <td class="px-2 py-2">
                                    <input type="text" name="items[0][vendor]"
                                        class="w-full rounded-lg px-2 py-1 text-sm border-slate-300 focus:ring-2 focus:ring-blue-500">
                                </td>

                                @if(!$isReadOnly)
                                <td class="px-2 py-2 text-center">—</td>
                                @endif
                            </tr>

                        @endif

                    </tbody>

                    <tfoot class="bg-slate-50 border-t border-slate-200">
                        <tr>
                            <td colspan="4" class="px-3 py-2 text-right text-xs font-semibold text-slate-600">
                                Total Estimated Cost (₱)
                            </td>
                            <td class="px-3 py-2">
                                <input type="text" readonly id="purchaseTotal"
                                    class="w-full rounded-lg border border-slate-300 bg-white px-2 py-1 text-sm font-semibold">
                            </td>
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>

                </table>

            </div>

        </div>

    </div>

</div>