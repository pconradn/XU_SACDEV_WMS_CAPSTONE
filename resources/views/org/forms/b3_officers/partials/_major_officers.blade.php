<div class="rounded-2xl border border-blue-200 bg-gradient-to-b from-blue-50 to-white p-6 shadow-sm mb-6">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h3 class="text-sm font-semibold text-blue-900">
                Major Officers
            </h3>
            <p class="text-xs text-blue-700 mt-1">
                Assign key organizational roles. Required for submission.
            </p>
        </div>
    </div>

    @php
        $majorRoles = [
            'president' => 'President',
            'vice_president' => 'Vice President',
            'treasurer' => 'Treasurer',
            'finance_officer' => 'Finance Officer',
        ];

        $existing = collect($registration->items ?? [])
            ->filter(fn($i) => !empty($i->major_officer_role))
            ->keyBy('major_officer_role');
    @endphp

    <div class="space-y-6">

        @foreach($majorRoles as $roleKey => $systemRoleLabel)

        @php
            $item = $existing->get($roleKey);

            $idx = "major_{$roleKey}";
            $hasError = collect($errors->get("items.$idx.*"))->flatten()->isNotEmpty();
        @endphp

        <div data-officer-row
             class="rounded-2xl border p-5 bg-white shadow-sm
             {{ $hasError ? 'border-rose-300 bg-rose-50/40' : 'border-blue-200' }}">

            <div class="flex items-center justify-between mb-4">
                <div class="text-sm font-semibold text-blue-800">
                    {{ $systemRoleLabel }}
                </div>
                <span class="text-[10px] px-2 py-0.5 rounded-md bg-blue-100 text-blue-700 font-semibold">
                    System Role
                </span>
            </div>

            <input type="hidden"
                name="items[{{ $idx }}][major_officer_role]"
                value="{{ $roleKey }}">

            {{-- POSITION --}}
            <div class="mb-4">
                <label class="text-xs font-medium text-slate-600">
                    Display Position Title
                </label>

                <input type="text"
                    name="items[{{ $idx }}][position]"
                    value="{{ old("items.$idx.position") ?? ($item->position ?? $systemRoleLabel) }}"
                    class="mt-1 w-full rounded-lg border px-3 py-2 text-sm border-slate-300 focus:ring-2 focus:ring-blue-200"
                    {{ $isLocked ? 'readonly' : '' }}>
            </div>

            {{-- NAME --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">

                <input type="text"
                    name="items[{{ $idx }}][prefix]"
                    value="{{ old("items.$idx.prefix") ?? ($item->prefix ?? '') }}"
                    placeholder="Prefix"
                    class="mt-1 w-full rounded-lg border px-3 py-2 text-sm border-slate-300">

                <input type="text"
                    name="items[{{ $idx }}][first_name]"
                    value="{{ old("items.$idx.first_name") ?? ($item->first_name ?? '') }}"
                    placeholder="First Name"
                    class="mt-1 w-full rounded-lg border px-3 py-2 text-sm border-slate-300">

                <input type="text"
                    name="items[{{ $idx }}][middle_initial]"
                    value="{{ old("items.$idx.middle_initial") ?? ($item->middle_initial ?? '') }}"
                    maxlength="1"
                    placeholder="M.I."
                    class="mt-1 w-full rounded-lg border px-3 py-2 text-sm border-slate-300">

                <input type="text"
                    name="items[{{ $idx }}][last_name]"
                    value="{{ old("items.$idx.last_name") ?? ($item->last_name ?? '') }}"
                    placeholder="Last Name"
                    class="mt-1 w-full rounded-lg border px-3 py-2 text-sm border-slate-300">

            </div>

            {{-- PREVIEW --}}
            <div class="mt-2 text-[11px] text-slate-500">
                Full Name:
                <span id="preview_{{ $idx }}">
                    {{
                        trim(
                            (($item->prefix ?? '') ? $item->prefix . ' ' : '') .
                            ($item->first_name ?? '') .
                            (($item->middle_initial ?? '') ? ' ' . rtrim($item->middle_initial, '.') . '.' : '') .
                            (($item->last_name ?? '') ? ' ' . $item->last_name : '')
                        )
                    }}
                </span>
            </div>

            {{-- DETAILS --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">

                <input type="text"
                    name="items[{{ $idx }}][student_id_number]"
                    value="{{ old("items.$idx.student_id_number") ?? ($item->student_id_number ?? '') }}"
                    placeholder="Student ID"
                    class="mt-1 w-full rounded-lg border px-3 py-2 text-sm border-slate-300">

                <input type="text"
                    name="items[{{ $idx }}][course_and_year]"
                    value="{{ old("items.$idx.course_and_year") ?? ($item->course_and_year ?? '') }}"
                    placeholder="Course & Year"
                    class="mt-1 w-full rounded-lg border px-3 py-2 text-sm border-slate-300">

                <input type="text"
                    name="items[{{ $idx }}][mobile_number]"
                    value="{{ old("items.$idx.mobile_number") ?? ($item->mobile_number ?? '') }}"
                    placeholder="Mobile"
                    class="mt-1 w-full rounded-lg border px-3 py-2 text-sm border-slate-300">

            </div>

            {{-- QPI --}}
            <div class="grid grid-cols-3 gap-4 mt-4">

                <input type="number"
                    step="0.01" min="0" max="4"
                    name="items[{{ $idx }}][first_sem_qpi]"
                    value="{{ old("items.$idx.first_sem_qpi") ?? ($item->first_sem_qpi ?? '') }}"
                    placeholder="1st Sem"
                    class="mt-1 w-full rounded-lg border px-3 py-2 text-sm border-slate-300">

                <input type="number"
                    step="0.01" min="0" max="4"
                    name="items[{{ $idx }}][second_sem_qpi]"
                    value="{{ old("items.$idx.second_sem_qpi") ?? ($item->second_sem_qpi ?? '') }}"
                    placeholder="2nd Sem"
                    class="mt-1 w-full rounded-lg border px-3 py-2 text-sm border-slate-300">

                <input type="number"
                    step="0.01" min="0" max="4"
                    name="items[{{ $idx }}][intersession_qpi]"
                    value="{{ old("items.$idx.intersession_qpi") ?? ($item->intersession_qpi ?? '') }}"
                    placeholder="Intersession"
                    class="mt-1 w-full rounded-lg border px-3 py-2 text-sm border-slate-300">

            </div>

        </div>

        @endforeach

    </div>

</div>

<script>
document.addEventListener('input', function(e) {

    const row = e.target.closest('[data-officer-row]');
    if (!row) return;

    const prefix = row.querySelector('[name*="[prefix]"]')?.value || '';
    const first  = row.querySelector('[name*="[first_name]"]')?.value || '';
    const mi     = row.querySelector('[name*="[middle_initial]"]')?.value || '';
    const last   = row.querySelector('[name*="[last_name]"]')?.value || '';

    let name = '';

    if (prefix) name += prefix + ' ';
    name += first;
    if (mi) name += ' ' + mi + '.';
    if (last) name += ' ' + last;

    const preview = row.querySelector('[id^="preview_"]');
    if (preview) preview.textContent = name.trim();
});
</script>