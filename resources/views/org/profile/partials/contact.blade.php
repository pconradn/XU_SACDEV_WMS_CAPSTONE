<div x-data="{ editing: {{ $errors->any() ? 'true' : 'false' }} }" class="card p-4">

    <div class="flex items-center justify-between">
        <div class="card-header">Contact Information</div>

        @if($isOwner)
            <button type="button"
                    @click="editing = !editing"
                    class="text-xs text-blue-600 hover:underline">
                <span x-show="!editing">Edit</span>
                <span x-show="editing">Cancel</span>
            </button>
        @endif
    </div>

    <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">

        <div>
            <div class="text-[11px] text-slate-500">Mobile Number</div>
            <div x-show="!editing" class="mt-1 text-sm font-medium">
                {{ $profile->mobile_number ?? '—' }}
            </div>
            @if($isOwner)
                <input x-show="editing" type="text" name="mobile_number"
                       value="{{ old('mobile_number', $profile->mobile_number) }}"
                       class="mt-1 w-full rounded-lg border border-slate-200 text-sm">
            @endif
        </div>

        <div>
            <div class="text-[11px] text-slate-500">Email</div>
            <div x-show="!editing" class="mt-1 text-sm font-medium">
                {{ $profile->email ?? '—' }}
            </div>
            @if($isOwner)
                <input x-show="editing" type="email" name="email"
                       value="{{ old('email', $profile->email) }}"
                       class="mt-1 w-full rounded-lg border border-slate-200 text-sm">
            @endif
        </div>

        <div>
            <div class="text-[11px] text-slate-500">Landline</div>
            <div x-show="!editing" class="mt-1 text-sm font-medium">
                {{ $profile->landline ?? '—' }}
            </div>
            @if($isOwner)
                <input x-show="editing" type="text" name="landline"
                       value="{{ old('landline', $profile->landline) }}"
                       class="mt-1 w-full rounded-lg border border-slate-200 text-sm">
            @endif
        </div>

        <div>
            <div class="text-[11px] text-slate-500">Facebook URL</div>
            <div x-show="!editing" class="mt-1 text-sm font-medium break-all">
                {{ $profile->facebook_url ?? '—' }}
            </div>
            @if($isOwner)
                <input x-show="editing" type="url" name="facebook_url"
                       value="{{ old('facebook_url', $profile->facebook_url) }}"
                       class="mt-1 w-full rounded-lg border border-slate-200 text-sm">
            @endif
        </div>

    </div>

</div>