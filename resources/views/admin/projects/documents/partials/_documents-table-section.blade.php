@php
$colorMap = [
    'rose' => 'bg-rose-50 border-rose-200 text-rose-700',
    'amber' => 'bg-amber-50 border-amber-200 text-amber-700',
    'blue' => 'bg-blue-50 border-blue-200 text-blue-700',
    'emerald' => 'bg-emerald-50 border-emerald-200 text-emerald-700',
    'slate' => 'bg-slate-50 border-slate-200 text-slate-700',
];
@endphp


<div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

    {{-- HEADER --}}
    <div class="px-4 py-3 flex items-center justify-between border-b {{ $colorMap[$color] }}">

        <div class="flex items-center gap-2 min-w-0">

            <div class="flex items-center justify-center w-7 h-7 rounded-lg border bg-white/60">
                <i data-lucide="{{ $icon }}" class="w-3.5 h-3.5"></i>
            </div>

            <div class="flex items-center gap-2 min-w-0">

                <span class="text-xs font-semibold uppercase tracking-wide">
                    {{ $title }}
                </span>

                <span class="text-[10px] font-medium px-2 py-0.5 rounded-full bg-white/70 border border-white/60 text-slate-600">
                    {{ $items->count() }}
                </span>

            </div>

        </div>

    </div>


    {{-- TABLE --}}
    <div class="divide-y divide-slate-100">

        @forelse($items as $form)
            @include('admin.projects.documents.partials._documents-table-row', [
                'form' => $form,
                'color' => $color
            ])
        @empty

            <div class="px-4 py-6 text-center">

                <div class="mx-auto mb-2 flex h-9 w-9 items-center justify-center rounded-full bg-slate-100 text-slate-500">
                    <i data-lucide="inbox" class="w-4 h-4"></i>
                </div>

                <div class="text-xs font-medium text-slate-600">
                    No records found
                </div>

            </div>

        @endforelse

    </div>

</div>