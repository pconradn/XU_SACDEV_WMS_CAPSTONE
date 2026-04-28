<x-app-layout>

<div class="max-w-6xl mx-auto space-y-6">

@php
    $status = $document->status ?? 'draft';

    $isProjectHead = $isProjectHead ?? false;

    $isDraftee = \App\Models\ProjectAssignment::where('project_id', $project->id)
        ->where('user_id', auth()->id())
        ->where('assignment_role', 'draftee')
        ->whereNull('archived_at')
        ->exists();

    $canEditRole = $isProjectHead || $isDraftee;

    $isEditable = $canEditRole && ($status === 'draft');

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

{{-- ================= STATUS CARD ================= --}}
@include('components.document.status-bar', ['document' => $document])


{{-- ================= HEADER ================= --}}
@include('org.projects.documents.solicitation-sponsorship-report.partials._header')


<div class="rounded-xl border border-slate-200 bg-slate-50 p-4 flex justify-between items-center">

    <div class="text-xs text-slate-600">
        Please review submission guidelines before proceeding.
    </div>

    <button onclick="openModal('instructionsModal')"
        class="text-xs font-medium text-blue-600 hover:underline">
        View Full Instructions
    </button>

</div>


{{-- ================= FORM ================= --}}
<form
    id="proposalForm"
    method="POST"
    action="{{ route('org.projects.documents.solicitation-sponsorship-report.store', $project) }}"
>
@csrf
<input type="hidden" name="last_updated_at" value="{{ $document->updated_at }}">
<input type="hidden" name="action" id="formAction" value="draft">

@if($isReadOnly)
<fieldset disabled class="space-y-6">
@endif

<div class="grid gap-6">


    @include('org.projects.documents.solicitation-sponsorship-report.partials._activity-info')




    @include('org.projects.documents.solicitation-sponsorship-report.partials._items-table')


</div>

@if($isReadOnly)
</fieldset>
@endif

</form>


{{-- ================= SIGNATURES ================= --}}
<div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-5">
    @include('org.projects.documents.project-proposal.partials._signatures')
</div>


{{-- ================= ACTIONS ================= --}}
@include('components.project-document.actions._actions', [
    'project' => $project,
    'document' => $document,
    'currentSignature' => $document?->signatures
        ?->where('user_id', auth()->id())
        ->first(),
    'isProjectHead' => $isProjectHead ?? false,
    'isAdmin' => auth()->user()->system_role === 'sacdev_admin',
])


{{-- ================= MODALS ================= --}}
@include('org.projects.documents.solicitation-sponsorship-report.partials._items-modal')

@include('org.projects.documents.solicitation-sponsorship-report.partials._instructions-modal')


@include('org.projects.documents.solicitation-sponsorship-report.partials._scripts')

</div>

</x-app-layout>