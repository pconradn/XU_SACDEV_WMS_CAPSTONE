<div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

    <div class="h-1 bg-blue-500"></div>

    <div class="p-5 space-y-5">

        <div class="flex flex-col">
            <h3 class="text-sm font-semibold text-slate-900">
                Source of Funds
            </h3>
            <p class="text-xs text-blue-700">
                Specify where the funding for this purchase will be sourced.
            </p>
        </div>

        @php
            $xu = old('xu_finance_amount', $data->xu_finance_amount ?? '');
            $membership = old('membership_fee_amount', $data->membership_fee_amount ?? '');
            $pta = old('pta_amount', $data->pta_amount ?? '');
            $solicitations = old('solicitations_amount', $data->solicitations_amount ?? '');
            $othersLabel = old('others_label', $data->others_label ?? '');
            $othersAmount = old('others_amount', $data->others_amount ?? '');
        @endphp

        <div class="border border-slate-200 rounded-xl overflow-hidden">

            <table class="min-w-full text-sm">

                <thead class="bg-slate-50 text-slate-600 text-xs uppercase tracking-wide">
                    <tr>
                        <th class="px-3 py-2 text-left">Fund Source</th>
                        <th class="px-3 py-2 text-left w-[180px]">Amount (₱)</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-200">

                    <tr>
                        <td class="px-3 py-2 text-slate-800">XU Finance</td>
                        <td class="px-3 py-2">
                            <input type="text"
                                inputmode="decimal"
                                name="xu_finance_amount"
                                value="{{ $xu }}"
                                oninput="formatCurrencyInput(this); updateFundTotal();"
                                class="w-full rounded-lg px-3 py-1.5 text-sm
                                    {{ $errors->has('xu_finance_amount')
                                        ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500'
                                        : 'border-slate-300 focus:ring-blue-500 focus:border-blue-500' }}
                                    focus:ring-2 focus:outline-none transition"
                                placeholder="0.00">
                        </td>
                    </tr>

                    <tr>
                        <td class="px-3 py-2 text-slate-800">Membership Fee</td>
                        <td class="px-3 py-2">
                            <input type="text"
                                inputmode="decimal"
                                name="membership_fee_amount"
                                value="{{ $membership }}"
                                oninput="formatCurrencyInput(this); updateFundTotal();"
                                class="w-full rounded-lg px-3 py-1.5 text-sm
                                    {{ $errors->has('membership_fee_amount')
                                        ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500'
                                        : 'border-slate-300 focus:ring-blue-500 focus:border-blue-500' }}
                                    focus:ring-2 focus:outline-none transition"
                                placeholder="0.00">
                        </td>
                    </tr>

                    <tr>
                        <td class="px-3 py-2 text-slate-800">PTA</td>
                        <td class="px-3 py-2">
                            <input type="text"
                                inputmode="decimal"
                                name="pta_amount"
                                value="{{ $pta }}"
                                oninput="formatCurrencyInput(this); updateFundTotal();"
                                class="w-full rounded-lg px-3 py-1.5 text-sm
                                    {{ $errors->has('pta_amount')
                                        ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500'
                                        : 'border-slate-300 focus:ring-blue-500 focus:border-blue-500' }}
                                    focus:ring-2 focus:outline-none transition"
                                placeholder="0.00">
                        </td>
                    </tr>

                    <tr>
                        <td class="px-3 py-2 text-slate-800">Solicitations</td>
                        <td class="px-3 py-2">
                            <input type="text"
                                inputmode="decimal"
                                name="solicitations_amount"
                                value="{{ $solicitations }}"
                                oninput="formatCurrencyInput(this); updateFundTotal();"
                                class="w-full rounded-lg px-3 py-1.5 text-sm
                                    {{ $errors->has('solicitations_amount')
                                        ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500'
                                        : 'border-slate-300 focus:ring-blue-500 focus:border-blue-500' }}
                                    focus:ring-2 focus:outline-none transition"
                                placeholder="0.00">
                        </td>
                    </tr>

                    <tr>
                        <td class="px-3 py-2">
                            <div class="flex flex-col gap-1">
                                <span class="text-slate-800 text-sm">Others</span>
                                <input type="text"
                                    name="others_label"
                                    value="{{ $othersLabel }}"
                                    placeholder="Specify source"
                                    class="rounded-md px-2 py-1 text-xs
                                        {{ $errors->has('others_label')
                                            ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500'
                                            : 'border-slate-300 focus:ring-blue-500 focus:border-blue-500' }}
                                        focus:ring-2 focus:outline-none transition">
                            </div>
                        </td>

                        <td class="px-3 py-2">
                            <input type="text"
                                inputmode="decimal"
                                name="others_amount"
                                value="{{ $othersAmount }}"
                                oninput="formatCurrencyInput(this); updateFundTotal();"
                                class="w-full rounded-lg px-3 py-1.5 text-sm
                                    {{ $errors->has('others_amount')
                                        ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500'
                                        : 'border-slate-300 focus:ring-blue-500 focus:border-blue-500' }}
                                    focus:ring-2 focus:outline-none transition"
                                placeholder="0.00">
                        </td>
                    </tr>

                </tbody>

                <tfoot class="bg-slate-50 border-t border-slate-200">
                    <tr>
                        <td class="px-3 py-2 text-right text-xs font-semibold text-slate-600">
                            Total Funds (₱)
                        </td>
                        <td class="px-3 py-2">
                            <input type="text"
                                id="fundTotal"
                                readonly
                                class="w-full rounded-lg border border-slate-300 bg-white px-2 py-1.5 text-sm font-semibold">
                        </td>
                    </tr>
                </tfoot>

            </table>

        </div>

    </div>

</div>

<script>
function formatCurrencyInput(input) {
    let value = input.value.replace(/,/g, '');
    if (value === '') return;

    if (!isNaN(value)) {
        let parts = value.split('.');
        parts[0] = parseInt(parts[0], 10).toLocaleString('en-US');
        input.value = parts.join('.');
    }
}
</script>
<script>

function parseCurrency(value) {
    if (!value) return 0;
    return parseFloat(value.toString().replace(/,/g, '')) || 0;
}

function formatCurrency(value) {
    if (value === '' || value === null || isNaN(value)) return '';
    return Number(value).toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

function formatCurrencyInput(input) {
    let value = input.value.replace(/,/g, '');
    if (value === '') return;

    if (!isNaN(value)) {
        let parts = value.split('.');
        parts[0] = parseInt(parts[0], 10).toLocaleString('en-US');
        input.value = parts.join('.');
    }
}

function updateFundTotal() {

    const fields = [
        document.querySelector('[name="xu_finance_amount"]'),
        document.querySelector('[name="membership_fee_amount"]'),
        document.querySelector('[name="pta_amount"]'),
        document.querySelector('[name="solicitations_amount"]'),
        document.querySelector('[name="others_amount"]'),
    ];

    let total = 0;

    fields.forEach(field => {
        if (field) {
            total += parseCurrency(field.value);
        }
    });

    const totalField = document.getElementById('fundTotal');

    if (totalField) {
        totalField.value = formatCurrency(total);
    }
}

document.addEventListener('DOMContentLoaded', function () {
    updateFundTotal();
});

</script>