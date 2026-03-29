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
<div class="rounded-2xl border {{ $style }} px-5 py-4 shadow-sm">

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">

        <div class="font-semibold tracking-wide">
            OFF CAMPUS FORM STATUS:
            <span class="ml-1 uppercase">{{ $status }}</span>
        </div>

        @if($status === 'submitted' && $currentApprover)
            <div class="text-xs font-medium">
                Awaiting:
                <span class="capitalize font-semibold">
                    {{ str_replace('_',' ', $currentApprover->role) }}
                </span>
            </div>
        @endif

        @if(in_array($status, ['approved','approved_by_sacdev']))
            <div class="text-xs font-medium">
                Fully approved and finalized.
            </div>
        @endif

        @if($status === 'draft')
            <div class="text-xs">
                This form is still editable.
            </div>
        @endif

        @if($status === 'returned')
            <div class="text-xs font-medium">
                Returned for revision. Please update and resubmit.
            </div>
        @endif

    </div>

</div>


{{-- ================= RETURN REMARKS ================= --}}
@if(isset($document) && $document->remarks && $isProjectHead)

<div class="rounded-2xl border border-amber-200 bg-amber-50 shadow-sm p-5 relative">

    <button
        onclick="this.closest('div').remove()"
        class="absolute top-3 right-4 text-amber-500 hover:text-amber-700 text-sm">
        ✕
    </button>

    <div class="font-semibold text-amber-800 mb-2">
        Returned for Revision
    </div>

    <div class="text-sm text-amber-700 mb-2">
        {{ $document->remarks }}
    </div>

    @if($document->returnedBy)
    <div class="text-xs text-amber-600 italic">
        Returned by {{ $document->returnedBy->name }}
        @if($document->returned_at)
            on {{ \Carbon\Carbon::parse($document->returned_at)->format('F d, Y h:i A') }}
        @endif
    </div>
    @endif

</div>

@endif


{{-- ================= HEADER ================= --}}
@include('org.projects.documents.off-campus.partials._header')



{{-- ================= FORM ================= --}}
<form id="proposalForm"
      method="POST"
      action="{{ route('org.projects.documents.off-campus.store', $project) }}">

@csrf

@if($isReadOnly)
<fieldset disabled class="space-y-6">
@endif


<div class="grid gap-6">

    {{-- ACTIVITY INFO --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-5">
        @include('org.projects.documents.off-campus.partials._activity-info')
    </div>

    {{-- PARTICIPANTS --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-5">
        @include('org.projects.documents.off-campus.partials._participants')
    </div>

</div>


@if($isReadOnly)
</fieldset>
@endif

</form>


{{-- ================= SIGNATURES ================= --}}
@include('org.projects.documents.documentation-report.partials._signatures')


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