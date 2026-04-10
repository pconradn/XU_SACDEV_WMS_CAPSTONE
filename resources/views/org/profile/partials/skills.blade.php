<div x-data="{ editing: {{ $errors->any() ? 'true' : 'false' }} }" class="card p-4">

    <div class="flex items-center justify-between">
        <div class="card-header">Skills & Interests</div>

        @if($isOwner)
            <button type="button"
                    @click="editing = !editing"
                    class="text-xs text-blue-600 hover:underline">
                <span x-show="!editing">Edit</span>
                <span x-show="editing">Cancel</span>
            </button>
        @endif
    </div>

    <div class="mt-4">

        {{-- VIEW MODE --}}
        <div x-show="!editing" class="text-sm text-slate-700 whitespace-pre-line">
            {{ $profile->skills_and_interests ?? '—' }}
        </div>

        {{-- EDIT MODE --}}
        @if($isOwner)
            <textarea x-show="editing"
                      name="skills_and_interests"
                      rows="4"
                      class="w-full rounded-lg border border-slate-200 text-sm"
                      placeholder="Enter skills, interests, or areas of expertise...">{{ old('skills_and_interests', $profile->skills_and_interests) }}</textarea>
        @endif

    </div>

</div>