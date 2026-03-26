<x-app-layout>

<div class="max-w-6xl mx-auto space-y-6">

@php
    $status = $document->status ?? 'draft';

    $isProjectHead = $isProjectHead ?? false;

    $isEditable = $isProjectHead && in_array($status, ['draft','submitted','returned']);

    if (in_array($status, ['approved','approved_by_sacdev'])) {
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
<div class="rounded-2xl border {{ $style }} px-5 py-4 shadow-sm">

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">

        <div class="font-semibold tracking-wide">
            LIQUIDATION REPORT STATUS:
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

        @if($status === 'approved')
            <div class="text-xs font-medium">
                Fully approved and finalized.
            </div>
        @endif

        @if($status === 'draft')
            <div class="text-xs">
                This report is still editable.
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


{{-- ================= FORM ================= --}}
<form method="POST"
      action="{{ route('org.projects.documents.liquidation-report.store', $project) }}"
      id="proposalForm">

@csrf

@if($isReadOnly)
<fieldset disabled class="space-y-6">
@endif


<div class="grid gap-6">

    {{-- HEADER --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-5">
        @include('org.projects.documents.liquidation-report.partials._header')
    </div>

    {{-- SOURCE OF FUNDS --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-5">
        @include('org.projects.documents.liquidation-report.partials._funds')
    </div>

    {{-- EXPENSES --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-5">
        @include('org.projects.documents.liquidation-report.partials._expenses')
    </div>

    {{-- SUMMARY --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-5">
        @include('org.projects.documents.liquidation-report.partials._summary')
    </div>

</div>


@if($isReadOnly)
</fieldset>
@endif

</form>


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


{{-- ================= SIGNATURE TRAIL ================= --}}
@if($document && $document->signatures && $document->signatures->count())

<div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

    <div class="bg-slate-50 border-b border-slate-200 px-5 py-3 text-xs font-semibold tracking-wide">
        Approval Trail
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 divide-y md:divide-y-0 md:divide-x">

        @foreach($document->signatures->sortBy('id') as $sig)

        @php $sigStatus = $sig->status; @endphp

        <div class="px-5 py-4 flex justify-between items-center text-sm">

            <div class="flex flex-col">
                <div class="font-medium capitalize">
                    {{ str_replace('_',' ', $sig->role) }}
                </div>
                <div class="text-slate-500 text-xs">
                    {{ $sig->user?->name ?? 'Unknown User' }}
                </div>
            </div>

            <div class="text-right">

                @if($sigStatus === 'signed')
                    <div class="text-emerald-600 font-semibold">
                        Approved
                    </div>
                    <div class="text-xs text-slate-500">
                        {{ $sig->signed_at?->format('M d, Y h:i A') }}
                    </div>

                @elseif($sigStatus === 'pending')
                    <div class="text-amber-500 font-semibold">
                        Pending
                    </div>
                @endif

            </div>

        </div>

        @endforeach

    </div>

</div>

@endif


@include('org.projects.documents.liquidation-report.partials._script')

</div>

</x-app-layout>