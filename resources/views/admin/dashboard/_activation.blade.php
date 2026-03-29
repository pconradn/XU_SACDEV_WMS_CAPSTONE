<div class="hidden">
    bg-emerald-50 text-emerald-700 border-emerald-200
    bg-emerald-100 text-emerald-700
</div>
@if($readyForActivation->isNotEmpty())
<div class="bg-emerald-50 border border-emerald-200 rounded-2xl shadow-sm overflow-hidden">

    <div class="px-5 py-4 border-b border-emerald-200 flex justify-between items-center bg-emerald-100/60">
        <h3 class="text-sm font-semibold text-emerald-800">
            Ready for Activation
        </h3>

        <span class="text-xs font-semibold px-2 py-1 rounded-full bg-white text-emerald-700 border border-emerald-200">
            {{ $readyForActivation->count() }} ready
        </span>
    </div>

    <div class="divide-y divide-emerald-100 max-h-[300px] overflow-y-auto pr-2">

        @forelse($readyForActivation as $item)
            <a href="{{ $item->route }}"
               class="block px-5 pr-6 py-4 hover:bg-emerald-100/50 hover:shadow-sm transition-all">

                <div class="flex items-center justify-between">

                    <div class="text-sm font-semibold text-emerald-900">
                        {{ $item->organization->name ?? 'Org' }}
                    </div>

                    <span class="text-[10px] px-2 py-1 rounded-full bg-emerald-100 text-emerald-700 font-semibold">
                        Ready
                    </span>

                </div>

                <div class="text-xs text-emerald-700 mt-1">
                    {{ $item->school_year->name ?? '' }}
                </div>

            </a>
        @empty
            <div class="px-5 py-4 text-sm text-emerald-700">
                No organizations ready
            </div>
        @endforelse

    </div>

</div>
@endif