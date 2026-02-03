<script>
(function () {
    const locked = {{ $isLocked ? 'true' : 'false' }};
    if (locked) return;

    const tbody = document.getElementById('leadershipTbody');
    const addBtn = document.getElementById('addLeadershipBtn');

    if (!tbody || !addBtn) return;

    function reindex() {
        const rows = tbody.querySelectorAll('tr.leadership-row');
        rows.forEach((row, idx) => {
            row.querySelectorAll('input[name^="leaderships["]').forEach((input) => {
                input.name = input.name.replace(/leaderships\[\d+\]/, `leaderships[${idx}]`);
            });
        });
    }

    function removeEmptyHint() {
        const hint = document.getElementById('leadershipEmptyHint');
        if (hint) hint.remove();
    }

    function addRow() {
        removeEmptyHint();

        const idx = tbody.querySelectorAll('tr.leadership-row').length;

        const tr = document.createElement('tr');
        tr.className = 'leadership-row';

        tr.innerHTML = `
            <td class="py-2 px-2">
                <input type="text" name="leaderships[${idx}][organization_name]"
                       class="w-56 rounded-lg border border-slate-300 px-3 py-2 text-sm">
            </td>
            <td class="py-2 px-2">
                <input type="text" name="leaderships[${idx}][position]"
                       class="w-44 rounded-lg border border-slate-300 px-3 py-2 text-sm">
            </td>
            <td class="py-2 px-2">
                <input type="text" name="leaderships[${idx}][organization_address]"
                       class="w-56 rounded-lg border border-slate-300 px-3 py-2 text-sm">
            </td>
            <td class="py-2 px-2">
                <input type="text" name="leaderships[${idx}][inclusive_years]"
                       class="w-36 rounded-lg border border-slate-300 px-3 py-2 text-sm" placeholder="e.g., 2023-2025">
            </td>
            <td class="py-2 px-2 text-right">
                <button type="button"
                        class="removeLeadershipBtn inline-flex rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-xs font-semibold text-red-700 hover:bg-red-100">
                    Remove
                </button>
            </td>
        `;

        tbody.appendChild(tr);
    }

    function onRemove(e) {
        const btn = e.target.closest('.removeLeadershipBtn');
        if (!btn) return;

        const row = btn.closest('tr.leadership-row');
        if (!row) return;

        row.remove();
        reindex();

        const remaining = tbody.querySelectorAll('tr.leadership-row').length;
        if (remaining === 0) {
            const hint = document.createElement('tr');
            hint.id = 'leadershipEmptyHint';
            hint.innerHTML = `
                <td colspan="5" class="py-6 px-2 text-sm text-slate-600">
                    No entries yet. Click <span class="font-semibold">Add row</span> if applicable.
                </td>
            `;
            tbody.appendChild(hint);
        }
    }

    addBtn.addEventListener('click', addRow);
    tbody.addEventListener('click', onRemove);
})();
</script>

<script>
(function () {
    const openBtn = document.getElementById('openEditRequestModalBtn');
    const modal = document.getElementById('editRequestModal');
    const closeBtns = document.querySelectorAll('[data-close-edit-request-modal]');

    if (!openBtn || !modal) return;

    openBtn.addEventListener('click', () => modal.classList.remove('hidden'));
    closeBtns.forEach(b => b.addEventListener('click', () => modal.classList.add('hidden')));
})();
</script>