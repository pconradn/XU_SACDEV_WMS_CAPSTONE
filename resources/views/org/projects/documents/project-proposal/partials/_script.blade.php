<script>
    function removeRow(btn) {
        const row = btn.closest('div');
        if (row) row.remove();
    }

    function addRow(containerId) {
        const wrap = document.getElementById(containerId);
        const index = wrap.querySelectorAll('input, textarea').length; 

        const row = document.createElement('div');
        row.className = 'flex gap-2 items-center dynamic-row';

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
            <input type="text" name="partners[${i}][name]" class="rounded-lg border border-slate-300 px-3 py-2 text-sm 
       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition" placeholder="Name">
            <input type="text" name="partners[${i}][type]" class="rounded-lg border border-slate-300 px-3 py-2 text-sm 
       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition" placeholder="Type (optional)">
            <button type="button" class="remove-btn text-slate-400 hover:text-red-600 text-sm px-2">Remove</button>
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
            <input type="text" name="roles[${i}][role_name]" class="rounded-lg border border-slate-300 px-3 py-2 text-sm 
       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition" placeholder="Role name">
            <input type="text" name="roles[${i}][description]" class="rounded-lg border border-slate-300 px-3 py-2 text-sm 
       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition" placeholder="Description (optional)">
            <button type="button" class="remove-btn text-slate-400 hover:text-red-600 text-sm px-2">Remove</button>
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
            <input type="text" name="guests[${i}][full_name]" class="rounded-lg border border-slate-300 px-3 py-2 text-sm 
       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition" placeholder="Full Name">
            <input type="text" name="guests[${i}][affiliation]" class="rounded-lg border border-slate-300 px-3 py-2 text-sm 
       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition" placeholder="Affiliation">
            <input type="text" name="guests[${i}][designation]" class="rounded-lg border border-slate-300 px-3 py-2 text-sm 
       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition" placeholder="Designation">
            <button type="button" class="remove-btn text-slate-400 hover:text-red-600 text-sm px-2">Remove</button>
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
            <input type="date" name="plan[${i}][date]" class="rounded-lg border border-slate-300 px-3 py-2 text-sm 
       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition">
            <input type="time" name="plan[${i}][time]" class="rounded-lg border border-slate-300 px-3 py-2 text-sm 
       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition">
            <input type="text" name="plan[${i}][activity]" class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm lg:col-span-2" placeholder="Activity / Particulars">
            <input type="text" name="plan[${i}][venue]" class="rounded-lg border border-slate-300 px-3 py-2 text-sm 
       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition" placeholder="Venue">
            <button type="button" class="remove-btn text-slate-400 hover:text-red-600 text-sm px-2">Remove</button>
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
    document.addEventListener('DOMContentLoaded', function () {



        function createSimpleRow(name, placeholder) {
            const row = document.createElement('div');
            row.className = 'flex gap-2 dynamic-row';

            row.innerHTML = `
                <input type="text"
                    name="${name}"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm 
       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition"
                    placeholder="${placeholder}">
                <button type="button"
                        class="text-red-600 text-[12px] px-2 remove-btn">
                    ✕
                </button>
            `;

            return row;
        }

        function setupAddButton(buttonId, wrapperId, name, placeholder) {
            const btn = document.getElementById(buttonId);
            const wrapper = document.getElementById(wrapperId);

            if (!btn || !wrapper) return;

            btn.addEventListener('click', function () {
                wrapper.appendChild(createSimpleRow(name, placeholder));
            });
        }

       
        document.addEventListener('click', function (e) {
            if (!e.target.classList.contains('remove-btn')) return;

            const row = e.target.closest('.dynamic-row');
            if (!row) return;

            const wrapper = row.parentElement;

            if (wrapper.querySelectorAll('.dynamic-row').length > 1) {
                row.remove();
            }
        });



        setupAddButton('addObjectiveBtn', 'objectivesWrap', 'objectives[]', 'Enter objective');
        setupAddButton('addIndicatorBtn', 'indicatorsWrap', 'success_indicators[]', 'Enter success indicator');
        setupAddButton('addPartnerBtn', 'partnersWrap', 'partners[]', 'Partner name');
        setupAddButton('addRoleBtn', 'rolesWrap', 'roles[]', 'Role title');



        function toggleMainOrganizer() {
            const eng = document.querySelector('input[name="engagement_type"]:checked')?.value;
            const wrap = document.getElementById('mainOrganizerWrap');
            if (!wrap) return;
            wrap.style.display = (eng === 'participant') ? '' : 'none';
        }

        function toggleGuests() {
            const val = document.querySelector('input[name="has_guest_speakers"]:checked')?.value;
            const wrap = document.getElementById('guestListWrap');
            if (!wrap) return;
            wrap.classList.toggle('hidden', val !== '1');
        }

        document.addEventListener('change', function (e) {
            if (e.target.name === 'engagement_type') toggleMainOrganizer();
            if (e.target.name === 'has_guest_speakers') toggleGuests();
        });

        toggleMainOrganizer();
        toggleGuests();

    });
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.fund-source-checkbox').forEach(cb => {
        cb.addEventListener('change', function () {

            const targetId = this.dataset.target;
            const input = document.getElementById(targetId);

            if (!input) return;

            if (this.checked) {
                input.classList.remove('hidden');
                input.required = true;
            } else {
                input.classList.add('hidden');
                input.value = '';
                input.required = false;
            }
        });
    });

});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {

  
    document.addEventListener('click', function (e) {
        if (!e.target.classList.contains('remove-btn')) return;

        const row = e.target.closest('.guest-row, .plan-row');
        if (!row) return;

        const wrapper = row.parentElement;

        if (wrapper.children.length > 1) {
            row.remove();
        }
    });

    document.getElementById('addGuestBtn')?.addEventListener('click', function () {

        const wrap = document.getElementById('guestsWrap');
        const i = wrap.querySelectorAll('.guest-row').length;

        wrap.insertAdjacentHTML('beforeend', `
            <div class="grid grid-cols-1 md:grid-cols-12 gap-2 items-center guest-row">

                <input type="text"
                    name="guests[${i}][full_name]"
                    class="md:col-span-4 rounded-lg border border-slate-300 px-3 py-2 text-sm 
                        focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition"
                    placeholder="Full Name">

                <input type="text"
                    name="guests[${i}][affiliation]"
                    class="md:col-span-3 rounded-lg border border-slate-300 px-3 py-2 text-sm 
                        focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition"
                    placeholder="Affiliation">

                <input type="text"
                    name="guests[${i}][designation]"
                    class="md:col-span-3 rounded-lg border border-slate-300 px-3 py-2 text-sm 
                        focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition"
                    placeholder="Designation">

                <div class="md:col-span-2 flex justify-end">
                    <button type="button"
                        class="remove-btn text-xs font-medium text-slate-400 hover:text-red-600 transition">
                        Remove
                    </button>
                </div>

            </div>
        `);
    });

   
    document.getElementById('addPlanBtn')?.addEventListener('click', function () {

        const wrap = document.getElementById('planWrap');
        const i = wrap.querySelectorAll('.plan-row').length;

        wrap.insertAdjacentHTML('beforeend', `
            <div class="grid grid-cols-1 md:grid-cols-12 gap-2 items-center plan-row">

                <input type="date"
                    name="plan_of_actions[${i}][date]"
                    class="md:col-span-2 rounded-lg border border-slate-300 px-2 py-2 text-sm 
                        focus:ring-2 focus:ring-blue-500 focus:outline-none">

                <input type="time"
                    name="plan_of_actions[${i}][time]"
                    class="md:col-span-2 rounded-lg border border-slate-300 px-2 py-2 text-sm 
                        focus:ring-2 focus:ring-blue-500 focus:outline-none">

                <input type="text"
                    name="plan_of_actions[${i}][activity]"
                    class="md:col-span-4 rounded-lg border border-slate-300 px-3 py-2 text-sm 
                        focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    placeholder="Activity">

                <input type="text"
                    name="plan_of_actions[${i}][venue]"
                    class="md:col-span-3 rounded-lg border border-slate-300 px-3 py-2 text-sm 
                        focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    placeholder="Venue">

                <div class="md:col-span-1 flex justify-end">
                    <button type="button"
                        class="remove-btn text-xs font-medium text-slate-400 hover:text-red-600 transition">
                        Remove
                    </button>
                </div>

            </div>
        `);
    });
    
    function toggleGuests() {
        const val = document.querySelector('input[name="has_guest_speakers"]:checked')?.value;
        const wrap = document.getElementById('guestListWrap');
        if (!wrap) return;
        wrap.classList.toggle('hidden', val !== '1');
    }

    document.querySelectorAll('input[name="has_guest_speakers"]').forEach(r => {
        r.addEventListener('change', toggleGuests);
    });

    toggleGuests();
});
</script>