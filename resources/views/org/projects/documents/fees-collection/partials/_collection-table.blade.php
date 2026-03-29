<div>

    {{-- SECTION HEADER --}}
    <div class="mb-4 flex items-start justify-between gap-3">
        <div>
            <h3 class="text-sm font-semibold text-slate-900 tracking-wide">
                Summary of Cash Collection
            </h3>
            <p class="text-xs text-slate-500 mt-1">
                Record all collected payments, including number of payers, amount received, and official receipt references.
            </p>
        </div>

        @if(!$isReadOnly)
        <button
            type="button"
            onclick="addCollectionRow()"
            class="text-xs bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg font-semibold shadow-sm">
            + Add Row
        </button>
        @endif
    </div>


    {{-- TABLE --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

        <div class="overflow-x-auto">

            <table class="min-w-full text-sm">

                {{-- HEADER --}}
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>

                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">
                            Number of Payers
                        </th>

                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">
                            Amount Paid (₱)
                        </th>

                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">
                            Receipt / Control No.
                        </th>

                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">
                            SACDEV Remarks
                        </th>

                        @if(!$isReadOnly)
                        <th class="px-4 py-3 text-center text-xs font-semibold text-slate-600 uppercase">
                            Action
                        </th>
                        @endif

                    </tr>
                </thead>


                {{-- BODY --}}
                <tbody id="collectionTable" class="divide-y">

                @php
                    $items = old('items', $items ?? []);
                @endphp


                @if(count($items))

                    @foreach($items as $i => $item)

                    <tr>

                        {{-- NUMBER OF PAYERS --}}
                        <td class="px-4 py-2">
                            <input
                                type="number"
                                name="items[{{ $i }}][number_of_payers]"
                                value="{{ $item['number_of_payers'] ?? '' }}"
                                placeholder="e.g. 50"
                                class="w-full rounded-md border border-slate-300 px-2 py-1.5 text-sm focus:ring-2 focus:ring-blue-500"
                                @if($isReadOnly || $isAdmin) disabled @endif>
                        </td>


                        {{-- AMOUNT --}}
                        <td class="px-4 py-2">
                            <input
                                type="number"
                                step="0.01"
                                name="items[{{ $i }}][amount_paid]"
                                value="{{ $item['amount_paid'] ?? '' }}"
                                placeholder="e.g. 1500.00"
                                class="w-full rounded-md border border-slate-300 px-2 py-1.5 text-sm focus:ring-2 focus:ring-blue-500 amount-input"
                                @if($isReadOnly || $isAdmin) disabled @endif>
                        </td>


                        {{-- RECEIPT --}}
                        <td class="px-4 py-2">
                            <input
                                type="text"
                                name="items[{{ $i }}][receipt_series]"
                                value="{{ $item['receipt_series'] ?? '' }}"
                                placeholder="OR # / Control No."
                                class="w-full rounded-md border border-slate-300 px-2 py-1.5 text-sm focus:ring-2 focus:ring-blue-500"
                                @if($isReadOnly || $isAdmin) disabled @endif>
                        </td>


                        {{-- REMARKS --}}
                        <td class="px-4 py-2">
                            <input
                                type="text"
                                name="items[{{ $i }}][remarks]"
                                value="{{ $item['remarks'] ?? '' }}"
                                placeholder="For SACDEV use"
                                class="w-full rounded-md border border-amber-200 bg-amber-50 px-2 py-1.5 text-sm"
                                @if(!$isAdmin) disabled @endif>
                        </td>


                        {{-- ACTION --}}
                        @if(!$isReadOnly)
                        <td class="px-4 py-2 text-center">
                            <button
                                type="button"
                                onclick="this.closest('tr').remove(); updateCollectionTotal();"
                                class="text-rose-600 hover:text-rose-800 text-xs font-semibold">
                                Remove
                            </button>
                        </td>
                        @endif

                    </tr>

                    @endforeach

                @else

                    <tr>

                        <td class="px-4 py-2">
                            <input type="number" name="items[0][number_of_payers]"
                                class="w-full rounded-md border border-slate-300 px-2 py-1.5 text-sm"
                                @if($isReadOnly || $isAdmin) disabled @endif>
                        </td>

                        <td class="px-4 py-2">
                            <input type="number" step="0.01" name="items[0][amount_paid]"
                                class="w-full rounded-md border border-slate-300 px-2 py-1.5 text-sm amount-input"
                                @if($isReadOnly || $isAdmin) disabled @endif>
                        </td>

                        <td class="px-4 py-2">
                            <input type="text" name="items[0][receipt_series]"
                                class="w-full rounded-md border border-slate-300 px-2 py-1.5 text-sm"
                                @if($isReadOnly || $isAdmin) disabled @endif>
                        </td>

                        <td class="px-4 py-2">
                            <input type="text" name="items[0][remarks]"
                                class="w-full rounded-md border border-amber-200 bg-amber-50 px-2 py-1.5 text-sm"
                                @if(!$isAdmin) disabled @endif>
                        </td>

                        @if(!$isReadOnly)
                        <td class="px-4 py-2 text-center text-slate-400 text-xs">
                            —
                        </td>
                        @endif

                    </tr>

                @endif

                </tbody>

            </table>

        </div>

    </div>


    {{-- TOTAL DISPLAY --}}
    <div class="mt-4">
        <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide">
            Total Collection (₱)
        </label>

        <div
            id="totalCollectionDisplay"
            class="mt-2 w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm font-semibold text-slate-900">
            ₱ 0.00
        </div>

        <p class="text-[11px] text-slate-400 mt-1">
            Automatically calculated based on the amount paid entries above.
        </p>
    </div>

</div>