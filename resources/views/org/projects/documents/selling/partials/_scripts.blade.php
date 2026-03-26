<script>

function addSellingItemRow() {
    const table = document.getElementById('sellingItemsTable');
    const index = table.children.length;

    const isAdmin = @json($isAdmin);

    const row = `
<tr class="hover:bg-slate-50 transition">

    <td class="px-2 py-1">
        <input
            type="number"
            name="items[${index}][quantity]"
            oninput="updateSubtotal(this)"
            class="w-full rounded-md border border-slate-300 px-2 py-1 text-sm">
    </td>

    <td class="px-2 py-1">
        <input
            type="text"
            name="items[${index}][particulars]"
            class="w-full rounded-md border border-slate-300 px-2 py-1 text-sm">
    </td>

    <td class="px-2 py-1">
        <input
            type="number"
            step="0.01"
            name="items[${index}][selling_price]"
            oninput="updateSubtotal(this)"
            class="w-full rounded-md border border-slate-300 px-2 py-1 text-sm">
    </td>

    <td class="px-2 py-1">
        <input
            type="text"
            readonly
            class="w-full rounded-md border border-slate-200 bg-slate-50 px-2 py-1 text-sm subtotal-field">
    </td>

    <td class="px-2 py-1">
        <input
            type="text"
            name="items[${index}][remarks]"
            class="w-full rounded-md border border-amber-200 bg-amber-50 px-2 py-1 text-sm"
            ${isAdmin ? '' : 'disabled'}>
    </td>

    <td class="px-2 py-1 text-center">
        <button
            type="button"
            onclick="removeRow(this)"
            class="text-rose-600 hover:text-rose-800 text-xs">
            Remove
        </button>
    </td>

</tr>
`;

    table.insertAdjacentHTML('beforeend', row);
}


function removeRow(btn) {
    btn.closest('tr').remove();
    reindexRows();
    updateTotal();
}


function reindexRows() {
    const rows = document.querySelectorAll('#sellingItemsTable tr');

    rows.forEach((row, index) => {
        row.querySelectorAll('input').forEach(input => {
            input.name = input.name.replace(/items\[\d+\]/, `items[${index}]`);
        });
    });
}


/**
 * Calculate subtotal per row
 */
function updateSubtotal(input) {

    const row = input.closest('tr');

    const quantity = parseFloat(
        row.querySelector('[name*="[quantity]"]')?.value || 0
    );

    const price = parseFloat(
        row.querySelector('[name*="[selling_price]"]')?.value || 0
    );

    const subtotal = quantity * price;

    const subtotalField = row.querySelector('.subtotal-field');
    if (subtotalField) {
        subtotalField.value = subtotal.toFixed(2);
    }

    updateTotal();
}


function updateTotal() {
    let total = 0;

    document.querySelectorAll('.subtotal-field').forEach(el => {
        total += parseFloat(el.value || 0);
    });

    const totalField = document.getElementById('sellingTotal');
    if (totalField) {
        totalField.value = total.toFixed(2);
    }
}


/**
 * Initialize on page load
 */
document.addEventListener('DOMContentLoaded', () => {

    // Recalculate all subtotals
    document.querySelectorAll('#sellingItemsTable tr').forEach(row => {
        const qtyInput = row.querySelector('[name*="[quantity]"]');
        if (qtyInput) updateSubtotal(qtyInput);
    });

    updateTotal();
});



</script>