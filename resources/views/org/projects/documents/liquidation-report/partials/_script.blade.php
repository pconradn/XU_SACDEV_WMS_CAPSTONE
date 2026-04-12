@push('scripts')
<script>
window.initialItems = @json($report?->items ?? []);

function hydrateReceiptsFromItems(items) {
    const map = {};

    items.forEach(item => {
        const key = [
            item.or_number || '',
            item.source_document_type || '',
            item.source_document_description || '',
            item.date || ''
        ].join('|');

        if (!map[key]) {
            map[key] = {
                ref: item.or_number || '',
                type: item.source_document_type || '',
                desc: item.source_document_description || '',
                date: item.date || '',
                items: []
            };
        }

        map[key].items.push({
            particulars: item.particulars || '',
            amount: item.amount || '',
            section: item.section_label || 'Others'
        });
    });

    return Object.values(map);
}

document.addEventListener('DOMContentLoaded', function () {
    let receipts = [];

    const modal = document.getElementById('receiptModal');
    const receiptList = document.getElementById('receiptList');
    const itemsContainer = document.getElementById('receiptItems');
    const tooltip = document.getElementById('receiptTooltip');

    const addBtn = document.getElementById('addReceiptBtn');
    const closeBtn = document.getElementById('closeReceiptModal');
    const saveBtn = document.getElementById('saveReceipt');
    const addItemBtn = document.getElementById('addReceiptItem');

    const receiptType = document.getElementById('receiptType');
    const receiptRef = document.getElementById('receiptRef');
    const receiptDesc = document.getElementById('receiptDesc');
    const receiptDate = document.getElementById('receiptDate');

    function cleanNumber(value) {
        if (!value) return 0;
        return parseFloat(String(value).replace(/,/g, '').replace(/[^\d.]/g, '')) || 0;
    }

    function formatMoney(value) {
        return Number(value || 0).toLocaleString('en-PH', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    function resetModalFields() {
        receiptType.value = '';
        receiptRef.value = '';
        receiptDesc.value = '';
        receiptDate.value = '';
        itemsContainer.innerHTML = '';
    }

    function openModal() {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function addReceiptItemRow(particulars = '', amount = '', section = '') {
        const row = document.createElement('div');
        row.className = 'flex gap-2';
        row.innerHTML = `
            <input type="text" value="${particulars}" placeholder="Particulars" class="flex-1 border rounded px-2 py-1 text-xs">
            <input type="text" value="${amount}" placeholder="Amount" class="w-24 border rounded px-2 py-1 text-xs text-right">
            <input type="text" value="${section}" placeholder="Section" class="w-32 border rounded px-2 py-1 text-xs">
        `;
        itemsContainer.appendChild(row);
    }

    function calculateExpenses() {
        let total = 0;

        receipts.forEach(receipt => {
            receipt.items.forEach(item => {
                total += cleanNumber(item.amount);
            });
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

    function renderReceipts() {
        if (!receiptList) return;

        receiptList.innerHTML = '';

        receipts.forEach((receipt, index) => {
            const subtotal = receipt.items.reduce((sum, item) => sum + cleanNumber(item.amount), 0);

            const card = document.createElement('div');
            card.className = 'border rounded-xl p-3 bg-slate-50';

            card.innerHTML = `
                <div class="flex justify-between items-start">
                    <div>
                        <div class="text-xs font-semibold">${receipt.type || ''} ${receipt.ref || ''}</div>
                        <div class="text-xs text-slate-500">${receipt.desc || ''}</div>
                        <div class="text-[10px] text-slate-400">${receipt.date || ''}</div>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="text-xs font-semibold">₱ ${formatMoney(subtotal)}</div>
                        <button type="button" class="text-[10px] px-2 py-1 rounded bg-slate-200 hover:bg-slate-300" data-edit="${index}">Edit</button>
                        <button type="button" class="text-[10px] px-2 py-1 rounded bg-rose-500 text-white hover:bg-rose-600" data-delete="${index}">X</button>
                    </div>
                </div>
            `;

            receiptList.appendChild(card);
        });
    }

    function renderExpenseTable() {
        const tbody = document.getElementById('expenseRows');
        if (!tbody) return;

        tbody.innerHTML = '';

        let formIndex = 0;
        const grouped = {};

        receipts.forEach((receipt, receiptIndex) => {
            receipt.items.forEach(item => {
                const section = (item.section || '').trim() || 'Others';

                if (!grouped[section]) grouped[section] = [];

                grouped[section].push({
                    particulars: item.particulars || '',
                    amount: item.amount || '',
                    section: section,
                    type: receipt.type || '',
                    desc: receipt.desc || '',
                    ref: receipt.ref || '',
                    date: receipt.date || '',
                    receiptIndex: receiptIndex
                });
            });
        });

        Object.keys(grouped).forEach(section => {
            const headerRow = document.createElement('tr');
            headerRow.className = 'bg-slate-100';
            headerRow.innerHTML = `<td colspan="7" class="px-3 py-2 font-semibold text-slate-700">${section}</td>`;
            tbody.appendChild(headerRow);

            grouped[section].forEach(row => {
                const tr = document.createElement('tr');

                tr.innerHTML = `
                    <td class="px-2 py-1 text-xs text-slate-600">${row.date || ''}</td>
                    <td class="px-2 py-1 text-xs text-slate-800">${row.particulars || ''}</td>
                    <td class="px-2 py-1 text-xs text-right font-medium">₱ ${formatMoney(row.amount)}</td>
                    <td class="px-2 py-1 text-xs text-center">${row.type || ''}</td>
                    <td class="px-2 py-1 text-xs">${row.desc || ''}</td>
                    <td class="px-2 py-1 text-xs cursor-pointer" data-index="${row.receiptIndex}">${row.ref || ''}</td>
                    <td class="px-2 py-1 hidden-inputs-cell"></td>
                `;

                const hiddenCell = tr.querySelector('.hidden-inputs-cell');

                hiddenCell.innerHTML = `
                    <input type="hidden" name="items[${formIndex}][section_label]" value="${section}">
                    <input type="hidden" name="items[${formIndex}][particulars]" value="${row.particulars}">
                    <input type="hidden" name="items[${formIndex}][amount]" value="${row.amount}">
                    <input type="hidden" name="items[${formIndex}][source_document_type]" value="${row.type}">
                    <input type="hidden" name="items[${formIndex}][source_document_description]" value="${row.desc}">
                    <input type="hidden" name="items[${formIndex}][or_number]" value="${row.ref}">
                    <input type="hidden" name="items[${formIndex}][date]" value="${row.date}">
                `;

                tbody.appendChild(tr);
                formIndex++;
            });
        });

        calculateAll();
    }

    if (addBtn) {
        addBtn.onclick = function () {
            resetModalFields();
            openModal();
        };
    }

    if (closeBtn) {
        closeBtn.onclick = function () {
            closeModal();
        };
    }

    if (addItemBtn) {
        addItemBtn.onclick = function () {
            addReceiptItemRow('', '', '');
        };
    }

    if (saveBtn) {
        saveBtn.onclick = function () {
            const items = [];

            itemsContainer.querySelectorAll('div').forEach(row => {
                const inputs = row.querySelectorAll('input');

                items.push({
                    particulars: inputs[0]?.value || '',
                    amount: inputs[1]?.value || '',
                    section: inputs[2]?.value || 'Others'
                    
                });
            });

            receipts.push({
                type: receiptType.value ? receiptType.value : null,
                ref: receiptRef.value || '',
                desc: receiptDesc.value || '',
                date: receiptDate.value || '',
                items: items
            });

            renderReceipts();
            renderExpenseTable();
            resetModalFields();
            closeModal();
        };
    }

    if (receiptList) {
        receiptList.addEventListener('click', function (e) {
            if (e.target.dataset.delete !== undefined) {
                const i = parseInt(e.target.dataset.delete, 10);
                receipts.splice(i, 1);
                renderReceipts();
                renderExpenseTable();
                return;
            }

            if (e.target.dataset.edit !== undefined) {
                const i = parseInt(e.target.dataset.edit, 10);
                const receipt = receipts[i];
                if (!receipt) return;

                receiptType.value = receipt.type || '';

                    if (receipt.type && receiptType.value !== receipt.type) {
                        const option = [...receiptType.options].find(opt => opt.value === receipt.type);
                        if (option) {
                            option.selected = true;
                        }
                    }
                receiptRef.value = receipt.ref || '';
                receiptDesc.value = receipt.desc || '';
                receiptDate.value = receipt.date || '';
                itemsContainer.innerHTML = '';

                receipt.items.forEach(item => {
                    addReceiptItemRow(
                        item.particulars || '',
                        item.amount || '',
                        item.section || ''
                    );
                });

                receipts.splice(i, 1);
                openModal();
            }
        });
    }

    document.addEventListener('mouseover', function (e) {
        const cell = e.target.closest('[data-index]');
        if (!cell || !tooltip) return;

        const receipt = receipts[cell.dataset.index];
        if (!receipt) return;

        const total = receipt.items.reduce((sum, item) => sum + cleanNumber(item.amount), 0);

        tooltip.innerHTML = `
            <div class="font-semibold">Receipt ${receipt.ref || ''}</div>
            <div class="text-slate-600">Total: ₱ ${formatMoney(total)}</div>
        `;

        tooltip.classList.remove('hidden');
    });

    document.addEventListener('mousemove', function (e) {
        if (!tooltip || tooltip.classList.contains('hidden')) return;

        tooltip.style.left = (e.clientX + 12) + 'px';
        tooltip.style.top = (e.clientY + 12) + 'px';
    });

    document.addEventListener('mouseout', function (e) {
        if (!tooltip) return;

        if (e.target.closest('[data-index]')) {
            tooltip.classList.add('hidden');
        }
    });

    document.addEventListener('input', function (e) {
        if (
            ['finance_amount', 'fund_raising_amount', 'sacdev_amount', 'pta_amount'].includes(e.target.name)
        ) {
            calculateAll();
        }
    });

    if (window.initialItems && window.initialItems.length) {
        receipts = hydrateReceiptsFromItems(window.initialItems);
        renderReceipts();
        renderExpenseTable();
    } else {
        calculateAll();
    }
});
</script>
@endpush