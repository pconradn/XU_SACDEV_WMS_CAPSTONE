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
   
    $document = $proposalData['document'] ?? null;
    $status = $document->status ?? 'draft';

    $isProjectHead = $proposalData['isProjectHead'] ?? false;

    $isDraftee = \App\Models\ProjectAssignment::where('project_id', $project->id)
        ->where('user_id', auth()->id())
        ->where('assignment_role', 'draftee')
        ->whereNull('archived_at')
        ->exists();

    $canEditRole = $isProjectHead || $isDraftee;

    $isEditable = (
        ($isProjectHead && (
            in_array($status, ['draft', 'submitted', 'returned'])
            || ($status === 'approved_by_sacdev' && $document->edit_mode)
        ))
        || ($isDraftee && $status === 'draft')
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

@php
    $isAdminDocumentPage = auth()->user()?->system_role === 'sacdev_admin';

    $documentTitle = $document->formType?->name
        ?? $document->formType?->code
        ?? 'Document';
@endphp

<div class="bg-slate-50 pt-6">
    <div class="max-w-7xl mx-auto px-4">
        <nav class="text-xs text-slate-500">
            <ol class="flex flex-wrap items-center gap-1.5">

                @if($isAdminDocumentPage)

                    <li>
                        <a href="{{ route('admin.orgs_by_sy.index') }}"
                           class="font-medium text-slate-600 hover:text-slate-900 transition">
                            Organizations by School Year
                        </a>
                    </li>

                    <li class="text-slate-300">/</li>

                    <li>
                        <a href="{{ route('admin.orgs_by_sy.show', [$project->organization_id, $project->school_year_id]) }}"
                           class="font-medium text-slate-600 hover:text-slate-900 transition">
                            {{ $project->organization?->acronym ?: $project->organization?->name }}
                        </a>
                    </li>

                    <li class="text-slate-300">/</li>

                    <li>
                        <a href="{{ route('admin.org.projects.index', [$project->organization_id, $project->school_year_id]) }}"
                           class="font-medium text-slate-600 hover:text-slate-900 transition">
                            Projects
                        </a>
                    </li>

                    <li class="text-slate-300">/</li>

                    <li>
                        <a href="{{ route('admin.projects.documents.hub', $project) }}"
                           class="font-medium text-slate-600 hover:text-slate-900 transition">
                            Document Hub
                        </a>
                    </li>

                @else

                    <li>
                        <a href="{{ route('org.organization-info.show') }}"
                           class="font-medium text-slate-600 hover:text-slate-900 transition">
                            Organization
                        </a>
                    </li>

                    <li class="text-slate-300">/</li>

                    <li>
                        <a href="{{ route('org.projects.index') }}"
                           class="font-medium text-slate-600 hover:text-slate-900 transition">
                            Projects
                        </a>
                    </li>

                    <li class="text-slate-300">/</li>

                    <li>
                        <a href="{{ route('org.projects.documents.hub', $project) }}"
                           class="font-medium text-slate-600 hover:text-slate-900 transition">
                            Document Hub
                        </a>
                    </li>

                @endif

                <li class="text-slate-300">/</li>

                <li class="font-medium text-indigo-700 truncate max-w-[220px]">
                    {{ $documentTitle }}
                </li>

            </ol>
        </nav>
    </div>
</div>

@include('components.document.status-bar', ['document' => $document])

{{-- HEADER --}}
@include('org.projects.documents.project-proposal.partials._header', [
    'project' => $project
])


<form method="POST"
      action="{{ route('org.projects.documents.combined-proposal.store', $project) }}"
      id="proposalForm">
      

@csrf
<input type="hidden" name="action" id="formAction" value="draft">

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

    <div class="mt-10 border-t pt-6 space-y-6">

        <div id="budgetSectionsWrapper">


        
            @include('org.projects.documents.budget-proposal.partials._budget_sections')
        </div>



    </div>

    @include('org.projects.documents.project-proposal.partials._guests_plan_of_action')
</div>



@if($isReadOnly)
</fieldset>
@endif

</form>


@include('org.projects.documents.project-proposal.partials._student_agreement')

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