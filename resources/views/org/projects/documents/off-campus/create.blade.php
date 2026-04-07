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
    'draft'              => 'bg-slate-50 text-slate-700 border-slate-200',
    'submitted'          => 'bg-blue-50 text-blue-800 border-blue-200',
    'returned'           => 'bg-rose-50 text-rose-800 border-rose-200',
    'approved'           => 'bg-emerald-50 text-emerald-800 border-emerald-200',
    'approved_by_sacdev' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
];

$style = $statusStyles[$status] ?? $statusStyles['draft'];

$currentApprover = $document?->signatures
    ?->where('status', 'pending')
    ->sortBy('id')
    ->first();

@endphp


{{-- ================= STATUS CARD ================= --}}
@include('components.document.status-bar', ['document' => $document])



@include('org.projects.documents.off-campus.partials._header')



{{-- ================= FORM ================= --}}
<form id="proposalForm"
      method="POST"
      action="{{ route('org.projects.documents.off-campus.store', $project) }}">

@csrf
<input type="hidden" name="action" id="formAction" value="draft">
@if($isReadOnly)
<fieldset disabled class="space-y-6">
@endif


<div class="grid gap-6">

    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

    {{-- ACCENT LINE --}}
    
    <div class="h-1 bg-blue-500"></div>
    
    <div class=" border border-slate-200 bg-white shadow-sm p-5">
        
        
        @include('org.projects.documents.off-campus.partials._activity-info')
    </div>

    </div>

    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

    {{-- ACCENT LINE --}}
    
    <div class="h-1 bg-blue-500"></div>
    
    <div class=" border border-slate-200 bg-white shadow-sm p-5">
        @include('org.projects.documents.off-campus.partials._participants')
    </div>

    </div>

</div>


@if($isReadOnly)
</fieldset>
@endif

</form>


{{-- ================= SIGNATURES ================= --}}
@include('org.projects.documents.project-proposal.partials._signatures')


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
@include('org.projects.documents.off-campus.partials._scripts')


</div>

</x-app-layout>