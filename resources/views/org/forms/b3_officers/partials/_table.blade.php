<div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

    <div class="flex items-center justify-between gap-3">
        <div>
            <h3 class="text-sm font-semibold text-slate-900">
                Other Officers
            </h3>
            <p class="mt-1 text-xs text-slate-500">
                Add the remaining officers for this organization.
            </p>
        </div>

        <button type="button"
                id="addOfficerBtn"
                class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-800 transition hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-50"
                {{ $isLocked ? 'disabled' : '' }}>
            + Add Officer
        </button>
    </div>

    @php
        $oldItems = old('items');

        $items = is_array($oldItems)
            ? $oldItems
            : ($registration->items?->map(fn($i) => $i->toArray())->toArray() ?? []);

        $items = collect($items)
            ->filter(fn($item) => empty($item['major_officer_role']))
            ->values()
            ->toArray();
    @endphp

    <div class="mt-4 overflow-hidden rounded-2xl border border-slate-200">
        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm">
                <thead class="bg-slate-50 text-[11px] font-semibold uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-4 py-3">Position</th>
                        <th class="px-4 py-3">Officer Name</th>
                        <th class="px-4 py-3">Student ID</th>
                        <th class="px-4 py-3">Course & Year</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>

                <tbody id="officerRows" class="divide-y divide-slate-100 bg-white">
                    @forelse($items as $idx => $row)
                        <tr class="hover:bg-slate-50"
                            data-row-index="{{ $idx }}">
                            <td class="px-4 py-3 align-top">
                                <div class="font-medium text-slate-800">
                                    {{ $row['position'] ?? '—' }}
                                </div>
                                <input type="hidden" name="items[{{ $idx }}][position]" value="{{ $row['position'] ?? '' }}">
                                <input type="hidden" name="items[{{ $idx }}][prefix]" value="{{ $row['prefix'] ?? '' }}">
                                <input type="hidden" name="items[{{ $idx }}][first_name]" value="{{ $row['first_name'] ?? '' }}">
                                <input type="hidden" name="items[{{ $idx }}][middle_initial]" value="{{ $row['middle_initial'] ?? '' }}">
                                <input type="hidden" name="items[{{ $idx }}][last_name]" value="{{ $row['last_name'] ?? '' }}">
                                <input type="hidden" name="items[{{ $idx }}][student_id_number]" value="{{ $row['student_id_number'] ?? '' }}">
                                <input type="hidden" name="items[{{ $idx }}][course_and_year]" value="{{ $row['course_and_year'] ?? '' }}">
                                <input type="hidden" name="items[{{ $idx }}][first_sem_qpi]" value="{{ $row['first_sem_qpi'] ?? '' }}">
                                <input type="hidden" name="items[{{ $idx }}][second_sem_qpi]" value="{{ $row['second_sem_qpi'] ?? '' }}">
                                <input type="hidden" name="items[{{ $idx }}][intersession_qpi]" value="{{ $row['intersession_qpi'] ?? '' }}">
                                <input type="hidden" name="items[{{ $idx }}][mobile_number]" value="{{ $row['mobile_number'] ?? '' }}">
                            </td>

                            <td class="px-4 py-3 align-top">
                                <div class="font-medium text-slate-800">
                                    {{
                                        trim(
                                            (($row['prefix'] ?? '') ? ($row['prefix'] . ' ') : '') .
                                            ($row['first_name'] ?? '') .
                                            (($row['middle_initial'] ?? '') ? (' ' . rtrim($row['middle_initial'], '.') . '.') : '') .
                                            (($row['last_name'] ?? '') ? (' ' . $row['last_name']) : '')
                                        ) ?: '—'
                                    }}
                                </div>
                            </td>

                            <td class="px-4 py-3 align-top text-slate-700">
                                {{ $row['student_id_number'] ?? '—' }}
                            </td>

                            <td class="px-4 py-3 align-top text-slate-700">
                                {{ $row['course_and_year'] ?? '—' }}
                            </td>

                            <td class="px-4 py-3 align-top">
                                <div class="flex justify-end gap-2">
                                    <button type="button"
                                            class="editOfficerBtn inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-50"
                                            data-index="{{ $idx }}"
                                            {{ $isLocked ? 'disabled' : '' }}>
                                        Edit
                                    </button>

                                    <button type="button"
                                            class="removeOfficerBtn inline-flex items-center rounded-lg border border-rose-200 bg-white px-3 py-1.5 text-xs font-semibold text-rose-700 transition hover:bg-rose-50"
                                            data-index="{{ $idx }}"
                                            {{ $isLocked ? 'disabled' : '' }}>
                                        Remove
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr id="emptyHint">
                            <td colspan="5" class="px-4 py-8 text-center text-sm text-slate-500">
                                No officers yet. Click <span class="font-semibold text-slate-700">Add Officer</span>.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@include('org.forms.b3_officers.partials._modal', [
    'isLocked' => $isLocked,
])

<script>
document.addEventListener('DOMContentLoaded', function () {
    const rowsContainer = document.getElementById('officerRows');
    const addOfficerBtn = document.getElementById('addOfficerBtn');
    const saveOfficerBtn = document.getElementById('saveOfficerBtn');
    const emptyHintId = 'emptyHint';

    const modal = document.getElementById('officerModal');
    const modalTitle = document.getElementById('officerModalTitle');

    const fields = {
        position: document.getElementById('modal_position'),
        prefix: document.getElementById('modal_prefix'),
        first_name: document.getElementById('modal_first'),
        middle_initial: document.getElementById('modal_mi'),
        last_name: document.getElementById('modal_last'),
        student_id_number: document.getElementById('modal_student_id'),
        course_and_year: document.getElementById('modal_course'),
        first_sem_qpi: document.getElementById('modal_first_qpi'),
        second_sem_qpi: document.getElementById('modal_second_qpi'),
        intersession_qpi: document.getElementById('modal_inter_qpi'),
        mobile_number: document.getElementById('modal_mobile'),
    };

    let editingIndex = null;

    function buildOfficerName(data) {
        const prefix = (data.prefix || '').trim();
        const first = (data.first_name || '').trim();
        const miRaw = (data.middle_initial || '').trim().replace(/\./g, '').toUpperCase();
        const last = (data.last_name || '').trim();

        let full = '';
        if (prefix) full += prefix + ' ';
        if (first) full += first;
        if (miRaw) full += (full ? ' ' : '') + miRaw + '.';
        if (last) full += (full ? ' ' : '') + last;

        return full.trim() || '—';
    }

    function escapeHtml(value) {
        return String(value ?? '')
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');
    }

    function getNextIndex() {
        const rows = rowsContainer.querySelectorAll('tr[data-row-index]');
        if (!rows.length) return 0;

        let max = -1;
        rows.forEach(row => {
            const idx = parseInt(row.getAttribute('data-row-index'), 10);
            if (!isNaN(idx) && idx > max) max = idx;
        });

        return max + 1;
    }

    function clearModalFields() {
        Object.values(fields).forEach(input => {
            if (input) input.value = '';
        });

        const preview = document.getElementById('modal_full_preview');
        if (preview) preview.textContent = '—';
    }

    function fillModalFields(data) {
        Object.keys(fields).forEach(key => {
            if (fields[key]) {
                fields[key].value = data[key] ?? '';
            }
        });

        const preview = document.getElementById('modal_full_preview');
        if (preview) preview.textContent = buildOfficerName(data);
    }

    function collectModalData() {
        return {
            position: fields.position.value.trim(),
            prefix: fields.prefix.value.trim(),
            first_name: fields.first_name.value.trim(),
            middle_initial: fields.middle_initial.value.trim().replace(/\./g, '').toUpperCase(),
            last_name: fields.last_name.value.trim(),
            student_id_number: fields.student_id_number.value.trim(),
            course_and_year: fields.course_and_year.value.trim(),
            first_sem_qpi: fields.first_sem_qpi.value.trim(),
            second_sem_qpi: fields.second_sem_qpi.value.trim(),
            intersession_qpi: fields.intersession_qpi.value.trim(),
            mobile_number: fields.mobile_number.value.trim(),
        };
    }

    function validateModalData(data) {
        if (!data.position || !data.first_name || !data.last_name || !data.student_id_number || !data.course_and_year || !data.mobile_number) {
            alert('Please complete Position, First Name, Last Name, Student ID, Course & Year, and Mobile Number.');
            return false;
        }

        return true;
    }

    function renderRow(index, data) {
        const officerName = buildOfficerName(data);

        return `
            <tr class="hover:bg-slate-50" data-row-index="${index}">
                <td class="px-4 py-3 align-top">
                    <div class="font-medium text-slate-800">${escapeHtml(data.position || '—')}</div>
                    <input type="hidden" name="items[${index}][position]" value="${escapeHtml(data.position)}">
                    <input type="hidden" name="items[${index}][prefix]" value="${escapeHtml(data.prefix)}">
                    <input type="hidden" name="items[${index}][first_name]" value="${escapeHtml(data.first_name)}">
                    <input type="hidden" name="items[${index}][middle_initial]" value="${escapeHtml(data.middle_initial)}">
                    <input type="hidden" name="items[${index}][last_name]" value="${escapeHtml(data.last_name)}">
                    <input type="hidden" name="items[${index}][student_id_number]" value="${escapeHtml(data.student_id_number)}">
                    <input type="hidden" name="items[${index}][course_and_year]" value="${escapeHtml(data.course_and_year)}">
                    <input type="hidden" name="items[${index}][first_sem_qpi]" value="${escapeHtml(data.first_sem_qpi)}">
                    <input type="hidden" name="items[${index}][second_sem_qpi]" value="${escapeHtml(data.second_sem_qpi)}">
                    <input type="hidden" name="items[${index}][intersession_qpi]" value="${escapeHtml(data.intersession_qpi)}">
                    <input type="hidden" name="items[${index}][mobile_number]" value="${escapeHtml(data.mobile_number)}">
                </td>

                <td class="px-4 py-3 align-top">
                    <div class="font-medium text-slate-800">${escapeHtml(officerName)}</div>
                </td>

                <td class="px-4 py-3 align-top text-slate-700">
                    ${escapeHtml(data.student_id_number || '—')}
                </td>

                <td class="px-4 py-3 align-top text-slate-700">
                    ${escapeHtml(data.course_and_year || '—')}
                </td>

                <td class="px-4 py-3 align-top">
                    <div class="flex justify-end gap-2">
                        <button type="button"
                                class="editOfficerBtn inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-50"
                                data-index="${index}">
                            Edit
                        </button>

                        <button type="button"
                                class="removeOfficerBtn inline-flex items-center rounded-lg border border-rose-200 bg-white px-3 py-1.5 text-xs font-semibold text-rose-700 transition hover:bg-rose-50"
                                data-index="${index}">
                            Remove
                        </button>
                    </div>
                </td>
            </tr>
        `;
    }

    function removeEmptyHint() {
        const emptyHint = document.getElementById(emptyHintId);
        if (emptyHint) emptyHint.remove();
    }

    function showEmptyHintIfNeeded() {
        const rows = rowsContainer.querySelectorAll('tr[data-row-index]');
        if (rows.length) return;

        rowsContainer.innerHTML = `
            <tr id="${emptyHintId}">
                <td colspan="5" class="px-4 py-8 text-center text-sm text-slate-500">
                    No officers yet. Click <span class="font-semibold text-slate-700">Add Officer</span>.
                </td>
            </tr>
        `;
    }

    addOfficerBtn?.addEventListener('click', function () {
        editingIndex = null;
        modalTitle.textContent = 'Add Officer';
        clearModalFields();
        if (typeof window.openOfficerModal === 'function') {
            window.openOfficerModal();
        }
    });

    saveOfficerBtn?.addEventListener('click', function () {
        const data = collectModalData();

        if (!validateModalData(data)) return;

        removeEmptyHint();

        if (editingIndex !== null) {
            const row = rowsContainer.querySelector(`tr[data-row-index="${editingIndex}"]`);
            if (row) {
                row.outerHTML = renderRow(editingIndex, data);
            }
        } else {
            const index = getNextIndex();
            rowsContainer.insertAdjacentHTML('beforeend', renderRow(index, data));
        }

        editingIndex = null;
        clearModalFields();

        if (typeof window.closeOfficerModal === 'function') {
            window.closeOfficerModal();
        }
    });

    rowsContainer.addEventListener('click', function (e) {
        const editBtn = e.target.closest('.editOfficerBtn');
        const removeBtn = e.target.closest('.removeOfficerBtn');

        if (editBtn) {
            const index = editBtn.getAttribute('data-index');
            const row = rowsContainer.querySelector(`tr[data-row-index="${index}"]`);
            if (!row) return;

            editingIndex = index;
            modalTitle.textContent = 'Edit Officer';

            const data = {
                position: row.querySelector(`[name="items[${index}][position]"]`)?.value || '',
                prefix: row.querySelector(`[name="items[${index}][prefix]"]`)?.value || '',
                first_name: row.querySelector(`[name="items[${index}][first_name]"]`)?.value || '',
                middle_initial: row.querySelector(`[name="items[${index}][middle_initial]"]`)?.value || '',
                last_name: row.querySelector(`[name="items[${index}][last_name]"]`)?.value || '',
                student_id_number: row.querySelector(`[name="items[${index}][student_id_number]"]`)?.value || '',
                course_and_year: row.querySelector(`[name="items[${index}][course_and_year]"]`)?.value || '',
                first_sem_qpi: row.querySelector(`[name="items[${index}][first_sem_qpi]"]`)?.value || '',
                second_sem_qpi: row.querySelector(`[name="items[${index}][second_sem_qpi]"]`)?.value || '',
                intersession_qpi: row.querySelector(`[name="items[${index}][intersession_qpi]"]`)?.value || '',
                mobile_number: row.querySelector(`[name="items[${index}][mobile_number]"]`)?.value || '',
            };

            fillModalFields(data);

            if (typeof window.openOfficerModal === 'function') {
                window.openOfficerModal();
            }
        }

        if (removeBtn) {
            const index = removeBtn.getAttribute('data-index');
            const row = rowsContainer.querySelector(`tr[data-row-index="${index}"]`);
            if (!row) return;

            row.remove();
            showEmptyHintIfNeeded();
        }
    });
});
</script>