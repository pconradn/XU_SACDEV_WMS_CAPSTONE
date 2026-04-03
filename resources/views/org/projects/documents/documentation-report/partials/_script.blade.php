<script>
document.addEventListener('DOMContentLoaded', function () {

    function parseMoney(value) {
        if (!value) return 0;
        return parseFloat(String(value).replace(/,/g, '')) || 0;
    }

    function formatMoney(value) {
        return new Intl.NumberFormat('en-PH', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }).format(value || 0);
    }

    function formatMoneyInputValue(value) {
        const raw = String(value ?? '').replace(/,/g, '').trim();
        if (raw === '') return '';
        const parsed = parseFloat(raw);
        if (isNaN(parsed)) return '';
        return formatMoney(parsed);
    }

    function formatMoneyInputElement(input) {
        if (!input) return;
        input.value = formatMoneyInputValue(input.value);
    }

    window.parseMoney = parseMoney;
    window.formatMoney = formatMoney;
    window.formatMoneyInputElement = formatMoneyInputElement;

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
                class="w-full rounded-md border border-slate-300 px-2 py-1 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">

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
            wrapper.appendChild(createSimpleRow(name, placeholder));
        });
    }

    /*
    |--------------------------------------------------------------------------
    | ATTENDEES (TABLE STYLE)
    |--------------------------------------------------------------------------
    */

    function createAttendeeRow(index) {
        return `
        <tr class="hover:bg-slate-50 attendee-row dynamic-row">

            <td class="px-2 py-2 border-r">
                <input type="text"
                    name="attendees[${index}][name]"
                    placeholder="e.g. BSIT Students or Juan Dela Cruz"
                    class="w-full rounded-md border border-slate-300 px-2 py-1 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </td>

            <td class="px-2 py-2 border-r">
                <input type="text"
                    name="attendees[${index}][affiliation]"
                    placeholder="e.g. Xavier University"
                    class="w-full rounded-md border border-slate-300 px-2 py-1 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </td>

            <td class="px-2 py-2 border-r">
                <input type="text"
                    name="attendees[${index}][designation]"
                    placeholder="e.g. Participants"
                    class="w-full rounded-md border border-slate-300 px-2 py-1 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
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
            wrapper.insertAdjacentHTML('beforeend', createAttendeeRow(index));
        });
    }

    /*
    |--------------------------------------------------------------------------
    | PARTNERS (TABLE STYLE)
    |--------------------------------------------------------------------------
    */

    function createPartnerRow(index) {
        return `
        <tr class="hover:bg-slate-50 partner-row dynamic-row">

            <td class="px-2 py-2 border-r">
                <input type="text"
                    name="partners[${index}][name]"
                    placeholder="e.g. Red Cross"
                    class="w-full rounded-md border border-slate-300 px-2 py-1 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </td>

            <td class="px-2 py-2 border-r">
                <input type="text"
                    name="partners[${index}][type]"
                    placeholder="e.g. Sponsor"
                    class="w-full rounded-md border border-slate-300 px-2 py-1 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
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
            wrapper.insertAdjacentHTML('beforeend', createPartnerRow(index));
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
        const proposedField = document.querySelector('[name="proposed_budget"]');
        const actualField = document.querySelector('[name="actual_budget"]');
        const balanceField = document.querySelector('[name="balance"]');

        if (!proposedField || !actualField || !balanceField) return;

        const proposed = parseMoney(proposedField.value);
        const actual = parseMoney(actualField.value);
        const balance = proposed - actual;

        balanceField.value = formatMoney(balance);
    }

    window.updateBalance = updateBalance;

    function initializeMoneyFields() {
        document.querySelectorAll('[data-money], [name="proposed_budget"], [name="actual_budget"]').forEach(input => {
            if (input.value) {
                input.value = formatMoneyInputValue(input.value);
            }
        });
    }

    document.addEventListener('input', function (e) {
        if (e.target.name === 'proposed_budget' || e.target.name === 'actual_budget') {
            const cursorEnd = e.target.selectionEnd;
            const raw = e.target.value.replace(/[^0-9.]/g, '');
            e.target.value = raw;
            try {
                e.target.setSelectionRange(cursorEnd, cursorEnd);
            } catch (_) {}
            updateBalance();
        }
    });

    document.addEventListener('blur', function (e) {
        if (e.target.name === 'proposed_budget' || e.target.name === 'actual_budget') {
            formatMoneyInputElement(e.target);
            updateBalance();
        }
    }, true);

    document.addEventListener('submit', function (e) {
        const form = e.target;
        if (!(form instanceof HTMLFormElement)) return;

        form.querySelectorAll('[name="proposed_budget"], [name="actual_budget"], [name="balance"], [data-money]').forEach(input => {
            input.value = String(input.value || '').replace(/,/g, '');
        });
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
    initializeMoneyFields();
    updateBalance();
});
</script>