<x-layouts.form-only
    title="Off Campus Form — {{ $project->title }}"
    :backRoute="route('org.projects.documents.hub', $project)"
>

<div class="mx-auto max-w-5xl">

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
OFF CAMPUS FORM STATUS:
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
This form is still editable.
</div>
@endif

@if($status === 'returned')
<div class="text-[12px] font-medium">
Returned for revision. Please update and resubmit.
</div>
@endif

</div>

</div>


@include('org.projects.documents.off-campus.partials._header')

@include('org.projects.documents.off-campus.partials._flash')


<form id="offCampusForm"
      method="POST"
      action="{{ route('org.projects.off-campus.store', $project) }}">
@csrf


@if($isReadOnly)
<fieldset disabled class="space-y-6">
@endif

@include('org.projects.documents.off-campus.partials._activity-info')

@include('org.projects.documents.off-campus.partials._participants')


@if($isReadOnly)
</fieldset>
@endif

</form>


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


@include('org.projects.documents.off-campus.partials._actions')

@include('org.projects.documents.off-campus.partials._scripts')

</div>

</x-layouts.form-only>