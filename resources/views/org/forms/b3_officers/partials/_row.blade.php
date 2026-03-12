<tr class="officer-row cursor-pointer hover:bg-slate-50"
    data-index="{{ $idx }}">

    {{-- Summary columns --}}

    <td class="py-3 px-2 font-medium text-slate-800 officer-position">
        {{ $row['position'] ?? '' }}
    </td>

    <td class="py-3 px-2 text-slate-700 officer-name">
        {{ $row['officer_name'] ?? '' }}
    </td>

    <td class="py-3 px-2 text-slate-700 officer-student-id">
        {{ $row['student_id_number'] ?? '' }}
    </td>

    <td class="py-3 px-2 text-right space-x-2">

        <button type="button"
            class="editOfficerBtn inline-flex rounded-lg border border-blue-200 bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700 hover:bg-blue-100"
            {{ $isLocked ? 'disabled' : '' }}>
            Edit
        </button>

        <button type="button"
            class="removeOfficerBtn inline-flex rounded-lg border border-red-200 bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-700 hover:bg-red-100"
            {{ $isLocked ? 'disabled' : '' }}>
            Remove
        </button>

    </td>


    {{-- Hidden Inputs (REAL DATA STORAGE) --}}

    <input type="hidden"
        name="items[{{ $idx }}][position]"
        value="{{ $row['position'] ?? '' }}"
        class="input-position">

    <input type="hidden"
        name="items[{{ $idx }}][officer_name]"
        value="{{ $row['officer_name'] ?? '' }}"
        class="input-name">

    <input type="hidden"
        name="items[{{ $idx }}][student_id_number]"
        value="{{ $row['student_id_number'] ?? '' }}"
        class="input-student-id">

    <input type="hidden"
        name="items[{{ $idx }}][course_and_year]"
        value="{{ $row['course_and_year'] ?? '' }}"
        class="input-course">

    <input type="hidden"
        name="items[{{ $idx }}][first_sem_qpi]"
        value="{{ $row['first_sem_qpi'] ?? '' }}"
        class="input-first-qpi">

    <input type="hidden"
        name="items[{{ $idx }}][second_sem_qpi]"
        value="{{ $row['second_sem_qpi'] ?? '' }}"
        class="input-second-qpi">

    <input type="hidden"
        name="items[{{ $idx }}][intersession_qpi]"
        value="{{ $row['intersession_qpi'] ?? '' }}"
        class="input-intersession-qpi">

    <input type="hidden"
        name="items[{{ $idx }}][mobile_number]"
        value="{{ $row['mobile_number'] ?? '' }}"
        class="input-mobile">

</tr>