<script>
function removeRow(btn) {
    const tr = btn.closest('tr');
    if (tr) tr.remove();
    reindexAll();
}

function addRow(tbodyId, rowHtml) {
    const tbody = document.getElementById(tbodyId);
    if (!tbody) return;
    tbody.insertAdjacentHTML('beforeend', rowHtml);
    reindexAll();
}

function reindexAll() {
    reindexTbody('leadershipsTable', 'leaderships');
    reindexTbody('trainingsTable', 'trainings');
    reindexTbody('awardsTable', 'awards');
}

function reindexTbody(tbodyId, prefix) {
    const tbody = document.getElementById(tbodyId);
    if (!tbody) return;

    const rows = tbody.querySelectorAll('tr');
    rows.forEach((tr, i) => {
        tr.querySelectorAll('input, textarea, select').forEach(el => {
            const name = el.getAttribute('name');
            if (!name) return;
            el.setAttribute('name', name.replace(new RegExp(`^${prefix}\\[\\d+\\]`), `${prefix}[${i}]`));
        });
    });
}

function leadershipTemplate() {
    return `
        <tr>
            <td class="py-2 pr-3">
                <input class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                       name="leaderships[0][organization_name]">
            </td>
            <td class="py-2 pr-3">
                <input class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                       name="leaderships[0][position]">
            </td>
            <td class="py-2 pr-3">
                <input class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                       name="leaderships[0][organization_address]">
            </td>
            <td class="py-2 pr-3">
                <input class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                       name="leaderships[0][inclusive_years]" placeholder="e.g., 2024-2025">
            </td>
            <td class="py-2 text-right">
                <button type="button"
                        class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50"
                        onclick="removeRow(this)">
                    Remove
                </button>
            </td>
        </tr>
    `;
}

function trainingTemplate() {
    return `
        <tr>
            <td class="py-2 pr-3">
                <input class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                       name="trainings[0][seminar_title]">
            </td>
            <td class="py-2 pr-3">
                <input class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                       name="trainings[0][organizer]">
            </td>
            <td class="py-2 pr-3">
                <input class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                       name="trainings[0][venue]">
            </td>
            <td class="py-2 pr-3">
                <input type="date" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                       name="trainings[0][date_from]">
            </td>
            <td class="py-2 pr-3">
                <input type="date" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                       name="trainings[0][date_to]">
            </td>
            <td class="py-2 text-right">
                <button type="button"
                        class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50"
                        onclick="removeRow(this)">
                    Remove
                </button>
            </td>
        </tr>
    `;
}

function awardTemplate() {
    return `
        <tr>
            <td class="py-2 pr-3">
                <input class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                       name="awards[0][award_name]">
            </td>
            <td class="py-2 pr-3">
                <textarea rows="2" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                          name="awards[0][award_description]"></textarea>
            </td>
            <td class="py-2 pr-3">
                <input class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                       name="awards[0][conferred_by]">
            </td>
            <td class="py-2 pr-3">
                <input type="date" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                       name="awards[0][date_received]">
            </td>
            <td class="py-2 text-right">
                <button type="button"
                        class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50"
                        onclick="removeRow(this)">
                    Remove
                </button>
            </td>
        </tr>
    `;
}
</script>
