<script>

document.addEventListener('DOMContentLoaded', () => {
    attachAmountListeners();
    updateCollectionTotal();
});


/* ================= ADD ROW ================= */
function addCollectionRow() {

    const table = document.getElementById('collectionTable');
    if (!table) return;

    const index = table.querySelectorAll('tr').length;

    const row = document.createElement('tr');

    row.innerHTML = `
        <td class="px-4 py-2">
            <input
                type="number"
                name="items[${index}][number_of_payers]"
                placeholder="e.g. 50"
                class="w-full rounded-md border border-slate-300 px-2 py-1.5 text-sm focus:ring-2 focus:ring-blue-500"
            >
        </td>

        <td class="px-4 py-2">
            <input
                type="number"
                step="0.01"
                name="items[${index}][amount_paid]"
                placeholder="e.g. 1500.00"
                class="w-full rounded-md border border-slate-300 px-2 py-1.5 text-sm focus:ring-2 focus:ring-blue-500 amount-input"
            >
        </td>

        <td class="px-4 py-2">
            <input
                type="text"
                name="items[${index}][receipt_series]"
                placeholder="OR # / Control No."
                class="w-full rounded-md border border-slate-300 px-2 py-1.5 text-sm focus:ring-2 focus:ring-blue-500"
            >
        </td>

        <td class="px-4 py-2">
            <input
                type="text"
                name="items[${index}][remarks]"
                placeholder="For SACDEV use"
                class="w-full rounded-md border border-amber-200 bg-amber-50 px-2 py-1.5 text-sm"
            >
        </td>

        <td class="px-4 py-2 text-center">
            <button
                type="button"
                onclick="removeCollectionRow(this)"
                class="text-rose-600 hover:text-rose-800 text-xs font-semibold"
            >
                Remove
            </button>
        </td>
    `;

    table.appendChild(row);

    attachAmountListeners();
    updateCollectionTotal();
}


/* ================= REMOVE ROW ================= */
function removeCollectionRow(button) {
    const row = button.closest('tr');
    if (!row) return;

    row.remove();

    updateCollectionTotal();
}


/* ================= TOTAL CALCULATION ================= */
function updateCollectionTotal() {

    const inputs = document.querySelectorAll('.amount-input');
    let total = 0;

    inputs.forEach(input => {
        const value = parseFloat(input.value);
        if (!isNaN(value)) {
            total += value;
        }
    });

    const display = document.getElementById('totalCollectionDisplay');
    if (display) {
        display.textContent = '₱ ' + total.toLocaleString(undefined, {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }
}


/* ================= LISTENERS ================= */
function attachAmountListeners() {

    const inputs = document.querySelectorAll('.amount-input');

    inputs.forEach(input => {

        input.removeEventListener('input', updateCollectionTotal);
        input.addEventListener('input', updateCollectionTotal);

    });

}

</script>