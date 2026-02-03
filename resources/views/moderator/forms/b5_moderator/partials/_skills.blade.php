<div class="mt-4 rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
    <h3 class="text-base font-semibold text-slate-900">Special Skills / Interests</h3>
    <p class="mt-1 text-sm text-slate-600">
        Related to the nature, scope, cause, or advocacy of the nominating organization.
    </p>

    <div class="mt-4">
        <textarea name="skills_and_interests" rows="4"
                  class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                  {{ $isLocked ? 'disabled' : '' }}>{{ old('skills_and_interests', $submission->skills_and_interests) }}</textarea>
    </div>
</div>
