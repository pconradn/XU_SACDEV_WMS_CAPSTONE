<div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm mb-4">

    <h3 class="text-base font-semibold text-slate-900">
        Personal Information
    </h3>

    <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">


        {{-- Full Name (full width) --}}
        <div class="sm:col-span-2">

            <label class="text-sm font-medium text-slate-700">
                Full Name
            </label>

            <input type="text"
                   name="full_name"
                   value="{{ old('full_name', $registration->full_name) }}"
                   class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900
                          focus:border-slate-400 focus:ring-2 focus:ring-slate-200
                          @error('full_name') border-red-300 ring-1 ring-red-200 @enderror"
                   {{ $isLocked ? 'disabled' : '' }}>

            @error('full_name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror

        </div>



        {{-- Course & Year --}}
        <div>

            <label class="text-sm font-medium text-slate-700">
                Course & Year
            </label>

            <input type="text"
                   name="course_and_year"
                   value="{{ old('course_and_year', $registration->course_and_year) }}"
                   class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900
                          focus:border-slate-400 focus:ring-2 focus:ring-slate-200
                          @error('course_and_year') border-red-300 ring-1 ring-red-200 @enderror"
                   {{ $isLocked ? 'disabled' : '' }}>

            @error('course_and_year')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror

        </div>



        {{-- Birthday --}}
        <div>

            <label class="text-sm font-medium text-slate-700">
                Birthday
            </label>

            <input type="date"
                   name="birthday"
                   value="{{ old('birthday', optional($registration->birthday)->format('Y-m-d')) }}"
                   class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900
                          focus:border-slate-400 focus:ring-2 focus:ring-slate-200
                          @error('birthday') border-red-300 ring-1 ring-red-200 @enderror"
                   {{ $isLocked ? 'disabled' : '' }}>

            @error('birthday')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror

        </div>



        {{-- Age --}}
        <div>

            <label class="text-sm font-medium text-slate-700">
                Age (optional)
            </label>

            <input type="number"
                   name="age"
                   value="{{ old('age', $registration->age) }}"
                   class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900
                          focus:border-slate-400 focus:ring-2 focus:ring-slate-200"
                   {{ $isLocked ? 'disabled' : '' }}>

        </div>



        {{-- Sex --}}
        <div>

            <label class="text-sm font-medium text-slate-700">
                Sex
            </label>

            <input type="text"
                   name="sex"
                   value="{{ old('sex', $registration->sex) }}"
                   class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900
                          focus:border-slate-400 focus:ring-2 focus:ring-slate-200
                          @error('sex') border-red-300 ring-1 ring-red-200 @enderror"
                   {{ $isLocked ? 'disabled' : '' }}>

            @error('sex')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror

        </div>



        {{-- Religion --}}
        <div>

            <label class="text-sm font-medium text-slate-700">
                Religion
            </label>

            <input type="text"
                   name="religion"
                   value="{{ old('religion', $registration->religion) }}"
                   class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900
                          focus:border-slate-400 focus:ring-2 focus:ring-slate-200"
                   {{ $isLocked ? 'disabled' : '' }}>

        </div>


    </div>

</div>