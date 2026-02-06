@php
    $syLabel = $schoolYear?->name ?? ('SY #' . (int) $targetSyId);
@endphp

<div class="mb-4">
    <h2 class="text-xl font-semibold text-slate-900">
        B-3 Officers List
        <span class="text-slate-500 font-normal">(Target SY: {{ $syLabel }})</span>
    </h2>
</div>