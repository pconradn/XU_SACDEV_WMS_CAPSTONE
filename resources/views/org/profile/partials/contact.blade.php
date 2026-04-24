<div class="space-y-5">

    <div class="flex items-start justify-between">
        <div>
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                    <i data-lucide="phone" class="w-4 h-4"></i>
                </div>
                <div class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                    Contact Information
                </div>
            </div>
            <div class="text-[11px] text-slate-400 mt-1">
                Reachable contact details and links
            </div>
        </div>
    </div>

    @if($errors->any())
        <div class="rounded-xl border border-rose-200 bg-rose-50 px-3 py-2 text-[11px] text-rose-700">
            Please check your contact details.
        </div>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

        <div class="space-y-1">
            <div class="flex items-center gap-1 text-[11px] text-slate-500">
                <i data-lucide="smartphone" class="w-3.5 h-3.5"></i>
                Mobile Number
            </div>

            <input type="text"
                   name="mobile_number"
                   value="{{ old('mobile_number', $profile->mobile_number) }}"
                   placeholder="09XXXXXXXXX"
                   :readonly="!editingAll"
                   @input="$dispatch('mark-dirty', 'contact')"
                   class="w-full rounded-lg border text-sm px-2 py-1.5 transition
                          {{ $errors->has('mobile_number') ? 'border-rose-300' : 'border-slate-200' }}"
                   :class="editingAll ? 'bg-white focus:ring-1 focus:ring-blue-500' : 'bg-slate-50 text-slate-700'">
        </div>

        <div class="space-y-1">
            <div class="flex items-center gap-1 text-[11px] text-slate-500">
                <i data-lucide="mail" class="w-3.5 h-3.5"></i>
                Email
            </div>

            <input type="email"
                   name="email"
                   value="{{ old('email', $profile->email) }}"
                   placeholder="example@email.com"
                   :readonly="!editingAll"
                   @input="$dispatch('mark-dirty', 'contact')"
                   class="w-full rounded-lg border text-sm px-2 py-1.5 transition
                          {{ $errors->has('email') ? 'border-rose-300' : 'border-slate-200' }}"
                   :class="editingAll ? 'bg-white focus:ring-1 focus:ring-blue-500' : 'bg-slate-50 text-slate-700'">
        </div>

        <div class="space-y-1">
            <div class="flex items-center gap-1 text-[11px] text-slate-500">
                <i data-lucide="phone-call" class="w-3.5 h-3.5"></i>
                Landline
            </div>

            <input type="text"
                   name="landline"
                   value="{{ old('landline', $profile->landline) }}"
                   placeholder="Optional"
                   :readonly="!editingAll"
                   @input="$dispatch('mark-dirty', 'contact')"
                   class="w-full rounded-lg border text-sm px-2 py-1.5 transition
                          {{ $errors->has('landline') ? 'border-rose-300' : 'border-slate-200' }}"
                   :class="editingAll ? 'bg-white focus:ring-1 focus:ring-blue-500' : 'bg-slate-50 text-slate-700'">
        </div>

        <div class="space-y-1">
            <div class="flex items-center gap-1 text-[11px] text-slate-500">
                <i data-lucide="facebook" class="w-3.5 h-3.5"></i>
                Facebook URL
            </div>

            <input type="url"
                   name="facebook_url"
                   value="{{ old('facebook_url', $profile->facebook_url) }}"
                   placeholder="https://facebook.com/..."
                   :readonly="!editingAll"
                   @input="$dispatch('mark-dirty', 'contact')"
                   class="w-full rounded-lg border text-sm px-2 py-1.5 transition
                          {{ $errors->has('facebook_url') ? 'border-rose-300' : 'border-slate-200' }}"
                   :class="editingAll ? 'bg-white focus:ring-1 focus:ring-blue-500' : 'bg-slate-50 text-slate-700'">
        </div>

    </div>

</div>