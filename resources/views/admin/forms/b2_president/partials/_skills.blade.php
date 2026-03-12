<div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm mb-4">

    <div class="flex items-start justify-between gap-3">

        <div>

            <h3 class="text-base font-semibold text-slate-900">
                Skills and Interests
            </h3>

            <p class="mt-1 text-sm text-slate-600">
                Declared skills, hobbies, and personal interests.
            </p>

        </div>


        {{-- Status indicator --}}
        <div class="flex items-center gap-2 text-sm font-medium text-slate-800">

            <span class="flex h-6 w-6 items-center justify-center rounded-full bg-slate-100">

                <span class="h-2.5 w-2.5 rounded-full
                    {{ !empty($registration->skills_and_interests) ? 'bg-emerald-500' : 'bg-slate-400' }}">
                </span>

            </span>

            <span>
                {{ !empty($registration->skills_and_interests) ? 'Provided' : 'No entry' }}
            </span>

        </div>

    </div>



    {{-- Content --}}
    <div class="mt-4">

        @if(!empty($registration->skills_and_interests))

            <div class="rounded-lg border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 whitespace-pre-line">

                {{ $registration->skills_and_interests }}

            </div>

        @else

            <div class="rounded-lg border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-500">

                No skills or interests were submitted.

            </div>

        @endif

    </div>

</div>