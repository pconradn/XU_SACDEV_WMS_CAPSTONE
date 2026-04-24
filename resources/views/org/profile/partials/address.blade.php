<div class="space-y-5">

    <div class="flex items-start justify-between">
        <div>
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 flex items-center justify-center rounded-lg bg-amber-50 text-amber-600">
                    <i data-lucide="map-pin" class="w-4 h-4"></i>
                </div>
                <div class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                    Address Information
                </div>
            </div>
            <div class="text-[11px] text-slate-400 mt-1">
                Permanent and current location details
            </div>
        </div>
    </div>

    @if($errors->any())
        <div class="rounded-xl border border-rose-200 bg-rose-50 px-3 py-2 text-[11px] text-rose-700">
            Please check your address details.
        </div>
    @endif

    <div class="space-y-4">

        <div class="space-y-1">
            <div class="flex items-center gap-1 text-[11px] text-slate-500">
                <i data-lucide="home" class="w-3.5 h-3.5"></i>
                Home Address
            </div>

            <textarea
                name="home_address"
                rows="2"
                placeholder="Enter full home address"
                :readonly="!editingAll"
                @input="$dispatch('mark-dirty', 'address')"
                class="w-full rounded-lg border text-sm px-2 py-1.5 transition resize-none
                       {{ $errors->has('home_address') ? 'border-rose-300' : 'border-slate-200' }}"
                :class="editingAll ? 'bg-white focus:ring-1 focus:ring-blue-500' : 'bg-slate-50 text-slate-700'"
            >{{ old('home_address', $profile->home_address) }}</textarea>
        </div>

        <div class="space-y-1">
            <div class="flex items-center gap-1 text-[11px] text-slate-500">
                <i data-lucide="building-2" class="w-3.5 h-3.5"></i>
                City Address
            </div>

            <textarea
                name="city_address"
                rows="2"
                placeholder="Enter current city address"
                :readonly="!editingAll"
                @input="$dispatch('mark-dirty', 'address')"
                class="w-full rounded-lg border text-sm px-2 py-1.5 transition resize-none
                       {{ $errors->has('city_address') ? 'border-rose-300' : 'border-slate-200' }}"
                :class="editingAll ? 'bg-white focus:ring-1 focus:ring-blue-500' : 'bg-slate-50 text-slate-700'"
            >{{ old('city_address', $profile->city_address) }}</textarea>
        </div>

    </div>

</div>