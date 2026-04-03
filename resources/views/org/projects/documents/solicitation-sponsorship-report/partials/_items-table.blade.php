<div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

    <div class="h-1 bg-blue-500"></div>

    <div class="p-5 space-y-5">

        <div class="flex items-start justify-between">
            <div>
                <h3 class="text-sm font-semibold text-slate-900">
                    Solicitation / Sponsorship Recipients
                </h3>
                <p class="text-xs text-blue-700 mt-1">
                    List all recipients of solicitation letters, including contributions and assigned personnel.
                </p>
            </div>

            @if(!$isReadOnly)
            <button
                type="button"
                onclick="addSolicitationRow()"
                class="text-xs px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow-sm">
                + Add Entry
            </button>
            @endif
        </div>

        <div class="border border-slate-200 rounded-xl overflow-hidden">

            <div class="overflow-x-auto">

                <table class="min-w-full text-sm">

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

                    <tbody id="solicitationItemsTable" class="divide-y divide-slate-200">

                    @php
                        $items = old('items', $items ?? []);
                    @endphp

                    @if(count($items))

                        @foreach($items as $i => $item)
                        <tr class="hover:bg-slate-50 transition">

                            <td class="px-2 py-2">
                                <input type="text"
                                    name="items[{{ $i }}][control_number]"
                                    value="{{ $item['control_number'] ?? '' }}"
                                    placeholder="SOL-001"
                                    class="w-full rounded-lg px-2 py-1 text-sm
                                        {{ $errors->has("items.$i.control_number")
                                            ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500'
                                            : 'border-slate-300 focus:ring-blue-500 focus:border-blue-500' }}
                                        focus:ring-2 focus:outline-none transition"
                                    @if($isReadOnly) disabled @endif>
                            </td>

                            <td class="px-2 py-2">
                                <input type="text"
                                    name="items[{{ $i }}][person_in_charge]"
                                    value="{{ $item['person_in_charge'] ?? '' }}"
                                    placeholder="Assigned officer"
                                    class="w-full rounded-lg px-2 py-1 text-sm
                                        {{ $errors->has("items.$i.person_in_charge")
                                            ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500'
                                            : 'border-slate-300 focus:ring-blue-500 focus:border-blue-500' }}
                                        focus:ring-2 focus:outline-none transition"
                                    @if($isReadOnly) disabled @endif>
                            </td>

                            <td class="px-2 py-2">
                                <input type="text"
                                    name="items[{{ $i }}][recipient]"
                                    value="{{ $item['recipient'] ?? '' }}"
                                    placeholder="Sponsor / Donor"
                                    class="w-full rounded-lg px-2 py-1 text-sm
                                        {{ $errors->has("items.$i.recipient")
                                            ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500'
                                            : 'border-slate-300 focus:ring-blue-500 focus:border-blue-500' }}
                                        focus:ring-2 focus:outline-none transition"
                                    @if($isReadOnly) disabled @endif>
                            </td>

                            <td class="px-2 py-2">
                                <input type="text"
                                    inputmode="decimal"
                                    name="items[{{ $i }}][amount_given]"
                                    value="{{ $item['amount_given'] ?? '' }}"
                                    oninput="formatCurrencyInput(this); updateTotalRaised();"
                                    placeholder="0.00"
                                    class="w-full text-right rounded-lg px-2 py-1 text-sm
                                        {{ $errors->has("items.$i.amount_given")
                                            ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500'
                                            : 'border-slate-300 focus:ring-blue-500 focus:border-blue-500' }}
                                        focus:ring-2 focus:outline-none transition"
                                    @if($isReadOnly) disabled @endif>
                            </td>

                            <td class="px-2 py-2">
                                <input type="text"
                                    name="items[{{ $i }}][remarks]"
                                    value="{{ $item['remarks'] ?? '' }}"
                                    placeholder="Optional"
                                    class="w-full rounded-lg px-2 py-1 text-sm border-slate-300 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                    @if($isReadOnly) disabled @endif>
                            </td>

                            @if(!$isReadOnly)
                            <td class="px-2 py-2 text-center">
                                <button type="button"
                                    onclick="removeSolicitationRow(this)"
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
                                <input type="text" name="items[0][control_number]"
                                    class="w-full rounded-lg px-2 py-1 text-sm border-slate-300 focus:ring-2 focus:ring-blue-500">
                            </td>

                            <td class="px-2 py-2">
                                <input type="text" name="items[0][person_in_charge]"
                                    class="w-full rounded-lg px-2 py-1 text-sm border-slate-300 focus:ring-2 focus:ring-blue-500">
                            </td>

                            <td class="px-2 py-2">
                                <input type="text" name="items[0][recipient]"
                                    class="w-full rounded-lg px-2 py-1 text-sm border-slate-300 focus:ring-2 focus:ring-blue-500">
                            </td>

                            <td class="px-2 py-2">
                                <input type="text" name="items[0][amount_given]"
                                    oninput="formatCurrencyInput(this); updateTotalRaised();"
                                    class="w-full text-right rounded-lg px-2 py-1 text-sm border-slate-300 focus:ring-2 focus:ring-blue-500">
                            </td>

                            <td class="px-2 py-2">
                                <input type="text" name="items[0][remarks]"
                                    class="w-full rounded-lg px-2 py-1 text-sm border-slate-300 focus:ring-2 focus:ring-blue-500">
                            </td>

                            @if(!$isReadOnly)
                            <td class="px-2 py-2 text-center text-slate-400 text-xs">—</td>
                            @endif
                        </tr>

                    @endif

                    </tbody>

                </table>

            </div>

        </div>

        <p class="text-[11px] text-slate-500">
            Ensure all entries match the official solicitation letters submitted to SACDEV. Total is auto-calculated.
        </p>

    </div>

</div>

<script>
function parseCurrency(value) {
    if (!value) return 0;
    return parseFloat(value.toString().replace(/,/g, '')) || 0;
}

function formatCurrency(value) {
    if (!value || isNaN(value)) return '0.00';
    return Number(value).toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

function updateTotalRaised() {

    let total = 0;

    document.querySelectorAll('[name*="[amount_given]"]').forEach(field => {
        total += parseCurrency(field.value);
    });

    const totalField = document.getElementById('totalAmountRaised');

    if (totalField) {
        totalField.value = formatCurrency(total);
    }
}

document.addEventListener('DOMContentLoaded', function () {
    updateTotalRaised();
});
</script>