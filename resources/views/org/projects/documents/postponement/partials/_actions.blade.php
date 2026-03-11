@php
$status = $document->status ?? 'draft';
$isAdmin = auth()->user()->system_role === 'sacdev_admin';
@endphp


{{-- PROJECT HEAD ACTIONS --}}
@if($isProjectHead)

<div class="border border-slate-300 bg-white sticky bottom-0 z-50 shadow-md">

<div class="px-4 py-3 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

<div class="text-[11px] text-slate-600">

@if($status === 'submitted')
Editing and resubmitting will reset President and Moderator approvals.

@elseif($status === 'approved')
This form is finalized and cannot be modified.

@else
Saving will store this form as a draft.
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
form="postponementForm"
name="action"
value="draft"
class="border border-slate-500 px-4 py-2 text-[12px] text-slate-800 hover:bg-slate-100">
Save as Draft
</button>

<button type="button"
onclick="openAgreementModal()"
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



{{-- RESUBMIT MODAL --}}
@if($isProjectHead && $status === 'submitted')

<div id="resubmitModal"
class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">

<div class="bg-white w-full max-w-md rounded shadow-lg p-6">

<h2 class="text-[14px] font-semibold mb-3">
Confirm Resubmission
</h2>

<p class="text-[12px] text-slate-600 mb-4">
Resubmitting this form will remove existing approvals from the President and Moderator.
They will be required to review and approve this document again.
</p>

<div class="flex justify-end gap-3">

<button type="button"
onclick="closeResubmitModal()"
class="border border-slate-400 px-4 py-2 text-[12px] text-slate-700 hover:bg-slate-100">
Cancel
</button>

<button type="submit"
form="postponementForm"
name="action"
value="draft"
class="border border-slate-500 px-4 py-2 text-[12px] text-slate-800 hover:bg-slate-100">
Yes, Resubmit
</button>

</div>

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



{{-- APPROVAL ACTIONS --}}
@if(
$document &&
$document->status === 'submitted' &&
$currentSignature &&
$currentSignature->status === 'pending' &&
$currentSignature->user_id === auth()->id()
)

<div class="border border-slate-300 bg-white sticky bottom-0 z-50 shadow-md">

<div class="px-4 py-3 flex items-center justify-end gap-3">

{{-- APPROVE --}}
@if($isAdmin)

<form method="POST"
action="{{ route('admin.projects.documents.approve', [$project, $document->formType->code]) }}">
@csrf
<button
class="bg-emerald-600 px-4 py-2 text-white text-[12px] hover:bg-emerald-700">
Approve
</button>
</form>

@else

<form method="POST"
action="{{ route('org.projects.postponement.approve', [$project, $document]) }}">

@csrf

<button
class="bg-emerald-600 px-4 py-2 text-white text-[12px] hover:bg-emerald-700">
Approve
</button>

</form>

@endif


{{-- RETURN --}}
<button
type="button"
onclick="openReturnModal()"
class="bg-rose-600 px-4 py-2 text-[12px] text-white hover:bg-rose-700">
Return with Remarks
</button>

</div>

</div>

@endif



{{-- RETURN MODAL --}}
<div id="returnModal"
class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">

<div class="bg-white w-full max-w-md rounded shadow-lg p-6">

<h2 class="text-[14px] font-semibold mb-3">
Return Form
</h2>

<p class="text-[12px] text-slate-600 mb-4">
Returning this form will send it back to the Project Head for revision.
Please provide remarks explaining the required changes.
</p>

<form method="POST"
action="{{ $isAdmin
? route('admin.projects.documents.return', [$project, $document->formType->code])
: route('org.projects.postponement.return', [$project, $document]) }}">

@csrf

<textarea
name="remarks"
required
rows="4"
class="w-full border border-slate-300 px-3 py-2 text-[12px]"
placeholder="Enter remarks for revision..."></textarea>

<div class="flex justify-end gap-3 mt-4">

<button
type="button"
onclick="closeReturnModal()"
class="border border-slate-400 px-4 py-2 text-[12px] text-slate-700 hover:bg-slate-100">
Cancel
</button>

<button
type="submit"
class="bg-rose-600 px-4 py-2 text-[12px] text-white hover:bg-rose-700">
Return Form
</button>

</div>

</form>

</div>

</div>



{{-- SUBMIT CONFIRMATION --}}
<div id="agreementModal"
class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">

<div class="bg-white rounded-lg shadow-lg w-full max-w-md">

<div class="border-b px-4 py-3 font-semibold text-sm">
Confirm Submission
</div>

<div class="p-4 text-[12px] text-slate-700 space-y-3">

<p>
Submitting this Postponement Notice will forward it for approval to the
President, Moderator, and SACDEV Office.
</p>

<p class="font-medium">
You will not be able to edit this form unless it is returned for revision.
</p>

</div>

<div class="border-t px-4 py-3 flex justify-end gap-2">

<button
type="button"
onclick="closeAgreementModal()"
class="border border-slate-300 px-4 py-2 text-[12px]">
Cancel
</button>

<button
type="submit"
form="postponementForm"
name="action"
value="submit"
class="bg-blue-900 text-white px-4 py-2 text-[12px] hover:bg-blue-800">
Yes, Submit
</button>

</div>

</div>

</div>



<script>

function openAgreementModal() {
const modal = document.getElementById('agreementModal');
modal.classList.remove('hidden');
modal.classList.add('flex');
}

function closeAgreementModal() {
const modal = document.getElementById('agreementModal');
modal.classList.add('hidden');
modal.classList.remove('flex');
}

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

function openResubmitModal() {
const modal = document.getElementById('resubmitModal');
modal.classList.remove('hidden');
modal.classList.add('flex');
}

function closeResubmitModal() {
const modal = document.getElementById('resubmitModal');
modal.classList.add('hidden');
modal.classList.remove('flex');
}

</script>
