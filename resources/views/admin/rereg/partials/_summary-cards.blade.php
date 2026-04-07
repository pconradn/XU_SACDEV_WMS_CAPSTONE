<div class="grid grid-cols-1 sm:grid-cols-3 gap-3">

    {{-- PENDING --}}
    <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 shadow-sm">
        <div class="flex items-center justify-between">
            <span class="text-[10px] font-semibold uppercase tracking-[0.12em] text-red-600">
                Pending
            </span>
            <span class="text-lg font-bold text-red-700">
                {{ $summary['pending'] ?? 0 }}
            </span>
        </div>
    </div>

    {{-- READY --}}
    <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 shadow-sm">
        <div class="flex items-center justify-between">
            <span class="text-[10px] font-semibold uppercase tracking-[0.12em] text-emerald-600">
                Ready
            </span>
            <span class="text-lg font-bold text-emerald-700">
                {{ $summary['ready'] ?? 0 }}
            </span>
        </div>
    </div>

    {{-- REGISTERED --}}
    <div class="rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 shadow-sm">
        <div class="flex items-center justify-between">
            <span class="text-[10px] font-semibold uppercase tracking-[0.12em] text-blue-600">
                Registered
            </span>
            <span class="text-lg font-bold text-blue-700">
                {{ $summary['registered'] ?? 0 }}
            </span>
        </div>
    </div>

</div>