<div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm mb-5">

    <div class="flex items-start justify-between gap-3">
        <div>
            <h3 class="text-base font-semibold text-slate-900">
                Family Information
            </h3>
            <p class="mt-1 text-xs text-slate-500">
                All fields are optional
            </p>
        </div>
        <span class="text-[11px] text-slate-400">
           
        </span>
    </div>

    <div class="mt-5 space-y-6">

        <div>
            <div class="text-sm font-semibold text-slate-800 mb-3">
                Father
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">

                <div>
                    <label class="text-xs font-medium text-slate-600">
                        Name
                    </label>

                    <input type="text"
                           name="father_name"
                           value="{{ old('father_name', $registration->father_name) }}"
                           placeholder="Enter name"
                           class="mt-1 w-full rounded-lg border px-3 py-2 text-sm
                                  {{ $errors->has('father_name') ? 'border-rose-400 focus:ring-rose-200' : 'border-slate-300 focus:ring-slate-200' }}
                                  focus:ring-2 focus:outline-none"
                           {{ $isLocked ? 'disabled' : '' }}>

                    @error('father_name')
                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="text-xs font-medium text-slate-600">
                        Occupation
                    </label>

                    <input type="text"
                           name="father_occupation"
                           value="{{ old('father_occupation', $registration->father_occupation) }}"
                           placeholder="Enter occupation"
                           class="mt-1 w-full rounded-lg border px-3 py-2 text-sm
                                  {{ $errors->has('father_occupation') ? 'border-rose-400 focus:ring-rose-200' : 'border-slate-300 focus:ring-slate-200' }}
                                  focus:ring-2 focus:outline-none"
                           {{ $isLocked ? 'disabled' : '' }}>

                    @error('father_occupation')
                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="text-xs font-medium text-slate-600">
                        Mobile
                    </label>

                    <input type="text"
                           name="father_mobile"
                           value="{{ old('father_mobile', $registration->father_mobile) }}"
                           placeholder="09123456789"
                           class="mt-1 w-full rounded-lg border px-3 py-2 text-sm
                                  {{ $errors->has('father_mobile') ? 'border-rose-400 focus:ring-rose-200' : 'border-slate-300 focus:ring-slate-200' }}
                                  focus:ring-2 focus:outline-none"
                           {{ $isLocked ? 'disabled' : '' }}>

                    @error('father_mobile')
                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

            </div>
        </div>

        <div>
            <div class="text-sm font-semibold text-slate-800 mb-3">
                Mother
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">

                <div>
                    <label class="text-xs font-medium text-slate-600">
                        Name
                    </label>

                    <input type="text"
                           name="mother_name"
                           value="{{ old('mother_name', $registration->mother_name) }}"
                           placeholder="Enter name"
                           class="mt-1 w-full rounded-lg border px-3 py-2 text-sm
                                  {{ $errors->has('mother_name') ? 'border-rose-400 focus:ring-rose-200' : 'border-slate-300 focus:ring-slate-200' }}
                                  focus:ring-2 focus:outline-none"
                           {{ $isLocked ? 'disabled' : '' }}>

                    @error('mother_name')
                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="text-xs font-medium text-slate-600">
                        Occupation
                    </label>

                    <input type="text"
                           name="mother_occupation"
                           value="{{ old('mother_occupation', $registration->mother_occupation) }}"
                           placeholder="Enter occupation"
                           class="mt-1 w-full rounded-lg border px-3 py-2 text-sm
                                  {{ $errors->has('mother_occupation') ? 'border-rose-400 focus:ring-rose-200' : 'border-slate-300 focus:ring-slate-200' }}
                                  focus:ring-2 focus:outline-none"
                           {{ $isLocked ? 'disabled' : '' }}>

                    @error('mother_occupation')
                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="text-xs font-medium text-slate-600">
                        Mobile
                    </label>

                    <input type="text"
                           name="mother_mobile"
                           value="{{ old('mother_mobile', $registration->mother_mobile) }}"
                           placeholder="09123456789"
                           class="mt-1 w-full rounded-lg border px-3 py-2 text-sm
                                  {{ $errors->has('mother_mobile') ? 'border-rose-400 focus:ring-rose-200' : 'border-slate-300 focus:ring-slate-200' }}
                                  focus:ring-2 focus:outline-none"
                           {{ $isLocked ? 'disabled' : '' }}>

                    @error('mother_mobile')
                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

            </div>
        </div>

        <div>
            <div class="text-sm font-semibold text-slate-800 mb-3">
                Guardian
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">

                <div>
                    <label class="text-xs font-medium text-slate-600">
                        Name
                    </label>

                    <input type="text"
                           name="guardian_name"
                           value="{{ old('guardian_name', $registration->guardian_name) }}"
                           placeholder="Enter name"
                           class="mt-1 w-full rounded-lg border px-3 py-2 text-sm
                                  {{ $errors->has('guardian_name') ? 'border-rose-400 focus:ring-rose-200' : 'border-slate-300 focus:ring-slate-200' }}
                                  focus:ring-2 focus:outline-none"
                           {{ $isLocked ? 'disabled' : '' }}>

                    @error('guardian_name')
                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="text-xs font-medium text-slate-600">
                        Relationship
                    </label>

                    <input type="text"
                           name="guardian_relationship"
                           value="{{ old('guardian_relationship', $registration->guardian_relationship) }}"
                           placeholder="Ex: Uncle"
                           class="mt-1 w-full rounded-lg border px-3 py-2 text-sm
                                  {{ $errors->has('guardian_relationship') ? 'border-rose-400 focus:ring-rose-200' : 'border-slate-300 focus:ring-slate-200' }}
                                  focus:ring-2 focus:outline-none"
                           {{ $isLocked ? 'disabled' : '' }}>

                    @error('guardian_relationship')
                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="text-xs font-medium text-slate-600">
                        Mobile
                    </label>

                    <input type="text"
                           name="guardian_mobile"
                           value="{{ old('guardian_mobile', $registration->guardian_mobile) }}"
                           placeholder="09123456789"
                           class="mt-1 w-full rounded-lg border px-3 py-2 text-sm
                                  {{ $errors->has('guardian_mobile') ? 'border-rose-400 focus:ring-rose-200' : 'border-slate-300 focus:ring-slate-200' }}
                                  focus:ring-2 focus:outline-none"
                           {{ $isLocked ? 'disabled' : '' }}>

                    @error('guardian_mobile')
                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

            </div>
        </div>

        <div class="max-w-xs">
            <label class="text-xs font-medium text-slate-600">
                Number of Siblings
            </label>

            <input type="number"
                   name="siblings_count"
                   value="{{ old('siblings_count', $registration->siblings_count) }}"
                   placeholder="0"
                   class="mt-1 w-full rounded-lg border px-3 py-2 text-sm
                          {{ $errors->has('siblings_count') ? 'border-rose-400 focus:ring-rose-200' : 'border-slate-300 focus:ring-slate-200' }}
                          focus:ring-2 focus:outline-none"
                   {{ $isLocked ? 'disabled' : '' }}>

            @error('siblings_count')
                <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
            @enderror
        </div>

    </div>

</div>