<script>

document.addEventListener('DOMContentLoaded', () => {
    attachAmountListeners();
    updateCollectionTotal();
});


function formatCurrency(value) {
    return new Intl.NumberFormat('en-PH', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }).format(value);
}

function parseNumber(value) {
    if (!value) return 0;
    return parseFloat(value.toString().replace(/,/g, '')) || 0;
}


/* ================= ADD ROW ================= */
function addCollectionRow() {

    const table = document.getElementById('collectionTable');
    if (!table) return;

    const index = table.querySelectorAll('tr').length;

    const row = document.createElement('tr');

    row.classList.add('hover:bg-slate-50');

    row.innerHTML = `
        <td class="px-3 py-2 border-r">
            <input type="number"
                name="items[${index}][number_of_payers]"
                class="w-full rounded-md border border-slate-300 px-2 py-1.5 text-sm">
        </td>

        <td class="px-3 py-2 border-r">
            <input type="text"
                name="items[${index}][amount_paid]"
                class="w-full text-right rounded-md border border-slate-300 px-2 py-1.5 text-sm amount-input">
        </td>

        <td class="px-3 py-2 border-r">
            <input type="text"
                name="items[${index}][receipt_series]"
                class="w-full rounded-md border border-slate-300 px-2 py-1.5 text-sm">
        </td>

        <td class="px-3 py-2 border-r">
            <input type="text"
                name="items[${index}][remarks]"
                class="w-full rounded-md border border-amber-200 bg-amber-50 px-2 py-1.5 text-sm">
        </td>

        <td class="px-3 py-2 text-center">
            <button type="button"
                onclick="removeCollectionRow(this)"
                class="text-rose-600 text-xs font-semibold">
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


/* ================= TOTAL ================= */
function updateCollectionTotal() {

    const table = document.getElementById('collectionTable');
    if (!table) return;

    let total = 0;

    const rows = table.querySelectorAll('tr');

    rows.forEach(row => {

        const payersInput = row.querySelector('input[name*="[number_of_payers]"]');
        const amountInput = row.querySelector('input[name*="[amount_paid]"]');

        const payers = parseFloat(payersInput?.value) || 0;
        const amount = parseNumber(amountInput?.value);

        total += payers * amount;

    });

    const display = document.getElementById('totalCollectionDisplay');

    if (display) {
        display.textContent = '₱ ' + formatCurrency(total);
    }
}


/* ================= LISTENERS ================= */
function attachAmountListeners() {

    const inputs = document.querySelectorAll(
        'input[name*="[amount_paid]"], input[name*="[number_of_payers]"]'
    );

    inputs.forEach(input => {

        input.removeEventListener('input', handleInput);
        input.removeEventListener('blur', handleBlur);

        input.addEventListener('input', handleInput);
        input.addEventListener('blur', handleBlur);

    });
}


function handleInput(e) {

    const input = e.target;

    if (input.name.includes('amount_paid')) {
        const raw = input.value.replace(/[^0-9.]/g, '');
        input.value = raw;
    }

    updateCollectionTotal();
}

function handleBlur(e) {

    const input = e.target;

    const value = parseNumber(input.value);

    input.value = value ? formatCurrency(value) : '';

    updateCollectionTotal();
}

</script>