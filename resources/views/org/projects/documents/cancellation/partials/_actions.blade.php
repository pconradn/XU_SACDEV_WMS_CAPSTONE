@php
$status = $document->status ?? 'draft';
$isAdmin = auth()->user()->system_role === 'sacdev_admin';
@endphp

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
            form="cancellationForm"
            name="action"
            value="draft"
            class="border border-slate-500 px-4 py-2 text-[12px] text-slate-800 hover:bg-slate-100">
            Save as Draft
            </button>

            <button type="submit"
            form="cancellationForm"
            name="action"
            value="submit"
            class="bg-blue-900 px-4 py-2 text-[12px] text-white hover:bg-blue-800">
            Submit for Approval
            </button>
            @endif
        </div>

    </div>

</div>
@endif


@if(
$document &&
$document->status === 'submitted' &&
$currentSignature &&
$currentSignature->status === 'pending' &&
$currentSignature->user_id === auth()->id()
)

<div class="border border-slate-300 bg-white sticky bottom-0 z-50 shadow-md">
    <div class="px-4 py-3 flex items-center justify-end gap-3">

        @if($isAdmin)
        <form method="POST"
        action="{{ route('admin.projects.documents.approve', [$project, $document->formType->code]) }}">
            @csrf
            <button class="bg-emerald-600 px-4 py-2 text-white text-[12px] hover:bg-emerald-700">
                Approve
            </button>
        </form>
        @else
        <form method="POST"
        action="{{ route('org.projects.cancellation.approve', [$project, $document]) }}">
            @csrf
            <button class="bg-emerald-600 px-4 py-2 text-white text-[12px] hover:bg-emerald-700">
                Approve
            </button>
        </form>
        @endif

        <form method="POST"
        action="{{ $isAdmin
            ? route('admin.projects.documents.return', [$project, $document->formType->code])
            : route('org.projects.cancellation.return', [$project, $document]) }}">
            @csrf
            <input type="hidden" name="remarks" value="Returned for revision. Please review and update the form.">
            <button class="bg-rose-600 px-4 py-2 text-[12px] text-white hover:bg-rose-700">
                Return
            </button>
        </form>

    </div>
</div>

@endif