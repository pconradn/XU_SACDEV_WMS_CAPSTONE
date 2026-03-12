<div class="mt-4 rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
    <h3 class="text-base font-semibold text-slate-900">Personal Information</h3>

    <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div class="sm:col-span-2">
            <label class="block text-sm font-medium text-slate-700">Full Name</label>
            <input type="text" name="full_name" value="{{ old('full_name', $submission->full_name) }}"
                   class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                   {{ $isLocked ? 'disabled' : '' }}>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700">Birthday</label>
            <input type="date" name="birthday" value="{{ old('birthday', optional($submission->birthday)->format('Y-m-d')) }}"
                   class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                   {{ $isLocked ? 'disabled' : '' }}>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700">Age (optional)</label>
            <input type="number" min="0" max="120" name="age" value="{{ old('age', $submission->age) }}"
                   class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                   {{ $isLocked ? 'disabled' : '' }}>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700">Sex</label>
            <input type="text" name="sex" value="{{ old('sex', $submission->sex) }}"
                   class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                   placeholder="e.g., Male/Female"
                   {{ $isLocked ? 'disabled' : '' }}>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700">Religion</label>
            <input type="text" name="religion" value="{{ old('religion', $submission->religion) }}"
                   class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                   {{ $isLocked ? 'disabled' : '' }}>
        </div>
    </div>
</div>
