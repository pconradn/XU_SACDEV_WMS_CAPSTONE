<div x-data="{ editing: {{ $errors->any() ? 'true' : 'false' }} }" class="card p-4 border-purple-200">

    <div class="flex items-center justify-between">
        <div class="card-header text-purple-700">Moderator Information</div>

        @if($isOwner)
            <button type="button"
                    @click="editing = !editing"
                    class="text-xs text-purple-600 hover:underline">
                <span x-show="!editing">Edit</span>
                <span x-show="editing">Cancel</span>
            </button>
        @endif
    </div>

    <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">

        <div>
            <div class="text-[11px] text-slate-500">University Designation</div>
            <div x-show="!editing" class="mt-1 text-sm font-medium">
                {{ $profile->university_designation ?? '—' }}
            </div>
            @if($isOwner)
                <input x-show="editing"
                       type="text"
                       name="university_designation"
                       value="{{ old('university_designation', $profile->university_designation) }}"
                       class="mt-1 w-full rounded-lg border border-slate-200 text-sm">
            @endif
        </div>

        <div>
            <div class="text-[11px] text-slate-500">Unit / Department</div>
            <div x-show="!editing" class="mt-1 text-sm font-medium">
                {{ $profile->unit_department ?? '—' }}
            </div>
            @if($isOwner)
                <input x-show="editing"
                       type="text"
                       name="unit_department"
                       value="{{ old('unit_department', $profile->unit_department) }}"
                       class="mt-1 w-full rounded-lg border border-slate-200 text-sm">
            @endif
        </div>

        <div>
            <div class="text-[11px] text-slate-500">Employment Status</div>
            <div x-show="!editing" class="mt-1 text-sm font-medium">
                {{ $profile->employment_status ?? '—' }}
            </div>
            @if($isOwner)
                <input x-show="editing"
                       type="text"
                       name="employment_status"
                       value="{{ old('employment_status', $profile->employment_status) }}"
                       class="mt-1 w-full rounded-lg border border-slate-200 text-sm">
            @endif
        </div>

        <div>
            <div class="text-[11px] text-slate-500">Years of Service</div>
            <div x-show="!editing" class="mt-1 text-sm font-medium">
                {{ $profile->years_of_service ?? '—' }}
            </div>
            @if($isOwner)
                <input x-show="editing"
                       type="number"
                       name="years_of_service"
                       value="{{ old('years_of_service', $profile->years_of_service) }}"
                       class="mt-1 w-full rounded-lg border border-slate-200 text-sm">
            @endif
        </div>

    </div>

</div>