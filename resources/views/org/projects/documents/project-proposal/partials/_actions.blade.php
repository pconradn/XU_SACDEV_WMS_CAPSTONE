@php
    $status = $document->status ?? 'draft';
@endphp


{{-- ================= PROJECT HEAD ACTION BAR ================= --}}
@if($isProjectHead)

<div class="sticky bottom-0 z-50 border-t border-amber-200 bg-amber-50 shadow-md">

    <div class="px-5 py-3 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

        {{-- LEFT MESSAGE --}}
        <div class="text-xs text-amber-900">
            @if($status === 'submitted')
                Editing and resubmitting will reset Treasurer and President approvals.
            @elseif($status === 'approved')
                This proposal is finalized and cannot be modified.
            @else
                Saving will store this proposal as a draft. 
                Submitting will forward this document for approval.
            @endif
        </div>

        {{-- ACTION BUTTONS --}}
        <div class="flex items-center gap-2">

            {{-- BACK --}}
            <a href="{{ route('org.projects.documents.hub', $project) }}"
               class="rounded-lg border border-amber-300 bg-white px-4 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50">
                Back to Project Hub
            </a>

            @if(in_array($status, ['draft', 'returned']))

                <button type="submit"
                        form="proposalForm"
                        name="action"
                        value="draft"
                        class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50">
                    Save as Draft
                </button>

                <button type="submit"
                        form="proposalForm"
                        name="action"
                        value="submit"
                        class="rounded-lg bg-slate-900 px-4 py-2 text-xs font-semibold text-white hover:bg-slate-800">
                    Submit for Approval
                </button>

            @endif

            @if($status === 'submitted')

                <button type="button"
                        onclick="openResubmitModal()"
                        class="rounded-lg bg-amber-600 px-4 py-2 text-xs font-semibold text-white hover:bg-amber-700">
                    Resubmit with Changes
                </button>

            @endif

        </div>

    </div>

</div>

@endif


{{-- ================= RESUBMIT MODAL ================= --}}
@if($isProjectHead && $status === 'submitted')

<div id="resubmitModal"
     class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">

    <div class="bg-white w-full max-w-md rounded-xl shadow-lg p-6">

        <h2 class="text-sm font-semibold mb-3 text-slate-900">
            Confirm Resubmission
        </h2>

        <p class="text-xs text-slate-600 mb-4">
            Resubmitting this proposal will remove existing approvals from the Treasurer and President.
            They will be required to review and approve this document again.
        </p>

        <div class="flex justify-end gap-2">

            <button type="button"
                    onclick="closeResubmitModal()"
                    class="rounded-lg border border-slate-300 px-4 py-2 text-xs text-slate-700 hover:bg-slate-50">
                Cancel
            </button>

            <button type="submit"
                    form="proposalForm"
                    name="action"
                    value="draft"
                    class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-xs font-medium text-slate-800 hover:bg-slate-50">
                Yes, Resubmit
            </button>

        </div>

    </div>

</div>

@endif


{{-- ================= APPROVER ACTION BAR ================= --}}
@if(
    !$isProjectHead &&
    $isReadOnly &&
    isset($currentSignature) &&
    $currentSignature &&
    $currentSignature->status === 'pending'
)

<div class="sticky bottom-0 z-50 border-t border-amber-200 bg-amber-50 shadow-md">

    <div class="px-5 py-3 flex items-center justify-end gap-2">

        <form method="POST"
            action="{{ $currentSignature->role === 'sacdev_admin'
                ? route('admin.projects.documents.approve', [$project, $document->formType->code])
                : route('org.projects.project-proposal.approve', $project) }}">

            @csrf
            <button
                class="rounded-lg bg-emerald-600 px-4 py-2 text-xs font-semibold text-white hover:bg-emerald-700">
                Approve
            </button>
        </form>

        <button
            type="button"
            onclick="openReturnModal()"
            class="rounded-lg bg-rose-600 px-4 py-2 text-xs font-semibold text-white hover:bg-rose-700">
            Return with Remarks
        </button>

    </div>

</div>

@endif



{{-- ================= ADMIN REVERT ACTION BAR ================= --}}
@if(
    $document &&
    $isAdmin &&
    $status === 'approved_by_sacdev'
)

<div class="sticky bottom-0 z-50 border-t border-amber-200 bg-amber-50 shadow-md">

    <div class="px-5 py-3 flex items-center justify-end gap-2">

        <form method="POST"
            action="{{ route('admin.projects.documents.retract', [$project, $document->formType->code]) }}">
            @csrf

            <button
                class="rounded-lg bg-amber-600 px-4 py-2 text-xs font-semibold text-white hover:bg-amber-700">
                Revert Approval
            </button>
        </form>

    </div>

</div>

@endif


{{-- ================= RETURN MODAL ================= --}}
<div id="returnModal"
     class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">

    <div class="bg-white w-full max-w-md rounded-xl shadow-lg p-6">

        <h2 class="text-sm font-semibold mb-3 text-slate-900">
            Return Proposal
        </h2>

        <p class="text-xs text-slate-600 mb-4">
            Please provide remarks explaining why this proposal is being returned.
        </p>

        <form method="POST"
            action="{{ optional($currentSignature)->role === 'sacdev_admin' 
                ? route('admin.projects.documents.return', [$project, $document->formType->code])
                : route('org.projects.project-proposal.return', $project) }}">

            @csrf

            <textarea
                name="remarks"
                required
                rows="4"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-slate-900 focus:outline-none"></textarea>

            <div class="flex justify-end gap-2 mt-4">

                <button type="button"
                        onclick="closeReturnModal()"
                        class="rounded-lg border border-slate-300 px-4 py-2 text-xs text-slate-700 hover:bg-slate-50">
                    Cancel
                </button>

                <button type="submit"
                        class="rounded-lg bg-rose-600 px-4 py-2 text-xs font-semibold text-white hover:bg-rose-700">
                    Return Proposal
                </button>

            </div>

        </form>

    </div>

</div>


{{-- ================= MODAL SCRIPTS ================= --}}
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