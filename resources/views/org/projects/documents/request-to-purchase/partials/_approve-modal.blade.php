<div id="approveModal"
class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">

<div class="bg-white w-full max-w-md rounded shadow-lg p-6">

<h2 class="text-[14px] font-semibold mb-3">
Approve Request to Purchase
</h2>

<p class="text-[12px] text-slate-600 mb-4">
Approving this form will finalize the Request to Purchase document.
</p>

<form method="POST"
action="{{ route('admin.projects.documents.approve', [$project, $document->formType->code]) }}">

@csrf

<div class="flex justify-end gap-3">

<button
type="button"
onclick="closeApproveModal()"
class="border border-slate-400 px-4 py-2 text-[12px] text-slate-700 hover:bg-slate-100">
Cancel
</button>

<button
type="submit"
class="bg-emerald-600 px-4 py-2 text-[12px] text-white hover:bg-emerald-700">
Approve Document
</button>

</div>

</form>

</div>

</div>