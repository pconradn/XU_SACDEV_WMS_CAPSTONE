<x-app-layout>

<div class="max-w-6xl mx-auto space-y-6 relative">



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



@include('org.projects.documents.liquidation-report.partials._header')

@if($isReadOnly)
<fieldset disabled class="space-y-6">
@endif


<div class="grid gap-6">


        



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
<div class="rounded-2xl border bg-white p-5 shadow-sm">
    @include('org.projects.documents.project-proposal.partials._signatures')
</div>



@include('org.projects.documents.liquidation-report.partials._script')




{{-- RECEIPT MODAL --}}
<div id="receiptModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">

    <div class="bg-white w-full max-w-lg rounded-2xl shadow-lg p-6 space-y-4">

        <h3 class="text-sm font-semibold text-slate-900">
            Add Receipt
        </h3>

        {{-- HEADER --}}
        <div class="grid grid-cols-3 gap-3">

            <select id="receiptType" class="border rounded-lg px-3 py-2 text-xs">
                <option value="">Type</option>
                <option>OR</option>
                <option>SR</option>
                <option>CI</option>
                <option>SI</option>
                <option>AR</option>
                <option>PV</option>
            </select>

            <input type="text" id="receiptRef"
                placeholder="Reference No."
                class="border rounded-lg px-3 py-2 text-xs">

            <input type="date" id="receiptDate"
                class="border rounded-lg px-3 py-2 text-xs">

        </div>

        <input type="text" id="receiptDesc"
            placeholder="Description"
            class="w-full border rounded-lg px-3 py-2 text-xs">



        {{-- ITEMS --}}
        <div class="space-y-2">
            <div class="text-xs font-medium text-slate-600">
                Items
            </div>

            <div id="receiptItems" class="space-y-2"></div>

            <button type="button"
                id="addReceiptItem"
                class="text-xs px-3 py-1 border rounded-lg">
                + Add Item
            </button>
        </div>

        {{-- ACTIONS --}}
        <div class="flex justify-end gap-2 pt-2">

            <button type="button"
                id="closeReceiptModal"
                class="text-xs px-3 py-2 border rounded-lg">
                Cancel
            </button>

            <button type="button"
                id="saveReceipt"
                class="text-xs px-3 py-2 bg-blue-600 text-white rounded-lg">
                Save
            </button>

        </div>

    </div>

</div>

<div id="receiptTooltip"
class="fixed hidden z-50 bg-white border border-slate-200 rounded-lg shadow-lg px-3 py-2 text-xs pointer-events-none">
</div>


</x-app-layout>