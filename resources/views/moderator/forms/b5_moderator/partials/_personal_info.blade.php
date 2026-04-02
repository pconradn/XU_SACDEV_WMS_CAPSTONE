<div class="mt-5 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-base font-semibold text-slate-900">
                Personal Information
            </h3>
            <p class="text-xs text-slate-500 mt-1">
                Required fields must be completed before submission
            </p>
        </div>
    </div>

    <div class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-2">

        <div class="sm:col-span-2">
            <label class="text-xs font-medium text-slate-600">
                Full Name <span class="text-rose-500">*</span>
            </label>

            <input type="text"
                   name="full_name"
                   value="{{ old('full_name', $submission->full_name) }}"
                   placeholder="Enter full name"
                   class="mt-1 w-full rounded-lg border px-3 py-2 text-sm text-slate-900
                          {{ $errors->has('full_name') ? 'border-rose-400 focus:ring-rose-200' : 'border-slate-300 focus:ring-slate-200' }}
                          focus:ring-2 focus:outline-none"
                   {{ $isLocked ? 'disabled' : '' }}>

            @error('full_name')
                <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="text-xs font-medium text-slate-600">
                Birthday <span class="text-rose-500">*</span>
            </label>

            <input type="date"
                   name="birthday"
                   value="{{ old('birthday', optional($submission->birthday)->format('Y-m-d')) }}"
                   class="mt-1 w-full rounded-lg border px-3 py-2 text-sm text-slate-900
                          {{ $errors->has('birthday') ? 'border-rose-400 focus:ring-rose-200' : 'border-slate-300 focus:ring-slate-200' }}
                          focus:ring-2 focus:outline-none"
                   {{ $isLocked ? 'disabled' : '' }}>

            @error('birthday')
                <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="text-xs font-medium text-slate-600">
                Age <span class="text-rose-500">*</span>
            </label>

            <input type="number"
                   name="age"
                   min="0"
                   max="120"
                   value="{{ old('age', $submission->age) }}"
                   placeholder="Enter age"
                   class="mt-1 w-full rounded-lg border px-3 py-2 text-sm text-slate-900
                          {{ $errors->has('age') ? 'border-rose-400 focus:ring-rose-200' : 'border-slate-300 focus:ring-slate-200' }}
                          focus:ring-2 focus:outline-none"
                   {{ $isLocked ? 'disabled' : '' }}>

            @error('age')
                <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="text-xs font-medium text-slate-600">
                Sex <span class="text-rose-500">*</span>
            </label>

            <input type="text"
                   name="sex"
                   value="{{ old('sex', $submission->sex) }}"
                   placeholder="e.g., Male / Female"
                   class="mt-1 w-full rounded-lg border px-3 py-2 text-sm text-slate-900
                          {{ $errors->has('sex') ? 'border-rose-400 focus:ring-rose-200' : 'border-slate-300 focus:ring-slate-200' }}
                          focus:ring-2 focus:outline-none"
                   {{ $isLocked ? 'disabled' : '' }}>

            @error('sex')
                <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="text-xs font-medium text-slate-600">
                Religion <span class="text-rose-500">*</span>
            </label>

            <input type="text"
                   name="religion"
                   value="{{ old('religion', $submission->religion) }}"
                   placeholder="Enter religion"
                   class="mt-1 w-full rounded-lg border px-3 py-2 text-sm text-slate-900
                          {{ $errors->has('religion') ? 'border-rose-400 focus:ring-rose-200' : 'border-slate-300 focus:ring-slate-200' }}
                          focus:ring-2 focus:outline-none"
                   {{ $isLocked ? 'disabled' : '' }}>

            @error('religion')
                <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
            @enderror
        </div>

    </div>

</div>