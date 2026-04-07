<div 
    x-data="{ 
        openReturn: false, 
        openApprove: false, 
        openRevert: false, 
        openAllowEdit: false,
        loading: false,
    }"
    class="space-y-3"
>

    {{-- ================= HEADER TEXT ================= --}}
    @if(in_array($submission->status, ['submitted_to_sacdev','approved_by_sacdev'], true))

        <div class="text-xs text-slate-600">
            Review this moderator submission and take the appropriate action.
        </div>

    @endif


    {{-- ================= ACTION BUTTONS ================= --}}
    @if($submission->status === 'submitted_to_sacdev')

        <div class="space-y-2">

            {{-- APPROVE --}}
            <button type="button"
                    @click="openApprove = true"
                    :disabled="loading"
                    class="w-full inline-flex items-center justify-center gap-2 rounded-lg 
                           bg-slate-900 px-4 py-2 text-xs font-semibold text-white 
                           hover:bg-slate-800 transition
                           disabled:opacity-50 disabled:cursor-not-allowed">
                <i data-lucide="check" class="w-3 h-3"></i>
                Approve Submission
            </button>

            {{-- RETURN --}}
            <button type="button"
                    @click="openReturn = true; $nextTick(() => initReturnQuill())"
                    :disabled="loading"
                    class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2 text-xs font-medium text-slate-700 
                           hover:bg-slate-50 transition
                           disabled:opacity-50 disabled:cursor-not-allowed">
                Return for Revision
            </button>

        </div>

    @endif


    {{-- ================= ALLOW EDIT ================= --}}
    @if($submission->edit_requested && in_array($submission->status, ['submitted_to_sacdev','approved_by_sacdev'], true))

        <button type="button"
                @click="openAllowEdit = true"
                :disabled="loading"
                class="w-full rounded-lg border border-amber-200 bg-amber-50 px-4 py-2 text-xs font-medium text-amber-900 
                       hover:bg-amber-100 transition">
            Allow Edit
        </button>

    @endif


    {{-- ================= EMPTY STATE ================= --}}
    @if(
        !in_array($submission->status, ['submitted_to_sacdev','approved_by_sacdev'], true)
        || $submission->status === 'approved_by_sacdev'
    )

        <div class="text-center py-3">

            <div class="text-xs font-medium text-slate-700">
                No actions available
            </div>

            <div class="text-[10px] text-slate-500 mt-1">
                This submission is not in a reviewable state.
            </div>

        </div>

    @endif


    {{-- ================= FORMS ================= --}}
    <form id="approveForm"
          method="POST"
          action="{{ route('admin.moderator_submissions.approve', $submission) }}">
        @csrf
    </form>


    {{-- ================= MODALS ================= --}}
    @include('admin.forms.b5_moderator.partials._return_modal', ['submission' => $submission])
    @include('admin.forms.b5_moderator.partials._approve_modal', ['submission' => $submission])
    @include('admin.forms.b5_moderator.partials._revert_modal', ['submission' => $submission])
    @include('admin.forms.b5_moderator.partials._allow_edit_modal', ['submission' => $submission])

</div>