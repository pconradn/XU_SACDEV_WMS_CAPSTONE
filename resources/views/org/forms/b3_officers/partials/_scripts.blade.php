
<script>
    (function () {
        const isLocked = @json($isLocked);
        const tbody = document.getElementById('rowsTbody');
        const addBtn = document.getElementById('addRowBtn');

        function reindex() {
            const rows = tbody.querySelectorAll('tr.row-item');
            rows.forEach((row, idx) => {
                row.querySelectorAll('input[name^="items["]').forEach((input) => {
                    input.name = input.name.replace(/items\[\d+\]/, `items[${idx}]`);
                });
            });
        }

        function removeEmptyHintIfExists() {
            const hint = document.getElementById('emptyRowHint');
            if (hint) hint.remove();
        }

        function addRow() {
            removeEmptyHintIfExists();

            const idx = tbody.querySelectorAll('tr.row-item').length;

            const tr = document.createElement('tr');
            tr.className = 'row-item';

            tr.innerHTML = `
                <td class="py-2 px-2">
                    <input type="text" name="items[${idx}][position]"
                        class="w-48 rounded-lg border border-slate-300 px-3 py-2 text-sm" ${isLocked ? 'disabled' : ''}>
                </td>
                <td class="py-2 px-2">
                    <input type="text" name="items[${idx}][officer_name]"
                        class="w-56 rounded-lg border border-slate-300 px-3 py-2 text-sm" ${isLocked ? 'disabled' : ''}>
                </td>
                <td class="py-2 px-2">
                    <input type="text" name="items[${idx}][student_id_number]"
                        class="w-40 rounded-lg border border-slate-300 px-3 py-2 text-sm" ${isLocked ? 'disabled' : ''}>
                </td>
                <td class="py-2 px-2">
                    <input type="text" name="items[${idx}][course_and_year]"
                        class="w-56 rounded-lg border border-slate-300 px-3 py-2 text-sm" ${isLocked ? 'disabled' : ''}>
                </td>
                <td class="py-2 px-2">
                    <input type="number" step="0.01" min="0" max="4" name="items[${idx}][latest_qpi]"
                        class="w-28 rounded-lg border border-slate-300 px-3 py-2 text-sm" ${isLocked ? 'disabled' : ''}>
                </td>
                <td class="py-2 px-2">
                    <input type="text" name="items[${idx}][mobile_number]"
                        class="w-40 rounded-lg border border-slate-300 px-3 py-2 text-sm" ${isLocked ? 'disabled' : ''}>
                </td>
                <td class="py-2 px-2 text-right">
                    <button type="button"
                        class="removeRowBtn inline-flex rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-xs font-semibold text-red-700 hover:bg-red-100 ${isLocked ? 'opacity-50 pointer-events-none' : ''}">
                        Remove
                    </button>
                </td>
            `;

            tbody.appendChild(tr);
        }

        function onRemoveClick(e) {
            const btn = e.target.closest('.removeRowBtn');
            if (!btn) return;

            const row = btn.closest('tr.row-item');
            if (!row) return;

            row.remove();
            reindex();

            const remaining = tbody.querySelectorAll('tr.row-item').length;
            if (remaining === 0) {
                const hint = document.createElement('tr');
                hint.id = 'emptyRowHint';
                hint.innerHTML = `
                    <td colspan="7" class="py-6 px-2 text-sm text-slate-600">
                        No officer rows yet. Click <span class="font-semibold">Add row</span> to start encoding.
                    </td>
                `;
                tbody.appendChild(hint);
            }
        }

        if (addBtn && !isLocked) {
            addBtn.addEventListener('click', addRow);
        }
        tbody.addEventListener('click', onRemoveClick);
    })();
</script>
