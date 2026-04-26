@if($pendingCases->isNotEmpty())

@php
    $typeClasses = [
        'Strategic Plan' => 'bg-blue-100 text-blue-700 ring-1 ring-blue-200',
        'President Registration' => 'bg-purple-100 text-purple-700 ring-1 ring-purple-200',
        'Officer Submission' => 'bg-emerald-100 text-emerald-700 ring-1 ring-emerald-200',
        'Moderator Submission' => 'bg-indigo-100 text-indigo-700 ring-1 ring-indigo-200',
        'default' => 'bg-slate-100 text-slate-700 ring-1 ring-slate-200',
        'edit_requested' => 'bg-amber-100 text-amber-700 ring-1 ring-amber-200',
    ];
@endphp

<div class="rounded-2xl border border-blue-200 bg-gradient-to-br from-blue-50 via-white to-indigo-50 shadow-sm overflow-hidden">

    <div class="px-5 py-3 border-b border-blue-200 bg-gradient-to-r from-blue-100 to-indigo-100 flex items-center justify-between">

        <div class="flex items-center gap-3">
            <div class="w-9 h-9 flex items-center justify-center rounded-xl bg-white text-blue-600 shadow-sm ring-1 ring-blue-200">
                <i data-lucide="clipboard-list" class="w-4 h-4"></i>
            </div>

            <div>
                <div class="text-xs font-semibold text-blue-900">
                    Re-Registration Queue
                </div>
                <div class="text-[11px] text-blue-700">
                    Organizations with pending submissions
                </div>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <span class="text-[10px] font-semibold px-2.5 py-0.5 rounded-full bg-blue-600 text-white">
                {{ $pendingCases->count() }}
            </span>

            <a href="{{ route('admin.rereg.index') }}"
               class="text-[11px] font-semibold text-blue-700 hover:text-blue-900">
                View
            </a>
        </div>

    </div>

    <div class="px-5 py-2 text-[10px] font-semibold text-blue-700 uppercase tracking-wide border-b border-blue-100 bg-blue-50">
        Pending Organizations
    </div>

    <div class="divide-y divide-blue-100 max-h-[380px] overflow-y-auto">

        @forelse($pendingCases->groupBy(fn($c) => $c->organization_id) as $orgCases)

            @php
                $first = $orgCases->first();
                $count = $orgCases->count();
            @endphp

            <a href="{{ $first->route }}"
               class="block px-5 py-3 bg-white/70 hover:bg-blue-50 transition">

                <div class="flex items-start justify-between gap-3">

                    <div class="flex items-start gap-2 min-w-0">
                        <div class="mt-0.5 text-blue-400">
                            <i data-lucide="building-2" class="w-4 h-4"></i>
                        </div>

                        <div class="min-w-0">
                            <div class="text-xs font-semibold text-slate-900 truncate">
                                {{ $first->organization->name ?? 'Organization' }}
                            </div>

                            <div class="text-[10px] text-slate-500">
                                {{ $first->school_year->name ?? '' }}
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-1 shrink-0">
                        <i data-lucide="clock" class="w-3.5 h-3.5 text-blue-400"></i>
                        <span class="text-[10px] px-2 py-0.5 rounded-full bg-indigo-100 text-indigo-700 font-semibold">
                            {{ $count }} task{{ $count > 1 ? 's' : '' }}
                        </span>
                    </div>

                </div>

                <div class="flex flex-wrap gap-1.5 mt-2">

                    @foreach($orgCases as $case)
                        <span class="text-[10px] px-2.5 py-0.5 rounded-full font-semibold flex items-center gap-1
                            {{ $typeClasses[$case->type] ?? $typeClasses['default'] }}">

                            @if($case->type === 'edit_requested')
                                <i data-lucide="alert-triangle" class="w-3 h-3"></i>
                            @elseif($case->type === 'Strategic Plan')
                                <i data-lucide="target" class="w-3 h-3"></i>
                            @elseif($case->type === 'President Registration')
                                <i data-lucide="user-check" class="w-3 h-3"></i>
                            @elseif($case->type === 'Officer Submission')
                                <i data-lucide="users" class="w-3 h-3"></i>
                            @elseif($case->type === 'Moderator Submission')
                                <i data-lucide="shield-check" class="w-3 h-3"></i>
                            @else
                                <i data-lucide="file-text" class="w-3 h-3"></i>
                            @endif

                            {{ $case->type }}
                        </span>
                    @endforeach

                </div>

            </a>

        @empty
            <div class="px-5 py-4 text-xs text-slate-500 flex items-center gap-2">
                <i data-lucide="inbox" class="w-4 h-4 text-slate-400"></i>
                No pending cases
            </div>
        @endforelse

    </div>

</div>

@endif