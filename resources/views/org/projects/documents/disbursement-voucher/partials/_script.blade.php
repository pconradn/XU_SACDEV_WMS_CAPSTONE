<script>

function formatPeso(value)
{
    return '₱ ' + value.toLocaleString('en-PH', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}


function recalcDV()
{
    let subtotal = 0;

    document.querySelectorAll('.dv-amount').forEach(input => {

        if (!input.disabled && input.value) {
            subtotal += parseFloat(input.value) || 0;
        }

    });

    const tax = parseFloat(document.getElementById('taxInput')?.value || 0);
    const net = subtotal - tax;

    const subtotalEl = document.getElementById('dvSubtotal');
    const totalEl = document.getElementById('dvTotal');
    const netEl = document.getElementById('dvNetTotal');

    if (subtotalEl) subtotalEl.innerText = formatPeso(subtotal);
    if (totalEl) totalEl.innerText = formatPeso(subtotal);
    if (netEl) netEl.innerText = formatPeso(net);
}


/* ================= CHECKBOX BEHAVIOR ================= */
document.querySelectorAll('.dv-item').forEach(cb => {

    cb.addEventListener('change', function () {

        const id = this.dataset.id;
        const amount = this.dataset.amount;

        const input = document.querySelector(`[data-input-id="${id}"]`);

        if (!input) return;

        if (this.checked) {
            input.disabled = false;
            input.value = amount;
            input.classList.remove('bg-slate-100');
        } else {
            input.disabled = true;
            input.value = '';
            input.classList.add('bg-slate-100');
        }

        recalcDV();
    });

});




/* ================= INITIAL LOAD ================= */
document.addEventListener('DOMContentLoaded', function () {
    recalcDV();
});

</script>