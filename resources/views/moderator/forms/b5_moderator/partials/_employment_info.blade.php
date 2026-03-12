<div class="mt-4 rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
    <h3 class="text-base font-semibold text-slate-900">Employment Information</h3>

    <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div>
            <label class="block text-sm font-medium text-slate-700">Official University Designation</label>
            <input type="text" name="university_designation" value="{{ old('university_designation', $submission->university_designation) }}"
                   class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                   {{ $isLocked ? 'disabled' : '' }}>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700">Unit / College / Department</label>
            <input type="text" name="unit_department" value="{{ old('unit_department', $submission->unit_department) }}"
                   class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                   {{ $isLocked ? 'disabled' : '' }}>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700">Employment Status</label>
            <input type="text" name="employment_status" value="{{ old('employment_status', $submission->employment_status) }}"
                   class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                   placeholder="e.g., Full-time / Part-time / Contractual"
                   {{ $isLocked ? 'disabled' : '' }}>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700">Years of Service</label>
            <input type="number" min="0" max="80" name="years_of_service" value="{{ old('years_of_service', $submission->years_of_service) }}"
                   class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                   {{ $isLocked ? 'disabled' : '' }}>
        </div>
    </div>
</div>
