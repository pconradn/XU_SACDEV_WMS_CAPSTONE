<script>
document.addEventListener('DOMContentLoaded', () => {

let currentSection = '';
let itemIndex = document.querySelectorAll('#expenseRows input[name^="items"]').length;

const table = document.getElementById('expenseRows');
const addSectionBtn = document.getElementById('addSectionBtn');
const addExpenseBtn = document.getElementById('addExpenseBtn');


function cleanNumber(value) {
    if (!value) return 0;
    return parseFloat(
        String(value)
            .replace(/,/g, '')
            .replace(/[^\d.]/g, '')
    ) || 0;
}

function formatMoney(value) {
    return Number(value || 0).toLocaleString('en-PH', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

function formatMoneyInput(input) {
    if (!input) return;

    const raw = String(input.value || '')
        .replace(/,/g, '')
        .replace(/[^\d.]/g, '');

    if (raw === '') {
        input.value = '';
        return;
    }

    const parsed = parseFloat(raw);
    if (isNaN(parsed)) return;

    input.value = formatMoney(parsed);
}


if (addSectionBtn) {
    addSectionBtn.addEventListener('click', () => {

        const sectionName = prompt('Enter section name');
        if (!sectionName) return;

        currentSection = sectionName.trim();
        if (!currentSection) return;

        const row = document.createElement('tr');
        row.classList.add('section-row', 'bg-slate-100');
        row.dataset.section = currentSection;

        row.innerHTML = `
            <td colspan="7" class="px-3 py-2 flex justify-between items-center">
                <span class="font-semibold text-slate-700">${currentSection}</span>
                <button type="button" class="text-xs text-red-500 remove-section-btn">
                    Remove Section
                </button>
            </td>
        `;

        table.appendChild(row);
    });
}


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
<input type="text"
name="items[${itemIndex}][amount]"
data-money
class="w-full border rounded-lg px-2 py-1 text-xs text-right">
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


function calculateExpenses() {

    let total = 0;

    document.querySelectorAll('#expensesTable input[name*="[amount]"]').forEach(input => {
        total += cleanNumber(input.value);
    });

    const el = document.getElementById('totalExpenses');
    if (el) el.value = formatMoney(total);
}


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

        total += cleanNumber(input.value);
    });

    const el = document.getElementById('totalAdvanced');
    if (el) el.value = formatMoney(total);
}


function calculateBalance() {

    const expenses = cleanNumber(document.getElementById('totalExpenses')?.value);
    const advanced = cleanNumber(document.getElementById('totalAdvanced')?.value);

    const balance = advanced - expenses;

    const el = document.getElementById('balance');
    if (el) el.value = formatMoney(balance);
}


function calculateAll() {
    calculateExpenses();
    calculateAdvanced();
    calculateBalance();
}


document.addEventListener('input', (e) => {

    if (
        e.target.matches('#expensesTable input[name*="[amount]"]') ||
        ['finance_amount','fund_raising_amount','sacdev_amount','pta_amount'].includes(e.target.name)
    ) {
        calculateAll();
    }

    if (e.target.matches('[data-money]:not([readonly])')) {
        const raw = String(e.target.value || '')
            .replace(/,/g, '')
            .replace(/[^\d.]/g, '');

        e.target.value = raw;
    }

});


document.addEventListener('blur', (e) => {

    if (e.target.matches('[data-money]')) {
        formatMoneyInput(e.target);
        setTimeout(calculateAll, 50);
    }

}, true);


document.addEventListener('submit', function (e) {

    const form = e.target;
    if (!(form instanceof HTMLFormElement)) return;

    form.querySelectorAll('[data-money]').forEach((input) => {
        input.value = String(input.value || '').replace(/,/g, '');
    });

});


window.addEventListener('load', () => {
    setTimeout(calculateAll, 200);
});









});
</script>

<script>
(function () {

    function cleanNumber(value) {
        if (!value) return 0;
        return parseFloat(
            String(value).replace(/,/g, '').replace(/[^\d.]/g, '')
        ) || 0;
    }

    function validateReturns() {

        const a = cleanNumber(document.getElementById('clusterAReturn')?.value);
        const b = cleanNumber(document.getElementById('clusterBReturn')?.value);
        const balance = cleanNumber(document.getElementById('balance')?.value);

        const warning = document.getElementById('returnWarning');

        const totalReturn = a + b;

        if (Math.abs(totalReturn - balance) > 0.01) {
            warning.classList.remove('hidden');
            return false;
        } else {
            warning.classList.add('hidden');
            return true;
        }
    }

    document.addEventListener('input', function (e) {
        if (
            e.target.matches('#clusterAReturn') ||
            e.target.matches('#clusterBReturn')
        ) {
            validateReturns();
        }
    });

    document.addEventListener('blur', function (e) {
        if (
            e.target.matches('#clusterAReturn') ||
            e.target.matches('#clusterBReturn')
        ) {
            setTimeout(validateReturns, 50);
        }
    }, true);

    document.addEventListener('submit', function (e) {

        const form = e.target;
        if (!(form instanceof HTMLFormElement)) return;

        if (!validateReturns()) {
            e.preventDefault();
            alert('Cluster A + Cluster B must equal the Balance.');
        }

    });

})();
</script>