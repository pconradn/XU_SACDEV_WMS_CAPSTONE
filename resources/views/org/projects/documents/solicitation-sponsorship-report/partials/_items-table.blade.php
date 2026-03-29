<div>

    {{-- SECTION HEADER --}}
    <div class="mb-4 flex justify-between items-start">

        <div>
            <h3 class="text-sm font-semibold text-slate-900 tracking-wide">
                Solicitation / Sponsorship Recipients
            </h3>

            <p class="text-xs text-slate-500 mt-1">
                List all recipients of solicitation letters, including contributions and assigned personnel.
            </p>
        </div>

        @if(!$isReadOnly)
        <button
            type="button"
            onclick="addSolicitationRow()"
            class="text-xs px-3 py-1.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            + Add Entry
        </button>
        @endif

    </div>


    {{-- TABLE --}}
    <div class="border border-slate-200 rounded-xl overflow-hidden bg-white">

        <div class="overflow-x-auto">

            <table class="min-w-full text-sm">

                {{-- HEAD --}}
                <thead class="bg-slate-50 text-slate-600 text-xs uppercase tracking-wide">
                    <tr>
                        <th class="px-3 py-2 text-left w-[140px]">Control #</th>
                        <th class="px-3 py-2 text-left w-[180px]">Person-in-Charge</th>
                        <th class="px-3 py-2 text-left">Recipient</th>
                        <th class="px-3 py-2 text-right w-[140px]">Amount (₱)</th>
                        <th class="px-3 py-2 text-left">Remarks</th>
                        @if(!$isReadOnly)
                        <th class="px-3 py-2 text-center w-[80px]">Action</th>
                        @endif
                    </tr>
                </thead>


                {{-- BODY --}}
                <tbody id="solicitationItemsTable" class="divide-y">

                @php
                    $items = old('items', $items ?? []);
                @endphp


                @if(count($items))

                    @foreach($items as $i => $item)

                    <tr class="hover:bg-slate-50">

                        <td class="px-3 py-2">
                            <input type="text"
                                name="items[{{ $i }}][control_number]"
                                value="{{ $item['control_number'] ?? '' }}"
                                placeholder="e.g. SOL-001"
                                class="w-full rounded-md border border-slate-300 px-2 py-1 text-sm"
                                @if($isReadOnly) disabled @endif>
                        </td>

                        <td class="px-3 py-2">
                            <input type="text"
                                name="items[{{ $i }}][person_in_charge]"
                                value="{{ $item['person_in_charge'] ?? '' }}"
                                placeholder="Assigned officer"
                                class="w-full rounded-md border border-slate-300 px-2 py-1 text-sm"
                                @if($isReadOnly) disabled @endif>
                        </td>

                        <td class="px-3 py-2">
                            <input type="text"
                                name="items[{{ $i }}][recipient]"
                                value="{{ $item['recipient'] ?? '' }}"
                                placeholder="Sponsor / Donor name"
                                class="w-full rounded-md border border-slate-300 px-2 py-1 text-sm"
                                @if($isReadOnly) disabled @endif>
                        </td>

                        <td class="px-3 py-2">
                            <input type="number"
                                step="0.01"
                                name="items[{{ $i }}][amount_given]"
                                value="{{ $item['amount_given'] ?? '' }}"
                                oninput="updateTotalRaised()"
                                placeholder="0.00"
                                class="w-full text-right rounded-md border border-slate-300 px-2 py-1 text-sm"
                                @if($isReadOnly) disabled @endif>
                        </td>

                        <td class="px-3 py-2">
                            <input type="text"
                                name="items[{{ $i }}][remarks]"
                                value="{{ $item['remarks'] ?? '' }}"
                                placeholder="Optional notes"
                                class="w-full rounded-md border border-slate-300 px-2 py-1 text-sm"
                                @if($isReadOnly) disabled @endif>
                        </td>

                        @if(!$isReadOnly)
                        <td class="px-3 py-2 text-center">
                            <button type="button"
                                onclick="removeSolicitationRow(this)"
                                class="text-rose-600 hover:text-rose-800 text-xs font-medium">
                                Remove
                            </button>
                        </td>
                        @endif

                    </tr>

                    @endforeach

                @else

                    <tr>

                        <td class="px-3 py-2">
                            <input type="text" name="items[0][control_number]"
                                placeholder="e.g. SOL-001"
                                class="w-full rounded-md border border-slate-300 px-2 py-1 text-sm">
                        </td>

                        <td class="px-3 py-2">
                            <input type="text" name="items[0][person_in_charge]"
                                placeholder="Assigned officer"
                                class="w-full rounded-md border border-slate-300 px-2 py-1 text-sm">
                        </td>

                        <td class="px-3 py-2">
                            <input type="text" name="items[0][recipient]"
                                placeholder="Sponsor / Donor name"
                                class="w-full rounded-md border border-slate-300 px-2 py-1 text-sm">
                        </td>

                        <td class="px-3 py-2">
                            <input type="number" step="0.01"
                                name="items[0][amount_given]"
                                oninput="updateTotalRaised()"
                                placeholder="0.00"
                                class="w-full text-right rounded-md border border-slate-300 px-2 py-1 text-sm">
                        </td>

                        <td class="px-3 py-2">
                            <input type="text" name="items[0][remarks]"
                                placeholder="Optional notes"
                                class="w-full rounded-md border border-slate-300 px-2 py-1 text-sm">
                        </td>

                        @if(!$isReadOnly)
                        <td class="px-3 py-2 text-center text-slate-400 text-xs">—</td>
                        @endif

                    </tr>

                @endif

                </tbody>


            </table>

        </div>

    </div>


    {{-- HELPER TEXT --}}
    <p class="text-[11px] text-slate-400 mt-2">
        Ensure all entries match the official solicitation letters submitted to SACDEV. The total is automatically calculated from all recorded contributions.
    </p>

</div>


