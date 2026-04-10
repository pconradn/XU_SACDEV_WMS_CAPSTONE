@php
    $hasProfile = !empty($submission->org_name)
        && !empty($submission->mission)
        && !empty($submission->vision);

    $hasProjects = $submission->projects->count() > 0;
    $hasFunds = $submission->fundSources->count() > 0;

    $ready = $hasProfile && $hasProjects && $hasFunds;
@endphp

<form method="POST" action="{{ route('org.rereg.b1.submit') }}">
    @csrf

    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

        {{-- HEADER --}}
        <div class="px-6 py-4 border-b border-slate-200 bg-gradient-to-b from-slate-50 to-white">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-semibold text-slate-900">
                        Submit to Moderator
                    </h2>
                    <p class="text-xs text-slate-500 mt-1">
                        Once submitted, editing will be locked until returned
                    </p>
                </div>

                <span class="text-[10px] px-2 py-0.5 rounded-md font-semibold
                    {{ $ready ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                    {{ $ready ? 'Ready' : 'Incomplete' }}
                </span>
            </div>
        </div>

        <div class="px-6 py-6 space-y-5">

            {{-- STATUS CHECKLIST --}}
            <div class="space-y-2 text-xs">

                <div class="flex items-center justify-between">
                    <span>Organization Profile</span>
                    <span class="{{ $hasProfile ? 'text-emerald-600' : 'text-rose-600' }}">
                        {{ $hasProfile ? 'Complete' : 'Missing' }}
                    </span>
                </div>

                <div class="flex items-center justify-between">
                    <span>Projects</span>
                    <span class="{{ $hasProjects ? 'text-emerald-600' : 'text-rose-600' }}">
                        {{ $hasProjects ? 'Added' : 'None' }}
                    </span>
                </div>

                <div class="flex items-center justify-between">
                    <span>Sources of Funds</span>
                    <span class="{{ $hasFunds ? 'text-emerald-600' : 'text-rose-600' }}">
                        {{ $hasFunds ? 'Added' : 'None' }}
                    </span>
                </div>

            </div>

            {{-- CONFIRM --}}
            <div class="flex items-start gap-3 pt-3 border-t border-slate-200">
                <input
                    id="confirmSubmit"
                    type="checkbox"
                    name="confirm"
                    value="yes"
                    class="mt-1 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                    {{ old('confirm') === 'yes' ? 'checked' : '' }}
                >

                <label for="confirmSubmit" class="text-xs text-slate-700">
                    I confirm that the Strategic Plan is complete and ready for review.
                </label>
            </div>

            @error('confirm')
                <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror

        </div>

        {{-- ACTION --}}
        <div class="border-t border-slate-200 bg-white px-6 py-4 flex justify-between items-center">

            <p class="text-xs {{ $ready ? 'text-emerald-600' : 'text-rose-600' }}">
                {{ $ready
                    ? 'All requirements completed. Ready to submit.'
                    : 'Complete all sections before submitting.' }}
            </p>

            <button
                type="submit"
                class="inline-flex items-center px-4 py-2 text-xs font-semibold rounded-lg text-white
                    {{ $ready ? 'bg-emerald-600 hover:bg-emerald-700' : 'bg-slate-300 cursor-not-allowed' }}"
                {{ $ready ? '' : 'disabled' }}
            >
                Submit
            </button>

        </div>

    </div>
</form>