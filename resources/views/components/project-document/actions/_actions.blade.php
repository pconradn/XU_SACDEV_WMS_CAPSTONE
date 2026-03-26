
@php
    $status = $document->status ?? 'draft';

    $isDraft = $status === 'draft';
    $isSubmitted = $status === 'submitted';
    $isApprovedBySacdev = $status === 'approved_by_sacdev';

    $isSignatory = $currentSignature !== null;
    $isSigned = $isSignatory && $currentSignature->status === 'signed';
    $isPendingApproval = $isSignatory && $currentSignature->status === 'pending';

    $signatures = collect($document->signatures ?? [])->sortBy('id')->values();

    $currentIndex = $isSignatory
        ? $signatures->search(fn($s) => $s->id === $currentSignature->id)
        : false;

    $hasLaterApproval = ($currentIndex !== false && $currentIndex !== null)
        ? $signatures->slice($currentIndex + 1)->contains(fn($s) => $s->status === 'signed')
        : false;

    $canRetract = $isSigned && !$hasLaterApproval;

    $nextPending = $signatures->firstWhere('status', 'pending');

    $isCurrentTurn = $isPendingApproval
        && $nextPending
        && $nextPending->id === $currentSignature?->id;

    $onlySacdevLeft = $signatures->where('status', 'pending')->count() === 1
        && optional($signatures->firstWhere('status', 'pending'))->role === 'sacdev_admin';

    $editRequested = $document->edit_requested ?? false;
    $editMode = $document->edit_mode ?? false;
@endphp


{{-- ================= ACTION BAR ================= --}}
<div class="sticky bottom-0 z-50 border-t border-slate-200 bg-white shadow-md">

    <div class="px-5 py-3 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">

        {{-- ================= LEFT INFO ================= --}}
        <div class="text-xs text-slate-600">

            @if($isDraft)
                Draft mode. You can save or submit this document.

            @elseif($isSubmitted)
                This document is currently under review.

            @elseif($isApprovedBySacdev)
                This document has been fully approved.

            @endif

            @if($editRequested && !$editMode)
                <span class="text-amber-600 ml-2">Edit request pending approval.</span>
            @endif

            @if($editMode)
                <span class="text-blue-600 ml-2">Edit mode enabled. Submit your revisions.</span>
            @endif

        </div>


        {{-- ================= RIGHT ACTIONS ================= --}}
        <div class="flex flex-wrap gap-2 justify-end">

            {{-- PROJECT HEAD --}}
            @include('components.project-document.actions._project_head')

            {{-- APPROVER --}}
            @include('components.project-document.actions._approver')

            {{-- ADMIN --}}
            @include('components.project-document.actions._admin')

        </div>

    </div>

</div>


{{-- ================= MODALS ================= --}}
@include('components.project-document.actions._modals')