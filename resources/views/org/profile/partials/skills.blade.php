<div class="space-y-5">

    <div class="flex items-start justify-between">
        <div>
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 flex items-center justify-center rounded-lg bg-purple-50 text-purple-600">
                    <i data-lucide="sparkles" class="w-4 h-4"></i>
                </div>
                <div class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                    Skills & Interests
                </div>
            </div>
            <div class="text-[11px] text-slate-400 mt-1">
                Areas of expertise, passions, and personal strengths
            </div>
        </div>
    </div>

    @if($errors->any())
        <div class="rounded-xl border border-rose-200 bg-rose-50 px-3 py-2 text-[11px] text-rose-700">
            Please review your skills and interests.
        </div>
    @endif

    <div class="space-y-2">

        <div class="flex items-center gap-1 text-[11px] text-slate-500">
            <i data-lucide="brain" class="w-3.5 h-3.5"></i>
            Description
        </div>

        <textarea
            name="skills_and_interests"
            rows="4"
            placeholder="Enter skills, interests, or areas of expertise..."
            :readonly="!editingAll"
            @input="$dispatch('mark-dirty', 'skills')"
            class="w-full rounded-lg border text-sm px-2 py-2 transition resize-none leading-relaxed
                   {{ $errors->has('skills_and_interests') ? 'border-rose-300' : 'border-slate-200' }}"
            :class="editingAll ? 'bg-white focus:ring-1 focus:ring-blue-500' : 'bg-slate-50 text-slate-700'"
        >{{ old('skills_and_interests', $profile->skills_and_interests) }}</textarea>

    </div>

</div>