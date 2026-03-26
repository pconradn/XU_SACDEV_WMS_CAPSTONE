@if($isAdmin)

<div class="flex flex-wrap gap-2 justify-end">

    {{-- ================= RETURN TO HUB ================= --}}
    <a href="{{ route('admin.projects.documents.hub', $project->id) }}"
    class="px-4 py-2 text-xs font-medium border border-slate-300 bg-white hover:bg-slate-50 rounded-lg">
        Return to Hub
    </a>

    {{-- ================= APPROVAL ACTIONS ================= --}}
    @if($document->status === 'submitted')

        {{-- CURRENT TURN --}}
        @if($isSignatory && $isCurrentTurn)

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

        {{-- NOT YOUR TURN --}}
        @elseif($isSignatory)

            <div class="px-4 py-2 text-xs font-medium bg-slate-100 text-slate-600 border border-slate-200 rounded-lg">
                Not your turn
            </div>

        @endif

    @endif


    {{-- ================= EDIT REQUEST HANDLING ================= --}}
    @if($editRequested && !$editMode && in_array($document->status, ['submitted','approved_by_sacdev']))

        <button type="button"
            onclick="openAllowEditModal()"
            class="px-4 py-2 text-xs font-semibold bg-amber-500 hover:bg-amber-600 text-white rounded-lg">
            Allow Edit
        </button>

    @endif


    {{-- ================= RETRACT SACDEV APPROVAL ================= --}}
    @if($isApprovedBySacdev && $canRetract && !$editMode)

        <button type="button"
            onclick="openRetractModal()"
            class="px-4 py-2 text-xs font-semibold border border-red-400 text-red-700 bg-red-50 hover:bg-red-100 rounded-lg">
            Retract Approval
        </button>

    @elseif($isApprovedBySacdev && !$canRetract)

        <div class="px-4 py-2 text-xs font-medium bg-slate-100 text-slate-600 border border-slate-200 rounded-lg">
            Cannot retract (locked approval)
        </div>

    @endif

</div>

@endif