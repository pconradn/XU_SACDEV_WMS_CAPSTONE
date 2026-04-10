<div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm p-5">

    @php
        $majorRoles = [
            'president' => 'President',
            'auditor' => 'Auditor',
            'treasurer' => 'Treasurer',
            'finance_officer' => 'Finance Officer',
        ];

        $existing = collect($registration->items ?? [])
            ->filter(fn($i) => !empty($i->major_officer_role))
            ->keyBy('major_officer_role');

        $presidentMembership = \App\Models\OrgMembership::where('organization_id', $registration->organization_id)
            ->where('school_year_id', $registration->target_school_year_id)
            ->where('role', 'president')
            ->whereNull('archived_at')
            ->with('user')
            ->first();

        $presidentUser = $presidentMembership?->user;
    @endphp

    {{-- HEADER --}}
    <div class="mb-4 flex items-start justify-between gap-4">
        <div>
            <div class="text-xs font-semibold text-slate-900 flex items-center gap-2">
                <i data-lucide="users" class="w-4 h-4 text-slate-500"></i>
                System Major Officers
            </div>
            <div class="text-[11px] text-slate-500 mt-1">
                Key roles required before submission
            </div>
        </div>
    </div>

    {{-- GRID --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        @foreach($majorRoles as $roleKey => $systemRoleLabel)

        @php
            $item = $existing->get($roleKey);
            $idx = "major_{$roleKey}";
            $isPresRow = $roleKey === 'president';

            $user = $isPresRow ? $presidentUser : null;

            $prefix = $isPresRow ? ($user->prefix ?? '') : ($item->prefix ?? '');
            $first  = $isPresRow ? ($user->first_name ?? '') : ($item->first_name ?? '');
            $mi     = $isPresRow ? ($user->middle_initial ?? '') : ($item->middle_initial ?? '');
            $last   = $isPresRow ? ($user->last_name ?? '') : ($item->last_name ?? '');

            $studentId = $isPresRow
                ? (isset($user->email) ? explode('@', $user->email)[0] : '')
                : ($item->student_id_number ?? '');
        @endphp

        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-4">

            {{-- HEADER --}}
            <div class="flex items-center justify-between mb-3">
                <div class="text-xs font-semibold text-slate-800">
                    {{ $systemRoleLabel }}
                </div>
                <span class="text-[10px] px-2 py-0.5 rounded-md bg-slate-100 text-slate-600">
                    System Role: {{ $systemRoleLabel }}
                </span>
            </div>

            <input type="hidden"
                name="items[{{ $idx }}][major_officer_role]"
                value="{{ $roleKey }}">

            {{-- POSITION --}}
            <div class="mb-3">
                <label class="block text-[10px] text-slate-500 mb-1">Display Position</label>
                <input type="text"
                    name="items[{{ $idx }}][position]"
                    value="{{ old("items.$idx.position") ?? ($item->position ?? $systemRoleLabel) }}"
                    class="w-full rounded-xl border px-3 py-2 text-xs border-slate-200"
                    {{ !$canEdit ? 'readonly' : '' }}>
            </div>

            {{-- NAME --}}
            <div class="grid grid-cols-2 gap-2">

                <div>
                    <label class="text-[10px] text-slate-500">Prefix</label>
                    <input type="text"
                        name="items[{{ $idx }}][prefix]"
                        value="{{ $prefix }}"
                        class="w-full rounded-xl border px-2 py-1.5 text-xs border-slate-200"
                        {{ $isPresRow ? 'readonly' : (!$canEdit ? 'readonly' : '') }}>
                </div>

                <div>
                    <label class="text-[10px] text-slate-500">First Name</label>
                    <input type="text"
                        name="items[{{ $idx }}][first_name]"
                        value="{{ $first }}"
                        class="w-full rounded-xl border px-2 py-1.5 text-xs border-slate-200"
                        {{ $isPresRow ? 'readonly' : (!$canEdit ? 'readonly' : '') }}>
                </div>

                <div>
                    <label class="text-[10px] text-slate-500">M.I.</label>
                    <input type="text"
                        name="items[{{ $idx }}][middle_initial]"
                        value="{{ $mi }}"
                        class="w-full rounded-xl border px-2 py-1.5 text-xs border-slate-200"
                        {{ $isPresRow ? 'readonly' : (!$canEdit ? 'readonly' : '') }}>
                </div>

                <div>
                    <label class="text-[10px] text-slate-500">Last Name</label>
                    <input type="text"
                        name="items[{{ $idx }}][last_name]"
                        value="{{ $last }}"
                        class="w-full rounded-xl border px-2 py-1.5 text-xs border-slate-200"
                        {{ $isPresRow ? 'readonly' : (!$canEdit ? 'readonly' : '') }}>
                </div>

            </div>

            {{-- STUDENT ID --}}
            <div class="mt-3">
                <label class="text-[10px] text-slate-500">Student ID</label>
                <input type="text"
                    name="items[{{ $idx }}][student_id_number]"
                    value="{{ $studentId }}"
                    class="w-full rounded-xl border px-2 py-1.5 text-xs border-slate-200"
                    {{ $isPresRow ? 'readonly' : (!$canEdit ? 'readonly' : '') }}>
            </div>

            {{-- COURSE + MOBILE --}}
            <div class="grid grid-cols-2 gap-2 mt-3">
                <div>
                    <label class="text-[10px] text-slate-500">Course & Year</label>
                    <input type="text"
                        name="items[{{ $idx }}][course_and_year]"
                        value="{{ old("items.$idx.course_and_year") ?? ($item->course_and_year ?? '') }}"
                        class="w-full rounded-xl border px-2 py-1.5 text-xs border-slate-200"
                        {{ !$canEdit ? 'readonly' : '' }}>
                </div>

                <div>
                    <label class="text-[10px] text-slate-500">Mobile</label>
                    <input type="text"
                        name="items[{{ $idx }}][mobile_number]"
                        value="{{ old("items.$idx.mobile_number") ?? ($item->mobile_number ?? '') }}"
                        class="w-full rounded-xl border px-2 py-1.5 text-xs border-slate-200"
                        {{ !$canEdit ? 'readonly' : '' }}>
                </div>
            </div>

            {{-- QPI --}}
            <div class="grid grid-cols-3 gap-2 mt-3">
                <div>
                    <label class="text-[10px] text-slate-500">1st Sem</label>
                    <input type="number" step="0.01" min="0" max="4"
                        name="items[{{ $idx }}][first_sem_qpi]"
                        value="{{ old("items.$idx.first_sem_qpi") ?? ($item->first_sem_qpi ?? '') }}"
                        class="w-full rounded-xl border px-2 py-1.5 text-xs border-slate-200"
                        {{ !$canEdit ? 'readonly' : '' }}>
                </div>

                <div>
                    <label class="text-[10px] text-slate-500">2nd Sem</label>
                    <input type="number" step="0.01" min="0" max="4"
                        name="items[{{ $idx }}][second_sem_qpi]"
                        value="{{ old("items.$idx.second_sem_qpi") ?? ($item->second_sem_qpi ?? '') }}"
                        class="w-full rounded-xl border px-2 py-1.5 text-xs border-slate-200"
                        {{ !$canEdit ? 'readonly' : '' }}>
                </div>

                <div>
                    <label class="text-[10px] text-slate-500">Inter</label>
                    <input type="number" step="0.01" min="0" max="4"
                        name="items[{{ $idx }}][intersession_qpi]"
                        value="{{ old("items.$idx.intersession_qpi") ?? ($item->intersession_qpi ?? '') }}"
                        class="w-full rounded-xl border px-2 py-1.5 text-xs border-slate-200"
                        {{ !$canEdit ? 'readonly' : '' }}>
                </div>
            </div>

        </div>

        @endforeach

    </div>

</div>