<script>

// ================= HELPERS =================
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


// ================= ADD ROW =================
function addPurchaseItemRow() {

    const table = document.getElementById('purchaseItemsTable');
    if (!table) return;

    const index = table.querySelectorAll('tr').length;

    const row = `
        <tr class="hover:bg-slate-50 transition">

            <td class="px-2 py-2">
                <input type="number"
                    name="items[${index}][quantity]"
                    oninput="updateAmount(this)"
                    placeholder="0"
                    class="w-full rounded-lg px-2 py-1 text-sm border-slate-300 focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
            </td>

            <td class="px-2 py-2">
                <input type="text"
                    name="items[${index}][unit]"
                    placeholder="pcs"
                    class="w-full rounded-lg px-2 py-1 text-sm border-slate-300 focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
            </td>

            <td class="px-2 py-2">
                <input type="text"
                    name="items[${index}][particulars]"
                    placeholder="Item description"
                    class="w-full rounded-lg px-2 py-1 text-sm border-slate-300 focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
            </td>

            <td class="px-2 py-2">
                <input type="text"
                    name="items[${index}][unit_price]"
                    inputmode="decimal"
                    oninput="formatCurrencyInput(this); updateAmount(this)"
                    placeholder="0.00"
                    class="w-full rounded-lg px-2 py-1 text-sm border-slate-300 focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
            </td>

            <td class="px-2 py-2">
                <input type="text" readonly
                    class="w-full rounded-lg border border-slate-200 bg-slate-50 px-2 py-1 text-sm amount-field font-medium">
            </td>

            <td class="px-2 py-2">
                <input type="text"
                    name="items[${index}][vendor]"
                    placeholder="Optional"
                    class="w-full rounded-lg px-2 py-1 text-sm border-slate-300 focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </td>

            <td class="px-2 py-2 text-center">
                <button type="button"
                    onclick="removePurchaseRow(this)"
                    class="text-rose-600 hover:text-rose-800 text-xs">
                    Remove
                </button>
            </td>

        </tr>
    `;

    table.insertAdjacentHTML('beforeend', row);
}
// ================= REMOVE ROW =================
function removePurchaseRow(button) {

    const row = button.closest('tr');
    if (!row) return;

    row.remove();

    updateTotal();
}


// ================= UPDATE AMOUNT =================
function updateAmount(input) {

    const row = input.closest('tr');
    if (!row) return;

    const quantity = parseCurrency(
        row.querySelector('input[name*="[quantity]"]')?.value
    );

    const unitPrice = parseCurrency(
        row.querySelector('input[name*="[unit_price]"]')?.value
    );

    const amount = quantity * unitPrice;

    const amountField = row.querySelector('.amount-field');
    if (amountField) {
        amountField.value = formatCurrency(amount);
    }

    updateTotal();
}


// ================= UPDATE TOTAL =================
function updateTotal() {

    const amountFields = document.querySelectorAll('.amount-field');

    let total = 0;

    amountFields.forEach(field => {
        total += parseCurrency(field.value);
    });

    const totalField = document.getElementById('purchaseTotal');

    if (totalField) {
        totalField.value = formatCurrency(total);
    }
}


// ================= FORMAT INPUT =================
function formatCurrencyInput(input) {

    let value = input.value.replace(/,/g, '');
    if (value === '') return;

    if (!isNaN(value)) {
        let parts = value.split('.');
        parts[0] = parseInt(parts[0], 10).toLocaleString('en-US');
        input.value = parts.join('.');
    }
}


// ================= INIT =================
document.addEventListener('DOMContentLoaded', function () {

    updateTotal();

    const status = @json($status ?? 'draft');

    if (status === 'draft') {
        openModal('instructionModal');
    }

});

</script>