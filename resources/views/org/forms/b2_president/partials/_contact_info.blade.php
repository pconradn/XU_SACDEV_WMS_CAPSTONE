<div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm mb-5">

    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-base font-semibold text-slate-900">
                Contact Information
            </h3>
            <p class="text-xs text-slate-500 mt-1">
                Required fields must be completed before submission
            </p>
        </div>
    </div>

    <div class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-2">

        <div>
            <label class="text-xs font-medium text-slate-600">
                Mobile Number <span class="text-rose-500">*</span>
            </label>

            <input type="text"
                   name="mobile_number"
                   value="{{ old('mobile_number', $registration->mobile_number) }}"
                   placeholder="Ex: 09123456789"
                   class="mt-1 w-full rounded-lg border px-3 py-2 text-sm text-slate-900
                          {{ $errors->has('mobile_number') ? 'border-rose-400 focus:ring-rose-200' : 'border-slate-300 focus:ring-slate-200' }}
                          focus:ring-2 focus:outline-none"
                   {{ $isLocked ? 'disabled' : '' }}>

            @error('mobile_number')
                <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="text-xs font-medium text-slate-600">
                Email <span class="text-rose-500">*</span>
            </label>

            <input type="email"
                   name="email"
                   value="{{ old('email', $registration->email) }}"
                   placeholder="Enter email"
                   class="mt-1 w-full rounded-lg border px-3 py-2 text-sm text-slate-900
                          {{ $errors->has('email') ? 'border-rose-400 focus:ring-rose-200' : 'border-slate-300 focus:ring-slate-200' }}
                          focus:ring-2 focus:outline-none"
                   {{ $isLocked ? 'disabled' : '' }}>

            @error('email')
                <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="text-xs font-medium text-slate-600">
                ID Number <span class="text-rose-500">*</span>
            </label>

            <input type="text"
                   name="id_number"
                   value="{{ old('id_number', $registration->id_number) }}"
                   placeholder="Enter ID number"
                   class="mt-1 w-full rounded-lg border px-3 py-2 text-sm text-slate-900
                          {{ $errors->has('id_number') ? 'border-rose-400 focus:ring-rose-200' : 'border-slate-300 focus:ring-slate-200' }}
                          focus:ring-2 focus:outline-none"
                   {{ $isLocked ? 'disabled' : '' }}>

            @error('id_number')
                <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="text-xs font-medium text-slate-600">
                City Landline <span class="text-rose-500">*</span>
            </label>

            <input type="text"
                   name="city_landline"
                   value="{{ old('city_landline', $registration->city_landline) }}"
                   placeholder="Enter landline"
                   class="mt-1 w-full rounded-lg border px-3 py-2 text-sm text-slate-900
                          {{ $errors->has('city_landline') ? 'border-rose-400 focus:ring-rose-200' : 'border-slate-300 focus:ring-slate-200' }}
                          focus:ring-2 focus:outline-none"
                   {{ $isLocked ? 'disabled' : '' }}>

            @error('city_landline')
                <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="text-xs font-medium text-slate-600">
                Provincial Landline (optional)
            </label>

            <input type="text"
                   name="provincial_landline"
                   value="{{ old('provincial_landline', $registration->provincial_landline) }}"
                   placeholder="Optional"
                   class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900
                          focus:ring-2 focus:ring-slate-200 focus:outline-none"
                   {{ $isLocked ? 'disabled' : '' }}>
        </div>

        <div>
            <label class="text-xs font-medium text-slate-600">
                Facebook URL (optional)
            </label>

            <input type="url"
                   name="facebook_url"
                   value="{{ old('facebook_url', $registration->facebook_url) }}"
                   placeholder="https://facebook.com/..."
                   class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900
                          focus:ring-2 focus:ring-slate-200 focus:outline-none"
                   {{ $isLocked ? 'disabled' : '' }}>
        </div>

        <div class="sm:col-span-2 grid grid-cols-1 gap-5 sm:grid-cols-2">

            <div>
                <label class="text-xs font-medium text-slate-600">
                    Complete Home Address
                </label>

                <textarea name="home_address" rows="3"
                          placeholder="Enter full home address"
                          class="mt-1 w-full rounded-lg border px-3 py-2 text-sm text-slate-900
                                 {{ $errors->has('home_address') ? 'border-rose-400 focus:ring-rose-200' : 'border-slate-300 focus:ring-slate-200' }}
                                 focus:ring-2 focus:outline-none"
                          {{ $isLocked ? 'disabled' : '' }}>{{ old('home_address', $registration->home_address) }}</textarea>

                @error('home_address')
                    <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="text-xs font-medium text-slate-600">
                    Complete City Address
                </label>

                <textarea name="city_address" rows="3"
                          placeholder="Enter city address"
                          class="mt-1 w-full rounded-lg border px-3 py-2 text-sm text-slate-900
                                 {{ $errors->has('city_address') ? 'border-rose-400 focus:ring-rose-200' : 'border-slate-300 focus:ring-slate-200' }}
                                 focus:ring-2 focus:outline-none"
                          {{ $isLocked ? 'disabled' : '' }}>{{ old('city_address', $registration->city_address) }}</textarea>

                @error('city_address')
                    <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                @enderror
            </div>

        </div>

    </div>

</div>