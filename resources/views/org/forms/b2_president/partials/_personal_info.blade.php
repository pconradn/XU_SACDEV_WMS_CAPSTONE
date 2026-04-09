<div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm mb-5">

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
                   value="{{ old('full_name', $registration->full_name) }}"
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
                Course & Year <span class="text-rose-500">*</span>
            </label>

            <input type="text"
                   name="course_and_year"
                   value="{{ old('course_and_year', $registration->course_and_year) }}"
                   placeholder="Ex: BSIT 3"
                   class="mt-1 w-full rounded-lg border px-3 py-2 text-sm text-slate-900
                          {{ $errors->has('course_and_year') ? 'border-rose-400 focus:ring-rose-200' : 'border-slate-300 focus:ring-slate-200' }}
                          focus:ring-2 focus:outline-none"
                   {{ $isLocked ? 'disabled' : '' }}>

            @error('course_and_year')
                <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
            @enderror
        </div>

        <div x-data="{
                birthday: '{{ old('birthday', optional($registration->birthday)->format('Y-m-d')) }}',
                age: null,
                calcAge() {
                    if (!this.birthday) {
                        this.age = null;
                        return;
                    }
                    const today = new Date();
                    const birth = new Date(this.birthday);
                    let age = today.getFullYear() - birth.getFullYear();
                    const m = today.getMonth() - birth.getMonth();
                    if (m < 0 || (m === 0 && today.getDate() < birth.getDate())) {
                        age--;
                    }
                    this.age = age;
                }
            }"
            x-init="calcAge()"
            class="contents"
        >

            <!-- Birthday -->
            <div>
                <label class="text-xs font-medium text-slate-600">
                    Birthday <span class="text-rose-500">*</span>
                </label>

                <input type="date"
                    name="birthday"
                    x-model="birthday"
                    @change="calcAge()"
                    class="mt-1 w-full rounded-lg border px-3 py-2 text-sm text-slate-900
                            {{ $errors->has('birthday') ? 'border-rose-400 focus:ring-rose-200' : 'border-slate-300 focus:ring-slate-200' }}
                            focus:ring-2 focus:outline-none"
                    {{ $isLocked ? 'disabled' : '' }}>

                @error('birthday')
                    <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Age -->
            <div>
                <label class="text-xs font-medium text-slate-600">
                    Age
                </label>

                <input type="text"
                    x-model="age"
                    placeholder="Auto-calculated"
                    class="mt-1 w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700
                            focus:outline-none"
                    readonly>
            </div>

        </div>

        <div>
            <label class="text-xs font-medium text-slate-600">
                Sex <span class="text-rose-500">*</span>
            </label>

            <input type="text"
                   name="sex"
                   value="{{ old('sex', $registration->sex) }}"
                   placeholder="Ex: Male / Female"
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
                   value="{{ old('religion', $registration->religion) }}"
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