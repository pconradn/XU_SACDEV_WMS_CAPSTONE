<tr class="row-item">
    <td class="py-2 px-2">
        <input type="text" name="items[{{ $idx }}][position]"
               value="{{ $row['position'] ?? '' }}"
               class="w-48 rounded-lg border border-slate-300 px-3 py-2 text-sm"
               {{ $isLocked ? 'disabled' : '' }}>
    </td>

    <td class="py-2 px-2">
        <input type="text" name="items[{{ $idx }}][officer_name]"
               value="{{ $row['officer_name'] ?? '' }}"
               class="w-56 rounded-lg border border-slate-300 px-3 py-2 text-sm"
               {{ $isLocked ? 'disabled' : '' }}>
    </td>

    <td class="py-2 px-2">
        <input type="text" name="items[{{ $idx }}][student_id_number]"
               value="{{ $row['student_id_number'] ?? '' }}"
               class="w-40 rounded-lg border border-slate-300 px-3 py-2 text-sm"
               {{ $isLocked ? 'disabled' : '' }}>
    </td>

    <td class="py-2 px-2">
        <input type="text" name="items[{{ $idx }}][course_and_year]"
               value="{{ $row['course_and_year'] ?? '' }}"
               class="w-56 rounded-lg border border-slate-300 px-3 py-2 text-sm"
               {{ $isLocked ? 'disabled' : '' }}>
    </td>

    <td class="py-2 px-2">
        <input type="number" step="0.01" min="0" max="4"
               name="items[{{ $idx }}][latest_qpi]"
               value="{{ $row['latest_qpi'] ?? '' }}"
               class="w-28 rounded-lg border border-slate-300 px-3 py-2 text-sm"
               {{ $isLocked ? 'disabled' : '' }}>
    </td>

    <td class="py-2 px-2">
        <input type="text" name="items[{{ $idx }}][mobile_number]"
               value="{{ $row['mobile_number'] ?? '' }}"
               class="w-40 rounded-lg border border-slate-300 px-3 py-2 text-sm"
               {{ $isLocked ? 'disabled' : '' }}>
    </td>

    <td class="py-2 px-2 text-right">
        <button type="button"
                class="removeRowBtn inline-flex rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-xs font-semibold text-red-700 hover:bg-red-100 disabled:opacity-50"
                {{ $isLocked ? 'disabled' : '' }}>
            Remove
        </button>
    </td>
</tr>