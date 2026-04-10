<div x-data="{
    editing: {{ $errors->any() ? 'true' : 'false' }},
    preview: null
}" class="space-y-5">

    {{-- HEADER --}}
    <div class="flex items-start justify-between">
        <div>
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 flex items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">
                    <i data-lucide="id-card" class="w-4 h-4"></i>
                </div>
                <div class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                    Personal Information
                </div>
            </div>
            <div class="text-[11px] text-slate-400 mt-1">
                Basic identity and personal details
            </div>
        </div>

        @if($isOwner)
            <button type="button"
                    @click="editing = !editing"
                    class="text-xs font-medium text-blue-600 hover:text-blue-700 transition">
                <span x-show="!editing">Edit</span>
                <span x-show="editing">Cancel</span>
            </button>
        @endif
    </div>


    <div class="flex items-center gap-4">

        <div class="w-20 h-20 rounded-xl border border-slate-200 overflow-hidden bg-slate-100 flex items-center justify-center">

            <template x-if="preview">
                <img :src="preview" class="w-full h-full object-cover">
            </template>

            <template x-if="!preview">
                @if($profile->photo_id_path)
                    <img src="{{ asset('storage/'.$profile->photo_id_path) }}" class="w-full h-full object-cover">
                @else
                    <i data-lucide="image" class="w-5 h-5 text-slate-400"></i>
                @endif
            </template>

        </div>

        @if($isOwner)
            <div x-show="editing" class="flex-1 space-y-1">
                <input type="file"
                       name="photo_id"
                       accept="image/*"
                       @change="preview = URL.createObjectURL($event.target.files[0])"
                       class="text-xs">

                <div class="text-[10px] text-slate-500">
                    JPG/PNG • max 2MB
                </div>
            </div>
        @endif

    </div>


    <div class="space-y-2">

        <div class="flex items-center gap-2 text-[11px] text-slate-500">
            <i data-lucide="user" class="w-3.5 h-3.5"></i>
            Full Name
        </div>

        <div class="grid grid-cols-12 gap-2">

            <input type="text"
                   name="prefix"
                   value="{{ old('prefix', $profile->prefix) }}"
                   placeholder="Prefix"
                   :readonly="!editing"
                   class="col-span-3 rounded-lg border text-sm px-2 py-1.5 bg-white focus:ring-1 focus:ring-blue-500 transition
                          {{ $errors->has('prefix') ? 'border-rose-300' : 'border-slate-200' }}
                          "
                   :class="editing ? 'bg-white' : 'bg-slate-50 text-slate-700'">

            <input type="text"
                   name="first_name"
                   value="{{ old('first_name', $profile->first_name) }}"
                   placeholder="First Name"
                   :readonly="!editing"
                   class="col-span-4 rounded-lg border text-sm px-2 py-1.5 focus:ring-1 focus:ring-blue-500 transition
                          {{ $errors->has('first_name') ? 'border-rose-300' : 'border-slate-200' }}"
                   :class="editing ? 'bg-white' : 'bg-slate-50 text-slate-700'">

            <input type="text"
                   name="middle_initial"
                   value="{{ old('middle_initial', $profile->middle_initial) }}"
                   placeholder="M.I."
                   :readonly="!editing"
                   class="col-span-2 rounded-lg border text-sm px-2 py-1.5 text-center transition
                          {{ $errors->has('middle_initial') ? 'border-rose-300' : 'border-slate-200' }}"
                   :class="editing ? 'bg-white' : 'bg-slate-50 text-slate-700'">

            <input type="text"
                   name="last_name"
                   value="{{ old('last_name', $profile->last_name) }}"
                   placeholder="Last Name"
                   :readonly="!editing"
                   class="col-span-3 rounded-lg border text-sm px-2 py-1.5 transition
                          {{ $errors->has('last_name') ? 'border-rose-300' : 'border-slate-200' }}"
                   :class="editing ? 'bg-white' : 'bg-slate-50 text-slate-700'">

        </div>

    </div>



    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

        <div class="space-y-1">
            <div class="text-[11px] text-slate-500">Birthday</div>
            <input type="date"
                   name="birthday"
                   value="{{ old('birthday', optional($profile->birthday)->format('Y-m-d')) }}"
                   :readonly="!editing"
                   class="w-full rounded-lg border border-slate-200 text-sm px-2 py-1.5 transition"
                   :class="editing ? 'bg-white' : 'bg-slate-50 text-slate-700'">
        </div>

        <div class="space-y-1">
            <div class="text-[11px] text-slate-500">Sex</div>
            <input type="text"
                   name="sex"
                   value="{{ old('sex', $profile->sex) }}"
                   :readonly="!editing"
                   class="w-full rounded-lg border border-slate-200 text-sm px-2 py-1.5 transition"
                   :class="editing ? 'bg-white' : 'bg-slate-50 text-slate-700'">
        </div>

        <div class="sm:col-span-2 space-y-1">
            <div class="text-[11px] text-slate-500">Religion</div>
            <input type="text"
                   name="religion"
                   value="{{ old('religion', $profile->religion) }}"
                   :readonly="!editing"
                   class="w-full rounded-lg border border-slate-200 text-sm px-2 py-1.5 transition"
                   :class="editing ? 'bg-white' : 'bg-slate-50 text-slate-700'">
        </div>

    </div>

</div>