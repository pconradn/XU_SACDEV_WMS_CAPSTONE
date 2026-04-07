<div class="mt-5 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

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
                   value="{{ old('mobile_number', $submission->mobile_number) }}"
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
                   value="{{ old('email', $submission->email) }}"
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
                Landline (optional)
            </label>

            <input type="text"
                   name="landline"
                   value="{{ old('landline', $submission->landline) }}"
                   placeholder="Enter landline"
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
                   value="{{ old('facebook_url', $submission->facebook_url) }}"
                   placeholder="https://facebook.com/..."
                   class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900
                          focus:ring-2 focus:ring-slate-200 focus:outline-none"
                   {{ $isLocked ? 'disabled' : '' }}>
        </div>

        <div class="sm:col-span-2">
            <label class="text-xs font-medium text-slate-600">
                Complete City Address <span class="text-rose-500">*</span>
            </label>

            <textarea name="city_address"
                      rows="3"
                      placeholder="Enter full address"
                      class="mt-1 w-full rounded-lg border px-3 py-2 text-sm text-slate-900
                             {{ $errors->has('city_address') ? 'border-rose-400 focus:ring-rose-200' : 'border-slate-300 focus:ring-slate-200' }}
                             focus:ring-2 focus:outline-none"
                      {{ $isLocked ? 'disabled' : '' }}>{{ old('city_address', $submission->city_address) }}</textarea>

            @error('city_address')
                <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
            @enderror
        </div>

    </div>

</div>