@php
$colorMap = [
    'rose' => 'bg-rose-50 border-rose-200 text-rose-700',
    'amber' => 'bg-amber-50 border-amber-200 text-amber-700',
    'blue' => 'bg-blue-50 border-blue-200 text-blue-700',
    'emerald' => 'bg-emerald-50 border-emerald-200 text-emerald-700',
    'slate' => 'bg-slate-50 border-slate-200 text-slate-700',
];
@endphp


<div class="">

    {{-- SECTION HEADER --}}
    <div class="px-5 py-3 flex items-center justify-between border-b border-slate-100 {{ $colorMap[$color] }}">

        <div class="flex items-center gap-2">

            <i data-lucide="{{ $icon }}" class="w-4 h-4"></i>

            <span class="text-xs font-semibold uppercase tracking-wide">
                {{ $title }}
            </span>

            <span class="text-[10px] bg-white/60 px-2 py-0.5 rounded-full">
                {{ $items->count() }}
            </span>

        </div>

    </div>


    {{-- ROWS --}}
    <div class="divide-y">

        @foreach($items as $form)
            @include('admin.projects.documents.partials._documents-table-row', [
                'form' => $form,
                'color' => $color
            ])
        @endforeach

    </div>

</div>