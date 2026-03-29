<script>

// ================= UTIL =================
function getNextIndex() {
    return document.querySelectorAll('#solicitationItemsTable tr').length;
}

function generateControlNumber(index) {
    return `SOL-${String(index + 1).padStart(3, '0')}`;
}

function formatCurrency(value) {
    return new Intl.NumberFormat('en-PH', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }).format(value);
}


// ================= ADD ROW =================
function addSolicitationRow() {

    const table = document.getElementById('solicitationItemsTable');
    if (!table) return;

    const index = getNextIndex();

    const row = `
<tr class="hover:bg-slate-50">

    <td class="px-3 py-2">
        <input type="text"
            name="items[${index}][control_number]"
            value="${generateControlNumber(index)}"
            placeholder="e.g. SOL-001"
            class="w-full rounded-md border border-slate-300 px-2 py-1 text-sm">
    </td>

    <td class="px-3 py-2">
        <input type="text"
            name="items[${index}][person_in_charge]"
            placeholder="Assigned officer"
            class="w-full rounded-md border border-slate-300 px-2 py-1 text-sm">
    </td>

    <td class="px-3 py-2">
        <input type="text"
            name="items[${index}][recipient]"
            placeholder="Sponsor / Donor name"
            class="w-full rounded-md border border-slate-300 px-2 py-1 text-sm">
    </td>

    <td class="px-3 py-2">
        <input type="number"
            step="0.01"
            name="items[${index}][amount_given]"
            placeholder="0.00"
            oninput="updateTotalRaised()"
            class="w-full text-right rounded-md border border-slate-300 px-2 py-1 text-sm">
    </td>

    <td class="px-3 py-2">
        <input type="text"
            name="items[${index}][remarks]"
            placeholder="Optional notes"
            class="w-full rounded-md border border-slate-300 px-2 py-1 text-sm">
    </td>

    <td class="px-3 py-2 text-center">
        <button type="button"
            onclick="removeSolicitationRow(this)"
            class="text-rose-600 hover:text-rose-800 text-xs font-medium">
            Remove
        </button>
    </td>

</tr>
`;

    table.insertAdjacentHTML('beforeend', row);

    updateTotalRaised();
}


// ================= REMOVE ROW =================
function removeSolicitationRow(button) {

    const row = button.closest('tr');
    if (!row) return;

    row.remove();

    updateTotalRaised();
}


// ================= CALCULATION =================
function updateTotalRaised() {

    let total = 0;

    const table = document.getElementById('solicitationItemsTable');

    if (!table) return;

    const fields = table.querySelectorAll('input[name*="[amount_given]"]');

    fields.forEach(field => {

        const value = parseFloat(field.value);

        if (!isNaN(value)) {
            total += value;
        }

    });

    const totalField = document.getElementById('totalAmountRaised');

    if (totalField) {
        totalField.value = formatCurrency(total);
    }
}


// ================= INIT =================
document.addEventListener('DOMContentLoaded', function () {

    updateTotalRaised();

});

</script>