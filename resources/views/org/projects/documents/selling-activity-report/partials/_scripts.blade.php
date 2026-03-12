<script>

function addSellingItemRow() {

    const table = document.getElementById('sellingItemsTable');
    const index = table.children.length;

    const row = `
<tr>

<td class="border border-slate-300">
<input
type="number"
name="items[${index}][quantity]"
oninput="updateSubtotal(this)"
class="w-full px-2 py-1 border-0 text-[12px]">
</td>

<td class="border border-slate-300">
<input
type="text"
name="items[${index}][particulars]"
class="w-full px-2 py-1 border-0 text-[12px]">
</td>

<td class="border border-slate-300">
<input
type="number"
step="0.01"
name="items[${index}][price]"
oninput="updateSubtotal(this)"
class="w-full px-2 py-1 border-0 text-[12px]">
</td>



<td class="border border-slate-300 bg-slate-50">
<input
type="text"
readonly
class="w-full px-2 py-1 border-0 text-[12px] subtotal-field">
</td>

<td class="border border-slate-300">
<input
type="text"
name="items[${index}][acknowledgement_receipt_number]"
class="w-full px-2 py-1 border-0 text-[12px]">
</td>

<td class="border border-slate-300 text-center">
<button
type="button"
onclick="removeSellingRow(this)"
class="text-rose-600 hover:text-rose-800">
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

    const quantity = parseFloat(
        row.querySelector('input[name*="[quantity]"]').value
    ) || 0;

    const price = parseFloat(
        row.querySelector('input[name*="[price]"]').value
    ) || 0;

    const subtotal = quantity * price;

    row.querySelector('.subtotal-field').value = subtotal.toFixed(2);

    updateTotalSales();

}



function updateTotalSales() {

    const subtotals = document.querySelectorAll('.subtotal-field');

    let total = 0;

    subtotals.forEach(field => {

        const value = parseFloat(field.value) || 0;
        total += value;

    });

    const totalDisplay = document.getElementById('totalSalesDisplay');

    if (totalDisplay) {
        totalDisplay.value = total.toFixed(2);
    }

}



document.addEventListener('DOMContentLoaded', function () {

    updateTotalSales();

});

</script>