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


{{-- ================= STATUS ================= --}}
@include('components.document.status-bar', ['document' => $document])


{{-- ================= HEADER ================= --}}
@include('org.projects.documents.solicitation.partials._header')


{{-- ================= FORM ================= --}}
<form id="proposalForm"
      method="POST"
      action="{{ route('org.projects.documents.solicitation.store', $project) }}"
      enctype="multipart/form-data">

@csrf
<input type="hidden" name="last_updated_at" value="{{ $document->updated_at }}">
<input type="hidden" name="action" id="formAction" value="draft">

@if($isReadOnly)
<fieldset disabled class="space-y-6">
@endif


<div class="grid gap-6">


        @include('org.projects.documents.solicitation.partials._activity-info')
    


        @include('org.projects.documents.solicitation.partials._benefactors')
  

</div>


@if($isReadOnly)
</fieldset>
@endif

</form>


{{-- ================= SIGNATURES ================= --}}
<div class="rounded-2xl border bg-white p-5 shadow-sm">
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
@include('org.projects.documents.solicitation.partials._instructions_modal')
@include('org.projects.documents.solicitation.partials._agreement_modal')


@include('org.projects.documents.solicitation.partials._scripts')

</div>

</x-app-layout>