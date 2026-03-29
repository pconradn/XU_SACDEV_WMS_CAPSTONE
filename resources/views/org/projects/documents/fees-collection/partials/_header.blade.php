<div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

    {{-- TOP LABEL (FORM CODE) --}}
    <div class="px-6 pt-4 flex justify-end">
        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wide">
            Form A3
        </span>
    </div>

    {{-- MAIN TITLE --}}
    <div class="px-6 pb-6 text-center">
        <h1 class="text-2xl md:text-3xl font-bold tracking-tight text-slate-900">
            Fees Collection Report
        </h1>
        <p class="text-sm text-slate-500 mt-1">
            Record and summarize all collected fees, contributions, and financial inflows related to the project
        </p>
    </div>

    {{-- SECTION LABEL --}}
    <div class="bg-slate-50 border-y border-slate-200 px-6 py-2 text-center">
        <span class="text-xs font-semibold text-slate-700 tracking-wide uppercase">
            Project Financial Overview
        </span>
    </div>

    {{-- PROJECT TITLE --}}
    <div class="px-6 py-6">
        <div class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2 text-center">
            Project Title
        </div>

        <div class="text-center">
            <h2 class="text-xl md:text-2xl font-semibold text-slate-900 tracking-tight">
                {{ $project->title }}
            </h2>
        </div>
    </div>

    {{-- META ROW --}}
    <div class="border-t border-slate-200 px-6 py-6">

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 text-center">

            {{-- ORGANIZATION --}}
            <div>
                <div class="text-xs font-semibold text-slate-500 uppercase tracking-wide">
                    Organization
                </div>

                <div class="mt-2 text-sm md:text-base font-medium text-slate-900">
                    {{ $project->organization->name ?? '—' }}
                </div>
            </div>

            {{-- SCHOOL YEAR --}}
            <div>
                <div class="text-xs font-semibold text-slate-500 uppercase tracking-wide">
                    School Year
                </div>

                <div class="mt-2 text-sm md:text-base font-medium text-slate-900">
                    {{ $project->schoolYear->name ?? '—' }}
                </div>
            </div>

            {{-- PROJECT HEAD --}}
            <div>
                <div class="text-xs font-semibold text-slate-500 uppercase tracking-wide">
                    Project Head
                </div>

                <div class="mt-2 text-sm md:text-base font-medium text-slate-900">
                    {{ $project->projectHead?->user?->name ?? '—' }}
                </div>
            </div>

            {{-- REPORT DATE --}}
            <div>
                <div class="text-xs font-semibold text-slate-500 uppercase tracking-wide">
                    Report Date
                </div>

                <div class="mt-2 text-sm md:text-base font-medium text-slate-900">
                    {{ now()->format('F d, Y') }}
                </div>
            </div>

        </div>

    </div>

</div>