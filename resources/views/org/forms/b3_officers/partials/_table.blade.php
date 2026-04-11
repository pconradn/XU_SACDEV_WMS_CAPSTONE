<div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-5">

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

    {{-- HEADER --}}
    <div class="flex items-start justify-between gap-4 mb-4">
        <div>
            <div class="text-xs font-semibold text-slate-900 flex items-center gap-2">
                <i data-lucide="list" class="w-4 h-4 text-slate-500"></i>
                Other Officers
            </div>
            <div class="text-[11px] text-slate-500 mt-1">
                Remaining organization members
            </div>
        </div>
        <div class="text-[10px] text-slate-500 mb-2">

</div>

        <button type="button"
                id="addOfficerBtn"
                class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition disabled:opacity-50"
                {{ (!$isPresident || $isLocked) ? 'disabled' : '' }}>
            <i data-lucide="plus" class="w-3.5 h-3.5"></i>
            Add
        </button>
    </div>

    {{-- TABLE --}}
    <div class="rounded-2xl border border-slate-200 overflow-hidden">

        <div class="overflow-x-auto">
            <table class="min-w-full text-xs text-left">

                <thead class="bg-slate-50 text-slate-500 uppercase tracking-wide">
                    <tr>
                        <th class="px-4 py-3">Position</th>
                        <th class="px-4 py-3">Name</th>
                        <th class="px-4 py-3">Student ID</th>
                        <th class="px-4 py-3">Course</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>

                <tbody id="officerRows" class="divide-y divide-slate-100 bg-white">

                    @forelse($items as $idx => $row)
                    <tr class="hover:bg-slate-50 transition" data-row-index="{{ $idx }}">

                        <td class="px-4 py-3">
                            <div class="font-medium text-slate-800">
                                {{ $row['position'] ?? '—' }}
                            </div>

                            @foreach($row as $key => $val)
                                <input type="hidden" name="items[{{ $idx }}][{{ $key }}]" value="{{ $val }}">
                            @endforeach
                        </td>

                        <td class="px-4 py-3 text-slate-800 font-medium">
                            {{
                                trim(
                                    (($row['prefix'] ?? '') ? ($row['prefix'] . ' ') : '') .
                                    ($row['first_name'] ?? '') .
                                    (($row['middle_initial'] ?? '') ? (' ' . rtrim($row['middle_initial'], '.') . '.') : '') .
                                    (($row['last_name'] ?? '') ? (' ' . $row['last_name']) : '')
                                ) ?: '—'
                            }}
                        </td>

                        <td class="px-4 py-3 text-slate-600">
                            {{ $row['student_id_number'] ?? '—' }}
                        </td>

                        <td class="px-4 py-3 text-slate-600">
                            {{ $row['course_and_year'] ?? '—' }}
                        </td>

                        <td class="px-4 py-3">
                            <div class="flex justify-end gap-2">

                                <button type="button"
                                        class="editOfficerBtn inline-flex items-center gap-1 rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50"
                                        data-index="{{ $idx }}"
                                        {{ (!$isPresident || $isLocked) ? 'disabled' : '' }}>
                                    Edit
                                </button>

                                <button type="button"
                                        class="removeOfficerBtn inline-flex items-center gap-1 rounded-lg border border-rose-200 px-3 py-1.5 text-xs font-semibold text-rose-700 hover:bg-rose-50"
                                        data-index="{{ $idx }}"
                                        {{ (!$isPresident || $isLocked) ? 'disabled' : '' }}>
                                    Remove
                                </button>

                            </div>
                        </td>

                    </tr>
               
                    @empty
                    <tr id="emptyHint">
                        <td colspan="5" class="px-4 py-2 text-center">

                            <div class="inline-flex flex-col items-center gap-2 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-[11px] text-amber-800">

                                <div class="flex items-center gap-1.5 font-semibold">
                                    <i data-lucide="info" class="w-3.5 h-3.5"></i>
                                    No officers added in submission yet.
                                </div>

                                <div class="text-[10px] text-amber-700">
                                    Add officers above, then click <span class="font-semibold">Save Draft</span> to keep your progress.
                                </div>

                            </div>

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

    function buildName(d) {
        return [
            d.prefix,
            d.first_name,
            d.middle_initial ? d.middle_initial.replace('.', '') + '.' : '',
            d.last_name
        ].filter(Boolean).join(' ') || '—';
    }

    function escape(v) {
        return String(v ?? '')
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');
    }

    function getIndex() {
        const rows = rowsContainer.querySelectorAll('tr[data-row-index]');
        let max = -1;
        rows.forEach(r => {
            const i = parseInt(r.dataset.rowIndex);
            if (i > max) max = i;
        });
        return max + 1;
    }

    function collect() {
        return Object.fromEntries(
            Object.entries(fields).map(([k, el]) => [k, el.value.trim()])
        );
    }

    function render(i, d) {
        return `
        <tr data-row-index="${i}" class="hover:bg-slate-50">
            <td class="px-4 py-3">
                <div class="font-medium text-slate-800">${escape(d.position || '—')}</div>
                ${Object.entries(d).map(([k,v]) =>
                    `<input type="hidden" name="items[${i}][${k}]" value="${escape(v)}">`
                ).join('')}
            </td>
            <td class="px-4 py-3 font-medium text-slate-800">${escape(buildName(d))}</td>
            <td class="px-4 py-3 text-slate-600">${escape(d.student_id_number || '—')}</td>
            <td class="px-4 py-3 text-slate-600">${escape(d.course_and_year || '—')}</td>
            <td class="px-4 py-3">
                <div class="flex justify-end gap-2">
                    <button class="editOfficerBtn px-3 py-1.5 text-xs border rounded-lg border-slate-200">Edit</button>
                    <button class="removeOfficerBtn px-3 py-1.5 text-xs border rounded-lg border-rose-200 text-rose-700">Remove</button>
                </div>
            </td>
        </tr>`;
    }

    addOfficerBtn?.addEventListener('click', () => {
        editingIndex = null;
        modalTitle.textContent = 'Add Officer';
        Object.values(fields).forEach(f => f.value = '');
        window.openOfficerModal?.();
    });

    saveOfficerBtn?.addEventListener('click', () => {
        const data = collect();
        if (!data.position || !data.first_name || !data.last_name) return;

        if (editingIndex !== null) {
            rowsContainer.querySelector(`[data-row-index="${editingIndex}"]`)
                .outerHTML = render(editingIndex, data);
        } else {
            rowsContainer.insertAdjacentHTML('beforeend', render(getIndex(), data));
        }

        editingIndex = null;
        window.closeOfficerModal?.();
    });

    rowsContainer.addEventListener('click', e => {
        const edit = e.target.closest('.editOfficerBtn');
        const del = e.target.closest('.removeOfficerBtn');

        if (edit) {
            const row = edit.closest('tr');
            editingIndex = row.dataset.rowIndex;

            Object.keys(fields).forEach(k => {
                fields[k].value = row.querySelector(`[name="items[${editingIndex}][${k}]"]`)?.value || '';
            });

            modalTitle.textContent = 'Edit Officer';
            window.openOfficerModal?.();
        }

        if (del) {
            del.closest('tr').remove();
        }
    });
});
</script>