
@php
    $accent = match($document?->status) {
        'returned' => 'bg-rose-500',
        'approved_by_sacdev' => 'bg-emerald-500',
        'submitted' => 'bg-blue-500',
        default => 'bg-slate-400'
    };
@endphp




<div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

    {{-- ACCENT --}}
    <div class="h-1  {{ $accent }}"></div>

    {{-- TOP LABEL --}}
    <div class="px-6 pt-4 flex justify-end">
        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wide">
            Form A4
        </span>
    </div>

    {{-- TITLE --}}
    <div class="px-6 pb-6 text-center">
        <h1 class="text-2xl md:text-3xl font-bold tracking-tight text-slate-900">
            Liquidation Report
        </h1>
        <p class="text-sm text-slate-500 mt-1">
            Provide a detailed breakdown of actual expenses and financial reconciliation of the project
        </p>
    </div>

    {{-- SECTION LABEL --}}
    <div class="bg-slate-50 border-y border-slate-200 px-6 py-2 text-center">
        <span class="text-xs font-semibold text-slate-700 tracking-wide uppercase">
            Project Financial Summary
        </span>
    </div>

    {{-- PROJECT TITLE --}}
    <div class="px-6 py-6 text-center">
        <div class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">
            Project Title
        </div>

        <h2 class="text-xl md:text-2xl font-semibold text-slate-900 tracking-tight">
            {{ $project->title }}
        </h2>
    </div>

    {{-- META ROW --}}
    <div class="border-t border-slate-200 px-6 py-6">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">

            <div>
                <div class="text-xs font-semibold text-slate-500 uppercase tracking-wide">
                    Organization
                </div>

                <div class="mt-2 text-sm md:text-base font-medium text-slate-900">
                    {{ $project->organization->name ?? '—' }}
                </div>
            </div>

            <div>
                <div class="text-xs font-semibold text-slate-500 uppercase tracking-wide">
                    Implementation Date
                </div>

                <div class="mt-2 text-sm md:text-base font-medium text-slate-900">
                    {{ optional($project->proposalDocument?->proposalData?->start_date)
                        ? \Carbon\Carbon::parse($project->proposalDocument->proposalData->start_date)->format('F d, Y')
                        : '—' }}
                </div>
            </div>

            <div>
                <div class="text-xs font-semibold text-slate-500 uppercase tracking-wide">
                    Project Head
                </div>

                <div class="mt-2 text-sm md:text-base font-medium text-slate-900">
                    {{ $project->projectHead?->user?->name ?? '—' }}
                </div>
            </div>

        </div>

    </div>

    {{-- SECOND ROW --}}
    <div class="border-t border-slate-200 px-6 py-6">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center items-start">

            {{-- CONTACT NUMBER --}}
            <div>
                <div class="text-xs font-semibold text-slate-500 uppercase tracking-wide">
                    Contact Number
                </div>

                <div class="mt-2">
                    <input
                        type="text"
                        name="contact_number"
                        value="{{ old('contact_number', $report->contact_number ?? $project->projectHead->user->officerEntries->first()->mobile_number ?? '') }}"
                        placeholder="Enter contact number"
                        class="w-full md:w-[220px] text-center rounded-lg px-3 py-2 text-sm border
                            {{ $errors->has('contact_number')
                                ? 'border-rose-500 focus:ring-rose-500'
                                : 'border-slate-300 focus:ring-blue-500' }}
                            focus:ring-2 focus:outline-none">
                </div>

                <p class="text-[11px] text-slate-400 mt-1">
                    Active contact for verification if needed.
                </p>
            </div>

            {{-- REPORT DATE --}}
            <div>
                <div class="text-xs font-semibold text-slate-500 uppercase tracking-wide">
                    Report Date
                </div>

                <div class="mt-2 text-sm md:text-base font-semibold text-slate-900">
                    {{ now()->format('F d, Y') }}
                </div>
            </div>

        </div>

    </div>

</div>