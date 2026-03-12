<div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm mb-4">
    <h3 class="text-base font-semibold text-slate-900">Educational Background</h3>

    <div class="mt-4">
        <div class="text-sm font-semibold text-slate-800">High School</div>

        <div class="mt-3 grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div>
                <label class="text-sm font-medium text-slate-700">Name</label>
                <input type="text" name="high_school_name"
                       value="{{ old('high_school_name', $registration->high_school_name) }}"
                       class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" {{ $isLocked ? 'disabled' : '' }}>
            </div>
            <div>
                <label class="text-sm font-medium text-slate-700">Address</label>
                <input type="text" name="high_school_address"
                       value="{{ old('high_school_address', $registration->high_school_address) }}"
                       class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" {{ $isLocked ? 'disabled' : '' }}>
            </div>
            <div>
                <label class="text-sm font-medium text-slate-700">Year Graduated</label>
                <input type="text" name="high_school_year_graduated"
                       value="{{ old('high_school_year_graduated', $registration->high_school_year_graduated) }}"
                       class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" {{ $isLocked ? 'disabled' : '' }}>
            </div>
        </div>
    </div>

    <div class="mt-6">
        <div class="text-sm font-semibold text-slate-800">Grade School</div>

        <div class="mt-3 grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div>
                <label class="text-sm font-medium text-slate-700">Name</label>
                <input type="text" name="grade_school_name"
                       value="{{ old('grade_school_name', $registration->grade_school_name) }}"
                       class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" {{ $isLocked ? 'disabled' : '' }}>
            </div>
            <div>
                <label class="text-sm font-medium text-slate-700">Address</label>
                <input type="text" name="grade_school_address"
                       value="{{ old('grade_school_address', $registration->grade_school_address) }}"
                       class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" {{ $isLocked ? 'disabled' : '' }}>
            </div>
            <div>
                <label class="text-sm font-medium text-slate-700">Year Graduated</label>
                <input type="text" name="grade_school_year_graduated"
                       value="{{ old('grade_school_year_graduated', $registration->grade_school_year_graduated) }}"
                       class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" {{ $isLocked ? 'disabled' : '' }}>
            </div>
        </div>
    </div>

    <div class="mt-6">
        <div class="text-sm font-semibold text-slate-800">Scholarship (if any)</div>

        <div class="mt-3 grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label class="text-sm font-medium text-slate-700">Scholarship Name</label>
                <input type="text" name="scholarship_name"
                       value="{{ old('scholarship_name', $registration->scholarship_name) }}"
                       class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" {{ $isLocked ? 'disabled' : '' }}>
            </div>
            <div>
                <label class="text-sm font-medium text-slate-700">Year Granted</label>
                <input type="text" name="scholarship_year_granted"
                       value="{{ old('scholarship_year_granted', $registration->scholarship_year_granted) }}"
                       class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" {{ $isLocked ? 'disabled' : '' }}>
            </div>
        </div>
    </div>
</div>
