<script>

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
            row.className = 'flex gap-2 items-center dynamic-row';

            row.innerHTML = `
                <input type="text"
                    name="${name}"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-xs 
                    focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition"
                    placeholder="${placeholder}">
                    <button type="button"
                        class="remove-btn text-slate-400 hover:text-rose-600 text-sm px-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 6 6 18"></path>
                            <path d="m6 6 12 12"></path>
                        </svg>
                    </button>
            `;

            return row;
        }

        function setupAddButton(buttonId, wrapperId, name, placeholder) {
            const btn = document.getElementById(buttonId);
            const wrapper = document.getElementById(wrapperId);

            if (!btn || !wrapper) return;

            btn.addEventListener('click', function () {
                const row = createSimpleRow(name, placeholder);
                wrapper.appendChild(row);

                if (window.lucide) lucide.createIcons();
            });
        }



        document.addEventListener('click', function (e) {
            const btn = e.target.closest('.remove-btn');
            if (!btn) return;

            const row = btn.closest('.dynamic-row, .guest-row, .plan-row');
            if (!row) return;

            const wrapper = row.parentElement;
            if (!wrapper) return;

            const rowCount = wrapper.querySelectorAll('.dynamic-row, .guest-row, .plan-row').length;

            if (rowCount > 1) {
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


    document.getElementById('addGuestBtn')?.addEventListener('click', function () {

        const wrap = document.getElementById('guestsWrap');
        const i = wrap.querySelectorAll('.guest-row').length;

        wrap.insertAdjacentHTML('beforeend', `
            <div class="grid grid-cols-1 md:grid-cols-12 gap-2 items-center border border-slate-200 rounded-xl p-2 bg-white hover:bg-slate-50 transition guest-row">

                <input type="text"
                    name="guests[${i}][full_name]"
                    class="md:col-span-4 rounded-lg border border-slate-300 px-3 py-2 text-xs focus:ring-2 focus:ring-blue-500"
                    placeholder="Full Name">

                <input type="text"
                    name="guests[${i}][affiliation]"
                    class="md:col-span-3 rounded-lg border border-slate-300 px-3 py-2 text-xs focus:ring-2 focus:ring-blue-500"
                    placeholder="Affiliation">

                <input type="text"
                    name="guests[${i}][designation]"
                    class="md:col-span-3 rounded-lg border border-slate-300 px-3 py-2 text-xs focus:ring-2 focus:ring-blue-500"
                    placeholder="Designation">

                <div class="md:col-span-2 flex justify-end">
                    <button type="button"
                        class="remove-btn text-xs font-medium text-slate-400 hover:text-rose-600 transition">
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
            <div class="grid grid-cols-1 md:grid-cols-12 gap-2 items-center border border-slate-200 rounded-xl p-2 bg-white hover:bg-slate-50 transition plan-row">

                <input type="date"
                    name="plan_of_actions[${i}][date]"
                    class="md:col-span-2 rounded-lg border border-slate-300 px-2 py-2 text-xs focus:ring-2 focus:ring-blue-500">

                <input type="time"
                    name="plan_of_actions[${i}][time]"
                    class="md:col-span-2 rounded-lg border border-slate-300 px-2 py-2 text-xs focus:ring-2 focus:ring-blue-500">

                <input type="text"
                    name="plan_of_actions[${i}][activity]"
                    class="md:col-span-4 rounded-lg border border-slate-300 px-3 py-2 text-xs focus:ring-2 focus:ring-blue-500"
                    placeholder="Activity">

                <input type="text"
                    name="plan_of_actions[${i}][venue]"
                    class="md:col-span-3 rounded-lg border border-slate-300 px-3 py-2 text-xs focus:ring-2 focus:ring-blue-500"
                    placeholder="Venue">

                <div class="md:col-span-1 flex justify-end">
                    <button type="button"
                        class="remove-btn text-xs font-medium text-slate-400 hover:text-rose-600 transition">
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