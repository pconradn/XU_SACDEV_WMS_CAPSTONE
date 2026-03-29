@if($pendingCases->isNotEmpty())

@php
    $typeClasses = [
        'Strategic Plan' => 'bg-blue-100 text-blue-700',
        'President Registration' => 'bg-purple-100 text-purple-700',
        'Officer Submission' => 'bg-emerald-100 text-emerald-700',
        'Moderator Submission' => 'bg-indigo-100 text-indigo-700',
        'default' => 'bg-slate-100 text-slate-700',
    ];
@endphp

<div class="hidden">
    bg-blue-100 text-blue-700
    bg-purple-100 text-purple-700
    bg-emerald-100 text-emerald-700
    bg-indigo-100 text-indigo-700
    bg-slate-100 text-slate-700
</div>

<div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">

    <div class="px-5 py-4 border-b flex justify-between items-center bg-slate-50">
        <h3 class="text-sm font-semibold text-slate-900">
            Org Re-Registration
        </h3>

        <div class="flex items-center gap-2">
            <span class="text-xs font-semibold px-2 py-1 rounded-full bg-slate-200 text-slate-700">
                {{ $pendingCases->count() }} pending
            </span>

            <a href="{{ route('admin.rereg.index') }}" class="text-xs text-blue-600 font-medium">
                View All
            </a>
        </div>
    </div>

    <div class="divide-y max-h-[420px] overflow-y-auto">

        @forelse($pendingCases->groupBy(fn($c) => $c->organization_id) as $orgCases)

            @php
                $first = $orgCases->first();
                $count = $orgCases->count();
            @endphp

            <a href="{{ $first->route }}"
               class="block px-5 py-4 hover:bg-slate-50 hover:shadow-sm transition-all">

                <div class="flex items-center justify-between">

                    <div class="text-sm font-semibold text-slate-900">
                        {{ $first->organization->name ?? 'Org' }}
                    </div>

                    <span class="text-[10px] px-2 py-1 rounded-full bg-blue-100 text-blue-700 font-semibold">
                        {{ $count }} form{{ $count > 1 ? 's' : '' }}
                    </span>

                </div>

                <div class="text-xs text-slate-500 mt-1">
                    {{ $first->school_year->name ?? '' }}
                </div>

                <div class="flex flex-wrap gap-1 mt-3 max-h-[60px] overflow-y-auto pr-1">

                    @foreach($orgCases as $case)

                        <span class="text-[10px] px-2 py-1 rounded-full font-semibold {{ $typeClasses[$case->type] ?? $typeClasses['default'] }}">
                            {{ $case->type }}
                        </span>

                    @endforeach

                </div>

            </a>

        @empty
            <div class="px-5 py-4 text-sm text-slate-500">
                No pending cases
            </div>
        @endforelse

    </div>

</div>


@endif