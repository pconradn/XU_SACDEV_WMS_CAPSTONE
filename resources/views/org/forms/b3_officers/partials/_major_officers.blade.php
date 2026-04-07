<div class="rounded-2xl border border-blue-200 bg-gradient-to-br from-blue-50 to-white p-6 shadow-sm mb-6">

    <div class="flex items-center justify-between mb-5">
        <div>
            <h3 class="text-base font-semibold text-blue-900">
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
            ->keyBy('major_officer_role');
    @endphp

    <div class="space-y-6">

        @foreach($majorRoles as $roleKey => $systemRoleLabel)

            @php
                $item = $existing[$roleKey] ?? null;

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
                $hasError = collect($errors->get("items.$idx.*"))->flatten()->isNotEmpty();
            @endphp

            <div class="border rounded-xl p-5 bg-white shadow-sm
                {{ $hasError ? 'border-rose-300 bg-rose-50/40' : 'border-blue-200' }}">

                <div class="flex items-center justify-between mb-4">
                    <div class="text-sm font-semibold text-blue-800">
                        {{ $systemRoleLabel }}
                    </div>

                    <div class="text-[11px] text-slate-400">
                        System Role
                    </div>
                </div>

                <input type="hidden"
                    name="items[{{ $idx }}][major_officer_role]"
                    value="{{ $roleKey }}">

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">
                            Display Position Title
                        </label>

                        <input type="text"
                            name="items[{{ $idx }}][position]"
                            value="{{ old("items.$idx.position", $item->position ?? $systemRoleLabel) }}"
                            placeholder="Ex: Prime Minister"
                            class="w-full rounded-lg border px-3 py-2 text-sm
                                {{ $errors->has("items.$idx.position") ? 'border-rose-400 focus:ring-rose-200' : 'border-slate-300 focus:ring-blue-200' }}
                                focus:ring-2 focus:outline-none"
                            {{ $isLocked ? 'disabled' : '' }}>

                        @error("items.$idx.position")
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">
                            Officer Name
                        </label>

                        <input type="text"
                            name="items[{{ $idx }}][officer_name]"
                            value="{{ old("items.$idx.officer_name", $item->officer_name ?? '') }}"
                            class="w-full rounded-lg border px-3 py-2 text-sm
                                {{ $roleKey === 'president' ? 'bg-slate-100' : '' }}
                                {{ $errors->has("items.$idx.officer_name") ? 'border-rose-400 focus:ring-rose-200' : 'border-slate-300 focus:ring-blue-200' }}
                                focus:ring-2 focus:outline-none"
                            {{ $roleKey === 'president' ? 'readonly' : '' }}
                            {{ $isLocked ? 'disabled' : '' }}>

                        @error("items.$idx.officer_name")
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">
                            Student ID
                        </label>

                        <input type="text"
                            inputmode="numeric"
                            pattern="\d*"
                            name="items[{{ $idx }}][student_id_number]"
                            value="{{ old("items.$idx.student_id_number", $item->student_id_number ?? '') }}"
                            class="w-full rounded-lg border px-3 py-2 text-sm
                                {{ $roleKey === 'president' ? 'bg-slate-100' : '' }}
                                {{ $errors->has("items.$idx.student_id_number") ? 'border-rose-400 focus:ring-rose-200' : 'border-slate-300 focus:ring-blue-200' }}
                                focus:ring-2 focus:outline-none"
                            {{ $roleKey === 'president' ? 'readonly' : '' }}
                            {{ $isLocked ? 'disabled' : '' }}>

                        @error("items.$idx.student_id_number")
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">

                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">
                            Course & Year
                        </label>

                        <input type="text"
                            name="items[{{ $idx }}][course_and_year]"
                            value="{{ old("items.$idx.course_and_year", $item->course_and_year ?? '') }}"
                            class="w-full rounded-lg border px-3 py-2 text-sm
                                {{ $errors->has("items.$idx.course_and_year") ? 'border-rose-400 focus:ring-rose-200' : 'border-slate-300 focus:ring-blue-200' }}
                                focus:ring-2 focus:outline-none"
                            {{ $isLocked ? 'disabled' : '' }}>

                        @error("items.$idx.course_and_year")
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">
                            1st Sem QPI
                        </label>

                        <input type="number"
                            step="0.01"
                            min="0"
                            max="4"
                            name="items[{{ $idx }}][first_sem_qpi]"
                            value="{{ old("items.$idx.first_sem_qpi", $item->first_sem_qpi ?? '') }}"
                            class="w-full rounded-lg border px-3 py-2 text-sm
                                {{ $errors->has("items.$idx.first_sem_qpi") ? 'border-rose-400 focus:ring-rose-200' : 'border-slate-300 focus:ring-blue-200' }}
                                focus:ring-2 focus:outline-none"
                            {{ $isLocked ? 'disabled' : '' }}>

                        @error("items.$idx.first_sem_qpi")
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">
                            2nd Sem QPI
                        </label>

                        <input type="number"
                            step="0.01"
                            min="0"
                            max="4"
                            name="items[{{ $idx }}][second_sem_qpi]"
                            value="{{ old("items.$idx.second_sem_qpi", $item->second_sem_qpi ?? '') }}"
                            class="w-full rounded-lg border px-3 py-2 text-sm
                                {{ $errors->has("items.$idx.second_sem_qpi") ? 'border-rose-400 focus:ring-rose-200' : 'border-slate-300 focus:ring-blue-200' }}
                                focus:ring-2 focus:outline-none"
                            {{ $isLocked ? 'disabled' : '' }}>

                        @error("items.$idx.second_sem_qpi")
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">

                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">
                            Intersession QPI
                        </label>

                        <input type="number"
                            step="0.01"
                            min="0"
                            max="4"
                            name="items[{{ $idx }}][intersession_qpi]"
                            value="{{ old("items.$idx.intersession_qpi", $item->intersession_qpi ?? '') }}"
                            class="w-full rounded-lg border px-3 py-2 text-sm
                                {{ $errors->has("items.$idx.intersession_qpi") ? 'border-rose-400 focus:ring-rose-200' : 'border-slate-300 focus:ring-blue-200' }}
                                focus:ring-2 focus:outline-none"
                            {{ $isLocked ? 'disabled' : '' }}>

                        @error("items.$idx.intersession_qpi")
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">
                            Mobile Number
                        </label>

                        <input type="text"
                            name="items[{{ $idx }}][mobile_number]"
                            value="{{ old("items.$idx.mobile_number", $item->mobile_number ?? '') }}"
                            class="w-full rounded-lg border px-3 py-2 text-sm
                                {{ $errors->has("items.$idx.mobile_number") ? 'border-rose-400 focus:ring-rose-200' : 'border-slate-300 focus:ring-blue-200' }}
                                focus:ring-2 focus:outline-none"
                            {{ $isLocked ? 'disabled' : '' }}>

                        @error("items.$idx.mobile_number")
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

            </div>

        @endforeach

    </div>

</div>