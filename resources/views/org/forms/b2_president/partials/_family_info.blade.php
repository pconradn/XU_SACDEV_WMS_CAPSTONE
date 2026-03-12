<div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm mb-4">
    <div class="flex items-start justify-between gap-3">
        <div>
            <h3 class="text-base font-semibold text-slate-900">Family Information</h3>
            <p class="mt-1 text-sm text-slate-600">Optional for now (nullable fields).</p>
        </div>
        <span class="text-xs text-slate-500">You can leave this blank.</span>
    </div>

    <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-3">
        <div class="sm:col-span-3">
            <div class="text-sm font-semibold text-slate-800">Father</div>
        </div>

        <div>
            <label class="text-sm font-medium text-slate-700">Name</label>
            <input type="text" name="father_name" value="{{ old('father_name', $registration->father_name) }}"
                   class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" {{ $isLocked ? 'disabled' : '' }}>
        </div>
        <div>
            <label class="text-sm font-medium text-slate-700">Occupation</label>
            <input type="text" name="father_occupation" value="{{ old('father_occupation', $registration->father_occupation) }}"
                   class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" {{ $isLocked ? 'disabled' : '' }}>
        </div>
        <div>
            <label class="text-sm font-medium text-slate-700">Mobile</label>
            <input type="text" name="father_mobile" value="{{ old('father_mobile', $registration->father_mobile) }}"
                   class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" {{ $isLocked ? 'disabled' : '' }}>
        </div>

        <div class="sm:col-span-3 mt-2">
            <div class="text-sm font-semibold text-slate-800">Mother</div>
        </div>

        <div>
            <label class="text-sm font-medium text-slate-700">Name</label>
            <input type="text" name="mother_name" value="{{ old('mother_name', $registration->mother_name) }}"
                   class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" {{ $isLocked ? 'disabled' : '' }}>
        </div>
        <div>
            <label class="text-sm font-medium text-slate-700">Occupation</label>
            <input type="text" name="mother_occupation" value="{{ old('mother_occupation', $registration->mother_occupation) }}"
                   class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" {{ $isLocked ? 'disabled' : '' }}>
        </div>
        <div>
            <label class="text-sm font-medium text-slate-700">Mobile</label>
            <input type="text" name="mother_mobile" value="{{ old('mother_mobile', $registration->mother_mobile) }}"
                   class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" {{ $isLocked ? 'disabled' : '' }}>
        </div>

        <div class="sm:col-span-3 mt-2">
            <div class="text-sm font-semibold text-slate-800">Guardian (if not living with parents)</div>
        </div>

        <div>
            <label class="text-sm font-medium text-slate-700">Name</label>
            <input type="text" name="guardian_name" value="{{ old('guardian_name', $registration->guardian_name) }}"
                   class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" {{ $isLocked ? 'disabled' : '' }}>
        </div>
        <div>
            <label class="text-sm font-medium text-slate-700">Relationship (NA if not applicable)</label>
            <input type="text" name="guardian_relationship" value="{{ old('guardian_relationship', $registration->guardian_relationship) }}"
                   class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" {{ $isLocked ? 'disabled' : '' }}>
        </div>
        <div>
            <label class="text-sm font-medium text-slate-700">Mobile</label>
            <input type="text" name="guardian_mobile" value="{{ old('guardian_mobile', $registration->guardian_mobile) }}"
                   class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" {{ $isLocked ? 'disabled' : '' }}>
        </div>

        <div class="sm:col-span-1">
            <label class="text-sm font-medium text-slate-700">Number of Siblings</label>
            <input type="number" name="siblings_count" value="{{ old('siblings_count', $registration->siblings_count) }}"
                   class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" {{ $isLocked ? 'disabled' : '' }}>
        </div>
    </div>
</div>
