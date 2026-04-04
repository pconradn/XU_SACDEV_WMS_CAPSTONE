<div class="hidden">
    bg-emerald-50 text-emerald-700 border-emerald-200
    bg-emerald-100 text-emerald-700
</div>

@if($readyForActivation->isNotEmpty())
<div class="rounded-2xl border border-emerald-200 bg-gradient-to-b from-emerald-50 to-white shadow-sm overflow-hidden">

    {{-- HEADER --}}
    <div class="px-4 py-3 border-b border-emerald-100 flex items-center justify-between">

        <div>
            <div class="text-xs font-semibold text-emerald-800">
                Ready for Activation
            </div>
            <div class="text-[10px] text-emerald-600">
                All requirements approved, awaiting activation
            </div>
        </div>

        <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-white text-emerald-700 border border-emerald-200">
            {{ $readyForActivation->count() }}
        </span>

    </div>

    {{-- LIST --}}
    <div class="divide-y divide-emerald-100 max-h-[260px] overflow-y-auto">

        @forelse($readyForActivation as $item)
            <a href="{{ $item->route }}"
               class="block px-4 py-3 hover:bg-emerald-50 transition">

                <div class="flex items-center justify-between">

                    <div class="text-xs font-semibold text-emerald-900">
                        {{ $item->organization->name ?? 'Organization' }}
                    </div>

                    <span class="text-[9px] px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 font-semibold">
                        Ready
                    </span>

                </div>

                <div class="text-[10px] text-emerald-600 mt-0.5">
                    {{ $item->school_year->name ?? '' }}
                </div>

            </a>
        @empty
            <div class="px-4 py-3 text-xs text-emerald-600">
                No organizations ready for activation
            </div>
        @endforelse

    </div>

</div>
@endif