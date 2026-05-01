@php
    $readyForActivation = collect($readyForActivation ?? []);
@endphp

<div class="space-y-3">

    @forelse($readyForActivation as $item)
        <a href="{{ $item->route }}"
           class="group block rounded-2xl border border-emerald-200 bg-emerald-50 p-4 transition hover:bg-emerald-100">

            <div class="flex items-start justify-between gap-3">

                <div class="flex items-start gap-3 min-w-0">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-emerald-200 bg-white text-emerald-700">
                        <i data-lucide="building-2" class="w-5 h-5"></i>
                    </div>

                    <div class="min-w-0">
                        <div class="text-sm font-semibold text-slate-900 truncate">
                            {{ $item->organization->name ?? 'Organization' }}
                        </div>

                        <div class="mt-1 text-xs text-emerald-700">
                            {{ $item->school_year->name ?? 'Active School Year' }}
                        </div>

                        <div class="mt-2 inline-flex items-center gap-1.5 rounded-full border border-emerald-200 bg-white px-2.5 py-1 text-[11px] font-semibold text-emerald-700">
                            <i data-lucide="check-circle-2" class="w-3 h-3"></i>
                            Requirements complete
                        </div>
                    </div>
                </div>

                <div class="shrink-0 text-emerald-700">
                    <i data-lucide="arrow-right" class="w-4 h-4 transition group-hover:translate-x-0.5"></i>
                </div>

            </div>
        </a>
    @empty
        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-6 text-center">
            <div class="mx-auto flex h-11 w-11 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-400">
                <i data-lucide="badge-check" class="w-5 h-5"></i>
            </div>

            <div class="mt-3 text-sm font-semibold text-slate-800">
                No organizations ready
            </div>

            <div class="mt-1 text-xs leading-5 text-slate-500">
                Organizations will appear here after completing all active school year requirements.
            </div>
        </div>
    @endforelse

</div>