<script>
    function removeRow(btn) {
        const row = btn.closest('div');
        if (row) row.remove();
    }

    function addRow(containerId) {
        const wrap = document.getElementById(containerId);
        const index = wrap.querySelectorAll('input, textarea').length; // ok for simple arrays

        const row = document.createElement('div');
        row.className = 'flex gap-2';

        const input = document.createElement('input');
        input.type = 'text';
        input.name = containerId + '[]';
        input.className = 'w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm';

        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-600 hover:bg-slate-50';
        btn.textContent = 'Remove';
        btn.onclick = () => row.remove();

        row.appendChild(input);
        row.appendChild(btn);
        wrap.appendChild(row);
    }

    function addPartnerRow() {
        const wrap = document.getElementById('partners');
        const i = wrap.querySelectorAll('[data-partner-row]').length;

        const row = document.createElement('div');
        row.dataset.partnerRow = '1';
        row.className = 'grid grid-cols-1 gap-2 md:grid-cols-3';

        row.innerHTML = `
            <input type="text" name="partners[${i}][name]" class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm" placeholder="Name">
            <input type="text" name="partners[${i}][type]" class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm" placeholder="Type (optional)">
            <button type="button" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-600 hover:bg-slate-50">Remove</button>
        `;
        row.querySelector('button').onclick = () => row.remove();
        wrap.appendChild(row);
    }

    function addRoleRow() {
        const wrap = document.getElementById('roles');
        const i = wrap.querySelectorAll('[data-role-row]').length;

        const row = document.createElement('div');
        row.dataset.roleRow = '1';
        row.className = 'grid grid-cols-1 gap-2 md:grid-cols-3';

        row.innerHTML = `
            <input type="text" name="roles[${i}][role_name]" class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm" placeholder="Role name">
            <input type="text" name="roles[${i}][description]" class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm" placeholder="Description (optional)">
            <button type="button" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-600 hover:bg-slate-50">Remove</button>
        `;
        row.querySelector('button').onclick = () => row.remove();
        wrap.appendChild(row);
    }

    function addGuestRow() {
        const wrap = document.getElementById('guests');
        const i = wrap.querySelectorAll('[data-guest-row]').length;

        const row = document.createElement('div');
        row.dataset.guestRow = '1';
        row.className = 'grid grid-cols-1 gap-2 lg:grid-cols-4';

        row.innerHTML = `
            <input type="text" name="guests[${i}][full_name]" class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm" placeholder="Full Name">
            <input type="text" name="guests[${i}][affiliation]" class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm" placeholder="Affiliation">
            <input type="text" name="guests[${i}][designation]" class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm" placeholder="Designation">
            <button type="button" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-600 hover:bg-slate-50">Remove</button>
        `;
        row.querySelector('button').onclick = () => row.remove();
        wrap.appendChild(row);
    }

    function addPlanRow() {
        const wrap = document.getElementById('planRows');
        const i = wrap.querySelectorAll('[data-plan-row]').length;

        const row = document.createElement('div');
        row.dataset.planRow = '1';
        row.className = 'grid grid-cols-1 gap-2 lg:grid-cols-5';

        row.innerHTML = `
            <input type="date" name="plan[${i}][date]" class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm">
            <input type="time" name="plan[${i}][time]" class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm">
            <input type="text" name="plan[${i}][activity]" class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm lg:col-span-2" placeholder="Activity / Particulars">
            <input type="text" name="plan[${i}][venue]" class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm" placeholder="Venue">
            <button type="button" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-600 hover:bg-slate-50">Remove</button>
        `;
        row.querySelector('button').onclick = () => row.remove();
        wrap.appendChild(row);
    }

    function toggleMainOrganizer() {
        const eng = document.querySelector('input[name="engagement_type"]:checked')?.value;
        const wrap = document.getElementById('mainOrganizerWrap');
        if (!wrap) return;
        wrap.style.display = (eng === 'participant') ? '' : 'none';
    }

    function toggleNatureOther() {
        const val = document.getElementById('projectNature')?.value;
        const wrap = document.getElementById('natureOtherWrap');
        if (!wrap) return;
        wrap.classList.toggle('hidden', val !== 'other');
    }

    function toggleCounterpart() {
        const val = document.getElementById('sourceFunds')?.value;
        const wrap = document.getElementById('counterpartWrap');
        if (!wrap) return;
        wrap.classList.toggle('hidden', val !== 'Counterpart');
    }

    function toggleGuests() {
        const val = document.querySelector('input[name="has_guest_speakers"]:checked')?.value;
        const wrap = document.getElementById('guestListWrap');
        if (!wrap) return;
        wrap.classList.toggle('hidden', val !== '1');
    }

    document.addEventListener('change', (e) => {
        if (e.target.name === 'engagement_type') toggleMainOrganizer();
        if (e.target.id === 'projectNature') toggleNatureOther();
        if (e.target.id === 'sourceFunds') toggleCounterpart();
        if (e.target.name === 'has_guest_speakers') toggleGuests();
    });

    // initial
    toggleMainOrganizer();
    toggleNatureOther();
    toggleCounterpart();
    toggleGuests();
</script>
<script>
document.addEventListener('DOMContentLoaded', () => {

    function addTextRow(wrapperId, inputName, placeholder) {
        const wrap = document.getElementById(wrapperId);
        if (!wrap) return;

        const input = document.createElement('input');
        input.type = 'text';
        input.name = inputName;
        input.placeholder = placeholder;
        input.className = 'w-full border border-slate-300 bg-white px-3 py-1 text-[12px]';

        wrap.appendChild(input);
    }

    document.getElementById('addObjectiveBtn')?.addEventListener('click', () => {
        addTextRow('objectivesWrap', 'objectives[]', 'Enter objective');
    });

    document.getElementById('addIndicatorBtn')?.addEventListener('click', () => {
        addTextRow('indicatorsWrap', 'success_indicators[]', 'Enter success indicator');
    });

    document.getElementById('addPartnerBtn')?.addEventListener('click', () => {
        addTextRow('partnersWrap', 'partners[]', 'Partner name');
    });

    document.getElementById('addRoleBtn')?.addEventListener('click', () => {
        addTextRow('rolesWrap', 'roles[]', 'Role title');
    });

});
</script>