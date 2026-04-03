<div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

    <div class="h-1 bg-blue-500"></div>

    <div class="px-5 py-4 border-b border-slate-200 bg-slate-50/70 flex justify-between items-center">

        <div>
            <h3 class="text-sm font-semibold text-slate-900">
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
            class="text-xs bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-lg shadow-sm transition">
            + Add Entry
        </button>
        @endif

    </div>




    <div class="p-5">

        <div class="border border-slate-300 rounded-xl overflow-hidden">

            <div class="overflow-x-auto overflow-y-auto max-h-[400px]">

                <table class="min-w-[900px] text-sm border border-slate-300" style="border-collapse: collapse;">

                    <thead class="bg-slate-100 text-slate-700 text-xs uppercase tracking-wide">
                        <tr class="border-b border-slate-300">

                            <th class="border border-slate-300 px-3 py-2 text-center w-[110px]">
                                Quantity
                            </th>

                            <th class="border border-slate-300 px-3 py-2 text-left">
                                Series / Control Numbers
                            </th>

                            <th class="border border-slate-300 px-3 py-2 text-right w-[150px]">
                                Price (₱)
                            </th>

                            <th class="border border-slate-300 px-3 py-2 text-right w-[150px]">
                                Amount (₱)
                            </th>

                            <th class="border border-slate-300 px-3 py-2 text-left">
                                Remarks
                            </th>

                            @if(!$isReadOnly)
                            <th class="border border-slate-300 px-3 py-2 text-center w-[80px]">
                                Action
                            </th>
                            @endif

                        </tr>
                    </thead>

                    <tbody id="ticketItemsTable">

                        @php
                            $items = old('items', $items ?? []);
                        @endphp

                        @if(count($items))

                            @foreach($items as $i => $item)

                            <tr class="border-b border-slate-300 hover:bg-slate-50 transition">

                                <td class="border border-slate-300 px-2 py-2">
                                    <input
                                        type="number"
                                        name="items[{{ $i }}][quantity]"
                                        value="{{ $item['quantity'] ?? '' }}"
                                        oninput="updateTicketAmount(this)"
                                        class="w-full px-2 py-1 text-sm text-center border-0 bg-transparent focus:ring-0"
                                        placeholder="0"
                                        @if($isReadOnly) disabled @endif>
                                </td>

                                <td class="border border-slate-300 px-2 py-2">
                                    <input
                                        type="text"
                                        name="items[{{ $i }}][series_control_numbers]"
                                        value="{{ $item['series_control_numbers'] ?? '' }}"
                                        placeholder="e.g. 001–100"
                                        class="w-full px-2 py-1 text-sm border-0 bg-transparent focus:ring-0"
                                        @if($isReadOnly) disabled @endif>
                                </td>

                                <td class="border border-slate-300 px-2 py-2">
                                    <input
                                        type="text"
                                        name="items[{{ $i }}][price_per_ticket]"
                                        value="{{ isset($item['price_per_ticket']) ? number_format($item['price_per_ticket'],2) : '' }}"
                                        oninput="formatCurrencyInput(this); updateTicketAmount(this)"
                                        class="w-full px-2 py-1 text-sm text-right border-0 bg-transparent focus:ring-0"
                                        placeholder="0.00"
                                        @if($isReadOnly) disabled @endif>
                                </td>

                                <td class="border border-slate-300 px-2 py-2 bg-slate-50">
                                    <input
                                        type="text"
                                        readonly
                                        class="w-full px-2 py-1 text-sm text-right font-semibold border-0 bg-transparent ticket-amount-field"
                                        value="{{ number_format((($item['quantity'] ?? 0) * ($item['price_per_ticket'] ?? 0)),2) }}">
                                </td>

                                <td class="border border-slate-300 px-2 py-2">
                                    <input
                                        type="text"
                                        name="items[{{ $i }}][remarks]"
                                        value="{{ $item['remarks'] ?? '' }}"
                                        placeholder="Optional"
                                        class="w-full px-2 py-1 text-sm border-0 bg-transparent focus:ring-0"
                                        @if($isReadOnly) disabled @endif>
                                </td>

                                @if(!$isReadOnly)
                                <td class="border border-slate-300 px-2 py-2 text-center">
                                    <button
                                        type="button"
                                        onclick="this.closest('tr').remove(); updateTicketTotal();"
                                        class="text-rose-600 hover:text-rose-800 text-xs font-medium">
                                        Remove
                                    </button>
                                </td>
                                @endif

                            </tr>

                            @endforeach

                        @else

                        <tr class="border-b border-slate-300">

                            <td class="border border-slate-300 px-2 py-2">
                                <input type="number" name="items[0][quantity]" oninput="updateTicketAmount(this)"
                                    class="w-full px-2 py-1 text-sm text-center border-0 bg-transparent"
                                    placeholder="0"
                                    @if($isReadOnly) disabled @endif>
                            </td>

                            <td class="border border-slate-300 px-2 py-2">
                                <input type="text" name="items[0][series_control_numbers]"
                                    class="w-full px-2 py-1 text-sm border-0 bg-transparent"
                                    placeholder="e.g. 001–100"
                                    @if($isReadOnly) disabled @endif>
                            </td>

                            <td class="border border-slate-300 px-2 py-2">
                                <input type="text" name="items[0][price_per_ticket]"
                                    oninput="formatCurrencyInput(this); updateTicketAmount(this)"
                                    class="w-full px-2 py-1 text-sm text-right border-0 bg-transparent"
                                    placeholder="0.00"
                                    @if($isReadOnly) disabled @endif>
                            </td>

                            <td class="border border-slate-300 px-2 py-2 bg-slate-50">
                                <input type="text" readonly
                                    class="w-full px-2 py-1 text-sm text-right border-0 bg-transparent ticket-amount-field">
                            </td>

                            <td class="border border-slate-300 px-2 py-2">
                                <input type="text" name="items[0][remarks]"
                                    class="w-full px-2 py-1 text-sm border-0 bg-transparent"
                                    placeholder="Optional"
                                    @if($isReadOnly) disabled @endif>
                            </td>

                            @if(!$isReadOnly)
                            <td class="border border-slate-300 px-2 py-2 text-center">—</td>
                            @endif

                        </tr>

                        @endif

                    </tbody>

                </table>

            </div>

        </div>

        <div class="flex justify-end mt-6">

            <div class="text-right">
                <p class="text-xs text-slate-500 mb-1">
                    Total Ticket Sales
                </p>

                <input
                    type="text"
                    id="totalTicketSales"
                    readonly
                    class="w-56 rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm font-semibold text-right shadow-sm"
                    placeholder="0.00">
            </div>

        </div>
            <p class="text-xs text-slate-500 mt-1">
               Unsold tickets must be attached to this report for auditing purpose. Submit this report to SACDEV-OSA upon submission of the liquidation report of the activity where the ticket-selling was meant for. 
            </p>

    </div>






</div>