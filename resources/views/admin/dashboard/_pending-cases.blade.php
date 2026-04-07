@if($pendingCases->isNotEmpty())

@php
    $typeClasses = [
        'Strategic Plan' => 'bg-blue-100 text-blue-700',
        'President Registration' => 'bg-purple-100 text-purple-700',
        'Officer Submission' => 'bg-emerald-100 text-emerald-700',
        'Moderator Submission' => 'bg-indigo-100 text-indigo-700',
        'default' => 'bg-slate-100 text-slate-700',
        'edit_requested' => 'bg-amber-100 text-amber-700',
    ];
@endphp

<div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm overflow-hidden">

    {{-- HEADER --}}
    <div class="px-4 py-3 border-b border-slate-100 flex items-center justify-between">

        <div class="flex items-center gap-2">
            {{-- Lucide: building --}}
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path d="M3 21h18"/>
                <path d="M5 21V7l8-4v18"/>
                <path d="M19 21V11l-6-4"/>
            </svg>

            <div>
                <div class="text-xs font-semibold text-slate-900">
                    Re-Registration Queue
                </div>
                <div class="text-[10px] text-slate-500">
                    Organizations with pending submissions
                </div>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-slate-100 text-slate-700">
                {{ $pendingCases->count() }}
            </span>

            <a href="{{ route('admin.rereg.index') }}"
               class="text-[10px] font-semibold text-blue-600 hover:underline">
                View
            </a>
        </div>

    </div>

    {{-- LIST --}}
    <div class="divide-y divide-slate-100 max-h-[380px] overflow-y-auto">

        @forelse($pendingCases->groupBy(fn($c) => $c->organization_id) as $orgCases)

            @php
                $first = $orgCases->first();
                $count = $orgCases->count();
            @endphp

            <a href="{{ $first->route }}"
               class="block px-4 py-3 hover:bg-slate-50 transition">

                {{-- TOP --}}
                <div class="flex items-start justify-between gap-2">

                    <div class="min-w-0">
                        <div class="text-xs font-semibold text-slate-900 truncate">
                            {{ $first->organization->name ?? 'Organization' }}
                        </div>

                        <div class="text-[10px] text-slate-500">
                            {{ $first->school_year->name ?? '' }}
                        </div>
                    </div>

                    <span class="text-[9px] px-2 py-0.5 rounded-full bg-blue-100 text-blue-700 font-semibold whitespace-nowrap">
                        {{ $count }} task{{ $count > 1 ? 's' : '' }}
                    </span>

                </div>

                {{-- TYPES --}}
                <div class="flex flex-wrap gap-1 mt-2">

                    @foreach($orgCases as $case)
                        <span class="text-[9px] px-2 py-0.5 rounded-full font-semibold
                            {{ $typeClasses[$case->type] ?? $typeClasses['default'] }}">
                            {{ $case->type }}
                        </span>
                    @endforeach

                </div>

            </a>

        @empty
            <div class="px-4 py-3 text-xs text-slate-500">
                No pending cases
            </div>
        @endforelse

    </div>

</div>

@endif