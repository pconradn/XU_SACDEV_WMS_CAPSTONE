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
@include('components.document.status-bar', ['document' => $document])


{{-- ================= FORM ================= --}}
<form method="POST"
      action="{{ route('org.projects.documents.liquidation-report.store', $project) }}"
      id="proposalForm">

@csrf
<input type="hidden" name="action" id="formAction" value="draft">

@if($isReadOnly)
<fieldset disabled class="space-y-6">
@endif


<div class="grid gap-6">


        @include('org.projects.documents.liquidation-report.partials._header')



        @include('org.projects.documents.liquidation-report.partials._funds')



        @include('org.projects.documents.liquidation-report.partials._expenses')

    {{-- SUMMARY --}}

        @include('org.projects.documents.liquidation-report.partials._summary')

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