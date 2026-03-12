<x-layouts.form-only
    title="Budget Proposal — {{ $project->title }}"
    :backRoute="route('org.projects.documents.hub', $project)"
>












<div class="mx-auto max-w-5xl">

@php
    $status = $document->status ?? 'draft';

    $isProjectHead = $isProjectHead ?? false;

    $isEditable = $isProjectHead && in_array($status, ['draft','returned']);

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

<div class="border border-slate-300 {{ $style }} px-4 py-3 text-sm ">

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">

        <div class="font-semibold tracking-wide">
            BUDGET PROPOSAL STATUS:
            <span class="ml-1 uppercase">
                {{ $status }}
            </span>
        </div>

        @if($status === 'submitted' && $currentApprover)
            <div class="text-[12px] font-medium">
                Awaiting:
                <span class="capitalize font-semibold">
                    {{ str_replace('_', ' ', $currentApprover->role) }}
                </span>
            </div>
        @endif

        @if($status === 'approved')
            <div class="text-[12px] font-medium">
                Budget proposal finalized.
            </div>
        @endif

        @if($status === 'draft')
            <div class="text-[12px]">
                This document is still editable.
            </div>
        @endif

        @if($status === 'returned')
            <div class="text-[12px] font-medium">
                Returned for revision. Please update and resubmit.
            </div>
        @endif

    </div>

</div>


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


@include('org.projects.documents.budget-proposal.partials._header')
@include('org.projects.documents.budget-proposal.partials._flash')


<form method="POST"
      action="{{ route('org.projects.budget-proposal.store', $project) }}"
      id="budgetForm">

@csrf

@if($isReadOnly)
<fieldset disabled class="space-y-6">
@endif


@include('org.projects.documents.budget-proposal.partials._budget_sections')

@include('org.projects.documents.budget-proposal.partials._sources_of_funds')


@if($isReadOnly)
</fieldset>
@endif

</form>
@include('org.projects.documents.budget-proposal.partials._signatures')

@include('org.projects.documents.budget-proposal.partials._actions')


</div>


@include('org.projects.documents.budget-proposal.partials._script')

</x-layouts.form-only>