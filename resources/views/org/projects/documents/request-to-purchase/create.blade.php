<x-layouts.form-only
    title="Request to Purchase — {{ $project->title }}"
    :backRoute="route('org.projects.documents.hub', $project)"
>

<div class="mx-auto max-w-5xl">

@php

$docStatus = $document->status ?? 'draft';

$isProjectHead = $isProjectHead ?? false;

            $isEditable = $isProjectHead && (
                in_array($status, ['draft','submitted','returned'])
                || ($status === 'approved_by_sacdev' && $document->edit_mode)
            );

if (in_array($docStatus, ['approved', 'approved_by_sacdev'])) {
    $isEditable = false;
}

$isReadOnly = !$isEditable;

$statusStyles = [
    'draft'              => 'bg-slate-50 text-slate-700',
    'submitted'          => 'bg-blue-50 text-blue-800',
    'returned'           => 'bg-rose-50 text-rose-800',
    'approved'           => 'bg-emerald-50 text-emerald-800',
    'approved_by_sacdev' => 'bg-emerald-50 text-emerald-800',
];

$style = $statusStyles[$docStatus] ?? $statusStyles['draft'];

$currentApprover = $document?->signatures
    ?->where('status', 'pending')
    ->sortBy('id')
    ->first();

@endphp



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



{{-- INSTRUCTIONS MODAL --}}
@if($isProjectHead)

<div id="instructionModal"
class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">

<div class="bg-white rounded-lg shadow-lg w-full max-w-xl">

<div class="border-b px-4 py-3 font-semibold text-sm">
Request to Purchase Instructions
</div>

<div class="p-4 text-[12px] text-slate-700 space-y-3">

<p>
Before submitting this request, please review the following guidelines:
</p>

<ul class="list-disc ml-5 space-y-1">

<li>
This form must be used when requesting the purchase of office
equipment, materials, or supplies related to the project activity.
</li>

<li>
All items to be purchased must be listed in the table together with
their quantity, estimated price, and vendor or supplier.
</li>

<li>
A price quotation from the target supplier should be prepared and
submitted together with the printed copy of this request.
</li>

<li>
Once this form is submitted, it will undergo approval by the
President, Moderator, and SACDEV before the purchase can proceed.
</li>

</ul>

</div>

<div class="border-t px-4 py-3 flex justify-end">

<button
type="button"
onclick="closeInstructionModal()"
class="bg-blue-900 text-white px-4 py-2 text-[12px] hover:bg-blue-800">
I Understand
</button>

</div>

</div>

</div>

@endif



{{-- STATUS BANNER --}}
<div class="border border-slate-300 {{ $style }} px-4 py-3 text-sm mb-6">

<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">

<div class="font-semibold tracking-wide">
REQUEST TO PURCHASE STATUS:
<span class="ml-1 uppercase">
{{ $docStatus }}
</span>
</div>

@if($docStatus === 'submitted' && $currentApprover)
<div class="text-[12px] font-medium">
Awaiting:
<span class="capitalize font-semibold">
{{ str_replace('_',' ', $currentApprover->role) }}
</span>
</div>
@endif

@if(in_array($docStatus, ['approved', 'approved_by_sacdev']))
<div class="text-[12px] font-medium">
Fully approved and finalized.
</div>
@endif

@if($docStatus === 'draft')
<div class="text-[12px]">
This form is still editable.
</div>
@endif

@if($docStatus === 'returned')
<div class="text-[12px] font-medium">
Returned for revision. Please update and resubmit.
</div>
@endif

</div>

</div>



@include('org.projects.documents.request-to-purchase.partials._header')

@include('org.projects.documents.request-to-purchase.partials._flash')


<form id="proposalForm"
      method="POST"
      action="{{ route('org.projects.documents.request-to-purchase.store', $project) }}">

@csrf

@if($isReadOnly)
<fieldset disabled class="space-y-6">
@endif

@include('org.projects.documents.request-to-purchase.partials._fund-sources')

@include('org.projects.documents.request-to-purchase.partials._items-table')

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
$sigStatus = $sig->status;
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

@if($sigStatus === 'signed')

<div class="text-emerald-700 font-medium">
Approved
</div>

<div class="text-slate-500 text-[11px]">
{{ $sig->signed_at?->format('M d, Y h:i A') }}
</div>

@elseif($sigStatus === 'pending')

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

    @include('components.project-document.actions._actions', [
        'project' => $project,
        'document' => $document,
        'currentSignature' => $document?->signatures
            ?->where('user_id', auth()->id())
            ->first(),
        'isProjectHead' => $isProjectHead ?? false,
        'isAdmin' => auth()->user()->system_role === 'sacdev_admin',
    ])


@include('org.projects.documents.request-to-purchase.partials._scripts')

</div>


<script>
function closeInstructionModal() {
    const modal = document.getElementById('instructionModal');

    if (!modal) return;

    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
</script>

</x-layouts.form-only>