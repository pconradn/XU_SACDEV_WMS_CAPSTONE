

@php
    $formCode = $document?->formType?->code;
@endphp
@php
$isDraftee = \App\Models\ProjectAssignment::where('project_id', $project->id)
    ->where('user_id', auth()->id())
    ->where('assignment_role', 'draftee')
    ->whereNull('archived_at')
    ->exists();
@endphp
@php
$formRouteMap = [
    'PROJECT_PROPOSAL' => 'project-proposal',
    'BUDGET_PROPOSAL' => 'budget-proposal',
    'SOLICITATION_APPLICATION' => 'solicitation',
    'SELLING_APPLICATION' => 'selling',
    'REQUEST_TO_PURCHASE' => 'request-to-purchase',
    'FEES_COLLECTION_REPORT' => 'fees-collection',
    'SELLING_ACTIVITY_REPORT' => 'selling-activity-report',
    'SOLICITATION_SPONSORSHIP_REPORT' => 'solicitation-sponsorship-report',
    'TICKET_SELLING_REPORT' => 'ticket-selling-report',
    'DOCUMENTATION_REPORT' => 'documentation-report',
    'LIQUIDATION_REPORT' => 'liquidation-report',
];

$routePrefix = $formRouteMap[$document?->formType?->code] ?? null;
@endphp


@php
$routeBase = $document?->formType
    ? "org.projects.documents.{$document->formType->slug}"
    : null;
@endphp

<div class="flex flex-wrap gap-2 justify-end">

    {{-- ================= RETURN TO HUB (ALWAYS VISIBLE) ================= --}}
    <a href="{{ route('org.projects.documents.hub', $project->id) }}"
       class="px-4 py-2 text-xs font-medium border border-slate-300 bg-white hover:bg-slate-50 rounded-lg">
        Return to Hub
    </a>

    @if($isProjectHead || $isDraftee)

        {{-- ================= DRAFT ================= --}}
        @if((!$document || $isDraft) && !$editMode)

            <button type="submit"
                form="proposalForm"
                name="action"
                value="draft"
                class="px-4 py-2 text-xs font-semibold bg-slate-200 hover:bg-slate-300 text-slate-800 rounded-lg">
                Save Draft
            </button>

            @if($isProjectHead)
            <button type="submit"
                form="proposalForm"
                name="action"
                value="submit"
                class="px-4 py-2 text-xs font-semibold bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                Submit for Approval
            </button>
            @endif

        @endif


        {{-- ================= SUBMITTED ================= --}}
        @if($isSubmitted && !$onlySacdevLeft && !$editMode && $isProjectHead)

            <button type="button"
                onclick="openResubmitModal()"
                class="px-4 py-2 text-xs font-semibold bg-amber-500 hover:bg-amber-600 text-white rounded-lg">
                Resubmit with Changes
            </button>

        @endif


        {{-- ================= APPROVED / SACDEV LEFT ================= --}}
        @if(($isApprovedBySacdev || $onlySacdevLeft) && !$editRequested && !$editMode && $isProjectHead)

            <button type="button"
                onclick="openRequestEditModal()"
                class="px-4 py-2 text-xs font-semibold bg-amber-500 hover:bg-amber-600 text-white rounded-lg">
                Request Edit
            </button>

        @endif


        {{-- ================= EDIT REQUESTED ================= --}}
        @if($editRequested && !$editMode)

            <div class="px-4 py-2 text-xs font-medium bg-amber-100 text-amber-800 border border-amber-200 rounded-lg">
                Edit request sent
            </div>

        @endif


        {{-- ================= EDIT MODE ================= --}}
        @if($editMode && $isProjectHead)

            <button type="button"
                onclick="openSubmitRevisionModal()"
                class="px-4 py-2 text-xs font-semibold bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                Submit Revisions
            </button>

            <div class="px-4 py-2 text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200 rounded-lg">
                Edit mode: Changes will be submitted directly for approval
            </div>

        @endif

    @endif

</div>