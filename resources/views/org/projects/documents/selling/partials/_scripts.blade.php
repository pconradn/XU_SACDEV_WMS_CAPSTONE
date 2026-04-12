<script>

function formatMoney(value) {
    if (!value || isNaN(value)) return '';
    return Number(value).toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

function parseMoney(value) {
    if (!value) return 0;
    return parseFloat(value.toString().replace(/,/g, '')) || 0;
}

function handleMoneyInput(input) {
    input.value = input.value.replace(/,/g, '');
}

function formatMoneyInput(input) {
    const number = parseMoney(input.value);
    input.value = number ? formatMoney(number) : '';
    updateSubtotal(input);
}


function addSellingItemRow() {
    const table = document.getElementById('sellingItemsTable');
    const index = table.querySelectorAll('tr').length;

    const isAdmin = @json($isAdmin);
    const isReadOnly = @json($isReadOnly);

    const row = `
    <tr class="hover:bg-slate-50 transition">

        <td class="px-2 py-2">
            <input
                type="number"
                name="items[${index}][quantity]"
                oninput="updateSubtotal(this)"
                class="w-full rounded-lg px-2 py-1 text-xs border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:outline-none transition"
                ${isReadOnly || isAdmin ? 'disabled' : ''}>
        </td>

        <td class="px-2 py-2">
            <input
                type="text"
                name="items[${index}][particulars]"
                class="w-full rounded-lg px-2 py-1 text-xs border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:outline-none transition"
                ${isReadOnly || isAdmin ? 'disabled' : ''}>
        </td>

        <td class="px-2 py-2">
            <input
                type="text"
                inputmode="decimal"
                name="items[${index}][selling_price]"
                oninput="handleMoneyInput(this); updateSubtotal(this)"
                onblur="formatMoneyInput(this)"
                class="w-full rounded-lg px-2 py-1 text-xs border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:outline-none transition"
                ${isReadOnly || isAdmin ? 'disabled' : ''}>
        </td>

        <td class="px-2 py-2">
            <input
                type="text"
                readonly
                class="w-full rounded-lg border border-slate-200 bg-slate-50 px-2 py-1 text-xs subtotal-field">
        </td>

        <td class="px-2 py-2">
            <input
                type="text"
                name="items[${index}][remarks]"
                class="w-full rounded-lg border border-amber-200 bg-amber-50 px-2 py-1 text-xs"
                ${!isAdmin ? 'disabled' : ''}>
        </td>

        ${!isReadOnly ? `
        <td class="px-2 py-1 text-center">
            <button
                type="button"
                onclick="removeRow(this)"
                class="text-rose-600 hover:text-rose-800 text-xs">
                Remove
            </button>
        </td>
        ` : ''}

    </tr>
    `;

    table.insertAdjacentHTML('beforeend', row);

    if (window.lucide) {
        lucide.createIcons();
    }

    updateTotal();
}

function removeRow(btn) {
    btn.closest('tr').remove();
    reindexRows();
    updateTotal();
}

function reindexRows() {
    const rows = document.querySelectorAll('#sellingItemsTable tr');

    rows.forEach((row, index) => {
        row.querySelectorAll('input').forEach(input => {
            input.name = input.name.replace(/items\[\d+\]/, `items[${index}]`);
        });
    });
}

function updateSubtotal(input) {

    const row = input.closest('tr');

    const quantity = parseFloat(
        row.querySelector('[name*="[quantity]"]')?.value || 0
    );

    const price = parseMoney(
        row.querySelector('[name*="[selling_price]"]')?.value
    );

    const subtotal = quantity * price;

    const subtotalField = row.querySelector('.subtotal-field');
    if (subtotalField) {
        subtotalField.value = subtotal ? formatMoney(subtotal) : '';
    }

    updateTotal();
}

function updateTotal() {
    let total = 0;

    document.querySelectorAll('.subtotal-field').forEach(el => {
        total += parseMoney(el.value);
    });

    const totalField = document.getElementById('sellingTotal');
    if (totalField) {
        totalField.value = total ? formatMoney(total) : '';
    }
    const projectedSales = document.getElementById('projectedSales');
    if (projectedSales) {
        projectedSales.value = total ? formatMoney(total) : '';
    }
}

document.addEventListener('DOMContentLoaded', () => {

    document.querySelectorAll('#sellingItemsTable tr').forEach(row => {
        const qtyInput = row.querySelector('[name*="[quantity]"]');
        if (qtyInput) updateSubtotal(qtyInput);
    });

    document.querySelectorAll('[name*="[selling_price]"]').forEach(input => {
        formatMoneyInput(input);
    });

    updateTotal();
});

</script>