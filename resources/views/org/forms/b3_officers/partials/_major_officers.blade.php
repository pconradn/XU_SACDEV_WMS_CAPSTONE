<div class="rounded-xl border border-blue-200 bg-blue-50 p-5 shadow-sm mb-6">

    <h3 class="text-base font-semibold text-blue-900 mb-4">
        Major Officers
    </h3>

    @php
        $majorRoles = [
            'president' => 'President',
            'vice_president' => 'Vice President',
            'treasurer' => 'Treasurer',
            'auditor' => 'Auditor',
        ];

        $existing = collect($registration->items ?? [])
            ->keyBy('major_officer_role');
    @endphp

    <div class="space-y-6">

        @foreach($majorRoles as $roleKey => $systemRoleLabel)

            @php
                $item = $existing[$roleKey] ?? null;

                // Auto-fill president if not yet saved
                if (!$item && $roleKey === 'president' && isset($currentUser)) {
                    $item = (object)[
                        'position' => $systemRoleLabel,
                        'officer_name' => $currentUser->full_name ?? $currentUser->name ?? '',
                        'student_id_number' => $currentUser->officerEntries->first()?->student_id_number ?? '',
                        'course_and_year' => $currentUser->officerEntries->first()?->course_and_year ?? '',
                        'mobile_number' => $currentUser->mobile_number ?? '',
                        'first_sem_qpi' => null,
                        'second_sem_qpi' => null,
                        'intersession_qpi' => null,
                    ];
                }

                

                $idx = "major_{$roleKey}";
            @endphp

            <div class="border border-blue-200 rounded-lg p-4 bg-white">

                {{-- System Role Label --}}
                <div class="font-semibold text-sm text-blue-800 mb-3">
                    System Role: {{ $systemRoleLabel }}
                </div>

                {{-- Hidden system role --}}
                <input type="hidden"
                    name="items[{{ $idx }}][major_officer_role]"
                    value="{{ $roleKey }}">

                {{-- Row 1 --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">

                    {{-- Display Position Title --}}
                    <div>
                        <label class="block text-xs text-slate-500 mb-1">
                            Display Position Title
                        </label>

                        <input type="text"
                            name="items[{{ $idx }}][position]"
                            value="{{ $item->position ?? $systemRoleLabel }}"
                            placeholder="Ex: Prime Minister"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                            {{ $isLocked ? 'disabled' : '' }}>
                    </div>

                    {{-- Officer Name --}}
                    <div>
                        <label class="block text-xs text-slate-500 mb-1">
                            Officer Name
                        </label>

                        <input type="text"
                            name="items[{{ $idx }}][officer_name]"
                            value="{{ $item->officer_name ?? '' }}"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm {{ $roleKey === 'president' ? 'bg-slate-100' : '' }}"
                            {{ $roleKey === 'president' ? 'readonly' : '' }}
                            {{ $isLocked ? 'disabled' : '' }}>
                    </div>

                    {{-- Student ID --}}
                    <div>
                        <label class="block text-xs text-slate-500 mb-1">
                            Student ID
                        </label>

                        <input type="text"
                            name="items[{{ $idx }}][student_id_number]"
                            value="{{ $item->student_id_number ?? '' }}"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm {{ $roleKey === 'president' ? 'bg-slate-100' : '' }}"
                            {{ $roleKey === 'president' ? 'readonly' : '' }}
                            {{ $isLocked ? 'disabled' : '' }}>
                    </div>

                </div>

                {{-- Row 2 --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mt-3">

                    {{-- Course --}}
                    <div>
                        <label class="block text-xs text-slate-500 mb-1">
                            Course & Year
                        </label>

                        <input type="text"
                            name="items[{{ $idx }}][course_and_year]"
                            value="{{ $item->course_and_year ?? '' }}"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                            {{ $isLocked ? 'disabled' : '' }}>
                    </div>

                    {{-- 1st Sem QPI --}}
                    <div>
                        <label class="block text-xs text-slate-500 mb-1">
                            1st Sem QPI
                        </label>

                        <input type="number"
                            step="0.01"
                            min="0"
                            max="4"
                            name="items[{{ $idx }}][first_sem_qpi]"
                            value="{{ $item->first_sem_qpi ?? '' }}"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                            {{ $isLocked ? 'disabled' : '' }}>
                    </div>

                    {{-- 2nd Sem QPI --}}
                    <div>
                        <label class="block text-xs text-slate-500 mb-1">
                            2nd Sem QPI
                        </label>

                        <input type="number"
                            step="0.01"
                            min="0"
                            max="4"
                            name="items[{{ $idx }}][second_sem_qpi]"
                            value="{{ $item->second_sem_qpi ?? '' }}"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                            {{ $isLocked ? 'disabled' : '' }}>
                    </div>

                </div>

                {{-- Row 3 --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-3">

                    {{-- Intersession --}}
                    <div>
                        <label class="block text-xs text-slate-500 mb-1">
                            Intersession QPI
                        </label>

                        <input type="number"
                            step="0.01"
                            min="0"
                            max="4"
                            name="items[{{ $idx }}][intersession_qpi]"
                            value="{{ $item->intersession_qpi ?? '' }}"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                            {{ $isLocked ? 'disabled' : '' }}>
                    </div>

                    {{-- Mobile --}}
                    <div>
                        <label class="block text-xs text-slate-500 mb-1">
                            Mobile Number
                        </label>

                        <input type="text"
                            name="items[{{ $idx }}][mobile_number]"
                            value="{{ $item->mobile_number ?? '' }}"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                            {{ $isLocked ? 'disabled' : '' }}>
                    </div>

                </div>

            </div>

        @endforeach

    </div>

</div>