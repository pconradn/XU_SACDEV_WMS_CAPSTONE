<div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm mb-4">
    <h3 class="text-base font-semibold text-slate-900">Contact Information</h3>

    <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div>
            <label class="text-sm font-medium text-slate-700">Mobile Number</label>
            <input type="text" name="mobile_number"
                   value="{{ old('mobile_number', $registration->mobile_number) }}"
                   class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-slate-400 focus:ring-2 focus:ring-slate-200
                          @error('mobile_number') border-red-300 ring-1 ring-red-200 @enderror"
                   {{ $isLocked ? 'disabled' : '' }}>
            @error('mobile_number') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="text-sm font-medium text-slate-700">Email</label>
            <input type="email" name="email"
                   value="{{ old('email', $registration->email) }}"
                   class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-slate-400 focus:ring-2 focus:ring-slate-200
                          @error('email') border-red-300 ring-1 ring-red-200 @enderror"
                   {{ $isLocked ? 'disabled' : '' }}>
            @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="text-sm font-medium text-slate-700">ID Number</label>
            <input type="text" name="id_number"
                   value="{{ old('id_number', $registration->id_number) }}"
                   class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-slate-400 focus:ring-2 focus:ring-slate-200
                          @error('id_number') border-red-300 ring-1 ring-red-200 @enderror"
                   {{ $isLocked ? 'disabled' : '' }}>
            @error('id_number') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="text-sm font-medium text-slate-700">City Landline (optional)</label>
            <input type="text" name="city_landline"
                   value="{{ old('city_landline', $registration->city_landline) }}"
                   class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-slate-400 focus:ring-2 focus:ring-slate-200"
                   {{ $isLocked ? 'disabled' : '' }}>
        </div>

        <div>
            <label class="text-sm font-medium text-slate-700">Provincial Landline (optional)</label>
            <input type="text" name="provincial_landline"
                   value="{{ old('provincial_landline', $registration->provincial_landline) }}"
                   class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-slate-400 focus:ring-2 focus:ring-slate-200"
                   {{ $isLocked ? 'disabled' : '' }}>
        </div>

        <div>
            <label class="text-sm font-medium text-slate-700">Facebook URL (optional)</label>
            <input type="url" name="facebook_url"
                   value="{{ old('facebook_url', $registration->facebook_url) }}"
                   class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-slate-400 focus:ring-2 focus:ring-slate-200"
                   {{ $isLocked ? 'disabled' : '' }}>
        </div>

        <div class="sm:col-span-2 grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label class="text-sm font-medium text-slate-700">Complete Home Address</label>
                <textarea name="home_address" rows="3"
                          class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-slate-400 focus:ring-2 focus:ring-slate-200
                                 @error('home_address') border-red-300 ring-1 ring-red-200 @enderror"
                          {{ $isLocked ? 'disabled' : '' }}>{{ old('home_address', $registration->home_address) }}</textarea>
                @error('home_address') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="text-sm font-medium text-slate-700">Complete City Address</label>
                <textarea name="city_address" rows="3"
                          class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-slate-400 focus:ring-2 focus:ring-slate-200
                                 @error('city_address') border-red-300 ring-1 ring-red-200 @enderror"
                          {{ $isLocked ? 'disabled' : '' }}>{{ old('city_address', $registration->city_address) }}</textarea>
                @error('city_address') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>
</div>
