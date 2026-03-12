<script>

function addSellingItemRow() {

    const table = document.getElementById('sellingItemsTable');
    const index = table.children.length;

    const isAdmin = @json($isAdmin);

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
name="items[${index}][selling_price]"
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
name="items[${index}][remarks]"
class="w-full px-2 py-1 border-0 text-[12px]"
${isAdmin ? '' : 'disabled'}>
</td>

<td class="border border-slate-300 text-center">
<button
type="button"
onclick="this.closest('tr').remove()"
class="text-rose-600 hover:text-rose-800">
Remove
</button>
</td>

</tr>
`;

    table.insertAdjacentHTML('beforeend', row);
}



function updateSubtotal(input) {

    const row = input.closest('tr');

    const quantity = parseFloat(
        row.querySelector('input[name*="[quantity]"]').value
    ) || 0;

    const price = parseFloat(
        row.querySelector('input[name*="[selling_price]"]').value
    ) || 0;

    const subtotal = quantity * price;

    row.querySelector('.subtotal-field').value = subtotal.toFixed(2);
}

</script>