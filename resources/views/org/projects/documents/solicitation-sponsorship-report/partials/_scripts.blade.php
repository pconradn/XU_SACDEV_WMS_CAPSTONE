<script>

function addSolicitationRow() {

    const table = document.getElementById('solicitationItemsTable');
    const index = table.children.length;

    const row = `
<tr>

<td class="border border-slate-300">
<input
type="text"
name="items[${index}][control_number]"
class="w-full px-2 py-1 border-0 text-[12px]">
</td>

<td class="border border-slate-300">
<input
type="text"
name="items[${index}][person_in_charge]"
class="w-full px-2 py-1 border-0 text-[12px]">
</td>

<td class="border border-slate-300">
<input
type="text"
name="items[${index}][recipient]"
class="w-full px-2 py-1 border-0 text-[12px]">
</td>

<td class="border border-slate-300">
<input
type="number"
step="0.01"
name="items[${index}][amount_given]"
oninput="updateTotalRaised()"
class="w-full px-2 py-1 border-0 text-[12px]">
</td>

<td class="border border-slate-300">
<input
type="text"
name="items[${index}][remarks]"
class="w-full px-2 py-1 border-0 text-[12px]">
</td>

<td class="border border-slate-300 text-center">
<button
type="button"
onclick="removeSolicitationRow(this)"
class="text-rose-600 hover:text-rose-800">
Remove
</button>
</td>

</tr>
`;

    table.insertAdjacentHTML('beforeend', row);

}



function removeSolicitationRow(button) {

    const row = button.closest('tr');
    row.remove();

    updateTotalRaised();

}



function updateTotalRaised() {

    const amounts = document.querySelectorAll(
        'input[name*="[amount_given]"]'
    );

    let total = 0;

    amounts.forEach(field => {

        const value = parseFloat(field.value) || 0;
        total += value;

    });

    const totalField = document.getElementById('totalAmountRaised');

    if (totalField) {
        totalField.value = total.toFixed(2);
    }

}



document.addEventListener('DOMContentLoaded', function () {

    updateTotalRaised();

});

</script>