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
<div class="border {{ $style }} px-4 py-3 text-sm">

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">

        <div class="font-semibold tracking-wide">
            APPLICATION FOR SELLING STATUS:
            <span class="ml-1 uppercase">{{ $status }}</span>
        </div>

        @if($status === 'submitted' && $currentApprover)
            <div class="text-[12px] font-medium">
                Awaiting:
                <span class="capitalize font-semibold">
                    {{ str_replace('_',' ', $currentApprover->role) }}
                </span>
            </div>
        @endif

        @if($status === 'approved')
            <div class="text-[12px] font-medium">
                Fully approved and finalized.
            </div>
        @endif

        @if($status === 'draft')
            <div class="text-[12px]">
                This form is still editable.
            </div>
        @endif

        @if($status === 'returned')
            <div class="text-[12px] font-medium">
                Returned for revision. Please update and resubmit.
            </div>
        @endif

    </div>
</div>


{{-- ================= REMARKS ================= --}}
@if(isset($document) && $document->remarks && $isProjectHead)
<div class="rounded-2xl border border-amber-200 bg-amber-50 p-4 shadow-sm">

    <div class="text-sm font-semibold text-amber-800 mb-1">
        Returned for Revision
    </div>

    <div class="text-sm text-amber-700">
        {{ $document->remarks }}
    </div>

    @if($document->returnedBy)
        <div class="text-xs text-amber-600 mt-2 italic">
            {{ $document->returnedBy->name }}
            • {{ \Carbon\Carbon::parse($document->returned_at)->format('M d, Y h:i A') }}
        </div>
    @endif

</div>
@endif


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

    {{-- ACTIVITY INFO --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        @include('org.projects.documents.selling.partials._activity-info')
    </div>

    {{-- GOODS TABLE --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        @include('org.projects.documents.selling.partials._goods-table')
    </div>

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