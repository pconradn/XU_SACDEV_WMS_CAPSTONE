<script>

function cleanNumber(value) {
    if (!value) return 0;

    // remove commas before parsing
    return parseFloat(value.toString().replace(/,/g, '')) || 0;
}

function formatCurrency(value) {
    return Number(value || 0).toLocaleString(undefined, {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}


function addSellingItemRow() {

    const table = document.getElementById('sellingItemsTable');
    const index = table.children.length;

    const row = `
<tr class="border-b border-slate-300 hover:bg-slate-50 transition">

<td class="border border-slate-300">
<input
type="number"
name="items[${index}][quantity]"
oninput="updateSubtotal(this)"
class="w-full px-2 py-1 text-sm text-center border-0 bg-transparent"
placeholder="0">
</td>

<td class="border border-slate-300">
<input
type="text"
name="items[${index}][particulars]"
class="w-full px-2 py-1 text-sm border-0 bg-transparent"
placeholder="Item name">
</td>

<td class="border border-slate-300">
<input
type="text"
name="items[${index}][price]"
oninput="formatCurrencyInput(this); updateSubtotal(this)"
class="w-full px-2 py-1 text-sm text-right border-0 bg-transparent"
placeholder="0.00">
</td>

<td class="border border-slate-300 bg-slate-50">
<input
type="text"
readonly
class="w-full px-2 py-1 text-sm text-right font-semibold border-0 bg-transparent subtotal-field">
</td>

<td class="border border-slate-300">
<input
type="text"
name="items[${index}][acknowledgement_receipt_number]"
class="w-full px-2 py-1 text-sm border-0 bg-transparent"
placeholder="Optional">
</td>

<td class="border border-slate-300 text-center">
<button
type="button"
onclick="removeSellingRow(this)"
class="text-rose-600 hover:text-rose-800 text-xs font-medium">
Remove
</button>
</td>

</tr>
`;

    table.insertAdjacentHTML('beforeend', row);
}



function removeSellingRow(button) {
    const row = button.closest('tr');
    row.remove();
    updateTotalSales();
}



function updateSubtotal(input) {

    const row = input.closest('tr');

    const quantity = cleanNumber(
        row.querySelector('input[name*="[quantity]"]').value
    );

    const price = cleanNumber(
        row.querySelector('input[name*="[price]"]').value
    );

    const subtotal = quantity * price;

    row.querySelector('.subtotal-field').value = formatCurrency(subtotal);

    updateTotalSales();
}



function updateTotalSales() {

    const subtotals = document.querySelectorAll('.subtotal-field');

    let total = 0;

    subtotals.forEach(field => {
        total += cleanNumber(field.value);
    });

    // table total (if exists)
    const totalTable = document.getElementById('totalSales');
    if (totalTable) {
        totalTable.value = formatCurrency(total);
    }

    // activity info total (this one)
    const totalDisplay = document.getElementById('totalSalesDisplay');
    if (totalDisplay) {
        totalDisplay.value = formatCurrency(total);
    }
}


function formatCurrencyInput(input) {

    let value = input.value.replace(/,/g, '');

    if (value === '') return;

    if (!isNaN(value)) {
        input.value = formatCurrency(value);
    }
}



document.addEventListener('DOMContentLoaded', function () {
    updateTotalSales();
});

</script>