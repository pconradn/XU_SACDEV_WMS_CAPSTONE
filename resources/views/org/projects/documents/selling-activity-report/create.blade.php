<x-app-layout>

<div class="max-w-6xl mx-auto space-y-6">

@php
$docStatus = $document->status ?? 'draft';

$isProjectHead = $isProjectHead ?? false;

$isDraftee = \App\Models\ProjectAssignment::where('project_id', $project->id)
    ->where('user_id', auth()->id())
    ->where('assignment_role', 'draftee')
    ->whereNull('archived_at')
    ->exists();

$canEditRole = $isProjectHead || $isDraftee;

$isEditable = $canEditRole && ($docStatus === 'draft');

if (in_array($docStatus, ['approved','approved_by_sacdev'])) {
    $isEditable = false;
}

$isReadOnly = !$isEditable;

$currentApprover = $document?->signatures
    ?->where('status','pending')
    ->sortBy('id')
    ->first();
@endphp


{{-- ================= STATUS BAR ================= --}}
@include('components.document.status-bar', ['document' => $document])


{{-- ================= HEADER ================= --}}
@include('org.projects.documents.selling-activity-report.partials._header')


{{-- ================= FORM ================= --}}
<form id="proposalForm"
      method="POST"
      action="{{ route('org.projects.documents.selling-activity-report.store', $project) }}">

@csrf
<input type="hidden" name="last_updated_at" value="{{ $document->updated_at }}">
<input type="hidden" name="action" id="formAction" value="draft">

@if($isReadOnly)
<fieldset disabled class="space-y-6">
@endif


<div class="space-y-6">


    @include('org.projects.documents.selling-activity-report.partials._activity-info')



    @include('org.projects.documents.selling-activity-report.partials._items-table')



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


{{-- ================= SCRIPTS ================= --}}
@include('org.projects.documents.selling-activity-report.partials._scripts')


</div>

</x-app-layout>