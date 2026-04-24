<div class="space-y-5">

    <div class="flex items-start justify-between">
        <div>
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 flex items-center justify-center rounded-lg bg-purple-50 text-purple-600">
                    <i data-lucide="shield-check" class="w-4 h-4"></i>
                </div>
                <div class="text-xs font-semibold text-purple-700 uppercase tracking-wide">
                    Moderator Information
                </div>
            </div>
            <div class="text-[11px] text-slate-400 mt-1">
                Academic and institutional details for moderator role
            </div>
        </div>
    </div>

    @if($errors->any())
        <div class="rounded-xl border border-rose-200 bg-rose-50 px-3 py-2 text-[11px] text-rose-700">
            Please review moderator details.
        </div>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

        <div class="space-y-1">
            <div class="flex items-center gap-1 text-[11px] text-slate-500">
                <i data-lucide="briefcase" class="w-3.5 h-3.5"></i>
                University Designation
            </div>

            <input type="text"
                   name="university_designation"
                   value="{{ old('university_designation', $profile->university_designation) }}"
                   placeholder="e.g. Instructor, Professor"
                   :readonly="!editingAll"
                   @input="$dispatch('mark-dirty', 'moderator')"
                   class="w-full rounded-lg border text-sm px-2 py-1.5 transition
                          {{ $errors->has('university_designation') ? 'border-rose-300' : 'border-slate-200' }}"
                   :class="editingAll ? 'bg-white focus:ring-1 focus:ring-purple-500' : 'bg-slate-50 text-slate-700'">
        </div>

        <div class="space-y-1">
            <div class="flex items-center gap-1 text-[11px] text-slate-500">
                <i data-lucide="building" class="w-3.5 h-3.5"></i>
                Unit / Department
            </div>

            <input type="text"
                   name="unit_department"
                   value="{{ old('unit_department', $profile->unit_department) }}"
                   placeholder="Department or unit"
                   :readonly="!editingAll"
                   @input="$dispatch('mark-dirty', 'moderator')"
                   class="w-full rounded-lg border text-sm px-2 py-1.5 transition
                          {{ $errors->has('unit_department') ? 'border-rose-300' : 'border-slate-200' }}"
                   :class="editingAll ? 'bg-white focus:ring-1 focus:ring-purple-500' : 'bg-slate-50 text-slate-700'">
        </div>

        <div class="space-y-1">
            <div class="flex items-center gap-1 text-[11px] text-slate-500">
                <i data-lucide="user-check" class="w-3.5 h-3.5"></i>
                Employment Status
            </div>

            <input type="text"
                   name="employment_status"
                   value="{{ old('employment_status', $profile->employment_status) }}"
                   placeholder="Full-time / Part-time"
                   :readonly="!editingAll"
                   @input="$dispatch('mark-dirty', 'moderator')"
                   class="w-full rounded-lg border text-sm px-2 py-1.5 transition
                          {{ $errors->has('employment_status') ? 'border-rose-300' : 'border-slate-200' }}"
                   :class="editingAll ? 'bg-white focus:ring-1 focus:ring-purple-500' : 'bg-slate-50 text-slate-700'">
        </div>

        <div class="space-y-1">
            <div class="flex items-center gap-1 text-[11px] text-slate-500">
                <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                Years of Service
            </div>

            <input type="number"
                   name="years_of_service"
                   value="{{ old('years_of_service', $profile->years_of_service) }}"
                   placeholder="Years"
                   :readonly="!editingAll"
                   @input="$dispatch('mark-dirty', 'moderator')"
                   class="w-full rounded-lg border text-sm px-2 py-1.5 transition
                          {{ $errors->has('years_of_service') ? 'border-rose-300' : 'border-slate-200' }}"
                   :class="editingAll ? 'bg-white focus:ring-1 focus:ring-purple-500' : 'bg-slate-50 text-slate-700'">
        </div>

    </div>

</div>