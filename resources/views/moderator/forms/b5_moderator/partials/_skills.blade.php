<div class="mt-5 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-base font-semibold text-slate-900">
                Special Skills / Interests
            </h3>
            <p class="text-xs text-slate-500 mt-1">
                Required — related to the organization’s nature, scope, or advocacy
            </p>
        </div>
    </div>

    <div class="mt-5">

        <label class="text-xs font-medium text-slate-600">
            Description <span class="text-rose-500">*</span>
        </label>

        <textarea name="skills_and_interests"
                  rows="4"
                  placeholder="Ex: Leadership, student mentoring, event management, advocacy work..."
                  class="mt-1 w-full rounded-lg border px-3 py-2 text-sm text-slate-900
                         {{ $errors->has('skills_and_interests') ? 'border-rose-400 focus:ring-rose-200' : 'border-slate-300 focus:ring-slate-200' }}
                         focus:ring-2 focus:outline-none"
                  {{ $isLocked ? 'disabled' : '' }}>{{ old('skills_and_interests', $submission->skills_and_interests) }}</textarea>

        @error('skills_and_interests')
            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
        @enderror

    </div>

</div>