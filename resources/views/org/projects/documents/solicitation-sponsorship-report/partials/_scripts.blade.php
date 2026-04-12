<script>

// ================= UTIL =================
function getNextIndex() {
    return document.querySelectorAll('#solicitationItemsTable tr').length;
}

function generateControlNumber(index) {
    return `SOL-${String(index + 1).padStart(3, '0')}`;
}

function parseCurrency(value) {
    if (!value) return 0;
    return parseFloat(value.toString().replace(/,/g, '')) || 0;
}

function formatCurrency(value) {
    if (!value || isNaN(value)) return '0.00';
    return Number(value).toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

function formatCurrencyInput(input) {
    let value = input.value.replace(/,/g, '');
    if (value === '') return;

    if (!isNaN(value)) {
        let parts = value.split('.');
        parts[0] = parseInt(parts[0], 10).toLocaleString('en-US');
        input.value = parts.join('.');
    }
}


// ================= ADD ROW =================
function addSolicitationRow() {

    const table = document.getElementById('solicitationItemsTable');
    if (!table) return;

    const index = getNextIndex();

    const row = `
        <tr class="hover:bg-slate-50 transition">

        <td class="px-2 py-2">
        <input type="text"
            name="items[${index}][control_number]"
            value="${generateControlNumber(index)}"
            placeholder="SOL-001"
            class="w-full rounded-lg px-2 py-1 text-sm border-slate-300 focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
        </td>

        <td class="px-2 py-2">
        <input type="text"
            name="items[${index}][person_in_charge]"
            placeholder="Assigned officer"
            class="w-full rounded-lg px-2 py-1 text-sm border-slate-300 focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
        </td>

        <td class="px-2 py-2">
        <input type="text"
            name="items[${index}][recipient]"
            placeholder="Sponsor / Donor"
            class="w-full rounded-lg px-2 py-1 text-sm border-slate-300 focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
        </td>

        <td class="px-2 py-2">
        <input type="text"
            name="items[${index}][amount_given]"
            placeholder="0.00"
            oninput="formatCurrencyInput(this); updateTotalRaised()"
            class="w-full text-right rounded-lg px-2 py-1 text-sm border-slate-300 focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
        </td>

        <td class="px-2 py-2">
        <input type="text"
            name="items[${index}][remarks]"
            placeholder="Optional"
            class="w-full rounded-lg px-2 py-1 text-sm border-slate-300 focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </td>

        <td class="px-2 py-2 text-center">
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
        total += parseCurrency(field.value);
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