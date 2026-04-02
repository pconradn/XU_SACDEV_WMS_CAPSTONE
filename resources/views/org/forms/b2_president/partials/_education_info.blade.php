<div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm mb-5">

    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-base font-semibold text-slate-900">
                Educational Background
            </h3>
            <p class="text-xs text-slate-500 mt-1">
                Required fields must be completed before submission
            </p>
        </div>
    </div>

    <div class="mt-5 space-y-6">

        <div>
            <div class="text-sm font-semibold text-slate-800 mb-3">
                High School
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">

                <div>
                    <label class="text-xs font-medium text-slate-600">
                        Name <span class="text-rose-500">*</span>
                    </label>

                    <input type="text"
                           name="high_school_name"
                           value="{{ old('high_school_name', $registration->high_school_name) }}"
                           placeholder="Enter school name"
                           class="mt-1 w-full rounded-lg border px-3 py-2 text-sm
                                  {{ $errors->has('high_school_name') ? 'border-rose-400 focus:ring-rose-200' : 'border-slate-300 focus:ring-slate-200' }}
                                  focus:ring-2 focus:outline-none"
                           {{ $isLocked ? 'disabled' : '' }}>

                    @error('high_school_name')
                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="text-xs font-medium text-slate-600">
                        Address <span class="text-rose-500">*</span>
                    </label>

                    <input type="text"
                           name="high_school_address"
                           value="{{ old('high_school_address', $registration->high_school_address) }}"
                           placeholder="Enter address"
                           class="mt-1 w-full rounded-lg border px-3 py-2 text-sm
                                  {{ $errors->has('high_school_address') ? 'border-rose-400 focus:ring-rose-200' : 'border-slate-300 focus:ring-slate-200' }}
                                  focus:ring-2 focus:outline-none"
                           {{ $isLocked ? 'disabled' : '' }}>

                    @error('high_school_address')
                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="text-xs font-medium text-slate-600">
                        Year Graduated <span class="text-rose-500">*</span>
                    </label>

                    <input type="text"
                           name="high_school_year_graduated"
                           value="{{ old('high_school_year_graduated', $registration->high_school_year_graduated) }}"
                           placeholder="Ex: 2022"
                           class="mt-1 w-full rounded-lg border px-3 py-2 text-sm
                                  {{ $errors->has('high_school_year_graduated') ? 'border-rose-400 focus:ring-rose-200' : 'border-slate-300 focus:ring-slate-200' }}
                                  focus:ring-2 focus:outline-none"
                           {{ $isLocked ? 'disabled' : '' }}>

                    @error('high_school_year_graduated')
                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

            </div>
        </div>

        <div>
            <div class="text-sm font-semibold text-slate-800 mb-3">
                Grade School
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">

                <div>
                    <label class="text-xs font-medium text-slate-600">
                        Name <span class="text-rose-500">*</span>
                    </label>

                    <input type="text"
                           name="grade_school_name"
                           value="{{ old('grade_school_name', $registration->grade_school_name) }}"
                           placeholder="Enter school name"
                           class="mt-1 w-full rounded-lg border px-3 py-2 text-sm
                                  {{ $errors->has('grade_school_name') ? 'border-rose-400 focus:ring-rose-200' : 'border-slate-300 focus:ring-slate-200' }}
                                  focus:ring-2 focus:outline-none"
                           {{ $isLocked ? 'disabled' : '' }}>

                    @error('grade_school_name')
                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="text-xs font-medium text-slate-600">
                        Address <span class="text-rose-500">*</span>
                    </label>

                    <input type="text"
                           name="grade_school_address"
                           value="{{ old('grade_school_address', $registration->grade_school_address) }}"
                           placeholder="Enter address"
                           class="mt-1 w-full rounded-lg border px-3 py-2 text-sm
                                  {{ $errors->has('grade_school_address') ? 'border-rose-400 focus:ring-rose-200' : 'border-slate-300 focus:ring-slate-200' }}
                                  focus:ring-2 focus:outline-none"
                           {{ $isLocked ? 'disabled' : '' }}>

                    @error('grade_school_address')
                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="text-xs font-medium text-slate-600">
                        Year Graduated <span class="text-rose-500">*</span>
                    </label>

                    <input type="text"
                           name="grade_school_year_graduated"
                           value="{{ old('grade_school_year_graduated', $registration->grade_school_year_graduated) }}"
                           placeholder="Ex: 2016"
                           class="mt-1 w-full rounded-lg border px-3 py-2 text-sm
                                  {{ $errors->has('grade_school_year_graduated') ? 'border-rose-400 focus:ring-rose-200' : 'border-slate-300 focus:ring-slate-200' }}
                                  focus:ring-2 focus:outline-none"
                           {{ $isLocked ? 'disabled' : '' }}>

                    @error('grade_school_year_graduated')
                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

            </div>
        </div>

        <div>
            <div class="text-sm font-semibold text-slate-800 mb-3">
                Scholarship
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                <div>
                    <label class="text-xs font-medium text-slate-600">
                        Scholarship Name
                    </label>

                    <input type="text"
                           name="scholarship_name"
                           value="{{ old('scholarship_name', $registration->scholarship_name) }}"
                           placeholder="Optional"
                           class="mt-1 w-full rounded-lg border px-3 py-2 text-sm
                                  {{ $errors->has('scholarship_name') ? 'border-rose-400 focus:ring-rose-200' : 'border-slate-300 focus:ring-slate-200' }}
                                  focus:ring-2 focus:outline-none"
                           {{ $isLocked ? 'disabled' : '' }}>

                    @error('scholarship_name')
                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="text-xs font-medium text-slate-600">
                        Year Granted
                    </label>

                    <input type="text"
                           name="scholarship_year_granted"
                           value="{{ old('scholarship_year_granted', $registration->scholarship_year_granted) }}"
                           placeholder="Optional"
                           class="mt-1 w-full rounded-lg border px-3 py-2 text-sm
                                  {{ $errors->has('scholarship_year_granted') ? 'border-rose-400 focus:ring-rose-200' : 'border-slate-300 focus:ring-slate-200' }}
                                  focus:ring-2 focus:outline-none"
                           {{ $isLocked ? 'disabled' : '' }}>

                    @error('scholarship_year_granted')
                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

            </div>
        </div>

    </div>

</div>