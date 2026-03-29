<script>

// ================= UTIL =================
function formatCurrency(value) {
    return new Intl.NumberFormat('en-PH', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }).format(value);
}

function getTicketTable() {
    return document.getElementById('ticketItemsTable');
}

function getNextIndex() {
    const table = getTicketTable();
    return table ? table.querySelectorAll('tr').length : 0;
}


// ================= ADD ROW =================
function addTicketRow() {

    const table = getTicketTable();
    if (!table) return;

    const index = getNextIndex();

    const row = `
<tr class="hover:bg-slate-50">

    <td class="px-2 py-2">
        <input type="number"
            name="items[${index}][quantity]"
            placeholder="0"
            oninput="updateTicketAmount(this)"
            class="w-full rounded-md border border-slate-200 px-2 py-1 text-sm">
    </td>

    <td class="px-2 py-2">
        <input type="text"
            name="items[${index}][series_control_numbers]"
            placeholder="e.g. 001–100"
            class="w-full rounded-md border border-slate-200 px-2 py-1 text-sm">
    </td>

    <td class="px-2 py-2">
        <input type="number"
            step="0.01"
            name="items[${index}][price_per_ticket]"
            placeholder="0.00"
            oninput="updateTicketAmount(this)"
            class="w-full rounded-md border border-slate-200 px-2 py-1 text-sm">
    </td>

    <td class="px-2 py-2">
        <input type="text"
            readonly
            class="w-full rounded-md border border-slate-100 bg-slate-50 px-2 py-1 text-sm font-semibold ticket-amount-field">
    </td>

    <td class="px-2 py-2">
        <input type="text"
            name="items[${index}][remarks]"
            placeholder="Optional notes"
            class="w-full rounded-md border border-slate-200 px-2 py-1 text-sm">
    </td>

    <td class="px-2 py-2 text-center">
        <button type="button"
            onclick="removeTicketRow(this)"
            class="text-xs text-rose-600 hover:text-rose-800 font-medium">
            Remove
        </button>
    </td>

</tr>
`;

    table.insertAdjacentHTML('beforeend', row);

}


// ================= REMOVE ROW =================
function removeTicketRow(button) {

    const row = button.closest('tr');
    if (!row) return;

    row.remove();

    updateTicketTotal();

}


// ================= ROW CALC =================
function updateTicketAmount(input) {

    const row = input.closest('tr');
    if (!row) return;

    const quantityField = row.querySelector('input[name*="[quantity]"]');
    const priceField = row.querySelector('input[name*="[price_per_ticket]"]');

    const quantity = parseFloat(quantityField?.value) || 0;
    const price = parseFloat(priceField?.value) || 0;

    const amount = quantity * price;

    const amountField = row.querySelector('.ticket-amount-field');

    if (amountField) {
        amountField.value = formatCurrency(amount);
    }

    updateTicketTotal();

}


// ================= TOTAL CALC =================
function updateTicketTotal() {

    const table = getTicketTable();
    if (!table) return;

    const fields = table.querySelectorAll('.ticket-amount-field');

    let total = 0;

    fields.forEach(field => {

        // remove commas before parsing
        const value = parseFloat(field.value.replace(/,/g, '')) || 0;
        total += value;

    });

    const totalDisplay = document.getElementById('totalTicketSalesDisplay');

    if (totalDisplay) {
        totalDisplay.innerText = "₱ " + formatCurrency(total);
    }

}


// ================= INIT =================
document.addEventListener('DOMContentLoaded', function () {

    updateTicketTotal();

});

</script>