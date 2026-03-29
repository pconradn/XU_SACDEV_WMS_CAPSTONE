<div>

    {{-- HEADER --}}
    <div class="flex items-center justify-between mb-4">
        <div>
            <h3 class="text-sm font-semibold text-slate-900 tracking-wide">
                Items to be Purchased
            </h3>
            <p class="text-xs text-slate-500 mt-1">
                List all requested items including quantity, estimated cost, and supplier details.
                Ensure values are accurate as totals will be computed automatically.
            </p>
        </div>

        @if(!$isReadOnly)
        <button
            type="button"
            onclick="addPurchaseItemRow()"
            class="px-3 py-2 text-xs font-semibold bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow-sm">
            + Add Item
        </button>
        @endif
    </div>


    {{-- TABLE --}}
    <div class="overflow-x-auto border border-slate-200 rounded-xl">

        <table class="min-w-full text-sm">

            <thead class="bg-slate-50 text-slate-600 text-xs uppercase tracking-wide">
                <tr>
                    <th class="px-3 py-2 text-left w-[90px]">Qty</th>
                    <th class="px-3 py-2 text-left w-[90px]">Unit</th>
                    <th class="px-3 py-2 text-left">Particulars</th>
                    <th class="px-3 py-2 text-left w-[140px]">Unit Price</th>
                    <th class="px-3 py-2 text-left w-[140px]">Amount</th>
                    <th class="px-3 py-2 text-left">Vendor</th>
                    @if(!$isReadOnly)
                    <th class="px-3 py-2 w-[70px] text-center">Action</th>
                    @endif
                </tr>

                {{-- HELPER ROW --}}
                <tr class="text-[10px] text-slate-400 normal-case">
                    <th class="px-3 pb-2 text-left">e.g. 5</th>
                    <th class="px-3 pb-2 text-left">pcs / boxes</th>
                    <th class="px-3 pb-2 text-left">Item description</th>
                    <th class="px-3 pb-2 text-left">PHP (₱)</th>
                    <th class="px-3 pb-2 text-left">Auto-calculated</th>
                    <th class="px-3 pb-2 text-left">Supplier name</th>
                    @if(!$isReadOnly)
                    <th></th>
                    @endif
                </tr>

            </thead>


            <tbody id="purchaseItemsTable" class="divide-y">

                @php
                    $items = old('items', $items ?? []);
                @endphp

                @if(count($items))

                    @foreach($items as $i => $item)
                    <tr>

                        {{-- QTY --}}
                        <td class="px-3 py-2">
                            <input type="number"
                                name="items[{{ $i }}][quantity]"
                                value="{{ $item['quantity'] ?? '' }}"
                                oninput="updateAmount(this)"
                                placeholder="0"
                                class="w-full rounded border border-slate-200 px-2 py-1 text-sm"
                                @if($isReadOnly) disabled @endif>
                        </td>

                        {{-- UNIT --}}
                        <td class="px-3 py-2">
                            <input type="text"
                                name="items[{{ $i }}][unit]"
                                value="{{ $item['unit'] ?? '' }}"
                                placeholder="pcs, boxes..."
                                class="w-full rounded border border-slate-200 px-2 py-1 text-sm"
                                @if($isReadOnly) disabled @endif>
                        </td>

                        {{-- PARTICULARS --}}
                        <td class="px-3 py-2">
                            <input type="text"
                                name="items[{{ $i }}][particulars]"
                                value="{{ $item['particulars'] ?? '' }}"
                                placeholder="Describe the item..."
                                class="w-full rounded border border-slate-200 px-2 py-1 text-sm"
                                @if($isReadOnly) disabled @endif>
                        </td>

                        {{-- UNIT PRICE --}}
                        <td class="px-3 py-2">
                            <input type="number" step="0.01"
                                name="items[{{ $i }}][unit_price]"
                                value="{{ $item['unit_price'] ?? '' }}"
                                oninput="updateAmount(this)"
                                placeholder="0.00"
                                class="w-full rounded border border-slate-200 px-2 py-1 text-sm"
                                @if($isReadOnly) disabled @endif>
                        </td>

                        {{-- AMOUNT --}}
                        <td class="px-3 py-2 bg-slate-50">
                            <input type="text" readonly
                                class="w-full rounded border border-slate-200 px-2 py-1 text-sm amount-field font-medium text-slate-700"
                                value="{{ ($item['quantity'] ?? 0) * ($item['unit_price'] ?? 0) }}">
                        </td>

                        {{-- VENDOR --}}
                        <td class="px-3 py-2">
                            <input type="text"
                                name="items[{{ $i }}][vendor]"
                                value="{{ $item['vendor'] ?? '' }}"
                                placeholder="Optional"
                                class="w-full rounded border border-slate-200 px-2 py-1 text-sm"
                                @if($isReadOnly) disabled @endif>
                        </td>

                        {{-- ACTION --}}
                        @if(!$isReadOnly)
                        <td class="px-3 py-2 text-center">
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

                    {{-- EMPTY ROW --}}
                    <tr>
                        <td class="px-3 py-2">
                            <input type="number" name="items[0][quantity]"
                                oninput="updateAmount(this)"
                                placeholder="0"
                                class="w-full rounded border border-slate-200 px-2 py-1 text-sm"
                                @if($isReadOnly) disabled @endif>
                        </td>

                        <td class="px-3 py-2">
                            <input type="text" name="items[0][unit]"
                                placeholder="pcs, boxes..."
                                class="w-full rounded border border-slate-200 px-2 py-1 text-sm"
                                @if($isReadOnly) disabled @endif>
                        </td>

                        <td class="px-3 py-2">
                            <input type="text" name="items[0][particulars]"
                                placeholder="Describe the item..."
                                class="w-full rounded border border-slate-200 px-2 py-1 text-sm"
                                @if($isReadOnly) disabled @endif>
                        </td>

                        <td class="px-3 py-2">
                            <input type="number" step="0.01" name="items[0][unit_price]"
                                oninput="updateAmount(this)"
                                placeholder="0.00"
                                class="w-full rounded border border-slate-200 px-2 py-1 text-sm"
                                @if($isReadOnly) disabled @endif>
                        </td>

                        <td class="px-3 py-2 bg-slate-50">
                            <input type="text" readonly
                                class="w-full rounded border border-slate-200 px-2 py-1 text-sm amount-field">
                        </td>

                        <td class="px-3 py-2">
                            <input type="text" name="items[0][vendor]"
                                placeholder="Optional"
                                class="w-full rounded border border-slate-200 px-2 py-1 text-sm"
                                @if($isReadOnly) disabled @endif>
                        </td>

                        @if(!$isReadOnly)
                        <td class="px-3 py-2 text-center">—</td>
                        @endif
                    </tr>

                @endif

            </tbody>


            {{-- TOTAL ROW --}}
            <tfoot class="bg-slate-50 border-t">
                <tr>
                    <td colspan="4" class="px-3 py-2 text-right font-semibold text-slate-700">
                        Total Estimated Cost (PHP)
                    </td>
                    <td class="px-3 py-2">
                        <input type="text" readonly id="purchaseTotal"
                            class="w-full rounded border border-slate-200 px-2 py-1 text-sm font-semibold text-slate-900">
                    </td>
                    <td colspan="2"></td>
                </tr>
            </tfoot>

        </table>

    </div>

</div>