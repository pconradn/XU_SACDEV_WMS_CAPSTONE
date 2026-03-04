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

<div class="border border-slate-300 {{ $style }} px-4 py-3 text-sm mb-6">

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

@include('org.projects.documents.budget-proposal.partials._actions')


</div>


@include('org.projects.documents.budget-proposal.partials._script')

</x-layouts.form-only>