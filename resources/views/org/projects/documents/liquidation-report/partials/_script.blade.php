<script>
document.addEventListener('DOMContentLoaded', () => {

let currentSection = '';
let itemIndex = document.querySelectorAll('#expenseRows input[name^="items"]').length;

const table = document.getElementById('expenseRows');
const addSectionBtn = document.getElementById('addSectionBtn');
const addExpenseBtn = document.getElementById('addExpenseBtn');


/*
|--------------------------------------------------------------------------
| ADD SECTION
|--------------------------------------------------------------------------
*/

if (addSectionBtn) {
    addSectionBtn.addEventListener('click', () => {

        const sectionName = prompt('Enter section name (e.g. Food, Materials)');
        if (!sectionName) return;

        currentSection = sectionName.trim();
        if (!currentSection) return;

        const row = document.createElement('tr');
        row.classList.add('section-row', 'bg-slate-100');
        row.dataset.section = currentSection;

        row.innerHTML = `
            <td colspan="7" class="px-3 py-2 flex justify-between items-center">
                <span class="font-semibold text-slate-700">${currentSection}</span>
                <button type="button"
                    class="text-xs text-red-500 hover:text-red-700 remove-section-btn">
                    Remove Section
                </button>
            </td>
        `;

        table.appendChild(row);
    });
}


/*
|--------------------------------------------------------------------------
| ADD EXPENSE
|--------------------------------------------------------------------------
*/

if (addExpenseBtn) {
    addExpenseBtn.addEventListener('click', () => {

        if (!currentSection) {
            alert('Please add a section first.');
            return;
        }

        const row = document.createElement('tr');
        row.dataset.section = currentSection;

        row.innerHTML = `
<td class="px-2 py-1">
<input type="hidden" name="items[${itemIndex}][section_label]" value="${currentSection}">
<input type="date" name="items[${itemIndex}][date]" class="w-full border rounded-lg px-2 py-1 text-xs">
</td>

<td class="px-2 py-1">
<input type="text" name="items[${itemIndex}][particulars]" class="w-full border rounded-lg px-2 py-1 text-xs">
</td>

<td class="px-2 py-1">
<input type="number" step="0.01"
name="items[${itemIndex}][amount]"
class="w-full border rounded-lg px-2 py-1 text-xs text-right amount-input">
</td>

<td class="px-2 py-1">
<select name="items[${itemIndex}][source_document_type]"
class="w-full border rounded-lg px-2 py-1 text-xs text-center">
<option value=""></option>
<option value="OR">OR</option>
<option value="SR">SR</option>
<option value="CI">CI</option>
<option value="SI">SI</option>
<option value="AR">AR</option>
<option value="PV">PV</option>
</select>
</td>

<td class="px-2 py-1">
<input type="text"
name="items[${itemIndex}][source_document_description]"
class="w-full border rounded-lg px-2 py-1 text-xs">
</td>

<td class="px-2 py-1">
<input type="text"
name="items[${itemIndex}][or_number]"
class="w-full border rounded-lg px-2 py-1 text-xs">
</td>

<td class="px-2 py-1 text-center">
<button type="button" class="remove-row-btn text-red-500">✕</button>
</td>
`;

        table.appendChild(row);
        itemIndex++;

        calculateAll();
    });
}


/*
|--------------------------------------------------------------------------
| REMOVE
|--------------------------------------------------------------------------
*/

document.addEventListener('click', (e) => {

    if (e.target.classList.contains('remove-row-btn')) {
        e.target.closest('tr')?.remove();
        calculateAll();
    }

    if (e.target.classList.contains('remove-section-btn')) {

        const section = e.target.closest('.section-row')?.dataset.section;

        if (!confirm('Remove this section and all its entries?')) return;

        document.querySelectorAll('#expenseRows tr').forEach(row => {
            if (row.dataset.section === section) row.remove();
        });

        calculateAll();
    }

});


/*
|--------------------------------------------------------------------------
| CALCULATIONS
|--------------------------------------------------------------------------
*/

function calculateAll() {

    calculateExpenses();
    calculateAdvanced();
    calculateBalance();

}


/* TOTAL EXPENSES */
function calculateExpenses() {

    let total = 0;

    document.querySelectorAll('.amount-input').forEach(input => {
        const val = parseFloat(input.value);
        if (!isNaN(val)) total += val;
    });

    const el = document.querySelector('input[name="total_expenses"]');
    if (el) el.value = total.toFixed(2);
}


/* TOTAL ADVANCED (FROM CASH RECEIVED) */
function calculateAdvanced() {

    const fields = [
        'finance_amount',
        'fund_raising_amount',
        'sacdev_amount',
        'pta_amount'
    ];

    let total = 0;

    fields.forEach(name => {
        const input = document.querySelector(`input[name="${name}"]`);
        if (!input) return;

        const val = parseFloat(input.value);
        if (!isNaN(val)) total += val;
    });

    const el = document.querySelector('input[name="total_advanced"]');
    if (el) el.value = total.toFixed(2);
}


/* BALANCE */
function calculateBalance() {

    const expenses = parseFloat(document.querySelector('input[name="total_expenses"]')?.value) || 0;
    const advanced = parseFloat(document.querySelector('input[name="total_advanced"]')?.value) || 0;

    const balance = advanced - expenses;

    const el = document.querySelector('input[name="balance"]');
    if (el) el.value = balance.toFixed(2);
}


/*
|--------------------------------------------------------------------------
| LIVE EVENTS
|--------------------------------------------------------------------------
*/

document.addEventListener('input', (e) => {

    if (
        e.target.classList.contains('amount-input') ||
        ['finance_amount','fund_raising_amount','sacdev_amount','pta_amount'].includes(e.target.name)
    ) {
        calculateAll();
    }

});


/*
|--------------------------------------------------------------------------
| INIT
|--------------------------------------------------------------------------
*/

calculateAll();

});
</script>