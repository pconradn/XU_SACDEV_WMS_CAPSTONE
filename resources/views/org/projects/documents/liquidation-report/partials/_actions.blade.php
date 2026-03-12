@php
$status = $document->status ?? 'draft';
@endphp


@if($isProjectHead)

<div class="border border-slate-300 bg-white sticky bottom-0 z-50 shadow-md">

<div class="px-4 py-3 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

<div class="text-[11px] text-slate-600">

@if($status === 'submitted')
Editing and resubmitting will reset President and Moderator approvals.

@elseif($status === 'approved')
This liquidation report is finalized and cannot be modified.

@else
Saving will store this report as a draft.
Submitting will forward this document for approval.
@endif

</div>


<div class="flex items-center gap-3">

<a href="{{ route('org.projects.documents.hub', $project) }}"
class="border border-slate-400 px-4 py-2 text-[12px] text-slate-700 hover:bg-slate-100">
Cancel
</a>


@if(in_array($status, ['draft','returned']))

<button type="submit"
form="liquidationForm"
name="action"
value="draft"
class="border border-slate-500 px-4 py-2 text-[12px] text-slate-800 hover:bg-slate-100">
Save as Draft
</button>

<button type="submit"
form="liquidationForm"
name="action"
value="submit"
class="bg-blue-900 px-4 py-2 text-[12px] text-white hover:bg-blue-800">
Submit for Approval
</button>

@endif


@if($status === 'submitted')

<button type="button"
onclick="openResubmitModal()"
class="bg-amber-600 px-4 py-2 text-[12px] text-white hover:bg-amber-700">
Resubmit with Changes
</button>

@endif

</div>

</div>

</div>

@endif



@if($isProjectHead && $status === 'submitted')

<div id="resubmitModal"
class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50">

<div class="bg-white w-full max-w-md rounded shadow-lg p-6">

<h2 class="text-[14px] font-semibold mb-3">
Confirm Resubmission
</h2>

<p class="text-[12px] text-slate-600 mb-4">
Resubmitting this liquidation report will remove existing approvals.
Reviewers will need to approve the document again.
</p>

<div class="flex justify-end gap-3">

<button type="button"
onclick="closeResubmitModal()"
class="border border-slate-400 px-4 py-2 text-[12px] text-slate-700 hover:bg-slate-100">
Cancel
</button>

<button type="submit"
form="liquidationForm"
name="action"
value="draft"
class="border border-slate-500 px-4 py-2 text-[12px] text-slate-800 hover:bg-slate-100">
Yes, Resubmit
</button>

</div>

</div>

</div>

@endif



@if(
!$isProjectHead &&
$isReadOnly &&
isset($currentSignature) &&
$currentSignature &&
$currentSignature->status === 'pending'
)

<div class="border border-slate-300 bg-white sticky bottom-0 z-50 shadow-md">

<div class="px-4 py-3 flex items-center justify-end gap-3">

<form method="POST"
action="{{ $currentSignature->role === 'sacdev_admin'
? route('admin.projects.documents.approve', [$project, $document->formType->code])
: route('org.projects.liquidation-report.approve', $project) }}">

@csrf

<button type="submit"
class="bg-emerald-600 px-4 py-2 text-[12px] text-white hover:bg-emerald-700">
Approve
</button>

</form>


<button type="button"
onclick="openReturnModal()"
class="bg-rose-600 px-4 py-2 text-[12px] text-white hover:bg-rose-700">
Return with Remarks
</button>

</div>

</div>

@endif


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




<div id="returnModal"
class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50">

<div class="bg-white w-full max-w-md rounded shadow-lg p-6">

<h2 class="text-[14px] font-semibold mb-3">
Return Liquidation Report
</h2>

<p class="text-[12px] text-slate-600 mb-4">
Please provide remarks explaining why this report is being returned.
</p>


<form method="POST"
action="{{ optional($currentSignature)->role === 'sacdev_admin'
? route('admin.projects.documents.return', [$project, $document->formType->code])
: route('org.projects.liquidation-report.return', $project) }}">

@csrf

<textarea
name="remarks"
required
rows="4"
class="w-full border border-slate-300 px-3 py-2 text-[12px]"></textarea>

<div class="flex justify-end gap-3 mt-4">

<button type="button"
onclick="closeReturnModal()"
class="border border-slate-400 px-4 py-2 text-[12px] text-slate-700 hover:bg-slate-100">
Cancel
</button>

<button type="submit"
class="bg-rose-600 px-4 py-2 text-[12px] text-white hover:bg-rose-700">
Return Report
</button>

</div>

</form>

</div>

</div>



<script>

function openReturnModal() {
document.getElementById('returnModal')?.classList.remove('hidden');
document.getElementById('returnModal')?.classList.add('flex');
}

function closeReturnModal() {
document.getElementById('returnModal')?.classList.add('hidden');
document.getElementById('returnModal')?.classList.remove('flex');
}

function openResubmitModal() {
document.getElementById('resubmitModal')?.classList.remove('hidden');
document.getElementById('resubmitModal')?.classList.add('flex');
}

function closeResubmitModal() {
document.getElementById('resubmitModal')?.classList.add('hidden');
document.getElementById('resubmitModal')?.classList.remove('flex');
}

</script>