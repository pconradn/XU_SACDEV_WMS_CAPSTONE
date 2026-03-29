@if(!$isProjectHead && !$isAdmin && $document?->status === 'submitted')

<div class="flex flex-wrap gap-2 justify-end">

    {{-- ================= NOT A SIGNATORY ================= --}}
    @if(!$isSignatory)

        <div class="px-4 py-2 text-xs font-medium bg-slate-100 text-slate-600 border border-slate-200 rounded-lg">
            You are not an approver for this document
        </div>

    @else

        {{-- ================= RETURN TO HUB ================= --}}
        <a href="{{ route('org.projects.documents.hub', $project->id) }}"
        class="px-4 py-2 text-xs font-medium border border-slate-300 bg-white hover:bg-slate-50 rounded-lg">
            Return to Hub
        </a>

        {{-- ================= CURRENT TURN ================= --}}
        @if($isCurrentTurn)

            <button type="button"
                onclick="openApproveModal()"
                class="px-4 py-2 text-xs font-semibold bg-green-600 hover:bg-green-700 text-white rounded-lg">
                Approve
            </button>

            <button type="button"
                onclick="openReturnModal()"
                class="px-4 py-2 text-xs font-semibold bg-red-600 hover:bg-red-700 text-white rounded-lg">
                Return
            </button>

        {{-- ================= WAITING TURN ================= --}}
        @elseif($document->status === 'submitted')

            <div class="px-4 py-2 text-xs font-medium bg-slate-100 text-slate-600 border border-slate-200 rounded-lg">
                Waiting for your turn
            </div>

        @endif


        {{-- ================= RETRACT ================= --}}
        @if($canRetract && $document->status === 'submitted')

            <button type="button"
                onclick="openRetractModal()"
                class="px-4 py-2 text-xs font-semibold border border-amber-400 text-amber-700 bg-amber-50 hover:bg-amber-100 rounded-lg">
                Retract Approval
            </button>

        @elseif($isSigned && !$canRetract)

            <div class="px-4 py-2 text-xs font-medium bg-slate-100 text-slate-600 border border-slate-200 rounded-lg">
                Cannot retract (next approver already approved)
            </div>

        @endif

    @endif

</div>

@endif