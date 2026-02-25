@php
$status = $b1->status ?? null;

$circle =
    !$status ? 'bg-slate-400' :
    ($status === 'approved_by_sacdev' ? 'bg-emerald-500' :
    (str_contains($status,'returned') ? 'bg-rose-500' :
    'bg-amber-500'));
@endphp

<div class="rounded-xl border border-slate-200 bg-white p-5">

<div class="flex items-start justify-between">

<div>

<div class="font-semibold text-slate-900">
B1 — Strategic Plan
</div>

<div class="mt-1 flex items-center gap-2 text-sm text-slate-700">

<span class="h-2.5 w-2.5 rounded-full {{ $circle }}"></span>

<span>
{{ $status ? ucwords(str_replace('_',' ',$status)) : 'No submission' }}
</span>

</div>

@if($b1?->submitted_to_moderator_at)
<div class="text-xs text-slate-500 mt-1">
Submitted: {{ $b1->submitted_to_moderator_at->format('M d, Y — h:i A') }}
</div>
@endif


@if($b1?->moderator_reviewed_at)
<div class="text-xs text-slate-500">
Reviewed: {{ $b1->moderator_reviewed_at->format('M d, Y — h:i A') }}
</div>
@endif

@if($b1?->approved_at)
<div class="text-xs text-slate-500">
Approved: {{ $b1->approved_at->format('M d, Y — h:i A') }}
</div>
@endif

</div>

@if($b1)
<a href="{{ route('org.moderator.strategic_plans.show',$b1) }}"
class="text-sm font-semibold text-blue-600 hover:text-blue-800">
Open
</a>
@endif

</div>

</div>