<script>
document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | SIMPLE ROWS (objectives / indicators)
    |--------------------------------------------------------------------------
    */

    function createSimpleRow(name, placeholder) {

        const row = document.createElement('div');
        row.className = 'flex gap-2 dynamic-row';

        row.innerHTML = `
            <input type="text"
                   name="${name}"
                   class="w-full border border-slate-300 bg-white px-3 py-1 text-[12px]"
                   placeholder="${placeholder}">

            <button type="button"
                    class="remove-btn text-red-600 text-[12px] px-2">
                ✕
            </button>
        `;

        return row;
    }


    function setupSimpleAdd(buttonId, wrapperId, name, placeholder) {

        const btn = document.getElementById(buttonId);
        const wrapper = document.getElementById(wrapperId);

        if (!btn || !wrapper) return;

        btn.addEventListener('click', function () {

            wrapper.appendChild(
                createSimpleRow(name, placeholder)
            );

        });
    }


    /*
    |--------------------------------------------------------------------------
    | ATTENDEE ROW
    |--------------------------------------------------------------------------
    */

    function createAttendeeRow(index) {

        const row = document.createElement('div');
        row.className = 'grid grid-cols-1 gap-2 md:grid-cols-4 dynamic-row';

        row.innerHTML = `
            <input type="text"
                   name="attendees[${index}][name]"
                   class="border border-slate-300 px-3 py-1 text-[12px]"
                   placeholder="Full Name">

            <input type="text"
                   name="attendees[${index}][affiliation]"
                   class="border border-slate-300 px-3 py-1 text-[12px]"
                   placeholder="Affiliation">

            <input type="text"
                   name="attendees[${index}][designation]"
                   class="border border-slate-300 px-3 py-1 text-[12px]"
                   placeholder="Designation">

            <button type="button"
                    class="remove-btn text-red-600 text-[12px] px-2">
                ✕
            </button>
        `;

        return row;
    }


    function setupAttendees() {

        const btn = document.getElementById('addAttendeeBtn');
        const wrapper = document.getElementById('attendeesWrap');

        if (!btn || !wrapper) return;

        btn.addEventListener('click', function () {

            const index = wrapper.querySelectorAll('.dynamic-row').length;

            wrapper.appendChild(
                createAttendeeRow(index)
            );

        });

    }


    /*
    |--------------------------------------------------------------------------
    | PARTNER ROW
    |--------------------------------------------------------------------------
    */

    function createPartnerRow(index) {

        const row = document.createElement('div');
        row.className = 'grid grid-cols-1 gap-2 md:grid-cols-3 dynamic-row';

        row.innerHTML = `
            <input type="text"
                   name="partners[${index}][name]"
                   class="border border-slate-300 px-3 py-1 text-[12px]"
                   placeholder="Partner / Sponsor name">

            <input type="text"
                   name="partners[${index}][type]"
                   class="border border-slate-300 px-3 py-1 text-[12px]"
                   placeholder="Type (optional)">

            <button type="button"
                    class="remove-btn text-red-600 text-[12px] px-2">
                ✕
            </button>
        `;

        return row;
    }


    function setupPartners() {

        const btn = document.getElementById('addReportPartnerBtn');
        const wrapper = document.getElementById('reportPartnersWrap');

        if (!btn || !wrapper) return;

        btn.addEventListener('click', function () {

            const index = wrapper.querySelectorAll('.dynamic-row').length;

            wrapper.appendChild(
                createPartnerRow(index)
            );

        });

    }


    /*
    |--------------------------------------------------------------------------
    | REMOVE ROW BUTTON
    |--------------------------------------------------------------------------
    */

    document.addEventListener('click', function (e) {

        if (!e.target.classList.contains('remove-btn')) return;

        const row = e.target.closest('.dynamic-row');
        if (!row) return;

        const wrapper = row.parentElement;

        if (wrapper.querySelectorAll('.dynamic-row').length > 1) {
            row.remove();
        }

    });


    /*
    |--------------------------------------------------------------------------
    | INIT
    |--------------------------------------------------------------------------
    */

    setupSimpleAdd(
        'addReportObjectiveBtn',
        'reportObjectivesWrap',
        'objectives[]',
        'Enter objective'
    );

    setupSimpleAdd(
        'addReportIndicatorBtn',
        'reportIndicatorsWrap',
        'success_indicators[]',
        'Enter success indicator'
    );

    setupAttendees();
    setupPartners();

});
</script>