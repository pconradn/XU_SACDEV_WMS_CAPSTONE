<script>

// ================= UTIL =================
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

function getTicketTable() {
    return document.getElementById('ticketItemsTable');
}

function getNextIndex() {
    const table = getTicketTable();
    return table ? table.querySelectorAll('tr').length : 0;
}


// ================= ADD ROW =================
function addTicketRow() {

    const table = document.getElementById('ticketItemsTable');
    if (!table) return;

    const index = table.querySelectorAll('tr').length;

    const isReadOnly = @json($isReadOnly);

    const row = `
        <tr class="hover:bg-slate-50 transition">

            <td class="px-2 py-2 border">
                <input type="number"
                    name="items[${index}][quantity]"
                    placeholder="0"
                    oninput="updateTicketAmount(this)"
                    class="w-full text-center text-xs border-0 bg-transparent focus:ring-0"
                    ${isReadOnly ? 'disabled' : ''}>
            </td>

            <td class="px-2 py-2 border">
                <input type="text"
                    name="items[${index}][series_control_numbers]"
                    placeholder="e.g. 001–100"
                    class="w-full text-xs border-0 bg-transparent focus:ring-0"
                    ${isReadOnly ? 'disabled' : ''}>
            </td>

            <td class="px-2 py-2 border">
                <input type="text"
                    name="items[${index}][price_per_ticket]"
                    placeholder="0.00"
                    oninput="formatCurrencyInput(this); updateTicketAmount(this)"
                    class="w-full text-right text-xs border-0 bg-transparent focus:ring-0"
                    ${isReadOnly ? 'disabled' : ''}>
            </td>

            <td class="px-2 py-2 border bg-slate-50">
                <input type="text"
                    readonly
                    class="w-full text-right text-xs font-semibold border-0 bg-transparent ticket-amount-field">
            </td>

            <td class="px-2 py-2 border">
                <input type="text"
                    name="items[${index}][remarks]"
                    placeholder="Optional"
                    class="w-full text-xs border-0 bg-transparent focus:ring-0"
                    ${isReadOnly ? 'disabled' : ''}>
            </td>

            ${!isReadOnly ? `
            <td class="px-2 py-2 border text-center">
                <button type="button"
                    onclick="this.closest('tr').remove(); updateTicketTotal();"
                    class="text-rose-600 hover:text-rose-800 transition text-xs">
                    Remove
                </button>
            </td>
            ` : ''}

        </tr>
    `;

    table.insertAdjacentHTML('beforeend', row);

    updateTicketTotal();
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
    const price = parseCurrency(priceField?.value);

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
        total += parseCurrency(field.value);
    });

    const totalDisplay = document.getElementById('totalTicketSalesDisplay');
    const totalInput = document.getElementById('totalTicketSales');

    if (totalDisplay) {
        totalDisplay.value = formatCurrency(total);
    }

    if (totalInput) {
        totalInput.value = formatCurrency(total);
    }

}


// ================= INIT =================
document.addEventListener('DOMContentLoaded', function () {
    updateTicketTotal();
});

</script>