<div class="border border-slate-300 bg-white sticky bottom-0 shadow-md">

<div class="px-4 py-3 flex justify-end gap-3 flex-wrap">

@php
$status = $document->status ?? 'draft';
@endphp


{{-- PROJECT HEAD ACTIONS --}}
@if($isProjectHead && in_array($status, ['draft','returned']))

<button
    type="submit"
    name="action"
    value="draft"
    form="budgetForm"
    class="border border-slate-400 px-4 py-2 text-[12px] hover:bg-slate-100">
Save Draft
</button>

<button
    type="submit"
    name="action"
    value="submit"
    form="budgetForm"
    class="bg-blue-900 px-4 py-2 text-white text-[12px] hover:bg-blue-800">
Submit Budget
</button>

@endif


{{-- APPROVER ACTIONS --}}
@if(
    $document &&
    $document->status === 'submitted' &&
    $currentSignature &&
    $currentSignature->status === 'pending'
)

@php
$isSacdev = $currentSignature->role === 'sacdev_admin';
@endphp


<form method="POST"
      action="{{ $isSacdev
        ? route('admin.projects.documents.approve', [$project, 'BUDGET_PROPOSAL'])
        : route('org.projects.budget-proposal.approve', $project)
      }}">
@csrf

<button
    type="submit"
    class="bg-emerald-700 px-4 py-2 text-white text-[12px] hover:bg-emerald-600">
Approve
</button>

</form>


<button
    type="button"
    onclick="openReturnModal()"
    class="border border-rose-400 text-rose-600 px-4 py-2 text-[12px] hover:bg-rose-50">
Return
</button>

@endif

</div>

</div>


{{-- ADMIN RETRACT APPROVAL --}}
@if(
$document &&
$isAdmin &&
$document->status === 'approved_by_sacdev'
)

<div class="border border-slate-300 bg-white sticky bottom-0 z-50 shadow-md">

<div class="px-4 py-3 flex justify-end">

<form method="POST"
action="{{ route('admin.projects.documents.retract', [$project, $document->formType->code]) }}">

@csrf

<button
class="bg-amber-600 px-4 py-2 text-[12px] text-white hover:bg-amber-700">
Retract SACDEV Approval
</button>

</form>

</div>

</div>

@endif



{{-- RETURN MODAL --}}
<div id="returnModal"
     class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">

<div class="bg-white rounded-lg shadow-lg w-full max-w-md">

<div class="border-b px-4 py-3 font-semibold text-sm">
Return Budget Proposal
</div>

<form method="POST"
      action="{{ isset($currentSignature) && $currentSignature->role === 'sacdev_admin'
        ? route('admin.projects.documents.return', [$project, 'BUDGET_PROPOSAL'])
        : route('org.projects.budget-proposal.return', $project)
      }}">

@csrf

<div class="p-4">

<label class="block text-[12px] font-medium mb-2">
Remarks (required)
</label>

<textarea
    name="remarks"
    required
    rows="4"
    class="w-full border border-slate-300 px-3 py-2 text-[12px]"
></textarea>

</div>

<div class="border-t px-4 py-3 flex justify-end gap-2">

<button
    type="button"
    onclick="closeReturnModal()"
    class="border border-slate-300 px-4 py-2 text-[12px] hover:bg-slate-100">
Cancel
</button>

<button
    type="submit"
    class="bg-rose-600 text-white px-4 py-2 text-[12px] hover:bg-rose-500">
Return Document
</button>

</div>

</form>

</div>

</div>


<script>

function openReturnModal() {
    const modal = document.getElementById('returnModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeReturnModal() {
    const modal = document.getElementById('returnModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

</script>