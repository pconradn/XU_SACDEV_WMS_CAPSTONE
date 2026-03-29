<script>

document.addEventListener('DOMContentLoaded', () => {
    reindexParticipants();
});


/* ================= GET INDEX ================= */
function getParticipantIndex() {
    const tbody = document.getElementById('participantsBody');
    if (!tbody) return 0;

    return tbody.querySelectorAll('tr').length;
}


/* ================= ADD ================= */
function addParticipant() {

    const tbody = document.getElementById('participantsBody');
    if (!tbody) return;

    const index = getParticipantIndex();

    const row = document.createElement('tr');

    row.innerHTML = `
        <td class="px-4 py-2">
            <input type="text"
                name="participants[${index}][student_name]"
                placeholder="Full name"
                class="w-full rounded-md border border-slate-300 px-2 py-1.5 text-sm focus:ring-2 focus:ring-blue-500">
        </td>

        <td class="px-4 py-2">
            <input type="text"
                name="participants[${index}][course_year]"
                placeholder="e.g. BSIT 3"
                class="w-full rounded-md border border-slate-300 px-2 py-1.5 text-sm focus:ring-2 focus:ring-blue-500">
        </td>

        <td class="px-4 py-2">
            <input type="text"
                name="participants[${index}][student_mobile]"
                placeholder="09XXXXXXXXX"
                class="w-full rounded-md border border-slate-300 px-2 py-1.5 text-sm focus:ring-2 focus:ring-blue-500">
        </td>

        <td class="px-4 py-2">
            <input type="text"
                name="participants[${index}][parent_name]"
                placeholder="Parent / Guardian"
                class="w-full rounded-md border border-slate-300 px-2 py-1.5 text-sm focus:ring-2 focus:ring-blue-500">
        </td>

        <td class="px-4 py-2">
            <input type="text"
                name="participants[${index}][parent_mobile]"
                placeholder="09XXXXXXXXX"
                class="w-full rounded-md border border-slate-300 px-2 py-1.5 text-sm focus:ring-2 focus:ring-blue-500">
        </td>

        <td class="px-4 py-2 text-center">
            <button type="button"
                onclick="removeParticipant(this)"
                class="text-rose-600 hover:text-rose-800 text-xs font-semibold">
                Remove
            </button>
        </td>
    `;

    tbody.appendChild(row);

    reindexParticipants(); 
}


/* ================= REMOVE ================= */
function removeParticipant(btn) {

    const row = btn.closest('tr');
    if (!row) return;

    row.remove();

    reindexParticipants(); 
}


/* ================= REINDEX (CRITICAL FIX) ================= */
function reindexParticipants() {

    const rows = document.querySelectorAll('#participantsBody tr');

    rows.forEach((row, index) => {

        const inputs = row.querySelectorAll('input');

        inputs.forEach(input => {

            const name = input.getAttribute('name');
            if (!name) return;

            const updated = name.replace(/participants\[\d+\]/, `participants[${index}]`);
            input.setAttribute('name', updated);

        });

    });

}

</script>