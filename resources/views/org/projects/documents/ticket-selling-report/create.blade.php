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

{{-- ================= HEADER ================= --}}
@include('org.projects.documents.ticket-selling-report.partials._header')


{{-- ================= FORM ================= --}}
<form
    id="proposalForm"
    method="POST"
    action="{{ route('org.projects.documents.ticket-selling-report.store', $project) }}"
>
@csrf
<input type="hidden" name="action" id="formAction" value="draft">
@if($isReadOnly)
<fieldset disabled class="space-y-6">
@endif

<div class="grid gap-6">


    @include('org.projects.documents.ticket-selling-report.partials._activity-info')



    @include('org.projects.documents.ticket-selling-report.partials._items-table')


</div>

@if($isReadOnly)
</fieldset>
@endif

</form>


{{-- ================= SIGNATURES ================= --}}
<div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-5">

    <h3 class="text-sm font-semibold text-slate-800 mb-4">
        Approval Trail
    </h3>

    @if($document && $document->signatures && $document->signatures->count())

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            @foreach($document->signatures->sortBy('id') as $sig)

                <div class="border border-slate-200 rounded-xl p-4 flex justify-between items-center">

                    <div>
                        <div class="text-sm font-medium capitalize">
                            {{ str_replace('_', ' ', $sig->role) }}
                        </div>

                        <div class="text-xs text-slate-500">
                            {{ $sig->user?->name ?? 'Unknown User' }}
                        </div>
                    </div>

                    <div class="text-right">

                        @if($sig->status === 'signed')
                            <div class="text-emerald-700 text-xs font-semibold">
                                Approved
                            </div>
                            <div class="text-xs text-slate-500">
                                {{ $sig->signed_at?->format('M d, Y h:i A') }}
                            </div>
                        @else
                            <div class="text-amber-600 text-xs font-semibold">
                                Pending
                            </div>
                        @endif

                    </div>

                </div>

            @endforeach

        </div>

    @else

        <div class="text-xs text-slate-400">
            No approval records yet.
        </div>

    @endif

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


@include('org.projects.documents.ticket-selling-report.partials._scripts')

</div>

</x-app-layout>