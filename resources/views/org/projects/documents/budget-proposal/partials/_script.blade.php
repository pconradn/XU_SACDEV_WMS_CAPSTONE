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
    });

    document.addEventListener('input', (e) => {

        const row = e.target.closest('[data-budget-row]');
        if (row) {
            const section = row.getAttribute('data-section');
            calculateRowAmount(row);
            if (section) recalcSection(section);
            recalcGrandTotal();
            recalcFunds();
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
        <div class="col-span-1">
            <input type="number" min="0" step="1" name="${section}[qty][]"
                   class="w-full border border-slate-300 px-2 py-1 text-[12px] text-center">
        </div>
        <div class="col-span-2">
            <input type="text" name="${section}[unit][]"
                   class="w-full border border-slate-300 px-2 py-1 text-[12px] text-center">
        </div>
        <div class="col-span-4">
            <input type="text" name="${section}[particulars][]"
                   class="w-full border border-slate-300 px-2 py-1 text-[12px]">
        </div>
        <div class="col-span-2">
            <input type="number" min="0" step="0.01" name="${section}[price][]"
                   class="w-full border border-slate-300 px-2 py-1 text-[12px] text-right">
        </div>
        <div class="col-span-2">
            <input type="number" min="0" step="0.01" name="${section}[amount][]"
                   class="w-full border border-slate-300 px-2 py-1 text-[12px] text-right bg-slate-50" readonly>
        </div>
        <div class="col-span-1 text-center">
            <button type="button" data-remove-budget="1"
                    class="text-red-600 text-[12px] font-semibold hover:underline">
                Remove
            </button>
        </div>
    `;

    container.appendChild(row);

    calculateRowAmount(row);
    recalcSection(section);
    recalcGrandTotal();
    recalcFunds();
}

function calculateRowAmount(row) {
    const qty   = row.querySelector('input[name*="[qty]"]');
    const price = row.querySelector('input[name*="[price]"]');
    const amt   = row.querySelector('input[name*="[amount]"]');

    const q = num(qty?.value);
    const p = num(price?.value);

    if (amt) amt.value = (q * p).toFixed(2);
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
    document.querySelectorAll('[id$="_total"]').forEach(el => {
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
</script>