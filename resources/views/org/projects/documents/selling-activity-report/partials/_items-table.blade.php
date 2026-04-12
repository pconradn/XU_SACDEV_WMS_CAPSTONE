<div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm overflow-hidden">

    {{-- ACCENT --}}
    <div class="h-1 bg-blue-500"></div>

    {{-- HEADER --}}
    <div class="px-4 py-4 border-b border-slate-200 bg-slate-50/70 flex justify-between items-start gap-3">

        <div class="flex items-start gap-3">
            <div class="w-9 h-9 rounded-xl bg-blue-50 border border-blue-200 flex items-center justify-center">
                <i data-lucide="clipboard-check" class="w-4 h-4 text-blue-600"></i>
            </div>

            <div>
                <h3 class="text-sm font-semibold text-slate-900 tracking-wide">
                    Actual Number of Items Sold
                </h3>

                <p class="text-xs text-slate-500 mt-1">
                    Enter the actual quantity sold and price per item. Subtotals and total sales are calculated automatically.
                </p>
            </div>
        </div>

        @if(!$isReadOnly)
        <button
            type="button"
            onclick="addSellingItemRow()"
            class="flex items-center gap-1 text-xs bg-blue-600 text-white px-3 py-2 rounded-lg hover:bg-blue-700 transition shadow-sm">
            <i data-lucide="plus" class="w-3 h-3"></i>
            Add
        </button>
        @endif

    </div>

    {{-- TABLE (UNTOUCHED) --}}
    <div class="p-4 overflow-x-auto">

        <div class="min-w-[900px]">

            <table class="min-w-full text-sm border border-slate-300" style="border-collapse: collapse;">

                <thead class="bg-slate-100 text-slate-700 text-xs uppercase tracking-wide">
                    <tr class="border-b border-slate-300">

                        <th class="border border-slate-300 px-3 py-2 text-center w-[90px]">
                            Quantity
                        </th>

                        <th class="border border-slate-300 px-3 py-2 text-left">
                            Particulars
                        </th>

                        <th class="border border-slate-300 px-3 py-2 text-right w-[140px]">
                            Price (₱)
                        </th>

                        <th class="border border-slate-300 px-3 py-2 text-right w-[140px]">
                            Subtotal (₱)
                        </th>

                        <th class="border border-slate-300 px-3 py-2 text-left w-[180px]">
                            Receipt #
                        </th>

                        @if(!$isReadOnly)
                        <th class="border border-slate-300 px-3 py-2 text-center w-[80px]">
                            Action
                        </th>
                        @endif

                    </tr>
                </thead>

                <tbody id="sellingItemsTable">

                    @php
                        $items = old('items', $items ?? []);
                    @endphp

                    @if(count($items))

                        @foreach($items as $i => $item)

                        <tr class="border-b border-slate-300 hover:bg-slate-50 transition">

                            <td class="border border-slate-300">
                                <input type="number"
                                    name="items[{{ $i }}][quantity]"
                                    value="{{ $item['quantity'] ?? '' }}"
                                    oninput="updateSubtotal(this)"
                                    class="w-full px-2 py-1 text-sm text-center border-0 bg-transparent focus:ring-0"
                                    placeholder="0"
                                    @if($isReadOnly) disabled @endif>
                            </td>

                            <td class="border border-slate-300">
                                <input type="text"
                                    name="items[{{ $i }}][particulars]"
                                    value="{{ $item['particulars'] ?? '' }}"
                                    class="w-full px-2 py-1 text-sm border-0 bg-transparent focus:ring-0"
                                    placeholder="Item name"
                                    @if($isReadOnly) disabled @endif>
                            </td>

                            <td class="border border-slate-300">
                                <input type="text"
                                    name="items[{{ $i }}][price]"
                                    value="{{ isset($item['price']) ? number_format($item['price'],2) : '' }}"
                                    oninput="formatCurrencyInput(this); updateSubtotal(this)"
                                    class="w-full px-2 py-1 text-sm text-right border-0 bg-transparent focus:ring-0"
                                    placeholder="0.00"
                                    @if($isReadOnly) disabled @endif>
                            </td>

                            <td class="border border-slate-300 bg-slate-50">
                                <input type="text"
                                    readonly
                                    class="w-full px-2 py-1 text-sm text-right font-semibold border-0 bg-transparent subtotal-field"
                                    value="{{ number_format((($item['quantity'] ?? 0) * ($item['price'] ?? 0)),2) }}">
                            </td>

                            <td class="border border-slate-300">
                                <input type="text"
                                    name="items[{{ $i }}][acknowledgement_receipt_number]"
                                    value="{{ $item['acknowledgement_receipt_number'] ?? '' }}"
                                    class="w-full px-2 py-1 text-sm border-0 bg-transparent focus:ring-0"
                                    placeholder="Optional"
                                    @if($isReadOnly) disabled @endif>
                            </td>

                            @if(!$isReadOnly)
                            <td class="border border-slate-300 text-center">
                                <button type="button"
                                    onclick="this.closest('tr').remove(); updateTotalSales();"
                                    class="text-rose-600 hover:text-rose-800 text-xs font-medium">
                                    Remove
                                </button>
                            </td>
                            @endif

                        </tr>

                        @endforeach

                    @else

                        <tr class="border-b border-slate-300">

                            <td class="border border-slate-300">
                                <input type="number"
                                    name="items[0][quantity]"
                                    oninput="updateSubtotal(this)"
                                    class="w-full px-2 py-1 text-sm text-center border-0 bg-transparent"
                                    placeholder="0"
                                    @if($isReadOnly) disabled @endif>
                            </td>

                            <td class="border border-slate-300">
                                <input type="text"
                                    name="items[0][particulars]"
                                    class="w-full px-2 py-1 text-sm border-0 bg-transparent"
                                    placeholder="Item name"
                                    @if($isReadOnly) disabled @endif>
                            </td>

                            <td class="border border-slate-300">
                                <input type="text"
                                    name="items[0][price]"
                                    oninput="formatCurrencyInput(this); updateSubtotal(this)"
                                    class="w-full px-2 py-1 text-sm text-right border-0 bg-transparent"
                                    placeholder="0.00"
                                    @if($isReadOnly) disabled @endif>
                            </td>

                            <td class="border border-slate-300 bg-slate-50">
                                <input type="text"
                                    readonly
                                    class="w-full px-2 py-1 text-sm text-right border-0 bg-transparent subtotal-field">
                            </td>

                            <td class="border border-slate-300">
                                <input type="text"
                                    name="items[0][acknowledgement_receipt_number]"
                                    class="w-full px-2 py-1 text-sm border-0 bg-transparent"
                                    placeholder="Optional"
                                    @if($isReadOnly) disabled @endif>
                            </td>

                            @if(!$isReadOnly)
                            <td class="border border-slate-300 text-center">—</td>
                            @endif

                        </tr>

                    @endif

                </tbody>

            </table>

        </div>

        {{-- TOTAL --}}
        <div class="flex justify-end mt-6">

            <div class="text-right">
                <p class="text-xs text-slate-500 mb-1">
                    Total Sales
                </p>

                <input
                    type="text"
                    id="totalSales"
                    readonly
                    class="w-56 rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm font-semibold text-right shadow-sm"
                    placeholder="0.00">
            </div>

        </div>

    </div>

</div>