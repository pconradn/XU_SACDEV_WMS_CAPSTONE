<div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

    {{-- MAIN TITLE --}}
    <div class="px-6 py-6 text-center">
        <h1 class="text-2xl md:text-3xl font-bold tracking-tight text-slate-900">
            Solicitation / Sponsorship Report
        </h1>
        <p class="text-sm text-slate-500 mt-1">
            Record and report all solicitation activities, sponsors, and contributions for your project
        </p>
    </div>

    {{-- SECTION LABEL --}}
    <div class="bg-slate-50 border-y border-slate-200 px-6 py-2 text-center">
        <span class="text-xs font-semibold text-slate-700 tracking-wide uppercase">
            Sponsorship Documentation
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

            {{-- APPLICATION DATE --}}
            <div>
                <div class="text-xs font-semibold text-slate-500 uppercase tracking-wide">
                    Application Date
                </div>

                <div class="mt-2 text-sm md:text-base font-medium text-slate-900">
                    {{ now()->format('F d, Y') }}
                </div>
            </div>

        </div>

    </div>

</div>