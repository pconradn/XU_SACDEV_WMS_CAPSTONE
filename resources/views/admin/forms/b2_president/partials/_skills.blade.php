@if(!empty($registration->skills_and_interests))

<div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm overflow-hidden">

    {{-- HEADER --}}
    <div class="px-5 py-3 border-b border-slate-200 flex items-start justify-between gap-4">

        <div>
            <h3 class="text-[11px] font-semibold text-slate-500 uppercase tracking-wide">
                Skills and Interests
            </h3>

            <p class="mt-1 text-xs text-slate-500 max-w-md">
                Declared skills, hobbies, and personal interests.
            </p>
        </div>

        <div class="flex items-center gap-2 text-[11px] font-medium">

            <span class="flex h-5 w-5 items-center justify-center rounded-full bg-slate-100">
                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
            </span>

            <span class="text-emerald-700">
                Provided
            </span>

        </div>

    </div>


    {{-- CONTENT --}}
    <div class="p-5">

        <div class="text-xs text-slate-800 whitespace-pre-line leading-relaxed">
            {{ $registration->skills_and_interests }}
        </div>

    </div>

</div>

@endif