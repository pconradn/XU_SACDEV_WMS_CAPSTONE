<div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">

    {{-- TITLE BAR (SUBTLE) --}}
    <div class="px-5 py-3 border-b border-slate-200 flex items-center justify-between">

        <div class="text-sm font-semibold text-slate-800 tracking-wide">
            Notice of Cancellation
        </div>

        <div class="text-[11px] text-slate-400 uppercase tracking-wide">
            Form A13
        </div>

    </div>


    {{-- META --}}
    <div class="px-5 py-5 grid grid-cols-1 md:grid-cols-2 gap-5">

        {{-- ORGANIZATION --}}
        <div>
            <div class="text-xs font-semibold text-slate-500 uppercase tracking-wide">
                Organization
            </div>

            <div class="mt-2 text-sm font-medium text-slate-900">
                {{ $project->organization->name ?? '—' }}
            </div>

            <p class="text-[11px] text-slate-400 mt-1">
                Organization responsible for the activity.
            </p>
        </div>


        {{-- ACTIVITY --}}
        <div>
            <div class="text-xs font-semibold text-slate-500 uppercase tracking-wide">
                Activity Name
            </div>

            <div class="mt-2 text-sm font-medium text-slate-900">
                {{ $project->title ?? '—' }}
            </div>

            <p class="text-[11px] text-slate-400 mt-1">
                Original project or activity being cancelled.
            </p>
        </div>

    </div>

</div>