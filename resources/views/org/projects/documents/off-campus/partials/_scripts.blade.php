<script>

document.addEventListener('DOMContentLoaded', () => {
    reindexParticipants();

    if (window.lucide) {
        lucide.createIcons();
    }
});

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

<script>

document.addEventListener('DOMContentLoaded', () => {
    reindexParticipants();
});


function getParticipantIndex() {
    const tbody = document.getElementById('participantsBody');
    if (!tbody) return 0;
    return tbody.querySelectorAll('tr').length;
}


function addParticipant() {

    const tbody = document.getElementById('participantsBody');
    if (!tbody) return;

    const index = getParticipantIndex();


    const trashIcon = `
        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M18 6 6 18"></path>
            <path d="m6 6 12 12"></path>
        </svg>
        `;

    const row = document.createElement('tr');
    row.className = "hover:bg-slate-50 transition";

    row.innerHTML = `
        <td class="px-3 py-2">
            <input type="text"
                name="participants[${index}][student_name]"
                placeholder="Full name"
                class="w-full rounded-lg border border-slate-300 px-2 py-1.5 text-xs focus:ring-2 focus:ring-purple-500">
        </td>

        <td class="px-3 py-2">
            <input type="text"
                name="participants[${index}][course_year]"
                placeholder="e.g. BSIT 3"
                class="w-full rounded-lg border border-slate-300 px-2 py-1.5 text-xs focus:ring-2 focus:ring-purple-500">
        </td>

        <td class="px-3 py-2">
            <input type="text"
                name="participants[${index}][student_mobile]"
                placeholder="09XXXXXXXXX"
                class="w-full rounded-lg border border-slate-300 px-2 py-1.5 text-xs focus:ring-2 focus:ring-purple-500">
        </td>

        <td class="px-3 py-2">
            <input type="text"
                name="participants[${index}][parent_name]"
                placeholder="Parent / Guardian name"
                class="w-full rounded-lg border border-slate-300 px-2 py-1.5 text-xs focus:ring-2 focus:ring-purple-500">
        </td>

        <td class="px-3 py-2">
            <input type="text"
                name="participants[${index}][parent_mobile]"
                placeholder="09XXXXXXXXX"
                class="w-full rounded-lg border border-slate-300 px-2 py-1.5 text-xs focus:ring-2 focus:ring-purple-500">
        </td>

        <td class="px-3 py-2 text-center">
            <button type="button"
                onclick="removeParticipant(this)"
                class="text-rose-600 hover:text-rose-800 transition">
                ${trashIcon}
            </button>
        </td>
    `;

    tbody.appendChild(row);

    reindexParticipants();

    setTimeout(() => {
        if (window.lucide) {
            lucide.createIcons();
        }
    }, 0);
}


function removeParticipant(btn) {
    const row = btn.closest('tr');
    if (!row) return;
    row.remove();
    reindexParticipants();
}


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