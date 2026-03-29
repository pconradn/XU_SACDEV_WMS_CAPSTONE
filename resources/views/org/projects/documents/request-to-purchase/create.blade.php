<x-app-layout>

<div class="max-w-6xl mx-auto space-y-6">

@php
    $status = $document->status ?? 'draft';

    $isProjectHead = $isProjectHead ?? false;

    $isEditable = $isProjectHead && (
        in_array($status, ['draft','submitted','returned'])
        || ($status === 'approved_by_sacdev' && $document->edit_mode)
    );

    if (in_array($status, ['approved','approved_by_sacdev'])) {
        $isEditable = false;
    }

    $isReadOnly = !$isEditable;

    $statusStyles = [
        'draft' => 'bg-slate-50 text-slate-700 border-slate-200',
        'submitted' => 'bg-blue-50 text-blue-800 border-blue-200',
        'returned' => 'bg-rose-50 text-rose-800 border-rose-200',
        'approved' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
        'approved_by_sacdev' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
    ];

    $style = $statusStyles[$status] ?? $statusStyles['draft'];

    $currentApprover = $document?->signatures
        ?->where('status','pending')
        ->sortBy('id')
        ->first();
@endphp


{{-- ================= STATUS --}}
<div class="border {{ $style }} px-4 py-3 text-sm">
    <div class="flex justify-between items-center flex-wrap gap-2">

        <div class="font-semibold tracking-wide">
            REQUEST TO PURCHASE STATUS:
            <span class="uppercase ml-1">{{ $status }}</span>
        </div>

        @if($status === 'submitted' && $currentApprover)
        <div class="text-xs font-medium">
            Awaiting:
            <span class="capitalize font-semibold">
                {{ str_replace('_',' ', $currentApprover->role) }}
            </span>
        </div>
        @endif

    </div>
</div>


{{-- ================= REMARKS --}}
@if(isset($document) && $document->remarks && $isProjectHead)
<div class="rounded-2xl border border-amber-200 bg-amber-50 p-4 shadow-sm">
    <div class="text-sm font-semibold text-amber-800 mb-1">
        Returned for Revision
    </div>

    <div class="text-sm text-amber-700">
        {{ $document->remarks }}
    </div>
</div>
@endif


{{-- ================= HEADER --}}
@include('org.projects.documents.request-to-purchase.partials._header')


{{-- ================= FORM --}}
<form id="proposalForm"
      method="POST"
      action="{{ route('org.projects.documents.request-to-purchase.store', $project) }}">

@csrf

@if($isReadOnly)
<fieldset disabled class="space-y-6">
@endif


<div class="space-y-6">

    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        @include('org.projects.documents.request-to-purchase.partials._fund-sources')
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        @include('org.projects.documents.request-to-purchase.partials._items-table')
    </div>

</div>


@if($isReadOnly)
</fieldset>
@endif

</form>


{{-- ================= SIGNATURES --}}
<div class="rounded-2xl border bg-white p-5 shadow-sm">
    @include('org.projects.documents.project-proposal.partials._signatures')
</div>


{{-- ================= ACTIONS --}}
@include('components.project-document.actions._actions', [
    'project' => $project,
    'document' => $document,
    'currentSignature' => $document?->signatures
        ?->where('user_id', auth()->id())
        ->first(),
    'isProjectHead' => $isProjectHead ?? false,
    'isAdmin' => auth()->user()->system_role === 'sacdev_admin',
])


{{-- ================= MODALS --}}
@include('org.projects.documents.request-to-purchase.partials._instructions_modal')

@include('org.projects.documents.request-to-purchase.partials._scripts')

</div>



</x-app-layout>