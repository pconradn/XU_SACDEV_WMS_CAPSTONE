<div x-data="{ editing: {{ $errors->any() ? 'true' : 'false' }} }" class="card p-4">

    <div class="flex items-center justify-between">
        <div class="card-header">Address Information</div>

        @if($isOwner)
            <button type="button"
                    @click="editing = !editing"
                    class="text-xs text-blue-600 hover:underline">
                <span x-show="!editing">Edit</span>
                <span x-show="editing">Cancel</span>
            </button>
        @endif
    </div>

    <div class="mt-4 grid grid-cols-1 gap-4">

        <div>
            <div class="text-[11px] text-slate-500">Home Address</div>
            <div x-show="!editing" class="mt-1 text-sm font-medium">
                {{ $profile->home_address ?? '—' }}
            </div>
            @if($isOwner)
                <textarea x-show="editing"
                          name="home_address"
                          rows="3"
                          class="mt-1 w-full rounded-lg border border-slate-200 text-sm">{{ old('home_address', $profile->home_address) }}</textarea>
            @endif
        </div>

        <div>
            <div class="text-[11px] text-slate-500">City Address</div>
            <div x-show="!editing" class="mt-1 text-sm font-medium">
                {{ $profile->city_address ?? '—' }}
            </div>
            @if($isOwner)
                <textarea x-show="editing"
                          name="city_address"
                          rows="3"
                          class="mt-1 w-full rounded-lg border border-slate-200 text-sm">{{ old('city_address', $profile->city_address) }}</textarea>
            @endif
        </div>

    </div>

</div>