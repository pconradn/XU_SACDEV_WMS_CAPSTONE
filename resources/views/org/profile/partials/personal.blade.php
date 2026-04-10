
<div x-data="{ editing: {{ $errors->any() ? 'true' : 'false' }},
    preview: null
}" class="card p-4">

    <div class="flex items-center justify-between">
        <div class="card-header">Personal Information</div>

        @if($isOwner)
            <button type="button"
                    @click="editing = !editing"
                    class="text-xs text-blue-600 hover:underline">
                <span x-show="!editing">Edit</span>
                <span x-show="editing">Cancel</span>
            </button>
        @endif
    </div>

    {{-- PHOTO ID --}}
    <div class="mt-4 flex items-center gap-4">

        <div class="w-20 h-20 rounded-xl border border-slate-200 overflow-hidden bg-slate-100 flex items-center justify-center">

            {{-- PREVIEW PRIORITY --}}
            <template x-if="preview">
                <img :src="preview" class="w-full h-full object-cover">
            </template>

            <template x-if="!preview">
                @if($profile->photo_id_path)
                    <img src="{{ asset('storage/'.$profile->photo_id_path) }}"
                         class="w-full h-full object-cover">
                @else
                    <span class="text-xs text-slate-400">No Photo</span>
                @endif
            </template>

        </div>

        @if($isOwner)
            <div x-show="editing" class="flex-1">
                <input type="file"
                       name="photo_id"
                       accept="image/*"
                       @change="preview = URL.createObjectURL($event.target.files[0])"
                       class="text-xs">

                <div class="text-[10px] text-slate-500 mt-1">
                    JPG or PNG, max 2MB
                </div>
            </div>
        @endif

    </div>

    <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">

        <div>
            <div class="text-[11px] text-slate-500">Prefix</div>
            <div x-show="!editing" class="mt-1 text-sm font-medium">
                {{ $profile->prefix ?? '—' }}
            </div>
            @if($isOwner)
                <input x-show="editing" type="text" name="prefix"
                       value="{{ old('prefix', $profile->prefix) }}"
                       class="mt-1 w-full rounded-lg border border-slate-200 text-sm">
            @endif
        </div>

        <div>
            <div class="text-[11px] text-slate-500">First Name</div>
            <div x-show="!editing" class="mt-1 text-sm font-medium">
                {{ $profile->first_name ?? '—' }}
            </div>
            @if($isOwner)
                <input x-show="editing" type="text" name="first_name"
                       value="{{ old('first_name', $profile->first_name) }}"
                       class="mt-1 w-full rounded-lg border border-slate-200 text-sm">
            @endif
        </div>

        <div>
            <div class="text-[11px] text-slate-500">Middle Initial</div>
            <div x-show="!editing" class="mt-1 text-sm font-medium">
                {{ $profile->middle_initial ?? '—' }}
            </div>
            @if($isOwner)
                <input x-show="editing" type="text" name="middle_initial"
                       value="{{ old('middle_initial', $profile->middle_initial) }}"
                       class="mt-1 w-full rounded-lg border border-slate-200 text-sm">
            @endif
        </div>

        <div>
            <div class="text-[11px] text-slate-500">Last Name</div>
            <div x-show="!editing" class="mt-1 text-sm font-medium">
                {{ $profile->last_name ?? '—' }}
            </div>
            @if($isOwner)
                <input x-show="editing" type="text" name="last_name"
                       value="{{ old('last_name', $profile->last_name) }}"
                       class="mt-1 w-full rounded-lg border border-slate-200 text-sm">
            @endif
        </div>

        <div>
            <div class="text-[11px] text-slate-500">Birthday</div>
            <div x-show="!editing" class="mt-1 text-sm font-medium">
                {{ $profile->birthday?->format('M d, Y') ?? '—' }}
            </div>
            @if($isOwner)
                <input x-show="editing" type="date" name="birthday"
                       value="{{ old('birthday', optional($profile->birthday)->format('Y-m-d')) }}"
                       class="mt-1 w-full rounded-lg border border-slate-200 text-sm">
            @endif
        </div>

        <div>
            <div class="text-[11px] text-slate-500">Sex</div>
            <div x-show="!editing" class="mt-1 text-sm font-medium">
                {{ $profile->sex ?? '—' }}
            </div>
            @if($isOwner)
                <input x-show="editing" type="text" name="sex"
                       value="{{ old('sex', $profile->sex) }}"
                       class="mt-1 w-full rounded-lg border border-slate-200 text-sm">
            @endif
        </div>

        <div class="sm:col-span-2">
            <div class="text-[11px] text-slate-500">Religion</div>
            <div x-show="!editing" class="mt-1 text-sm font-medium">
                {{ $profile->religion ?? '—' }}
            </div>
            @if($isOwner)
                <input x-show="editing" type="text" name="religion"
                       value="{{ old('religion', $profile->religion) }}"
                       class="mt-1 w-full rounded-lg border border-slate-200 text-sm">
            @endif
        </div>

    </div>

</div>