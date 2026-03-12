@php
    $st = (string) ($status ?? 'draft');
    $cls = 'bg-slate-100 text-slate-700';

    if ($st === 'submitted_to_sacdev') $cls = 'bg-amber-100 text-amber-800';
    if ($st === 'returned_by_sacdev') $cls = 'bg-red-100 text-red-800';
    if ($st === 'approved_by_sacdev') $cls = 'bg-emerald-100 text-emerald-800';
@endphp

<span class="inline-flex rounded-full px-2 py-1 text-xs font-semibold {{ $cls }}">
    {{ $st }}
</span>
