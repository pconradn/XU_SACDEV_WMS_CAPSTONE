@php
    $hasProfile = !empty($submission->org_name)
        && !empty($submission->mission)
        && !empty($submission->vision);

    $hasProjects = $submission->projects->count() > 0;
    $hasFunds = $submission->fundSources->count() > 0;
    $isDraft = $submission->status === 'draft';

    $checklistItems = [
        [
            'label' => 'Organization Profile',
            'done' => $hasProfile,
            'value' => $hasProfile ? 'Complete' : 'Incomplete',
            'hint' => 'Organization name, mission, and vision must be filled in.',
            'icon' => 'building-2',
        ],
        [
            'label' => 'Projects',
            'done' => $hasProjects,
            'value' => $hasProjects ? $submission->projects->count() . ' Added' : 'None',
            'hint' => 'At least one project must be added to the strategic plan.',
            'icon' => 'folders',
        ],
        [
            'label' => 'Sources of Funds',
            'done' => $hasFunds,
            'value' => $hasFunds ? 'Provided' : 'Missing',
            'hint' => 'Provide at least one source of funds before submission.',
            'icon' => 'wallet',
        ],
    ];

    $isReadyToSubmit = $hasProfile && $hasProjects && $hasFunds;

    $statusConfig = match($submission->status) {
        'draft' => [
            'label' => 'Draft',
            'badge' => 'bg-slate-100 text-slate-700 ring-1 ring-slate-200',
            'panel' => 'border-slate-200 bg-slate-50 text-slate-600',
            'summary' => 'This strategic plan is still being prepared and can still be edited.',
            'icon' => 'file-text',
        ],
        'submitted_to_moderator' => [
            'label' => 'Moderator Review',
            'badge' => 'bg-amber-100 text-amber-700 ring-1 ring-amber-200',
            'panel' => 'border-amber-200 bg-amber-50 text-amber-700',
            'summary' => 'This strategic plan is waiting for moderator review.',
            'icon' => 'clock-3',
        ],
        'approved_by_moderator' => [
            'label' => 'Ready for SACDEV Forwarding',
            'badge' => 'bg-blue-100 text-blue-700 ring-1 ring-blue-200',
            'panel' => 'border-blue-200 bg-blue-50 text-blue-700',
            'summary' => 'Moderator review is complete. This can now proceed to SACDEV.',
            'icon' => 'shield-check',
        ],
        'forwarded_to_sacdev' => [
            'label' => 'SACDEV Review',
            'badge' => 'bg-blue-100 text-blue-700 ring-1 ring-blue-200',
            'panel' => 'border-blue-200 bg-blue-50 text-blue-700',
            'summary' => 'This strategic plan is now under SACDEV review.',
            'icon' => 'send',
        ],
        'approved_by_sacdev' => [
            'label' => 'Approved',
            'badge' => 'bg-emerald-100 text-emerald-700 ring-1 ring-emerald-200',
            'panel' => 'border-emerald-200 bg-emerald-50 text-emerald-700',
            'summary' => 'This strategic plan has completed the full approval flow.',
            'icon' => 'badge-check',
        ],
        'returned_by_moderator' => [
            'label' => 'Returned by Moderator',
            'badge' => 'bg-rose-100 text-rose-700 ring-1 ring-rose-200',
            'panel' => 'border-rose-200 bg-rose-50 text-rose-700',
            'summary' => 'This strategic plan was returned by the moderator and needs revision.',
            'icon' => 'corner-up-left',
        ],
        'returned_by_sacdev' => [
            'label' => 'Returned by SACDEV',
            'badge' => 'bg-rose-100 text-rose-700 ring-1 ring-rose-200',
            'panel' => 'border-rose-200 bg-rose-50 text-rose-700',
            'summary' => 'This strategic plan was returned by SACDEV and needs revision.',
            'icon' => 'rotate-ccw',
        ],
        default => [
            'label' => ucwords(str_replace('_', ' ', $submission->status)),
            'badge' => 'bg-slate-100 text-slate-700 ring-1 ring-slate-200',
            'panel' => 'border-slate-200 bg-slate-50 text-slate-600',
            'summary' => 'Review the current submission status and follow the required next step.',
            'icon' => 'info',
        ],
    };

    $userAwarenessMessage = match(true) {
        $canSubmitToModerator => 'You can submit this strategic plan to the moderator once all required sections are complete.',
        $canReviewAsModerator => 'You can review this submission now because it is currently in moderator review.',
        $canSubmitToSacdev => 'You can now forward this submission to SACDEV because moderator review is already complete.',
        $canAdminAct && $submission->status === 'forwarded_to_sacdev' => 'SACDEV can review this submission now. Approval and return actions should only happen at this stage.',
        $canAdminAct && $submission->status !== 'forwarded_to_sacdev' => 'SACDEV actions are only available when the submission status is Forwarded to SACDEV.',
        $submission->status === 'submitted_to_moderator' => 'This submission is currently waiting for moderator action.',
        $submission->status === 'forwarded_to_sacdev' => 'This submission is currently waiting for SACDEV action.',
        $submission->status === 'approved_by_sacdev' => 'This submission is already fully approved and locked.',
        default => 'Complete the required sections and follow the current workflow status before taking action.',
    };
@endphp

<div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-white to-slate-50 shadow-sm overflow-hidden">

    <div class="px-5 py-5 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">

            <div class="space-y-2">
                <div class="flex items-center gap-2 text-[11px] font-semibold uppercase tracking-wide text-slate-500">
                    <i data-lucide="send-horizontal" class="w-3.5 h-3.5"></i>
                    Submission Review
                </div>

                <div>
                    <h2 class="text-base font-semibold text-slate-900">
                        Submit Strategic Plan
                    </h2>
                    <p class="mt-1 text-xs text-slate-500">
                        Review completeness, confirm workflow status, and proceed only when the required conditions are met.
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-[10px] font-semibold {{ $statusConfig['badge'] }}">
                    <i data-lucide="{{ $statusConfig['icon'] }}" class="w-3 h-3"></i>
                    {{ $statusConfig['label'] }}
                </span>

                @if($isApproved)
                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-3 py-1 text-[10px] font-semibold text-emerald-700 ring-1 ring-emerald-200">
                        <i data-lucide="lock" class="w-3 h-3"></i>
                        Locked
                    </span>
                @endif
            </div>

        </div>
    </div>

    <div class="px-5 py-5 space-y-5">

        <div class="rounded-2xl border px-4 py-3 text-xs {{ $statusConfig['panel'] }}">
            <div class="flex items-start gap-3">
                <div class="mt-0.5">
                    <i data-lucide="{{ $statusConfig['icon'] }}" class="w-4 h-4"></i>
                </div>
                <div class="space-y-1">
                    <div class="font-semibold">
                        Current Status
                    </div>
                    <div class="leading-5">
                        {{ $statusConfig['summary'] }}
                    </div>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
            <div class="px-4 py-3 border-b border-slate-200 bg-slate-50">
                <div class="text-xs font-semibold uppercase tracking-wide text-slate-600">
                    Submission Checklist
                </div>
                <p class="mt-1 text-[11px] text-slate-500">
                    These sections must be complete before the submission can move forward.
                </p>
            </div>

            <div class="divide-y divide-slate-100">
                @foreach($checklistItems as $item)
                    <div class="px-4 py-4 flex items-start justify-between gap-4">
                        <div class="flex items-start gap-3 min-w-0">
                            <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-xl {{ $item['done'] ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">
                                <i data-lucide="{{ $item['icon'] }}" class="w-4 h-4"></i>
                            </div>

                            <div class="min-w-0">
                                <div class="text-sm font-semibold text-slate-900">
                                    {{ $item['label'] }}
                                </div>
                                <div class="mt-1 text-[11px] leading-5 text-slate-500">
                                    {{ $item['hint'] }}
                                </div>
                            </div>
                        </div>

                        <div class="shrink-0 text-right">
                            <div class="text-xs font-semibold {{ $item['done'] ? 'text-emerald-600' : 'text-rose-600' }}">
                                {{ $item['value'] }}
                            </div>
                            <div class="mt-1 text-[10px] {{ $item['done'] ? 'text-emerald-500' : 'text-rose-500' }}">
                                {{ $item['done'] ? 'Ready' : 'Needs attention' }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="rounded-2xl border border-blue-200 bg-blue-50 px-4 py-3 text-xs text-blue-700">
            <div class="flex items-start gap-3">
                <i data-lucide="info" class="w-4 h-4 mt-0.5 shrink-0"></i>
                <div class="leading-5">
                    {{ $userAwarenessMessage }}
                </div>
            </div>
        </div>

        @if(!$isReadyToSubmit)
            <div class="rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-xs text-amber-700">
                <div class="flex items-start gap-3">
                    <i data-lucide="alert-triangle" class="w-4 h-4 mt-0.5 shrink-0"></i>
                    <div class="leading-5">
                        Complete all required sections before submitting. The system will keep the submit action disabled until the profile, projects, and sources of funds are all complete.
                    </div>
                </div>
            </div>
        @endif

    </div>

<div class="border-t border-slate-200 bg-gradient-to-r from-white to-slate-50 px-5 py-4 flex items-center justify-between gap-4">
    <div class="text-[11px] text-slate-400">
        Only valid actions for the current workflow stage should appear here.
    </div>

    <div class="flex items-center gap-2 flex-wrap">

        {{-- ORG SUBMIT --}}
        @if($canSubmitToModerator)
            <form method="POST" action="{{ route($submitRoute) }}">
                @csrf

                <button type="submit"
                        @disabled(!$isReadyToSubmit)
                        class="inline-flex items-center gap-2 rounded-lg px-4 py-2 text-xs font-semibold text-white transition shadow-sm {{ $isReadyToSubmit ? 'bg-blue-600 hover:bg-blue-700' : 'bg-slate-300 cursor-not-allowed' }}">
                    <i data-lucide="send" class="w-3.5 h-3.5"></i>
                    Submit to Moderator
                </button>
            </form>
        @endif


        {{-- MODERATOR ACTIONS --}}
        @if($canReviewAsModerator)

            <button type="button"
                    @click="openReturn = true"
                    class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition">
                <i data-lucide="corner-up-left" class="w-3.5 h-3.5"></i>
                Return
            </button>

            <button type="button"
                    @click="openForward = true"
                    class="inline-flex items-center gap-2 rounded-lg bg-slate-900 px-4 py-2 text-xs font-semibold text-white hover:bg-slate-800 transition">
                <i data-lucide="send" class="w-3.5 h-3.5"></i>
                Forward to SACDEV
            </button>

        @endif


        {{-- SACDEV / ADMIN ACTIONS --}}
        @if($canAdminAct && in_array($submission->status, ['forwarded_to_sacdev','returned_by_sacdev'], true))

            <button type="button"
                    @click="openReturn = true"
                    class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition">
                <i data-lucide="corner-up-left" class="w-3.5 h-3.5"></i>
                Return for Revision
            </button>

            <button type="button"
                    @click="openApprove = true"
                    class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-xs font-semibold text-white hover:bg-emerald-700 transition shadow-sm">
                <i data-lucide="check" class="w-3.5 h-3.5"></i>
                Approve
            </button>

        @endif

        @if($canAdminAct && $submission->status === 'approved_by_sacdev')

            <button type="button"
                    @click="openRevert = true"
                    class="inline-flex items-center gap-2 rounded-lg border border-amber-300 bg-white px-4 py-2 text-xs font-semibold text-amber-700 hover:bg-amber-50 transition">
                <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                Retract Approval
            </button>

        @endif


        {{-- APPROVED STATE --}}
        @if($submission->status === 'approved_by_sacdev')
            <span class="inline-flex items-center gap-2 rounded-lg px-3 py-2 text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                <i data-lucide="lock" class="w-3.5 h-3.5"></i>
                Approved — No Actions
            </span>
        @endif

    </div>
</div>

</div>

