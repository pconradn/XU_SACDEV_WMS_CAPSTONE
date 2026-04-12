@php
    $accent = match($document?->status) {
        'returned' => 'from-rose-500 to-rose-400',
        'approved_by_sacdev' => 'from-emerald-500 to-emerald-400',
        'submitted_to_sacdev' => 'from-blue-500 to-blue-400',
        default => 'from-slate-400 to-slate-300'
    };

    $statusColor = match($document?->status) {
        'returned' => 'text-rose-700 bg-rose-50 border-rose-200',
        'approved_by_sacdev' => 'text-emerald-700 bg-emerald-50 border-emerald-200',
        'submitted_to_sacdev' => 'text-blue-700 bg-blue-50 border-blue-200',
        default => 'text-slate-600 bg-slate-50 border-slate-200'
    };
@endphp

<div class="relative overflow-hidden rounded-2xl border border-slate-200 shadow-sm">

    <div class="absolute inset-0 bg-gradient-to-br {{ $accent }} opacity-[0.08]"></div>
    <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r {{ $accent }}"></div>

    <div class="absolute top-3 right-3 flex items-center gap-2">

        <span class="inline-flex items-center gap-1 px-2 py-0.5 text-[10px] font-semibold rounded-full border {{ $statusColor }}">
            <i data-lucide="circle" class="w-2 h-2 fill-current"></i>
            {{ str_replace('_', ' ', $document?->status ?? 'draft') }}
        </span>

        <span class="inline-flex items-center gap-1 px-2 py-0.5 text-[10px] font-semibold rounded-full border border-slate-200 bg-white text-slate-600">
            <i data-lucide="layers" class="w-3 h-3"></i>
            Pre-Implementation
        </span>

    </div>

    <div class="relative px-5 py-6 flex flex-col items-center text-center gap-2">

        <div class="p-2.5 rounded-xl bg-white border border-slate-200 text-slate-700 shadow-sm">
            <i data-lucide="handshake" class="w-5 h-5"></i>
        </div>

        <span class="text-[10px] font-semibold uppercase tracking-wide text-slate-500">
            Application for Solicitation / Sponsorship
        </span>

        <h1 class="text-base md:text-lg font-semibold text-slate-900">
            {{ $project->title }}
        </h1>

        <div class="flex items-center gap-1 text-[11px] text-slate-600 justify-center max-w-xl">
            <i data-lucide="file-text" class="w-3.5 h-3.5 text-slate-500"></i>
            <span>
                Request permission to conduct solicitation activities using the name of the University.
            </span>
        </div>

        <div class="mt-3 grid grid-cols-1 md:grid-cols-4 gap-3 w-full text-center">

            <div>
                <div class="text-[10px] font-semibold text-slate-500 uppercase tracking-wide">
                    Organization
                </div>
                <div class="mt-1 text-xs font-medium text-slate-900">
                    {{ $project->organization->name ?? '—' }}
                </div>
            </div>

            <div>
                <div class="text-[10px] font-semibold text-slate-500 uppercase tracking-wide">
                    School Year
                </div>
                <div class="mt-1 text-xs font-medium text-slate-900">
                    {{ $project->schoolYear->name ?? '—' }}
                </div>
            </div>

            <div>
                <div class="text-[10px] font-semibold text-slate-500 uppercase tracking-wide">
                    Project Head
                </div>
                <div class="mt-1 text-xs font-medium text-slate-900">
                    {{ $project->projectHead?->user?->name ?? '—' }}
                </div>
            </div>

            <div>
                <div class="text-[10px] font-semibold text-slate-500 uppercase tracking-wide">
                    Implementation Date
                </div>
                <div class="mt-1 text-xs font-medium text-slate-900">
                    {{ $project->implementation_date_display ?? 'Not specified' }}
                </div>
            </div>

        </div>

    </div>

</div>