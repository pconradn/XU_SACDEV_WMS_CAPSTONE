<script>

function formatPeso(value)
{
return '₱ ' + value.toLocaleString('en-PH',{
minimumFractionDigits:2,
maximumFractionDigits:2
});
}

function recalcDV()
{
    let subtotal = 0;

    document.querySelectorAll('.dv-item:checked').forEach(cb => {

        subtotal += parseFloat(cb.dataset.amount || 0);

    });

    document.getElementById('dvSubtotal').innerText = formatPeso(subtotal);
    document.getElementById('dvTotal').innerText = formatPeso(subtotal);
}

document.querySelectorAll('.dv-item').forEach(cb => {
cb.addEventListener('change', recalcDV);
});

document.getElementById('taxInput').addEventListener('input', recalcDV);

recalcDV();

</script>