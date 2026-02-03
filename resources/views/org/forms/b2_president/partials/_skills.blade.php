<div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm mb-4">
    <h3 class="text-base font-semibold text-slate-900">Skills and Interests</h3>
    <p class="mt-1 text-sm text-slate-600">You may list skills, hobbies, and interests.</p>

    <div class="mt-4">
        <textarea name="skills_and_interests" rows="4"
                  class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-slate-400 focus:ring-2 focus:ring-slate-200"
                  {{ $isLocked ? 'disabled' : '' }}>{{ old('skills_and_interests', $registration->skills_and_interests) }}</textarea>
    </div>
</div>
