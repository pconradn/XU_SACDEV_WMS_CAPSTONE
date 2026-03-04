<script>


document.addEventListener('DOMContentLoaded', () => {

    document.querySelectorAll('[data-add-budget]').forEach(btn => {
        btn.addEventListener('click', () => {
            const section = btn.getAttribute('data-add-budget');
            addBudgetRow(section);
        });
    });

    document.addEventListener('click', (e) => {
        const removeBtn = e.target.closest('[data-remove-budget]');
        if (removeBtn) {
            const row = removeBtn.closest('[data-budget-row]');
            const section = row?.getAttribute('data-section');
            if (row) row.remove();
            if (section) recalcSection(section);
            recalcGrandTotal();
        }
    });

    document.addEventListener('input', (e) => {
        const row = e.target.closest('[data-budget-row]');
        if (!row) return;

        const section = row.getAttribute('data-section');
        calculateRowAmount(row);
        if (section) recalcSection(section);
        recalcGrandTotal();
    });

});


function addBudgetRow(section) {
    const container = document.getElementById(section + "_container");
    if (!container) {
        console.error("Budget container not found for section:", section);
        return;
    }

    const row = document.createElement("div");
    row.setAttribute('data-budget-row', '1');
    row.setAttribute('data-section', section);
    row.className = "grid grid-cols-12 gap-2 items-center";

    row.innerHTML = `
        <div class="col-span-1">
            <input type="number"
                   min="0"
                   step="1"
                   name="${section}[qty][]"
                   class="w-full border border-slate-300 px-2 py-1 text-[12px] text-center">
        </div>

        <div class="col-span-2">
            <input type="text"
                   name="${section}[unit][]"
                   class="w-full border border-slate-300 px-2 py-1 text-[12px] text-center">
        </div>

        <div class="col-span-4">
            <input type="text"
                   name="${section}[particulars][]"
                   class="w-full border border-slate-300 px-2 py-1 text-[12px]">
        </div>

        <div class="col-span-2">
            <input type="number"
                   min="0"
                   step="0.01"
                   name="${section}[price][]"
                   class="w-full border border-slate-300 px-2 py-1 text-[12px] text-right">
        </div>

        <div class="col-span-2">
            <input type="number"
                   min="0"
                   step="0.01"
                   name="${section}[amount][]"
                   class="w-full border border-slate-300 px-2 py-1 text-[12px] text-right bg-slate-50"
                   readonly>
        </div>

        <div class="col-span-1 text-center">
            <button type="button"
                    data-remove-budget="1"
                    class="text-red-600 text-[12px] font-semibold hover:underline">
                Remove
            </button>
        </div>
    `;

    container.appendChild(row);

    // Initialize totals after adding
    calculateRowAmount(row);
    recalcSection(section);
    recalcGrandTotal();
}


function calculateRowAmount(row) {
    const qty   = row.querySelector('input[name*="[qty]"]');
    const price = row.querySelector('input[name*="[price]"]');
    const amt   = row.querySelector('input[name*="[amount]"]');

    const q = parseFloat(qty?.value || "0") || 0;
    const p = parseFloat(price?.value || "0") || 0;

    const total = q * p;
    if (amt) amt.value = total.toFixed(2);
}


function recalcSection(section) {
    const container = document.getElementById(section + "_container");
    const totalEl = document.getElementById(section + "_total");

    if (!container || !totalEl) return;

    let sum = 0;

    container.querySelectorAll('input[name^="' + section + '"][name*="[amount]"]').forEach(input => {
        sum += parseFloat(input.value || "0") || 0;
    });

    totalEl.textContent = sum.toFixed(2);
}


function recalcGrandTotal() {
    let grand = 0;

    document.querySelectorAll('[id$="_total"]').forEach(el => {
        grand += parseFloat(el.textContent || "0") || 0;
    });

    const grandEl = document.getElementById('grand_total');
    if (grandEl) grandEl.textContent = grand.toFixed(2);
}
</script>