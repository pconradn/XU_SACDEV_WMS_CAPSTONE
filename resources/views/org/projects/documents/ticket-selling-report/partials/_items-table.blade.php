<div>

    {{-- SECTION HEADER --}}
    <div class="mb-4 flex items-center justify-between">

        <div>
            <h3 class="text-sm font-semibold text-slate-900 tracking-wide">
                Actual Tickets Sold
            </h3>
            <p class="text-xs text-slate-500 mt-1">
                Record each batch of tickets sold including quantity, control numbers, and corresponding revenue.
            </p>
        </div>

        @if(!$isReadOnly)
        <button
            type="button"
            onclick="addTicketRow()"
            class="text-xs bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg shadow-sm">
            + Add Entry
        </button>
        @endif

    </div>


    {{-- TABLE --}}
    <div class="overflow-x-auto border border-slate-200 rounded-xl">

        <table class="min-w-full text-sm">

            <thead class="bg-slate-50 text-slate-600 text-xs uppercase tracking-wide">
                <tr>

                    <th class="px-3 py-2 text-left w-[110px]">
                        Quantity
                    </th>

                    <th class="px-3 py-2 text-left">
                        Series / Control Numbers
                    </th>

                    <th class="px-3 py-2 text-left w-[150px]">
                        Price (₱)
                    </th>

                    <th class="px-3 py-2 text-left w-[150px]">
                        Amount (₱)
                    </th>

                    <th class="px-3 py-2 text-left">
                        Remarks
                    </th>

                    @if(!$isReadOnly)
                    <th class="px-3 py-2 text-center w-[80px]">
                        Action
                    </th>
                    @endif

                </tr>
            </thead>


            <tbody id="ticketItemsTable" class="divide-y divide-slate-200">

                @php
                    $items = old('items', $items ?? []);
                @endphp


                @if(count($items))

                    @foreach($items as $i => $item)

                    <tr class="hover:bg-slate-50">

                        {{-- QUANTITY --}}
                        <td class="px-2 py-2">
                            <input
                                type="number"
                                name="items[{{ $i }}][quantity]"
                                value="{{ $item['quantity'] ?? '' }}"
                                oninput="updateTicketAmount(this)"
                                class="w-full rounded-md border border-slate-200 px-2 py-1 text-sm focus:ring-2 focus:ring-blue-500"
                                @if($isReadOnly) disabled @endif>

                            <p class="text-[10px] text-slate-400 mt-1">
                                Number of tickets sold
                            </p>
                        </td>


                        {{-- SERIES --}}
                        <td class="px-2 py-2">
                            <input
                                type="text"
                                name="items[{{ $i }}][series_control_numbers]"
                                value="{{ $item['series_control_numbers'] ?? '' }}"
                                placeholder="e.g. 001–100"
                                class="w-full rounded-md border border-slate-200 px-2 py-1 text-sm focus:ring-2 focus:ring-blue-500"
                                @if($isReadOnly) disabled @endif>

                            <p class="text-[10px] text-slate-400 mt-1">
                                Range of ticket numbers issued
                            </p>
                        </td>


                        {{-- PRICE --}}
                        <td class="px-2 py-2">
                            <input
                                type="number"
                                step="0.01"
                                name="items[{{ $i }}][price_per_ticket]"
                                value="{{ $item['price_per_ticket'] ?? '' }}"
                                oninput="updateTicketAmount(this)"
                                class="w-full rounded-md border border-slate-200 px-2 py-1 text-sm focus:ring-2 focus:ring-blue-500"
                                @if($isReadOnly) disabled @endif>

                            <p class="text-[10px] text-slate-400 mt-1">
                                Price per ticket
                            </p>
                        </td>


                        {{-- AMOUNT --}}
                        <td class="px-2 py-2">
                            <input
                                type="text"
                                readonly
                                class="w-full rounded-md border border-slate-100 bg-slate-50 px-2 py-1 text-sm font-semibold ticket-amount-field"
                                value="{{ ($item['quantity'] ?? 0) * ($item['price_per_ticket'] ?? 0) }}">

                            <p class="text-[10px] text-slate-400 mt-1">
                                Auto-calculated (qty × price)
                            </p>
                        </td>


                        {{-- REMARKS --}}
                        <td class="px-2 py-2">
                            <input
                                type="text"
                                name="items[{{ $i }}][remarks]"
                                value="{{ $item['remarks'] ?? '' }}"
                                placeholder="Optional notes"
                                class="w-full rounded-md border border-slate-200 px-2 py-1 text-sm focus:ring-2 focus:ring-blue-500"
                                @if($isReadOnly) disabled @endif>
                        </td>


                        {{-- ACTION --}}
                        @if(!$isReadOnly)
                        <td class="px-2 py-2 text-center">
                            <button
                                type="button"
                                onclick="this.closest('tr').remove(); updateTicketTotal();"
                                class="text-xs text-rose-600 hover:text-rose-800 font-medium">
                                Remove
                            </button>
                        </td>
                        @endif

                    </tr>

                    @endforeach

                @else

                {{-- DEFAULT ROW --}}
                <tr>

                    <td class="px-2 py-2">
                        <input type="number" name="items[0][quantity]" oninput="updateTicketAmount(this)"
                            class="w-full rounded-md border border-slate-200 px-2 py-1 text-sm"
                            @if($isReadOnly) disabled @endif>
                    </td>

                    <td class="px-2 py-2">
                        <input type="text" name="items[0][series_control_numbers]"
                            class="w-full rounded-md border border-slate-200 px-2 py-1 text-sm"
                            @if($isReadOnly) disabled @endif>
                    </td>

                    <td class="px-2 py-2">
                        <input type="number" step="0.01" name="items[0][price_per_ticket]"
                            oninput="updateTicketAmount(this)"
                            class="w-full rounded-md border border-slate-200 px-2 py-1 text-sm"
                            @if($isReadOnly) disabled @endif>
                    </td>

                    <td class="px-2 py-2">
                        <input type="text" readonly
                            class="w-full rounded-md border border-slate-100 bg-slate-50 px-2 py-1 text-sm ticket-amount-field">
                    </td>

                    <td class="px-2 py-2">
                        <input type="text" name="items[0][remarks]"
                            class="w-full rounded-md border border-slate-200 px-2 py-1 text-sm"
                            @if($isReadOnly) disabled @endif>
                    </td>

                    @if(!$isReadOnly)
                    <td class="px-2 py-2 text-center">—</td>
                    @endif

                </tr>

                @endif

            </tbody>

        </table>

    </div>


    {{-- HELPER TEXT --}}
    <p class="text-[11px] text-slate-400 mt-2">
        Ensure all ticket entries match the issued control numbers. Total sales will be automatically calculated.
    </p>

</div>