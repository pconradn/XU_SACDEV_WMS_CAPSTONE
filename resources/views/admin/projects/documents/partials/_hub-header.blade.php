@php
$projectStatusMap = [
    'planning' => 'bg-amber-100 text-amber-700',
    'drafting' => 'bg-amber-100 text-amber-700',
    'submitted' => 'bg-blue-100 text-blue-700',
    'under_review' => 'bg-blue-100 text-blue-700',
    'returned' => 'bg-rose-100 text-rose-700',
    'approved' => 'bg-emerald-100 text-emerald-700',
    'approved_by_sacdev' => 'bg-emerald-100 text-emerald-700',
    'completed' => 'bg-emerald-100 text-emerald-700',
    'postponed' => 'bg-purple-100 text-purple-700',
    'post_implementation' => 'bg-indigo-100 text-indigo-700',
    'cancelled' => 'bg-slate-200 text-slate-600',
];

$statusClass = $projectStatusMap[$header['status']] ?? 'bg-slate-100 text-slate-700';
$statusLabel = ucfirst(str_replace('_', ' ', $header['status']));

$headerStyle = match($header['status']) {
    'completed', 'approved', 'approved_by_sacdev'
        => 'border-emerald-200 bg-gradient-to-br from-emerald-50 via-white to-white',

    'submitted', 'under_review'
        => 'border-blue-200 bg-gradient-to-br from-blue-50 via-white to-white',

    'returned'
        => 'border-rose-200 bg-gradient-to-br from-rose-50 via-white to-white',

    'planning', 'drafting'
        => 'border-amber-200 bg-gradient-to-br from-amber-50 via-white to-white',

    'postponed'
        => 'border-purple-200 bg-gradient-to-br from-purple-50 via-white to-white',

    'post_implementation'
        => 'border-indigo-200 bg-gradient-to-br from-indigo-50 via-white to-white',

    'cancelled'
        => 'border-slate-300 bg-gradient-to-br from-slate-100 via-white to-white',

    default
        => 'border-slate-200 bg-gradient-to-br from-white to-slate-50',
};
@endphp


<div class="relative w-full rounded-2xl border shadow-sm px-5 py-4 mb-4 space-y-4 {{ $headerStyle }}">



    {{-- TOP --}}
    <div class="flex items-start justify-between gap-4">

        {{-- LEFT --}}
        <div class="min-w-0 space-y-1">

            <div class="text-[10px] uppercase tracking-wide text-slate-400">
                SACDEV Admin • Project Review
            </div>

            <h1 class="text-lg font-semibold text-slate-900 leading-tight truncate">
                {{ $header['title'] }}
            </h1>

            <div class="flex flex-wrap items-center gap-x-2 text-xs text-slate-500">
                <span>{{ $header['org'] }}</span>
                <span class="text-slate-300">•</span>
                <span>{{ $header['school_year'] }}</span>
            </div>

            <div class="text-[11px] text-slate-500">
                Project Head:
                <span class="text-slate-700 font-medium">
                    {{ $header['project_head'] ?? '—' }}
                </span>
            </div>

        </div>


        {{-- RIGHT --}}
        <div class="flex items-center gap-2 shrink-0">

            <a href="{{ route('admin.org.projects.index', [$project->organization_id, $project->school_year_id]) }}"
               class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-100 transition">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                Back
            </a>

        </div>

    </div>


    {{-- STATUS BAR --}}
    <div class="flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 pt-3">

        {{-- LEFT --}}
        <div class="flex flex-wrap items-center gap-2">

            {{-- MAIN STATUS --}}
            <span class="px-3 py-1 rounded-full text-[11px] font-semibold {{ $statusClass }}">
                {{ $statusLabel }}
            </span>

            {{-- SECONDARY --}}
            <div class="flex items-center gap-2 ml-1">

                @if(($pendingForAdmin ?? 0) > 0)
                    <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold bg-rose-100 text-rose-700">
                        {{ $pendingForAdmin }} pending
                    </span>
                @endif

                @if($snapshot['is_off_campus'] ?? false)
                    <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold bg-purple-100 text-purple-700">
                        Off-Campus
                    </span>
                @endif

            </div>

            {{-- PROGRESS --}}
            @if(isset($progress['percentage']))
                <span class="text-[11px] text-slate-500 ml-2">
                    {{ (int)$progress['percentage'] }}% complete
                </span>
            @endif

        </div>


        {{-- RIGHT --}}
        <div class="text-[10px] text-slate-400 whitespace-nowrap">
            Updated {{ now()->diffForHumans() }}
        </div>

    </div>

</div>