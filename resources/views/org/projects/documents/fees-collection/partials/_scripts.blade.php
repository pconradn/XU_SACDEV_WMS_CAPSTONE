<script>

function addCollectionRow() {

    const table = document.getElementById('collectionTable');

    if (!table) return;

    const index = table.querySelectorAll('tr').length;

    const row = document.createElement('tr');

    row.innerHTML = `
    <td class="border border-slate-300">
        <input
            type="number"
            name="items[${index}][number_of_payers]"
            class="w-full px-2 py-1 border-0 text-[12px]"
        >
    </td>

    <td class="border border-slate-300">
        <input
            type="number"
            step="0.01"
            name="items[${index}][amount_paid]"
            class="w-full px-2 py-1 border-0 text-[12px]"
        >
    </td>

    <td class="border border-slate-300">
        <input
            type="text"
            name="items[${index}][receipt_series]"
            class="w-full px-2 py-1 border-0 text-[12px]"
        >
    </td>

    <td class="border border-slate-300">
        <input
            type="text"
            name="items[${index}][remarks]"
            class="w-full px-2 py-1 border-0 text-[12px] bg-amber-50"
        >
    </td>

    <td class="border border-slate-300 text-center">
        <button
            type="button"
            onclick="removeCollectionRow(this)"
            class="text-rose-600 hover:text-rose-800"
        >
            Remove
        </button>
    </td>
    `;

    table.appendChild(row);
}



function removeCollectionRow(button) {

    const row = button.closest('tr');

    if (!row) return;

    row.remove();

}



</script>