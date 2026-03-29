<script>
document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | SIMPLE ROWS (Objectives / Indicators)
    |--------------------------------------------------------------------------
    */

    function createSimpleRow(name, placeholder) {

        const row = document.createElement('div');
        row.className = 'flex gap-2 items-center dynamic-row';

        row.innerHTML = `
            <input type="text"
                name="${name}"
                placeholder="${placeholder}"
                class="w-full rounded-md border border-slate-200 px-2 py-1 text-sm">

            <button type="button"
                class="remove-btn text-xs text-rose-600 hover:text-rose-800 whitespace-nowrap">
                Remove
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
    | ATTENDEES (TABLE STYLE)
    |--------------------------------------------------------------------------
    */

    function createAttendeeRow(index) {

        return `
        <tr class="attendee-row dynamic-row">

            <td class="px-2 py-2">
                <input type="text"
                    name="attendees[${index}][name]"
                    placeholder="e.g. BSIT Students or Juan Dela Cruz"
                    class="w-full rounded-md border border-slate-200 px-2 py-1 text-sm">
            </td>

            <td class="px-2 py-2">
                <input type="text"
                    name="attendees[${index}][affiliation]"
                    placeholder="e.g. Xavier University"
                    class="w-full rounded-md border border-slate-200 px-2 py-1 text-sm">
            </td>

            <td class="px-2 py-2">
                <input type="text"
                    name="attendees[${index}][designation]"
                    placeholder="e.g. Participants"
                    class="w-full rounded-md border border-slate-200 px-2 py-1 text-sm">
            </td>

            <td class="px-2 py-2 text-center">
                <button type="button"
                    onclick="removeTableRow(this)"
                    class="text-xs text-rose-600 hover:text-rose-800 font-medium">
                    Remove
                </button>
            </td>

        </tr>
        `;
    }

    function setupAttendees() {

        const btn = document.getElementById('addAttendeeBtn');
        const wrapper = document.getElementById('attendeesWrap');

        if (!btn || !wrapper) return;

        btn.addEventListener('click', function () {

            const index = wrapper.querySelectorAll('tr').length;

            wrapper.insertAdjacentHTML(
                'beforeend',
                createAttendeeRow(index)
            );

        });

    }


    /*
    |--------------------------------------------------------------------------
    | PARTNERS (TABLE STYLE)
    |--------------------------------------------------------------------------
    */

    function createPartnerRow(index) {

        return `
        <tr class="partner-row dynamic-row">

            <td class="px-2 py-2">
                <input type="text"
                    name="partners[${index}][name]"
                    placeholder="e.g. Red Cross"
                    class="w-full rounded-md border border-slate-200 px-2 py-1 text-sm">
            </td>

            <td class="px-2 py-2">
                <input type="text"
                    name="partners[${index}][type]"
                    placeholder="e.g. Sponsor"
                    class="w-full rounded-md border border-slate-200 px-2 py-1 text-sm">
            </td>

            <td class="px-2 py-2 text-center">
                <button type="button"
                    onclick="removeTableRow(this)"
                    class="text-xs text-rose-600 hover:text-rose-800 font-medium">
                    Remove
                </button>
            </td>

        </tr>
        `;
    }

    function setupPartners() {

        const btn = document.getElementById('addReportPartnerBtn');
        const wrapper = document.getElementById('reportPartnersWrap');

        if (!btn || !wrapper) return;

        btn.addEventListener('click', function () {

            const index = wrapper.querySelectorAll('tr').length;

            wrapper.insertAdjacentHTML(
                'beforeend',
                createPartnerRow(index)
            );

        });

    }


    /*
    |--------------------------------------------------------------------------
    | REMOVE (GENERIC)
    |--------------------------------------------------------------------------
    */

    window.removeTableRow = function (button) {

        const row = button.closest('tr');
        if (!row) return;

        const wrapper = row.parentElement;

        if (wrapper.querySelectorAll('tr').length > 1) {
            row.remove();
        }

    };

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
    | FINANCIAL AUTO COMPUTE
    |--------------------------------------------------------------------------
    */

    function updateBalance() {

        const proposed = parseFloat(
            document.querySelector('[name="proposed_budget"]')?.value
        ) || 0;

        const actual = parseFloat(
            document.querySelector('[name="actual_budget"]')?.value
        ) || 0;

        const balanceField = document.querySelector('[name="balance"]');

        if (balanceField) {
            balanceField.value = (proposed - actual).toFixed(2);
        }
    }

    document.addEventListener('input', function (e) {

        if (e.target.name === 'proposed_budget' || e.target.name === 'actual_budget') {
            updateBalance();
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

    updateBalance();

});
</script>