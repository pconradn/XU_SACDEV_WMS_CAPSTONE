<x-app-layout>




<div class="mx-auto max-w-5xl space-y-6">

@php
    $status = $document->status ?? 'draft';

    $isProjectHead = $isProjectHead ?? false;

            $isEditable = $isProjectHead && (
                in_array($status, ['draft','submitted','returned'])
                || ($status === 'approved_by_sacdev' && $document->edit_mode)
            );

    if ($status === 'approved') {
        $isEditable = false;
    }

    $isReadOnly = !$isEditable;

    $statusStyles = [
        'draft'     => 'bg-slate-50 text-slate-700 border-slate-200',
        'submitted' => 'bg-blue-50 text-blue-800 border-blue-200',
        'returned'  => 'bg-rose-50 text-rose-800 border-rose-200',
        'approved'  => 'bg-emerald-50 text-emerald-800 border-emerald-200',
    ];

    $style = $statusStyles[$status] ?? $statusStyles['draft'];

    $currentApprover = $document?->signatures
        ?->where('status', 'pending')
        ->sortBy('id')
        ->first();
@endphp


<div class="rounded-2xl border px-5 py-4 shadow-sm {{ $style }}">

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">

        <div class="text-sm font-semibold tracking-wide">
            Budget Proposal Status:
            <span class="ml-1 uppercase">
                {{ $status }}
            </span>
        </div>

        @if($status === 'submitted' && $currentApprover)
            <div class="text-xs font-medium">
                Awaiting:
                <span class="capitalize font-semibold">
                    {{ str_replace('_', ' ', $currentApprover->role) }}
                </span>
            </div>
        @endif

        @if($status === 'approved')
            <div class="text-xs font-medium">
                Budget proposal finalized.
            </div>
        @endif

        @if($status === 'draft')
            <div class="text-xs">
                This document is still editable.
            </div>
        @endif

        @if($status === 'returned')
            <div class="text-xs font-medium">
                Returned for revision. Please update and resubmit.
            </div>
        @endif

    </div>

</div>


@if(isset($document) && $document->remarks && $isProjectHead)

<div class="rounded-2xl border border-amber-200 bg-amber-50 p-5 shadow-sm text-sm text-amber-900 relative">

    <div class="font-semibold mb-2">
        Returned for Revision
    </div>

    <div class="text-sm leading-relaxed mb-2">
        {{ $document->remarks }}
    </div>

    @if($document->returnedBy)
        <div class="text-xs text-amber-700 italic">
            Returned by {{ $document->returnedBy->name }}
            @if($document->returned_at)
                on {{ \Carbon\Carbon::parse($document->returned_at)->format('F d, Y h:i A') }}
            @endif
        </div>
    @endif

</div>

@endif


@include('org.projects.documents.budget-proposal.partials._header')



<form method="POST"
      action="{{ route('org.projects.documents.budget-proposal.store', $project) }}"
      id="proposalForm">

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

</div>

    @include('components.project-document.actions._actions', [
        'project' => $project,
        'document' => $document,
        'currentSignature' => $document?->signatures
            ?->where('user_id', auth()->id())
            ->first(),
        'isProjectHead' => $isProjectHead ?? false,
        'isAdmin' => auth()->user()->system_role === 'sacdev_admin',
    ])


@include('org.projects.documents.budget-proposal.partials._script')

</x-app-layout>