<x-app-layout>

<div class="max-w-6xl mx-auto space-y-6">



@php
    $isCombined = true;
@endphp

@php
    $proposal = $proposalData['proposal'] ?? null;
    $document = $proposalData['document'] ?? null;
    $budget = $budgetData['budget'] ?? null;
@endphp



@php

$proposalDoc = $proposalData['document'] ?? null;
$budgetDoc   = $budgetData['document'] ?? null;

@endphp




@php
   
    $document = $proposalData['document'];
    $status = $document->status ?? 'draft';

    $isProjectHead = $proposalData['isProjectHead'] ?? false;

    $isEditable = $isProjectHead && (
        in_array($status, ['draft','submitted','returned'])
        || ($status === 'approved_by_sacdev' && $document->edit_mode)
    );

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

{{-- STATUS --}}
<div class="border {{ $style }} px-4 py-3 text-sm">
    <div class="flex justify-between">
        <div>
            PROJECT PROPOSAL + BUDGET STATUS:
            <span class="ml-1 uppercase">{{ $status }}</span>
        </div>

        @if($status === 'submitted' && $currentApprover)
            <div class="text-xs">
                Awaiting:
                {{ str_replace('_', ' ', $currentApprover->role) }}
            </div>
        @endif
    </div>
</div>

{{-- REMARKS --}}
@if(isset($document) && $document->remarks && $isProjectHead)
<div class="rounded-2xl border border-amber-200 bg-amber-50 p-4">
    {{ $document->remarks }}
</div>
@endif

{{-- HEADER --}}
@include('org.projects.documents.project-proposal.partials._header', [
    'project' => $project
])


<form method="POST"
      action="{{ route('org.projects.documents.combined-proposal.store', $project) }}"
      id="proposalForm">

@csrf

@if($isReadOnly)
<fieldset disabled class="space-y-6">
@endif

{{-- ================= PROPOSAL SECTION ================= --}}
<div class="space-y-6">
    @include('org.projects.documents.project-proposal.partials._schedule_venue')
    @include('org.projects.documents.project-proposal.partials._nature_sdg_area')
    @include('org.projects.documents.project-proposal.partials._description_link_cluster')
    @include('org.projects.documents.project-proposal.partials._multi_entries')

    @include('org.projects.documents.project-proposal.partials._budget_funds_audience')


    @include('org.projects.documents.combined.partials._combined_funding')



    @include('org.projects.documents.project-proposal.partials._guests_plan_of_action')
</div>

{{-- ================= BUDGET SECTION ================= --}}
<div class="mt-10 border-t pt-6 space-y-6">

    <div class="text-lg font-semibold">
        Budget Proposal Section
    </div>

    <div id="budgetSectionsWrapper">
        @include('org.projects.documents.budget-proposal.partials._budget_sections')
    </div>



</div>

@if($isReadOnly)
</fieldset>
@endif

</form>

{{-- SIGNATURES (proposal only drives) --}}
@include('org.projects.documents.project-proposal.partials._signatures')

{{-- ACTIONS --}}
@include('components.project-document.actions._actions', [
    'project' => $project,
    'document' => $document,
    'currentSignature' => $document?->signatures
        ?->where('user_id', auth()->id())
        ->first(),
    'isProjectHead' => $isProjectHead ?? false,
    'isAdmin' => auth()->user()->system_role === 'sacdev_admin',
    'isCombined' => true,
])

</div>

{{-- SCRIPTS --}}
@include('org.projects.documents.project-proposal.partials._script')
@include('org.projects.documents.budget-proposal.partials._script')

<script>
document.addEventListener('DOMContentLoaded', () => {

    function toggleBudgetSections() {

        const total = parseFloat(
            document.getElementById('hidden_total_budget')?.value || 0
        );

        const sections = document.querySelectorAll('[data-budget-section]');
        const budgetWrapper = document.querySelector('#budgetSectionsWrapper');

        if (total <= 0) {

            sections.forEach(el => el.classList.add('hidden'));

            if (budgetWrapper) {
                budgetWrapper.classList.add('hidden');
            }

        } else {

            sections.forEach(el => el.classList.remove('hidden'));

            if (budgetWrapper) {
                budgetWrapper.classList.remove('hidden');
            }
        }
    }

    toggleBudgetSections();

    document.addEventListener('input', () => {
        toggleBudgetSections();
    });

});
</script>



</x-app-layout>