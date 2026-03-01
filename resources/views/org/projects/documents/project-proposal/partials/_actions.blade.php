@php
    $status = $document->status ?? 'draft';
@endphp


<pre>
Auth ID: {{ auth()->id() }}
</pre>
<pre>
isProjectHead: {{ $isProjectHead ? 'true' : 'false' }}
isReadOnly: {{ $isReadOnly ? 'true' : 'false' }}
currentSignature: {{ $currentSignature ? 'exists' : 'null' }}
@if($currentSignature)
status: {{ $currentSignature->status }}
@endif
</pre>

@if($isProjectHead)

<div class="border border-slate-300 bg-white sticky bottom-0 z-50 shadow-md">

    <div class="px-4 py-3 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

        <div class="text-[11px] text-slate-600">
            @if($status === 'submitted')
                Editing and resubmitting will reset Treasurer and President approvals.
            @elseif($status === 'approved')
                This proposal is finalized and cannot be modified.
            @else
                Saving will store this proposal as a draft. 
                Submitting will forward this document for approval.
            @endif
        </div>

        <div class="flex items-center gap-3">

            <a href="{{ route('org.projects.documents.hub', $project) }}"
               class="border border-slate-400 px-4 py-2 text-[12px] text-slate-700 hover:bg-slate-100">
                Cancel
            </a>

            @if(in_array($status, ['draft', 'returned']))

                <button type="submit"
                        name="action"
                        value="draft"
                        class="border border-slate-500 px-4 py-2 text-[12px] text-slate-800 hover:bg-slate-100">
                    Save as Draft
                </button>

                <button type="submit"
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
            Resubmitting this proposal will remove existing approvals from the Treasurer and President.
            They will be required to review and approve this document again.
        </p>

        <div class="flex justify-end gap-3">

            <button type="button"
                    onclick="closeResubmitModal()"
                    class="border border-slate-400 px-4 py-2 text-[12px] text-slate-700 hover:bg-slate-100">
                Cancel
            </button>

            <button type="submit"
                    name="action"
                    value="submit"
                    form="proposalForm"
                    class="bg-amber-600 px-4 py-2 text-[12px] text-white hover:bg-amber-700">
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
                action="{{ route('org.projects.project-proposal.approve', $project) }}">
                @csrf
                <button
                    class="bg-emerald-600 px-4 py-2 text-[12px] text-white hover:bg-emerald-700">
                    Approve
                </button>
            </form>

            <form method="POST"
                action="{{ route('org.projects.project-proposal.return', $project) }}">
                @csrf
                <button
                    class="bg-rose-600 px-4 py-2 text-[12px] text-white hover:bg-rose-700">
                    Return with Remarks
                </button>
            </form>

        </div>

    </div>

@endif



<script>
    function openResubmitModal() {
        document.getElementById('resubmitModal')?.classList.remove('hidden');
        document.getElementById('resubmitModal')?.classList.add('flex');
    }

    function closeResubmitModal() {
        document.getElementById('resubmitModal')?.classList.add('hidden');
        document.getElementById('resubmitModal')?.classList.remove('flex');
    }
</script>