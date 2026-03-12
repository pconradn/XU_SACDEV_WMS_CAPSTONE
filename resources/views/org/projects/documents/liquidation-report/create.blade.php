<x-layouts.form-only
    title="Liquidation Report — {{ $project->title }}"
    :backRoute="route('org.projects.documents.hub', $project)"
>

<div class="mx-auto max-w-6xl">

@php

$status = $document->status ?? 'draft';

$isProjectHead = $isProjectHead ?? false;

$isEditable = $isProjectHead && in_array($status, ['draft','submitted','returned']);

if ($status === 'approved') {
    $isEditable = false;
}

$isReadOnly = !$isEditable;

$statusStyles = [
    'draft'     => 'bg-slate-50 text-slate-700',
    'submitted' => 'bg-blue-50 text-blue-800',
    'returned'  => 'bg-rose-50 text-rose-800',
    'approved'  => 'bg-emerald-50 text-emerald-800',
];

$style = $statusStyles[$status] ?? $statusStyles['draft'];

$currentApprover = $document?->signatures
    ?->where('status', 'pending')
    ->sortBy('id')
    ->first();

@endphp


{{-- STATUS BANNER --}}
<div class="border border-slate-300 {{ $style }} px-4 py-3 text-sm mb-6">

<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">

<div class="font-semibold tracking-wide">
LIQUIDATION REPORT STATUS:
<span class="ml-1 uppercase">
{{ $status }}
</span>
</div>

@if($status === 'submitted' && $currentApprover)
<div class="text-[12px] font-medium">
Awaiting:
<span class="capitalize font-semibold">
{{ str_replace('_',' ', $currentApprover->role) }}
</span>
</div>
@endif

@if($status === 'approved')
<div class="text-[12px] font-medium">
Fully approved and finalized.
</div>
@endif

@if($status === 'draft')
<div class="text-[12px]">
This report is still editable.
</div>
@endif

@if($status === 'returned')
<div class="text-[12px] font-medium">
Returned for revision. Please update and resubmit.
</div>
@endif

</div>

</div>


{{-- FLASH --}}
@include('org.projects.documents.documentation-report.partials._flash')



{{-- RETURN REMARKS --}}
@if(isset($document) && $document->remarks && $isProjectHead)

<div class="remarks-card border border-amber-200 bg-amber-50 shadow-lg rounded-xl p-4 text-sm text-amber-800 relative mb-6">

<button
onclick="this.closest('.remarks-card').remove()"
class="absolute top-2 right-3 text-amber-500 hover:text-amber-700 text-[14px]">
×
</button>

<div class="font-semibold mb-2 flex items-center gap-2">
<span class="text-amber-600">⚠</span>
Returned for Revision
</div>

<div class="text-[12px] leading-relaxed mb-2">
{{ $document->remarks }}
</div>

@if($document->returnedBy)
<div class="text-[11px] text-amber-700 italic">
Returned by {{ $document->returnedBy->name }}
@if($document->returned_at)
on {{ \Carbon\Carbon::parse($document->returned_at)->format('F d, Y h:i A') }}
@endif
</div>
@endif

</div>

@endif





<form method="POST"
      action="{{ route('org.projects.liquidation-report.store', $project) }}"
      id="liquidationForm">

@csrf

@if($isReadOnly)
<fieldset disabled class="space-y-6">
@endif


@include('org.projects.documents.liquidation-report.partials._header')


{{-- SOURCE OF FUNDS --}}
@include('org.projects.documents.liquidation-report.partials._funds')


{{-- EXPENSE TABLE --}}
@include('org.projects.documents.liquidation-report.partials._expenses')


{{-- SUMMARY --}}
@include('org.projects.documents.liquidation-report.partials._summary')


@if($isReadOnly)
</fieldset>
@endif




</form>
{{-- ACTION BUTTONS --}}
@include('org.projects.documents.liquidation-report.partials._actions')


{{-- SIGNATURE TRAIL --}}
@if($document && $document->signatures && $document->signatures->count())

<div class="border border-slate-300 bg-white mt-8">

<div class="bg-slate-50 border-b border-slate-300 px-4 py-2 text-[12px] font-semibold tracking-wide">
Approval Trail
</div>

<div class="grid grid-cols-1 md:grid-cols-2 divide-y md:divide-y-0 md:divide-x">

@foreach($document->signatures->sortBy('id') as $sig)

@php
$status = $sig->status;
@endphp

<div class="px-4 py-4 text-[12px] flex justify-between items-center">

<div class="flex flex-col">

<div class="font-medium capitalize">
{{ str_replace('_',' ', $sig->role) }}
</div>

<div class="text-slate-500">
{{ $sig->user?->name ?? 'Unknown User' }}
</div>

</div>

<div class="text-right">

@if($status === 'signed')

<div class="text-emerald-700 font-medium">
Approved
</div>

<div class="text-slate-500 text-[11px]">
{{ $sig->signed_at?->format('M d, Y h:i A') }}
</div>

@elseif($status === 'pending')

<div class="text-amber-600 font-medium">
Pending
</div>

@endif

</div>

</div>

@endforeach

</div>

</div>

@endif


@include('org.projects.documents.liquidation-report.partials._script')

</div>

</x-layouts.form-only>