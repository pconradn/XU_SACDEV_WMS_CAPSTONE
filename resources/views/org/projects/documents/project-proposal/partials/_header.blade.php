@php
    // OPTIONAL: dynamic accent based on status
    $accent = match($document?->status) {
        'returned' => 'bg-rose-500',
        'approved_by_sacdev' => 'bg-emerald-500',
        'submitted' => 'bg-blue-500',
        default => 'bg-slate-400'
    };
@endphp

<div class="rounded-2xl border border-slate-200 
    bg-gradient-to-r from-slate-50 via-white to-slate-50 
    shadow-sm overflow-hidden">

    {{-- TOP ACCENT --}}
    <div class="h-1 {{ $accent }}"></div>

    {{-- MAIN TITLE --}}
    <div class="px-6 py-6 text-center">
        <h1 class="text-2xl md:text-3xl font-bold tracking-tight text-slate-900">
            Project Proposal
        </h1>
        <p class="text-sm text-slate-500 mt-1">
            Define and plan your project details
        </p>
    </div>

    {{-- SECTION LABEL --}}
    <div class="bg-slate-50/80 border-y border-slate-200 px-6 py-2 text-center">
        <span class="text-xs font-semibold text-slate-700 tracking-wide uppercase">
            Project Definition
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

</div>