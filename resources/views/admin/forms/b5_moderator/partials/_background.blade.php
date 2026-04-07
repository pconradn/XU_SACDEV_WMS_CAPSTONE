<div class="mt-4 rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm overflow-hidden">

    {{-- HEADER --}}
    <div class="px-5 py-3 border-b border-slate-200 flex items-center gap-2">
        <i data-lucide="shield" class="w-4 h-4 text-slate-400"></i>
        <h3 class="text-[11px] font-semibold text-slate-500 uppercase tracking-wide">
            Moderator Background
        </h3>
    </div>


    {{-- CONTENT --}}
    <div class="grid md:grid-cols-2 divide-y md:divide-y-0 md:divide-x divide-slate-100">

        {{-- LEFT --}}
        <div class="px-5 py-4 space-y-3 text-xs">

            <div class="flex justify-between items-center">
                <span class="text-slate-400">Was moderator before</span>

                @if($submission->was_moderator_before)
                    <span class="px-2 py-0.5 text-[10px] rounded-full 
                        bg-emerald-50 text-emerald-700 border border-emerald-200">
                        Yes
                    </span>
                @else
                    <span class="px-2 py-0.5 text-[10px] rounded-full 
                        bg-slate-100 text-slate-600 border border-slate-200">
                        No
                    </span>
                @endif
            </div>

            <div class="flex justify-between items-center">
                <span class="text-slate-400">Organization</span>
                <span class="text-slate-900 font-medium text-right">
                    {{ $submission->moderated_org_name ?? '—' }}
                </span>
            </div>

        </div>


        {{-- RIGHT --}}
        <div class="px-5 py-4 space-y-3 text-xs">

            <div class="flex justify-between items-center">
                <span class="text-slate-400">Served nominating org</span>

                @if($submission->served_nominating_org_before)
                    <span class="px-2 py-0.5 text-[10px] rounded-full 
                        bg-emerald-50 text-emerald-700 border border-emerald-200">
                        Yes
                    </span>
                @else
                    <span class="px-2 py-0.5 text-[10px] rounded-full 
                        bg-slate-100 text-slate-600 border border-slate-200">
                        No
                    </span>
                @endif
            </div>

            <div class="flex justify-between items-center">
                <span class="text-slate-400">Years</span>
                <span class="text-slate-900 font-medium">
                    {{ $submission->served_nominating_org_years ?? '—' }}
                </span>
            </div>

        </div>

    </div>


    {{-- SKILLS --}}
    <div class="border-t border-slate-200 px-5 py-4">

        <div class="flex items-center gap-2 mb-2">
            <i data-lucide="sparkles" class="w-4 h-4 text-slate-400"></i>
            <h3 class="text-[11px] font-semibold text-slate-500 uppercase tracking-wide">
                Special Skills / Interests
            </h3>
        </div>

        <div class="text-xs text-slate-800 whitespace-pre-line leading-relaxed bg-white rounded-lg border border-slate-200 px-3 py-2">
            {{ $submission->skills_and_interests ?? '—' }}
        </div>

    </div>

</div>