<script>

// ================= ADD ROW =================
function addPurchaseItemRow() {

    const table = document.getElementById('purchaseItemsTable');
    if (!table) return;

    const index = table.querySelectorAll('tr').length;

    const row = `
<tr>

<td class="px-3 py-2">
<input type="number"
    name="items[${index}][quantity]"
    oninput="updateAmount(this)"
    class="w-full rounded border border-slate-200 px-2 py-1 text-sm">
</td>

<td class="px-3 py-2">
<input type="text"
    name="items[${index}][unit]"
    class="w-full rounded border border-slate-200 px-2 py-1 text-sm">
</td>

<td class="px-3 py-2">
<input type="text"
    name="items[${index}][particulars]"
    class="w-full rounded border border-slate-200 px-2 py-1 text-sm">
</td>

<td class="px-3 py-2">
<input type="number" step="0.01"
    name="items[${index}][unit_price]"
    oninput="updateAmount(this)"
    class="w-full rounded border border-slate-200 px-2 py-1 text-sm">
</td>

<td class="px-3 py-2 bg-slate-50">
<input type="text" readonly
    class="w-full rounded border border-slate-200 px-2 py-1 text-sm amount-field">
</td>

<td class="px-3 py-2">
<input type="text"
    name="items[${index}][vendor]"
    class="w-full rounded border border-slate-200 px-2 py-1 text-sm">
</td>

<td class="px-3 py-2 text-center">
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

    const quantity = parseFloat(
        row.querySelector('input[name*="[quantity]"]')?.value
    ) || 0;

    const unitPrice = parseFloat(
        row.querySelector('input[name*="[unit_price]"]')?.value
    ) || 0;

    const amount = quantity * unitPrice;

    const amountField = row.querySelector('.amount-field');
    if (amountField) {
        amountField.value = amount.toFixed(2);
    }

    updateTotal();
}


// ================= UPDATE TOTAL =================
function updateTotal() {

    const amountFields = document.querySelectorAll('.amount-field');

    let total = 0;

    amountFields.forEach(field => {
        total += parseFloat(field.value) || 0;
    });

    const totalField = document.getElementById('purchaseTotal');

    if (totalField) {
        totalField.value = total.toFixed(2);
    }
}


// ================= INIT =================
document.addEventListener('DOMContentLoaded', function () {

    // initialize totals
    updateTotal();

    // auto open instructions if draft
    const status = @json($status ?? 'draft');

    if (status === 'draft') {
        openModal('instructionModal');
    }

});

</script>