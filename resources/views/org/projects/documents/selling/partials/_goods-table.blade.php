<div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm overflow-hidden">

    <div class="h-1 bg-blue-500"></div>

    <div class="p-4 space-y-6">

        <div class="flex items-start justify-between gap-3">
            <div class="flex items-start gap-3">
                <div class="w-9 h-9 rounded-xl bg-blue-50 border border-blue-200 flex items-center justify-center">
                    <i data-lucide="package" class="w-4 h-4 text-blue-600"></i>
                </div>

                <div>
                    <h3 class="text-sm font-semibold text-slate-900 tracking-wide">
                        Goods to be Sold
                    </h3>
                    <p class="text-xs text-blue-700 mt-1">
                        List all items to be sold including quantity, pricing, and projected revenue.
                    </p>
                </div>
            </div>

            @if(!$isReadOnly)
            <button
                type="button"
                onclick="addSellingItemRow()"
                class="flex items-center gap-1 text-xs bg-blue-600 text-white px-3 py-2 rounded-lg hover:bg-blue-700 shadow-sm transition">
                <i data-lucide="plus" class="w-3 h-3"></i>
                Add
            </button>
            @endif
        </div>

        @php
            $items = old('items', $items ?? []);
        @endphp

        <div class="rounded-xl border border-slate-200 bg-white overflow-x-auto">

            <table class="min-w-full text-xs">

                <thead class="bg-slate-50 text-slate-600 uppercase tracking-wide border-b border-slate-200">
                    <tr>
                        <th class="px-3 py-2 text-left w-[90px]">Qty</th>
                        <th class="px-3 py-2 text-left">Particulars</th>
                        <th class="px-3 py-2 text-left w-[130px]">Price (₱)</th>
                        <th class="px-3 py-2 text-left w-[130px]">Subtotal</th>
                        <th class="px-3 py-2 text-left">Remarks (SACDEV)</th>
                        @if(!$isReadOnly)
                            <th class="px-3 py-2 text-center w-[70px]">Action</th>
                        @endif
                    </tr>
                </thead>

                <tbody id="sellingItemsTable" class="divide-y divide-slate-100">

                @if(count($items))

                    @foreach($items as $i => $item)
                    <tr class="hover:bg-slate-50 transition">

                        <td class="px-2 py-2">
                            <input
                                type="number"
                                name="items[{{ $i }}][quantity]"
                                value="{{ $item['quantity'] ?? '' }}"
                                oninput="updateSubtotal(this)"
                                class="w-full rounded-lg px-2 py-1 text-xs border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                @if($isReadOnly || $isAdmin) disabled @endif>
                        </td>

                        <td class="px-2 py-2">
                            <input
                                type="text"
                                name="items[{{ $i }}][particulars]"
                                value="{{ $item['particulars'] ?? '' }}"
                                class="w-full rounded-lg px-2 py-1 text-xs border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                @if($isReadOnly || $isAdmin) disabled @endif>
                        </td>

                        <td class="px-2 py-2">
                            <input
                                type="text"
                                inputmode="decimal"
                                name="items[{{ $i }}][selling_price]"
                                value="{{ isset($item['selling_price']) ? number_format($item['selling_price'], 2) : '' }}"
                                oninput="handleMoneyInput(this); updateSubtotal(this)"
                                onblur="formatMoneyInput(this)"
                                class="w-full rounded-lg px-2 py-1 text-xs border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                @if($isReadOnly || $isAdmin) disabled @endif>
                        </td>

                        <td class="px-2 py-2">
                            <input
                                type="text"
                                readonly
                                class="w-full rounded-lg border border-slate-200 bg-slate-50 px-2 py-1 text-xs subtotal-field"
                                value="{{ number_format(($item['quantity'] ?? 0) * ($item['selling_price'] ?? 0), 2) }}">
                        </td>

                        <td class="px-2 py-2">
                            <input
                                type="text"
                                name="items[{{ $i }}][remarks]"
                                value="{{ $item['remarks'] ?? '' }}"
                                class="w-full rounded-lg border border-amber-200 bg-amber-50 px-2 py-1 text-xs"
                                @if(!$isAdmin) disabled @endif>
                        </td>

                        @if(!$isReadOnly)
                        <td class="px-2 py-2 text-center">
                            <button
                                type="button"
                                onclick="this.closest('tr').remove(); updateTotal();"
                                class="text-rose-600 hover:text-rose-800">
                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                            </button>
                        </td>
                        @endif

                    </tr>
                    @endforeach

                @else

                    <tr class="hover:bg-slate-50 transition">

                        <td class="px-2 py-2">
                            <input type="number" name="items[0][quantity]"
                                oninput="updateSubtotal(this)"
                                class="w-full rounded-lg px-2 py-1 text-xs border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                @if($isReadOnly || $isAdmin) disabled @endif>
                        </td>

                        <td class="px-2 py-2">
                            <input type="text" name="items[0][particulars]"
                                class="w-full rounded-lg px-2 py-1 text-xs border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                @if($isReadOnly || $isAdmin) disabled @endif>
                        </td>

                        <td class="px-2 py-2">
                            <input
                                type="text"
                                inputmode="decimal"
                                name="items[0][selling_price]"
                                oninput="handleMoneyInput(this); updateSubtotal(this)"
                                onblur="formatMoneyInput(this)"
                                class="w-full rounded-lg px-2 py-1 text-xs border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                @if($isReadOnly || $isAdmin) disabled @endif>
                        </td>

                        <td class="px-2 py-2">
                            <input type="text" readonly
                                class="w-full rounded-lg border border-slate-200 bg-slate-50 px-2 py-1 text-xs subtotal-field">
                        </td>

                        <td class="px-2 py-2">
                            <input type="text" name="items[0][remarks]"
                                class="w-full rounded-lg border border-amber-200 bg-amber-50 px-2 py-1 text-xs"
                                @if(!$isAdmin) disabled @endif>
                        </td>

                        @if(!$isReadOnly)
                        <td class="px-2 py-2 text-center text-slate-400 text-xs">—</td>
                        @endif

                    </tr>

                @endif

                </tbody>

                <tfoot class="bg-slate-50 border-t border-slate-200">
                    <tr>
                        <td colspan="3" class="text-right px-3 py-2 text-[11px] font-semibold text-slate-600">
                            Total Projected Revenue:
                        </td>
                        <td class="px-3 py-2">
                            <input
                                type="text"
                                id="sellingTotal"
                                readonly
                                class="w-full rounded-lg border border-slate-300 bg-white px-2 py-1 text-xs font-semibold">
                        </td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>

            </table>

        </div>

    </div>

</div>