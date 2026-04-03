<div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

    <div class="h-1 bg-blue-500"></div>

    <div class="p-5 space-y-6">

        <div class="flex items-start justify-between">
            <div>
                <h3 class="text-sm font-semibold text-slate-900">
                    Goods to be Sold
                </h3>
                <p class="text-xs text-blue-700 mt-1">
                    List all items to be sold including quantity, pricing, and projected revenue.
                </p>
            </div>

            @if(!$isReadOnly)
            <button
                type="button"
                onclick="addSellingItemRow()"
                class="text-xs bg-blue-600 text-white px-3 py-2 rounded-lg hover:bg-blue-700 shadow-sm">
                + Add Item
            </button>
            @endif
        </div>

        @php
            $items = old('items', $items ?? []);
        @endphp

        <div class="border border-slate-200 rounded-xl overflow-x-auto">

            <table class="min-w-full text-sm">

                <thead class="bg-slate-50 text-slate-600 text-xs uppercase tracking-wide">
                    <tr>
                        <th class="px-3 py-2 text-left w-[100px]">Qty</th>
                        <th class="px-3 py-2 text-left">Particulars</th>
                        <th class="px-3 py-2 text-left w-[140px]">Price (₱)</th>
                        <th class="px-3 py-2 text-left w-[140px]">Subtotal</th>
                        <th class="px-3 py-2 text-left">Remarks (SACDEV)</th>
                        @if(!$isReadOnly)
                            <th class="px-3 py-2 text-center w-[70px]">Action</th>
                        @endif
                    </tr>
                </thead>

                <tbody id="sellingItemsTable" class="divide-y divide-slate-200">

                    @if(count($items))

                        @foreach($items as $i => $item)
                        <tr class="hover:bg-slate-50 transition">

                            <td class="px-2 py-2">
                                <input
                                    type="number"
                                    name="items[{{ $i }}][quantity]"
                                    value="{{ $item['quantity'] ?? '' }}"
                                    oninput="updateSubtotal(this)"
                                    class="w-full rounded-lg px-2 py-1 text-sm
                                        {{ $errors->has("items.$i.quantity")
                                            ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500'
                                            : 'border-slate-300 focus:ring-blue-500 focus:border-blue-500' }}
                                        focus:ring-2 focus:outline-none transition"
                                    @if($isReadOnly || $isAdmin) disabled @endif>
                            </td>

                            <td class="px-2 py-2">
                                <input
                                    type="text"
                                    name="items[{{ $i }}][particulars]"
                                    value="{{ $item['particulars'] ?? '' }}"
                                    class="w-full rounded-lg px-2 py-1 text-sm
                                        {{ $errors->has("items.$i.particulars")
                                            ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500'
                                            : 'border-slate-300 focus:ring-blue-500 focus:border-blue-500' }}
                                        focus:ring-2 focus:outline-none transition"
                                    @if($isReadOnly || $isAdmin) disabled @endif>
                            </td>

                            <td class="px-2 py-2">
                                <input
                                    type="number"
                                    step="0.01"
                                    name="items[{{ $i }}][selling_price]"
                                    value="{{ $item['selling_price'] ?? '' }}"
                                    oninput="updateSubtotal(this)"
                                    class="w-full rounded-lg px-2 py-1 text-sm
                                        {{ $errors->has("items.$i.selling_price")
                                            ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500'
                                            : 'border-slate-300 focus:ring-blue-500 focus:border-blue-500' }}
                                        focus:ring-2 focus:outline-none transition"
                                    @if($isReadOnly || $isAdmin) disabled @endif>
                            </td>

                            <td class="px-2 py-2">
                                <input
                                    type="text"
                                    readonly
                                    class="w-full rounded-lg border border-slate-200 bg-slate-50 px-2 py-1 text-sm subtotal-field"
                                    value="{{ ($item['quantity'] ?? 0) * ($item['selling_price'] ?? 0) }}">
                            </td>

                            <td class="px-2 py-2">
                                <input
                                    type="text"
                                    name="items[{{ $i }}][remarks]"
                                    value="{{ $item['remarks'] ?? '' }}"
                                    class="w-full rounded-lg border border-amber-200 bg-amber-50 px-2 py-1 text-sm"
                                    @if(!$isAdmin) disabled @endif>
                            </td>

                            @if(!$isReadOnly)
                            <td class="px-2 py-2 text-center">
                                <button
                                    type="button"
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
                                <input type="number" name="items[0][quantity]" oninput="updateSubtotal(this)"
                                    class="w-full rounded-lg px-2 py-1 text-sm border-slate-300 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                    @if($isReadOnly || $isAdmin) disabled @endif>
                            </td>

                            <td class="px-2 py-2">
                                <input type="text" name="items[0][particulars]"
                                    class="w-full rounded-lg px-2 py-1 text-sm border-slate-300 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                    @if($isReadOnly || $isAdmin) disabled @endif>
                            </td>

                            <td class="px-2 py-2">
                                <input type="number" step="0.01" name="items[0][selling_price]" oninput="updateSubtotal(this)"
                                    class="w-full rounded-lg px-2 py-1 text-sm border-slate-300 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                    @if($isReadOnly || $isAdmin) disabled @endif>
                            </td>

                            <td class="px-2 py-2">
                                <input type="text" readonly
                                    class="w-full rounded-lg border border-slate-200 bg-slate-50 px-2 py-1 text-sm subtotal-field">
                            </td>

                            <td class="px-2 py-2">
                                <input type="text" name="items[0][remarks]"
                                    class="w-full rounded-lg border border-amber-200 bg-amber-50 px-2 py-1 text-sm"
                                    @if(!$isAdmin) disabled @endif>
                            </td>

                            @if(!$isReadOnly)
                            <td class="px-2 py-2 text-center">—</td>
                            @endif

                        </tr>

                    @endif

                </tbody>

                <tfoot class="bg-slate-50 border-t border-slate-200">
                    <tr>
                        <td colspan="3" class="text-right px-3 py-2 text-xs font-semibold text-slate-600">
                            Total Projected Revenue:
                        </td>
                        <td class="px-3 py-2">
                            <input
                                type="text"
                                id="sellingTotal"
                                readonly
                                class="w-full rounded-lg border border-slate-300 bg-white px-2 py-1 text-sm font-semibold">
                        </td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>

            </table>

        </div>

    </div>

</div>