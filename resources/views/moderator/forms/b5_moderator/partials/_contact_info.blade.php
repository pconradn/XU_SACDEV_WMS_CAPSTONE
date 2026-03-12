<div class="mt-4 rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
    <h3 class="text-base font-semibold text-slate-900">Contact Information</h3>

    <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div>
            <label class="block text-sm font-medium text-slate-700">Mobile Number</label>
            <input type="text" name="mobile_number" value="{{ old('mobile_number', $submission->mobile_number) }}"
                   class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                   {{ $isLocked ? 'disabled' : '' }}>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700">Email</label>
            <input type="email" name="email" value="{{ old('email', $submission->email) }}"
                   class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                   {{ $isLocked ? 'disabled' : '' }}>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700">Landline</label>
            <input type="text" name="landline" value="{{ old('landline', $submission->landline) }}"
                   class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                   {{ $isLocked ? 'disabled' : '' }}>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700">Facebook URL (optional)</label>
            <input type="url" name="facebook_url" value="{{ old('facebook_url', $submission->facebook_url) }}"
                   class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                   placeholder="https://facebook.com/..."
                   {{ $isLocked ? 'disabled' : '' }}>
        </div>

        <div class="sm:col-span-2">
            <label class="block text-sm font-medium text-slate-700">Complete City Address</label>
            <textarea name="city_address" rows="3"
                      class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                      {{ $isLocked ? 'disabled' : '' }}>{{ old('city_address', $submission->city_address) }}</textarea>
        </div>
    </div>
</div>
