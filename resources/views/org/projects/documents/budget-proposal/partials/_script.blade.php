<script>
document.addEventListener('DOMContentLoaded', () => {

    document.querySelectorAll('[data-add-budget]').forEach(btn => {
        btn.addEventListener('click', () => {
            addBudgetRow(btn.getAttribute('data-add-budget'));
        });
    });

    document.addEventListener('click', (e) => {
        const removeBtn = e.target.closest('[data-remove-budget]');
        if (!removeBtn) return;

        const row = removeBtn.closest('[data-budget-row]');
        const section = row?.getAttribute('data-section');
        if (row) row.remove();

        if (section) recalcSection(section);
        recalcGrandTotal();
        recalcFunds();
        checkBudgetMatch();
    });

    document.addEventListener('input', (e) => {

        const row = e.target.closest('[data-budget-row]');
        if (row) {
            const section = row.getAttribute('data-section');
            calculateRowAmount(row);
            if (section) recalcSection(section);
            recalcGrandTotal();
            recalcFunds();
            checkBudgetMatch();
            return;
        }

        const id = e.target?.id || '';
        if (['counterpart_amount_per_pax','counterpart_pax','pta_amount','raised_funds'].includes(id)) {
            recalcFunds();
        }
    });

    initializeExistingRows();
    recalcAllSections();
    recalcGrandTotal();
    recalcFunds();
    checkBudgetMatch(); 
});



function num(val) {
    const s = (val ?? '').toString()
        .replace(/[₱,\s]/g, '')     
        .replace(/[^0-9.\-]/g, ''); 
    const n = parseFloat(s);
    return isNaN(n) ? 0 : n;
}

function setSpanText(id, value) {
    const el = document.getElementById(id);
    if (el) el.textContent = value.toFixed(2);
}

function setHiddenValue(id, value) {
    const el = document.getElementById(id);
    if (el) el.value = value.toFixed(2);
}



function addBudgetRow(section) {
    const container = document.getElementById(section + "_container");
    if (!container) {
        console.error("Budget container not found:", section);
        return;
    }

    const row = document.createElement("div");
    row.setAttribute('data-budget-row', '1');
    row.setAttribute('data-section', section);
    row.className = "grid grid-cols-12 gap-2 items-center";

    row.innerHTML = `
        <input type="hidden" data-section="${section}">

        <div class="col-span-1">
            <input type="number" step="1"
                name="${section}[qty][]"
                class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm text-center">
        </div>

        <div class="col-span-2">
            <input type="text"
                name="${section}[unit][]"
                class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm text-center">
        </div>

        <div class="col-span-4">
            <input type="text"
                name="${section}[particulars][]"
                class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm">
        </div>

        <div class="col-span-2">
            <input type="number" step="0.01"
                name="${section}[price][]"
                class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm text-right">
        </div>

        <div class="col-span-2 text-right font-semibold tabular-nums">
            ₱ <span data-amount>0.00</span>
            <input type="hidden" name="${section}[amount][]" value="0">
        </div>

        <div class="col-span-1 text-center">
            <button type="button" data-remove-budget
                class="text-red-500 text-xs hover:underline">
                Remove
            </button>
        </div>
    `;

    container.appendChild(row);

    calculateRowAmount(row);
    recalcSection(section);
    recalcGrandTotal();
    recalcFunds();
    checkBudgetMatch(); 
}

    function calculateRowAmount(row) {
        const qty   = row.querySelector('input[name*="[qty]"]');
        const price = row.querySelector('input[name*="[price]"]');
        const amtSpan = row.querySelector('[data-amount]');
        const amtInput = row.querySelector('input[name*="[amount]"]');

        const q = num(qty?.value);
        const p = num(price?.value);

        const total = q * p;

        if (amtSpan) {
            amtSpan.textContent = total.toLocaleString(undefined, { minimumFractionDigits: 2 });
        }

        if (amtInput) {
            amtInput.value = total.toFixed(2);
        }
    }



function recalcSection(section) {
    const container = document.getElementById(section + "_container");
    const totalEl = document.getElementById(section + "_total");
    if (!container || !totalEl) return;

    let sum = 0;
    container.querySelectorAll(`input[name^="${section}"][name*="[amount]"]`).forEach(input => {
        sum += num(input.value);
    });

    totalEl.textContent = sum.toFixed(2);
}

function recalcAllSections() {
    document.querySelectorAll('[id$="_container"]').forEach(container => {
        const section = container.id.replace('_container', '');
        recalcSection(section);
    });
}

function recalcGrandTotal() {
    let grand = 0;

    document.querySelectorAll('[data-section-total]').forEach(el => {
        grand += num(el.textContent);
    });

    const grandEl = document.getElementById('grand_total');
    if (grandEl) grandEl.textContent = grand.toFixed(2);
}



function recalcFunds() {
    const amountPerPax = num(document.getElementById('counterpart_amount_per_pax')?.value);
    const pax          = num(document.getElementById('counterpart_pax')?.value);
    const pta          = num(document.getElementById('pta_amount')?.value);
    const raised       = num(document.getElementById('raised_funds')?.value);

    const counterpartTotal = amountPerPax * pax;

    const grandTotal = num(document.getElementById('grand_total')?.textContent);
    
    const otherSources = counterpartTotal + pta + raised;
    const orgTotal = grandTotal - otherSources; 

    setSpanText('counterpart_total_display', counterpartTotal);
    setSpanText('org_total_display', orgTotal);

    setHiddenValue('counterpart_total', counterpartTotal);
    setHiddenValue('org_total', orgTotal);
}



function initializeExistingRows() {
    document.querySelectorAll('[id$="_container"]').forEach(container => {
        const section = container.id.replace('_container', '');

        container.querySelectorAll('.grid.grid-cols-12').forEach(row => {
            if (!row.hasAttribute('data-budget-row')) row.setAttribute('data-budget-row', '1');
            if (!row.hasAttribute('data-section')) row.setAttribute('data-section', section);
            calculateRowAmount(row);
        });
    });
}


function checkBudgetMatch() {
    const grandTotal = num(document.getElementById('grand_total')?.textContent);

    const systemTotal = num(document.getElementById('combined_total_display')?.textContent);

    const indicator = document.getElementById('budget_indicator');
    if (!indicator) return;

    indicator.classList.remove(
        'hidden',
        'text-green-600',
        'text-red-600',
        'bg-green-50',
        'bg-red-50'
    );

    if (Math.abs(grandTotal - systemTotal) < 0.01) {
        indicator.classList.add('text-green-600', 'bg-green-50', 'px-2', 'py-1', 'rounded');
        indicator.innerText = "✔ Budget Balanced";
    } else {
        indicator.classList.add('text-red-600', 'bg-red-50', 'px-2', 'py-1', 'rounded');

        const diff = Math.abs(grandTotal - systemTotal);

        indicator.innerText = `⚠ Difference: ₱ ${diff.toLocaleString(undefined, {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        })}`;
    }

}
</script>