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
@php
    $formCode = strtoupper($document->formType->code);

    $routeMap = [

        'PROJECT_PROPOSAL' => [
            'store' => 'org.projects.documents.project-proposal.store',
            'approve' => 'org.projects.documents.project-proposal.approve',
            'return' => 'org.projects.documents.project-proposal.return',
        ],

        'BUDGET_PROPOSAL' => [
            'store' => 'org.projects.documents.budget-proposal.store',
            'approve' => 'org.projects.documents.budget-proposal.approve',
            'return' => 'org.projects.documents.budget-proposal.return',
        ],

        'OFF_CAMPUS_APPLICATION' => [
            'store' => 'org.projects.documents.off-campus.store',
            'approve' => 'org.projects.documents.off-campus.approve',
            'return' => 'org.projects.documents.off-campus.return',
        ],

        'SOLICITATION_APPLICATION' => [
            'store' => 'org.projects.documents.solicitation.store',
            'approve' => 'org.projects.documents.solicitation.approve',
            'return' => 'org.projects.documents.solicitation.return',
        ],

        'SELLING_APPLICATION' => [
            'store' => 'org.projects.documents.selling.store',
            'approve' => 'org.projects.documents.selling.approve',
            'return' => 'org.projects.documents.selling.return',
        ],

        'REQUEST_TO_PURCHASE' => [
            'store' => 'org.projects.documents.request-to-purchase.store',
            'approve' => 'org.projects.documents.request-to-purchase.approve',
            'return' => 'org.projects.documents.request-to-purchase.return',
        ],

        'FEES_COLLECTION_REPORT' => [
            'store' => 'org.projects.fees-collection.store',
            'approve' => 'org.projects.fees-collection.approve',
            'return' => 'org.projects.fees-collection.return',
        ],

        'SELLING_ACTIVITY_REPORT' => [
            'store' => 'org.projects.selling-activity-report.store',
            'approve' => 'org.projects.selling-activity-report.approve',
            'return' => 'org.projects.selling-activity-report.return',
        ],

        'SOLICITATION_SPONSORSHIP_REPORT' => [
            'store' => 'org.projects.solicitation-sponsorship-report.store',
            'approve' => 'org.projects.solicitation-sponsorship-report.approve',
            'return' => 'org.projects.solicitation-sponsorship-report.return',
        ],

        'TICKET_SELLING_REPORT' => [
            'store' => 'org.projects.ticket-selling-report.store',
            'approve' => 'org.projects.ticket-selling-report.approve',
            'return' => 'org.projects.ticket-selling-report.return',
        ],

        'DOCUMENTATION_REPORT' => [
            'store' => 'org.projects.documentation-report.store',
            'approve' => 'org.projects.documentation-report.approve',
            'return' => 'org.projects.documentation-report.return',
        ],

        'LIQUIDATION_REPORT' => [
            'store' => 'org.projects.liquidation-report.store',
            'approve' => 'org.projects.liquidation-report.approve',
            'return' => 'org.projects.liquidation-report.return',
        ],

    ];

    $routes = $routeMap[$formCode] ?? null;
@endphp



<div class="sticky bottom-0 z-50 border-t border-slate-200 bg-white shadow-md">

    <div class="px-5 py-3 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">

        {{-- LEFT INFO --}}
        @include('components.project-document-actions._messages')

        {{-- RIGHT ACTIONS --}}
        <div class="flex flex-wrap gap-2 justify-end">

            {{-- RETURN TO HUB --}}
            @if($isAdmin)
                <a href="{{ route('admin.projects.documents.hub', $project) }}"
                   class="px-3 py-2 text-sm border border-slate-300 rounded-lg hover:bg-slate-50">
                    ← Return to Project Hub
                </a>
            @else
                <a href="{{ route('org.projects.documents.hub', $project) }}"
                   class="px-3 py-2 text-sm border border-slate-300 rounded-lg hover:bg-slate-50">
                    ← Return to Project Hub
                </a>
            @endif

            {{-- PROJECT HEAD --}}
            @includeWhen($isProjectHead, 'components.project-document-actions._project_head')

            {{-- APPROVER --}}
            @includeWhen(!$isProjectHead && !$isAdmin, 'components.project-document-actions._approver')

            {{-- ADMIN --}}
            @includeWhen($isAdmin, 'components.project-document-actions._admin')

        </div>

    </div>

</div>