<script>

function addPurchaseItemRow() {

    const table = document.getElementById('purchaseItemsTable');
    const index = table.children.length;

    const row = `
<tr>

<td class="border border-slate-300">
<input
type="number"
name="items[${index}][quantity]"
oninput="updateAmount(this)"
class="w-full px-2 py-1 border-0 text-[12px]">
</td>

<td class="border border-slate-300">
<input
type="text"
name="items[${index}][unit]"
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
name="items[${index}][unit_price]"
oninput="updateAmount(this)"
class="w-full px-2 py-1 border-0 text-[12px]">
</td>

<td class="border border-slate-300 bg-slate-50">
<input
type="text"
readonly
class="w-full px-2 py-1 border-0 text-[12px] amount-field">
</td>

<td class="border border-slate-300">
<input
type="text"
name="items[${index}][vendor]"
class="w-full px-2 py-1 border-0 text-[12px]">
</td>

<td class="border border-slate-300 text-center">
<button
type="button"
onclick="removePurchaseRow(this)"
class="text-rose-600 hover:text-rose-800">
Remove
</button>
</td>

</tr>
`;

    table.insertAdjacentHTML('beforeend', row);
}



function removePurchaseRow(button) {

    const row = button.closest('tr');
    row.remove();

    updateTotal();

}



function updateAmount(input) {

    const row = input.closest('tr');

    const quantity = parseFloat(
        row.querySelector('input[name*="[quantity]"]').value
    ) || 0;

    const unitPrice = parseFloat(
        row.querySelector('input[name*="[unit_price]"]').value
    ) || 0;

    const amount = quantity * unitPrice;

    row.querySelector('.amount-field').value = amount.toFixed(2);

    updateTotal();
}



function updateTotal() {

    const amounts = document.querySelectorAll('.amount-field');

    let total = 0;

    amounts.forEach(field => {

        const value = parseFloat(field.value) || 0;
        total += value;

    });

    const totalField = document.getElementById('purchaseTotal');

    if (totalField) {
        totalField.value = total.toFixed(2);
    }

}



document.addEventListener('DOMContentLoaded', function () {

    updateTotal();

});

</script>