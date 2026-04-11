@php
    $idCounts = collect($items)
        ->groupBy('student_id_number')
        ->map->count();

    $duplicateCount = $idCounts->filter(fn ($count) => $count > 1)->count();
    $conflictCount = collect($items)->filter(fn ($item) => count($conflictsByItemId[$item->id] ?? []) > 0)->count();
@endphp

<div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

    <div class="border-b border-slate-200 bg-gradient-to-b from-slate-50 to-white px-4 py-4 sm:px-5">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">

            <div class="min-w-0 space-y-1">
                <div class="flex items-center gap-2 text-[10px] font-semibold uppercase tracking-[0.14em] text-slate-500">
                    <i data-lucide="users" class="h-3.5 w-3.5"></i>
                    Officers Review
                </div>

                <div class="text-sm font-semibold text-slate-900">
                    Officers List
                </div>

                <div class="text-[11px] text-slate-500">
                    Academic performance shown is from previous school year.
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2 text-[10px] sm:text-[11px]">

                <div class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-2.5 py-1.5 text-slate-600 shadow-sm">
                    <i data-lucide="list" class="h-3.5 w-3.5 text-slate-400"></i>
                    <span class="font-medium text-slate-700">{{ count($items) }}</span>
                    <span>Entries</span>
                </div>

                @if($conflictCount > 0)
                    <div class="inline-flex items-center gap-1.5 rounded-xl border border-amber-200 bg-amber-50 px-2.5 py-1.5 text-amber-700 shadow-sm">
                        <i data-lucide="alert-triangle" class="h-3.5 w-3.5"></i>
                        <span class="font-medium">{{ $conflictCount }}</span>
                        <span>Conflict{{ $conflictCount > 1 ? 's' : '' }}</span>
                    </div>
                @endif

                @if($duplicateCount > 0)
                    <div class="inline-flex items-center gap-1.5 rounded-xl border border-rose-200 bg-rose-50 px-2.5 py-1.5 text-rose-700 shadow-sm">
                        <i data-lucide="copy" class="h-3.5 w-3.5"></i>
                        <span class="font-medium">{{ $duplicateCount }}</span>
                        <span>Duplicate ID{{ $duplicateCount > 1 ? 's' : '' }}</span>
                    </div>
                @endif

            </div>

        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full text-[11px] sm:text-xs">

            <thead class="bg-slate-50/90">
                
                <tr class="border-b border-slate-200 text-left font-semibold uppercase tracking-[0.12em] text-slate-500">
                    <th class="px-4 py-3">Officer</th>
                    <th class="px-4 py-3">Student ID</th>
                    <th class="px-4 py-3">Position</th>
                    <th class="px-4 py-3">Prev 1st Sem</th>
                    <th class="px-4 py-3">Prev 2nd Sem</th>
                    <th class="px-4 py-3">Prev Inter</th>
                    <th class="px-4 py-3 text-center">Status</th>
                </tr>

            </thead>

            <tbody class="divide-y divide-slate-100 bg-white">

                @foreach($items as $item)

                    @php
                        $conflicts = $conflictsByItemId[$item->id] ?? [];
                        $hasConflict = count($conflicts) > 0;
                        $isDuplicate = ($idCounts[$item->student_id_number] ?? 0) > 1;
                        $modalId = 'conflictModal_' . $item->id;

                        $rowClass = 'hover:bg-slate-50/80';
                        if ($hasConflict && $isDuplicate) {
                            $rowClass = 'bg-gradient-to-r from-amber-50 via-white to-rose-50 hover:from-amber-50 hover:to-rose-50';
                        } elseif ($hasConflict) {
                            $rowClass = 'bg-amber-50/60 hover:bg-amber-50';
                        } elseif ($isDuplicate) {
                            $rowClass = 'bg-rose-50/60 hover:bg-rose-50';
                        }
                    @endphp

                    <tr class="transition {{ $rowClass }}">

                        <td class="px-4 py-3.5 align-top">
                            <div class="min-w-[180px]">
                                <div class="font-semibold text-slate-900">
                                    {{ $item->officer_name }}
                                </div>
                            </div>
                        </td>

                        <td class="px-4 py-3.5 align-top">
                            <div class="flex flex-wrap items-center gap-1.5">
                                <span class="font-medium text-slate-700">
                                    {{ $item->student_id_number }}
                                </span>

                                @if($isDuplicate)
                                    <span class="inline-flex items-center gap-1 rounded-md border border-rose-200 bg-rose-50 px-2 py-0.5 text-[10px] font-semibold text-rose-700">
                                        <i data-lucide="copy" class="h-3 w-3"></i>
                                        Duplicate
                                    </span>
                                @endif
                            </div>
                        </td>

                        <td class="px-4 py-3.5 align-top text-slate-700">
                            <span class="inline-flex rounded-lg border border-slate-200 bg-slate-50 px-2.5 py-1 font-medium">
                                {{ $item->position }}
                            </span>
                        </td>

                        <td class="px-4 py-3.5 align-top text-slate-600">
                            <span class="inline-flex min-w-[52px] justify-center rounded-lg border border-slate-200 bg-white px-2 py-1">
                                {{ $item->first_sem_qpi ?? '—' }}
                            </span>
                        </td>

                        <td class="px-4 py-3.5 align-top text-slate-600">
                            <span class="inline-flex min-w-[52px] justify-center rounded-lg border border-slate-200 bg-white px-2 py-1">
                                {{ $item->second_sem_qpi ?? '—' }}
                            </span>
                        </td>

                        <td class="px-4 py-3.5 align-top text-slate-600">
                            <span class="inline-flex min-w-[52px] justify-center rounded-lg border border-slate-200 bg-white px-2 py-1">
                                {{ $item->intersession_qpi ?? '—' }}
                            </span>
                        </td>

                        <td class="px-4 py-3.5 align-top text-center">
                            <div class="flex flex-wrap items-center justify-center gap-1.5">

                                @if($hasConflict)
                                    <button
                                        type="button"
                                        onclick="openModal('{{ $modalId }}')"
                                        class="inline-flex items-center gap-1 rounded-lg border border-amber-200 bg-amber-50 px-2 py-1 text-[10px] font-semibold text-amber-700 hover:bg-amber-100 transition shadow-sm"
                                    >
                                        <i data-lucide="alert-triangle" class="h-3.5 w-3.5"></i>
                                        Conflict
                                    </button>
                                @endif

                                @if($isDuplicate)
                                    <span class="inline-flex items-center gap-1 rounded-lg border border-rose-200 bg-rose-50 px-2 py-1 text-[10px] font-semibold text-rose-700 shadow-sm">
                                        <i data-lucide="copy" class="h-3.5 w-3.5"></i>
                                        Duplicate ID
                                    </span>
                                @endif

                                @if(!$hasConflict && !$isDuplicate)
                                    <span class="inline-flex items-center gap-1 rounded-lg border border-emerald-200 bg-emerald-50 px-2 py-1 text-[10px] font-semibold text-emerald-700 shadow-sm">
                                        <i data-lucide="check-circle-2" class="h-3.5 w-3.5"></i>
                                        Clear
                                    </span>
                                @endif

                            </div>
                        </td>

                    </tr>

                    @if($hasConflict)
                        <div id="{{ $modalId }}"
                             class="fixed inset-0 z-50 hidden items-center justify-center p-4">

                            <div class="absolute inset-0 bg-slate-900/55 backdrop-blur-sm"
                                 onclick="closeModal('{{ $modalId }}')"></div>

                            <div class="relative w-full max-w-lg overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xl">

                                <div class="border-b border-slate-200 bg-gradient-to-b from-amber-50 to-white px-5 py-4">
                                    <div class="flex items-start justify-between gap-4">

                                        <div class="space-y-1">
                                            <div class="inline-flex items-center gap-2 text-[10px] font-semibold uppercase tracking-[0.14em] text-amber-700">
                                                <i data-lucide="alert-triangle" class="h-3.5 w-3.5"></i>
                                                Conflict Detected
                                            </div>

                                            <div class="text-sm font-semibold text-slate-900">
                                                Major Officer Conflict
                                            </div>

                                            <div class="text-[11px] text-slate-500">
                                                This student is already assigned as a major officer in another organization.
                                            </div>
                                        </div>

                                        <button type="button"
                                                onclick="closeModal('{{ $modalId }}')"
                                                class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-400 hover:text-slate-600 hover:bg-slate-50 transition">
                                            <i data-lucide="x" class="h-4 w-4"></i>
                                        </button>

                                    </div>
                                </div>

                                <div class="space-y-4 px-5 py-4">

                                    <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
                                        <div class="text-[10px] font-semibold uppercase tracking-[0.12em] text-slate-500">
                                            Selected Officer
                                        </div>
                                        <div class="mt-1 text-sm font-semibold text-slate-900">
                                            {{ $item->officer_name }}
                                        </div>
                                        <div class="mt-1 text-[11px] text-slate-500">
                                            Student ID: {{ $item->student_id_number }}
                                        </div>
                                    </div>

                                    <div class="space-y-2">
                                        <div class="text-[11px] font-semibold uppercase tracking-[0.12em] text-slate-500">
                                            Existing Major Officer Assignments
                                        </div>

                                        @foreach($conflicts as $conflict)
                                            <div class="rounded-xl border border-amber-200 bg-amber-50/70 px-4 py-3 shadow-sm">
                                                <div class="flex items-start justify-between gap-3">
                                                    <div>
                                                        <div class="text-xs font-semibold text-slate-900">
                                                            {{ ucfirst(str_replace('_', ' ', $conflict['position'] ?? 'unknown')) }}
                                                        </div>
                                                        <div class="mt-1 text-[11px] text-slate-600">
                                                            {{ $conflict['organization_name'] }}
                                                        </div>
                                                    </div>

                                                    <span class="inline-flex rounded-full border border-amber-200 bg-white px-2 py-0.5 text-[10px] font-semibold text-amber-700">
                                                        Conflict
                                                    </span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                </div>

                                <div class="border-t border-slate-200 bg-white px-5 py-4 text-right">
                                    <button type="button"
                                            onclick="closeModal('{{ $modalId }}')"
                                            class="inline-flex items-center gap-1.5 rounded-lg bg-slate-900 px-3 py-1.5 text-xs font-semibold text-white hover:bg-slate-800 transition shadow-sm">
                                        <i data-lucide="check" class="h-3.5 w-3.5"></i>
                                        Close
                                    </button>
                                </div>

                            </div>

                        </div>
                    @endif

                @endforeach

            </tbody>

        </table>
    </div>

</div>

<script>
function openModal(id){
    const modal = document.getElementById(id);
    if(modal){
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
}
function closeModal(id){
    const modal = document.getElementById(id);
    if(modal){
        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }
}
</script>
