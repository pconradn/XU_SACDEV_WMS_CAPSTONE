@php
$status = $b5->status ?? 'draft';

$circle =
    ($status === 'approved_by_sacdev' ? 'bg-emerald-500' :
    (str_contains($status,'returned') ? 'bg-rose-500' :
    ($status === 'draft' ? 'bg-slate-400' : 'bg-amber-500')));
@endphp

<div class="rounded-xl border border-slate-200 bg-white p-5">

<div class="flex items-start justify-between">

<div>

<div class="font-semibold text-slate-900">
B5 — Moderator Certification
</div>

<div class="mt-1 flex items-center gap-2 text-sm text-slate-700">

<span class="h-2.5 w-2.5 rounded-full {{ $circle }}"></span>

<span>
{{ ucwords(str_replace('_',' ',$status)) }}
</span>

</div>

@if($b5?->submitted_at)
<div class="text-xs text-slate-500 mt-1">
Submitted: {{ $b5->submitted_at->format('M d, Y — h:i A') }}
</div>
@endif

</div>

<a href="{{ route('org.moderator.rereg.b5.edit') }}"
class="text-sm font-semibold text-blue-600 hover:text-blue-800">
Open
</a>

</div>

</div>