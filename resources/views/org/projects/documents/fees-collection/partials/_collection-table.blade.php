<div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

    <div class="h-1 bg-blue-500"></div>

    <div class="p-5 space-y-6">

        <div class="flex items-start justify-between gap-3">
            <div>
                <h3 class="text-sm font-semibold text-slate-900 tracking-wide">
                    Summary of Cash Collection
                </h3>
                <p class="text-xs text-blue-700 mt-1">
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

        <div class="border border-slate-200 rounded-xl overflow-hidden">

            <div class="overflow-auto max-h-[400px]">

                <table class="min-w-[900px] w-full text-sm border border-slate-200">

                    <thead class="bg-slate-50 sticky top-0 z-10">
                        <tr class="border-b border-slate-200">

                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase border-r">
                                Number of Payers
                            </th>

                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase border-r">
                                Amount Paid (₱)
                            </th>

                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase border-r">
                                Receipt / Control No.
                            </th>

                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase border-r">
                                SACDEV Remarks
                            </th>

                            @if(!$isReadOnly)
                            <th class="px-4 py-3 text-center text-xs font-semibold text-slate-600 uppercase">
                                Action
                            </th>
                            @endif

                        </tr>
                    </thead>

                    <tbody id="collectionTable" class="divide-y divide-slate-200">

                    @php
                        $items = old('items', $items ?? []);
                    @endphp

                    @if(count($items))

                        @foreach($items as $i => $item)

                        <tr class="hover:bg-slate-50 transition">

                            <td class="px-3 py-2 border-r">
                                <input
                                    type="number"
                                    name="items[{{ $i }}][number_of_payers]"
                                    value="{{ $item['number_of_payers'] ?? '' }}"
                                    placeholder="e.g. 50"
                                    class="w-full rounded-md px-2 py-1.5 text-sm
                                        {{ $errors->has("items.$i.number_of_payers")
                                            ? 'border-rose-500 focus:ring-rose-500'
                                            : 'border-slate-300 focus:ring-blue-500' }}
                                        border focus:ring-2 focus:outline-none"
                                    @if($isReadOnly || $isAdmin) disabled @endif>
                            </td>

                            <td class="px-3 py-2 border-r">
                                <input
                                    type="text"
                                    name="items[{{ $i }}][amount_paid]"
                                    value="{{ $item['amount_paid'] ?? '' }}"
                                    placeholder="1,500.00"
                                    class="w-full text-right rounded-md px-2 py-1.5 text-sm amount-input
                                        {{ $errors->has("items.$i.amount_paid")
                                            ? 'border-rose-500 focus:ring-rose-500'
                                            : 'border-slate-300 focus:ring-blue-500' }}
                                        border focus:ring-2 focus:outline-none"
                                    @if($isReadOnly || $isAdmin) disabled @endif>
                            </td>

                            <td class="px-3 py-2 border-r">
                                <input
                                    type="text"
                                    name="items[{{ $i }}][receipt_series]"
                                    value="{{ $item['receipt_series'] ?? '' }}"
                                    placeholder="OR # / Control No."
                                    class="w-full rounded-md px-2 py-1.5 text-sm border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                    @if($isReadOnly || $isAdmin) disabled @endif>
                            </td>

                            <td class="px-3 py-2 border-r">
                                <input
                                    type="text"
                                    name="items[{{ $i }}][remarks]"
                                    value="{{ $item['remarks'] ?? '' }}"
                                    placeholder="For SACDEV use"
                                    class="w-full rounded-md px-2 py-1.5 text-sm border border-amber-200 bg-amber-50"
                                    @if(!$isAdmin) disabled @endif>
                            </td>

                            @if(!$isReadOnly)
                            <td class="px-3 py-2 text-center">
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

                            <td class="px-3 py-2 border-r">
                                <input type="number" name="items[0][number_of_payers]"
                                    class="w-full rounded-md border border-slate-300 px-2 py-1.5 text-sm"
                                    @if($isReadOnly || $isAdmin) disabled @endif>
                            </td>

                            <td class="px-3 py-2 border-r">
                                <input type="text" name="items[0][amount_paid]"
                                    class="w-full text-right rounded-md border border-slate-300 px-2 py-1.5 text-sm amount-input"
                                    @if($isReadOnly || $isAdmin) disabled @endif>
                            </td>

                            <td class="px-3 py-2 border-r">
                                <input type="text" name="items[0][receipt_series]"
                                    class="w-full rounded-md border border-slate-300 px-2 py-1.5 text-sm"
                                    @if($isReadOnly || $isAdmin) disabled @endif>
                            </td>

                            <td class="px-3 py-2 border-r">
                                <input type="text" name="items[0][remarks]"
                                    class="w-full rounded-md border border-amber-200 bg-amber-50 px-2 py-1.5 text-sm"
                                    @if(!$isAdmin) disabled @endif>
                            </td>

                            @if(!$isReadOnly)
                            <td class="px-3 py-2 text-center text-slate-400 text-xs">
                                —
                            </td>
                            @endif

                        </tr>

                    @endif

                    </tbody>

                </table>

            </div>

        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide">
                Total Collection (₱)
            </label>

            <div
                id="totalCollectionDisplay"
                class="mt-2 w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm font-semibold text-slate-900 text-right">
                ₱ 0.00
            </div>

            <p class="text-[11px] text-slate-400 mt-1">
                Automatically calculated based on the amount paid entries above.
            </p>
        </div>

    </div>

</div>