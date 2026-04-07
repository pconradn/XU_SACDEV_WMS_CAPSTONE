<x-app-layout>

<div class="max-w-6xl mx-auto space-y-6">

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


{{-- ================= STATUS CARD ================= --}}
@include('components.document.status-bar', ['document' => $document])


{{-- ================= HEADER ================= --}}
<div>
    @include('org.projects.documents.selling.partials._header')
</div>


{{-- ================= FORM ================= --}}
<form id="proposalForm"
      method="POST"
      action="{{ route('org.projects.documents.selling.store', $project) }}">

@csrf

@if($isReadOnly)
<fieldset disabled class="space-y-6">
@endif


<div class="grid gap-6">

    @include('org.projects.documents.selling.partials._activity-info')


    @include('org.projects.documents.selling.partials._goods-table')
  

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
@include('org.projects.documents.selling.partials._instructions_modal')


@include('org.projects.documents.selling.partials._scripts')

</div>

@if($isProjectHead && $status === 'draft')
<script>
    window.addEventListener('DOMContentLoaded', () => {
        openInstructionModal();
    });
</script>
@endif


<script>
    

    function openInstructionModal() {
        const modal = document.getElementById('instructionModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeInstructionModal() {
        const modal = document.getElementById('instructionModal');
        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }
</script>

</x-app-layout>