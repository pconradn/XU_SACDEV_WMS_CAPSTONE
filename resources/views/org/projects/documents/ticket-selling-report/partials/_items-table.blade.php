<div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm overflow-hidden">

    <div class="h-1 bg-blue-500"></div>

    {{-- HEADER --}}
    <div class="px-4 py-4 border-b border-slate-200 bg-slate-50/70 flex justify-between items-start gap-3">

        <div class="flex items-start gap-3">
            <div class="w-9 h-9 rounded-xl bg-blue-50 border border-blue-200 flex items-center justify-center">
                <i data-lucide="ticket" class="w-4 h-4 text-blue-600"></i>
            </div>

            <div>
                <h3 class="text-sm font-semibold text-slate-900 tracking-wide">
                    Actual Tickets Sold
                </h3>

                <p class="text-xs text-slate-500 mt-1">
                    Record each batch of tickets sold including quantity, control numbers, and corresponding revenue.
                </p>
            </div>
        </div>

        @if(!$isReadOnly)
        <button
            type="button"
            onclick="addTicketRow()"
            class="flex items-center gap-1 text-xs bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg shadow-sm transition">
            <i data-lucide="plus" class="w-3 h-3"></i>
            Add Entry
        </button>
        @endif

    </div>

    {{-- CONTENT --}}
    <div class="p-4 space-y-6">

        <div class="border border-slate-200 rounded-xl bg-white overflow-hidden">

            <div class="overflow-x-auto">

                <div class="min-w-[900px]">

                    <div class="max-h-[400px] overflow-y-auto">

                        <table class="min-w-full text-xs border-collapse">

                            <thead class="sticky top-0 bg-slate-100 text-slate-700 uppercase tracking-wide z-10">
                                <tr>
                                    <th class="px-3 py-2 text-center w-[100px] border">Quantity</th>
                                    <th class="px-3 py-2 text-left border">Series / Control Numbers</th>
                                    <th class="px-3 py-2 text-right w-[140px] border">Price (₱)</th>
                                    <th class="px-3 py-2 text-right w-[140px] border">Amount (₱)</th>
                                    <th class="px-3 py-2 text-left border">Remarks</th>
                                    @if(!$isReadOnly)
                                    <th class="px-3 py-2 text-center w-[80px] border">Action</th>
                                    @endif
                                </tr>
                            </thead>

                            <tbody id="ticketItemsTable" class="divide-y divide-slate-200 bg-white">

                                @php
                                    $items = old('items', $items ?? []);
                                @endphp

                                @if(count($items))

                                    @foreach($items as $i => $item)

                                    <tr class="hover:bg-slate-50">

                                        <td class="px-2 py-2 border">
                                            <input type="number"
                                                name="items[{{ $i }}][quantity]"
                                                value="{{ $item['quantity'] ?? '' }}"
                                                oninput="updateTicketAmount(this)"
                                                class="w-full text-center text-xs border-0 bg-transparent focus:ring-0"
                                                @if($isReadOnly) disabled @endif>
                                        </td>

                                        <td class="px-2 py-2 border">
                                            <input type="text"
                                                name="items[{{ $i }}][series_control_numbers]"
                                                value="{{ $item['series_control_numbers'] ?? '' }}"
                                                class="w-full text-xs border-0 bg-transparent focus:ring-0"
                                                @if($isReadOnly) disabled @endif>
                                        </td>

                                        <td class="px-2 py-2 border">
                                            <input type="text"
                                                name="items[{{ $i }}][price_per_ticket]"
                                                value="{{ isset($item['price_per_ticket']) ? number_format($item['price_per_ticket'],2) : '' }}"
                                                oninput="formatCurrencyInput(this); updateTicketAmount(this)"
                                                class="w-full text-right text-xs border-0 bg-transparent focus:ring-0"
                                                @if($isReadOnly) disabled @endif>
                                        </td>

                                        <td class="px-2 py-2 border bg-slate-50">
                                            <input type="text"
                                                readonly
                                                class="w-full text-right text-xs font-semibold border-0 bg-transparent ticket-amount-field"
                                                value="{{ number_format((($item['quantity'] ?? 0) * ($item['price_per_ticket'] ?? 0)),2) }}">
                                        </td>

                                        <td class="px-2 py-2 border">
                                            <input type="text"
                                                name="items[{{ $i }}][remarks]"
                                                value="{{ $item['remarks'] ?? '' }}"
                                                class="w-full text-xs border-0 bg-transparent focus:ring-0"
                                                @if($isReadOnly) disabled @endif>
                                        </td>

                                        @if(!$isReadOnly)
                                        <td class="px-2 py-2 border text-center">
                                            <button type="button"
                                                onclick="this.closest('tr').remove(); updateTicketTotal();"
                                                class="text-rose-600 text-xs">
                                                Remove
                                            </button>
                                        </td>
                                        @endif

                                    </tr>

                                    @endforeach

                                @else

                                <tr>

                                    <td class="px-2 py-2 border">
                                        <input type="number" name="items[0][quantity]"
                                            oninput="updateTicketAmount(this)"
                                            class="w-full text-center text-xs border-0 bg-transparent"
                                            @if($isReadOnly) disabled @endif>
                                    </td>

                                    <td class="px-2 py-2 border">
                                        <input type="text" name="items[0][series_control_numbers]"
                                            class="w-full text-xs border-0 bg-transparent"
                                            @if($isReadOnly) disabled @endif>
                                    </td>

                                    <td class="px-2 py-2 border">
                                        <input type="text" name="items[0][price_per_ticket]"
                                            oninput="formatCurrencyInput(this); updateTicketAmount(this)"
                                            class="w-full text-right text-xs border-0 bg-transparent"
                                            @if($isReadOnly) disabled @endif>
                                    </td>

                                    <td class="px-2 py-2 border bg-slate-50">
                                        <input type="text" readonly
                                            class="w-full text-right text-xs border-0 bg-transparent ticket-amount-field">
                                    </td>

                                    <td class="px-2 py-2 border">
                                        <input type="text" name="items[0][remarks]"
                                            class="w-full text-xs border-0 bg-transparent"
                                            @if($isReadOnly) disabled @endif>
                                    </td>

                                    @if(!$isReadOnly)
                                    <td class="px-2 py-2 border text-center">—</td>
                                    @endif

                                </tr>

                                @endif

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

        <div class="flex justify-end">

            <div class="text-right">
                <p class="text-xs text-slate-500 mb-1">
                    Total Ticket Sales
                </p>

                <input
                    type="text"
                    id="totalTicketSales"
                    readonly
                    class="w-56 rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs font-semibold text-right shadow-sm"
                    placeholder="0.00">
            </div>

        </div>

        <p class="text-[11px] text-slate-500">
            Unsold tickets must be attached to this report for auditing purpose. Submit this report to SACDEV-OSA upon submission of the liquidation report of the activity where the ticket-selling was meant for.
        </p>

    </div>

</div>