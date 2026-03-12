<script>

function addTicketRow() {

    const table = document.getElementById('ticketItemsTable');

    const index = table.querySelectorAll('tr').length;

    const row = `
<tr>

<td class="border border-slate-300">
<input
type="number"
name="items[${index}][quantity]"
oninput="updateTicketAmount(this)"
class="w-full px-2 py-1 border-0 text-[12px]">
</td>

<td class="border border-slate-300">
<input
type="text"
name="items[${index}][series_control_numbers]"
class="w-full px-2 py-1 border-0 text-[12px]">
</td>

<td class="border border-slate-300">
<input
type="number"
step="0.01"
name="items[${index}][price_per_ticket]"
oninput="updateTicketAmount(this)"
class="w-full px-2 py-1 border-0 text-[12px]">
</td>

<td class="border border-slate-300 bg-slate-50">
<input
type="text"
readonly
name="items[${index}][amount]"
class="w-full px-2 py-1 border-0 text-[12px] ticket-amount-field">
</td>

<td class="border border-slate-300">
<input
type="text"
name="items[${index}][remarks]"
class="w-full px-2 py-1 border-0 text-[12px]">
</td>

<td class="border border-slate-300 text-center">
<button
type="button"
onclick="removeTicketRow(this)"
class="text-rose-600 hover:text-rose-800">
Remove
</button>
</td>

</tr>
`;

    table.insertAdjacentHTML('beforeend', row);

}

function removeTicketRow(button) {

    const row = button.closest('tr');
    row.remove();

    updateTicketTotal();

}

function updateTicketAmount(input) {

    const row = input.closest('tr');

    const quantity = parseFloat(
        row.querySelector('input[name*="[quantity]"]').value
    ) || 0;

    const price = parseFloat(
        row.querySelector('input[name*="[price_per_ticket]"]').value
    ) || 0;

    const amount = quantity * price;

    const amountField = row.querySelector('.ticket-amount-field');

    if (amountField) {
        amountField.value = amount.toFixed(2);
    }

    updateTicketTotal();

}

function updateTicketTotal() {

    const amounts = document.querySelectorAll('.ticket-amount-field');

    let total = 0;

    amounts.forEach(field => {

        const value = parseFloat(field.value) || 0;
        total += value;

    });

    const totalDisplay = document.getElementById('totalTicketSalesDisplay');

    if (totalDisplay) {
        totalDisplay.innerText = total.toFixed(2);
    }

}

document.addEventListener('DOMContentLoaded', function () {

    updateTicketTotal();

});

</script>

<script>

function openAgreementModal() {
    const modal = document.getElementById('agreementModal');

    if (!modal) return;

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeAgreementModal() {
    const modal = document.getElementById('agreementModal');

    if (!modal) return;

    modal.classList.add('hidden');
    modal.classList.remove('flex');
}


function openReturnModal() {
    const modal = document.getElementById('returnModal');

    if (!modal) return;

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeReturnModal() {
    const modal = document.getElementById('returnModal');

    if (!modal) return;

    modal.classList.add('hidden');
    modal.classList.remove('flex');
}


function openResubmitModal() {
    const modal = document.getElementById('resubmitModal');

    if (!modal) return;

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeResubmitModal() {
    const modal = document.getElementById('resubmitModal');

    if (!modal) return;

    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function openApproveModal() {
    const modal = document.getElementById('approveModal');

    if (!modal) return;

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeApproveModal() {
    const modal = document.getElementById('approveModal');

    if (!modal) return;

    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

</script>