@php
    $document = $document ?? null;

    $timelines = $document?->timelines ?? collect();

    $status = $document->status ?? 'draft';

    $statusConfig = match ($status) {
        'draft' => [
            'label' => 'Draft',
            'badge' => 'bg-slate-100 text-slate-700 border-slate-200',
            'icon' => 'file-pen-line',
        ],
        'submitted' => [
            'label' => 'Submitted',
            'badge' => 'bg-blue-100 text-blue-700 border-blue-200',
            'icon' => 'send',
        ],
        'returned' => [
            'label' => 'Returned',
            'badge' => 'bg-rose-100 text-rose-700 border-rose-200',
            'icon' => 'file-warning',
        ],
        'approved' => [
            'label' => 'Approved',
            'badge' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
            'icon' => 'check-circle-2',
        ],
        'approved_by_sacdev' => [
            'label' => 'Approved by SACDEV',
            'badge' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
            'icon' => 'check-circle-2',
        ],
        'submitted_to_sacdev' => [
            'label' => 'Submitted to SACDEV',
            'badge' => 'bg-indigo-100 text-indigo-700 border-indigo-200',
            'icon' => 'send',
        ],
        'forwarded_to_sacdev' => [
            'label' => 'Forwarded to SACDEV',
            'badge' => 'bg-violet-100 text-violet-700 border-violet-200',
            'icon' => 'forward',
        ],
        default => [
            'label' => ucwords(str_replace('_', ' ', $status)),
            'badge' => 'bg-slate-100 text-slate-700 border-slate-200',
            'icon' => 'circle-dot',
        ],
    };

    $documentRemarks = $document?->remarks;
    $hasRemarks = filled(trim(strip_tags((string) $documentRemarks)));

    if (!function_exists('timelineActionConfig')) {
        function timelineActionConfig($action) {
            return match ($action) {
                'draft_saved',
                'saved_as_draft',
                'draft_created',
                'created_draft' => [
                    'label' => 'Saved as Draft',
                    'dot'   => 'bg-slate-400',
                    'pill'  => 'bg-slate-100 text-slate-700 border-slate-200',
                    'icon'  => 'file-pen-line',
                ],

                'submitted',
                'submitted_for_review',
                'submitted_to_treasurer',
                'submitted_to_president',
                'submitted_to_moderator',
                'submitted_to_sacdev' => [
                    'label' => 'Submitted',
                    'dot'   => 'bg-blue-500',
                    'pill'  => 'bg-blue-100 text-blue-700 border-blue-200',
                    'icon'  => 'send',
                ],

                'forwarded_to_sacdev',
                'endorsed_to_sacdev' => [
                    'label' => 'Forwarded to SACDEV',
                    'dot'   => 'bg-indigo-500',
                    'pill'  => 'bg-indigo-100 text-indigo-700 border-indigo-200',
                    'icon'  => 'forward',
                ],

                'returned',
                'returned_by_treasurer',
                'returned_by_president',
                'returned_by_moderator',
                'returned_by_sacdev' => [
                    'label' => 'Returned',
                    'dot'   => 'bg-rose-500',
                    'pill'  => 'bg-rose-100 text-rose-700 border-rose-200',
                    'icon'  => 'file-warning',
                ],

                'approved',
                'approved_by_treasurer',
                'approved_by_president',
                'approved_by_moderator',
                'approved_by_sacdev' => [
                    'label' => 'Approved',
                    'dot'   => 'bg-emerald-500',
                    'pill'  => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                    'icon'  => 'check-circle-2',
                ],

                'approval_reverted',
                'reverted_to_submitted',
                'retracted_approval' => [
                    'label' => 'Approval Reverted',
                    'dot'   => 'bg-amber-500',
                    'pill'  => 'bg-amber-100 text-amber-700 border-amber-200',
                    'icon'  => 'undo-2',
                ],

                'edit_requested' => [
                    'label' => 'Edit Requested',
                    'dot'   => 'bg-yellow-500',
                    'pill'  => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                    'icon'  => 'file-pen-line',
                ],

                'edit_granted' => [
                    'label' => 'Edit Granted',
                    'dot'   => 'bg-cyan-500',
                    'pill'  => 'bg-cyan-100 text-cyan-700 border-cyan-200',
                    'icon'  => 'unlock',
                ],

                'resubmitted',
                'resubmitted_after_return',
                'resubmitted_after_edit' => [
                    'label' => 'Resubmitted',
                    'dot'   => 'bg-sky-500',
                    'pill'  => 'bg-sky-100 text-sky-700 border-sky-200',
                    'icon'  => 'refresh-cw',
                ],

                'cancelled',
                'voided' => [
                    'label' => 'Cancelled',
                    'dot'   => 'bg-slate-500',
                    'pill'  => 'bg-slate-100 text-slate-700 border-slate-200',
                    'icon'  => 'ban',
                ],

                default => [
                    'label' => ucwords(str_replace('_', ' ', (string) $action)),
                    'dot'   => 'bg-slate-400',
                    'pill'  => 'bg-slate-100 text-slate-700 border-slate-200',
                    'icon'  => 'circle-dot',
                ],
            };
        }
    }
@endphp

<div
    x-data="{ openTimeline: false, openRemarks: false }"
    class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden"
>
    <div class="px-4 py-3 md:px-5 md:py-4 flex flex-col gap-3 md:flex-row md:items-center md:justify-between bg-gradient-to-r from-slate-50 to-white">

        <div class="flex items-center gap-2 flex-wrap">

            @if($hasRemarks)
                <button
                    type="button"
                    @click="openRemarks = true"
                    class="inline-flex items-center gap-2 rounded-xl border border-amber-200 bg-amber-50 px-3 py-2 text-xs font-semibold text-amber-700 transition hover:bg-amber-100"
                >
                    <i data-lucide="message-square-warning" class="w-4 h-4"></i>
                    Remarks Available
                </button>
            @endif

            <button
                type="button"
                @click="openTimeline = true"
                class="inline-flex items-center gap-2 rounded-xl border border-indigo-200 bg-indigo-50 px-3 py-2 text-xs font-semibold text-indigo-700 transition hover:bg-indigo-100"
            >
                <i data-lucide="history" class="w-4 h-4"></i>
                View Timeline
                @if($timelines->count())
                    <span class="rounded-full bg-white px-1.5 py-0.5 text-[10px] font-bold text-indigo-700">
                        {{ $timelines->count() }}
                    </span>
                @endif
            </button>
        </div>

        <div class="flex items-center gap-2 flex-wrap md:justify-end">
            <span class="text-xs font-medium text-slate-500">
                Current Status
            </span>

            <span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-xs font-semibold {{ $statusConfig['badge'] }}">
                <i data-lucide="{{ $statusConfig['icon'] }}" class="w-3.5 h-3.5"></i>
                {{ $statusConfig['label'] }}
            </span>
        </div>
    </div>

    <div
        x-show="openRemarks"
        x-cloak
        x-transition.opacity
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
    >
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="openRemarks = false"></div>

        <div class="relative w-full max-w-2xl overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl">

            <div class="flex items-start justify-between gap-4 border-b border-amber-200 bg-gradient-to-r from-amber-50 to-white px-5 py-4">
                <div>
                    <div class="flex items-center gap-2 text-sm font-semibold text-slate-900">
                        <i data-lucide="message-square-warning" class="w-4 h-4 text-amber-600"></i>
                        Latest Remarks
                    </div>

                    <p class="mt-1 text-xs text-slate-500">
                        Most recent remarks saved on this document.
                    </p>
                </div>

                <button
                    type="button"
                    @click="openRemarks = false"
                    class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 transition hover:bg-slate-50 hover:text-slate-800"
                >
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <div class="max-h-[60vh] overflow-y-auto p-5">
                @if($hasRemarks)
                    <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4">
                        <div class="mb-2 inline-flex items-center gap-1.5 rounded-full border border-amber-200 bg-white px-2.5 py-1 text-[11px] font-semibold text-amber-700">
                            <i data-lucide="message-square-text" class="w-3 h-3"></i>
                            Document Remarks
                        </div>

                        <div class="whitespace-pre-line break-words text-sm leading-6 text-slate-700">
                            {!! $documentRemarks !!}
                        </div>
                    </div>
                @else
                    <div class="py-12 text-center">
                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl border border-slate-200 bg-slate-50 text-slate-400">
                            <i data-lucide="message-square-off" class="w-6 h-6"></i>
                        </div>

                        <div class="mt-3 text-sm font-semibold text-slate-800">
                            No remarks available
                        </div>

                        <div class="mt-1 text-xs text-slate-500">
                            Remarks will appear here once saved.
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div
        x-show="openTimeline"
        x-cloak
        x-transition.opacity
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
    >
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="openTimeline = false"></div>

        <div class="relative w-full max-w-3xl overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl">

            <div class="flex items-start justify-between gap-4 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white px-5 py-4">
                <div>
                    <div class="flex items-center gap-2 text-lg font-semibold text-slate-900">
                        <i data-lucide="history" class="w-5 h-5 text-indigo-600"></i>
                        Submission Timeline
                    </div>

                    <p class="mt-1 text-xs text-slate-500">
                        Activity history, status changes, and remarks for this document.
                    </p>
                </div>

                <button
                    type="button"
                    @click="openTimeline = false"
                    class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 transition hover:bg-slate-50 hover:text-slate-800"
                >
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <div class="max-h-[70vh] overflow-y-auto p-5">

                @if($timelines->count())

                    <div class="relative">
                        <div class="absolute left-4 top-2 bottom-2 w-px bg-slate-200"></div>

                        <div class="space-y-5">
                            @foreach($timelines as $t)
                                @php
                                    $config = timelineActionConfig($t->action);
                                    $timelineHasRemarks = filled(trim(strip_tags((string) $t->remarks)));
                                @endphp

                                <div class="relative pl-11">

                                    <div class="absolute left-0 top-0.5 flex h-8 w-8 items-center justify-center rounded-full border-4 border-white shadow-sm {{ $config['dot'] }}">
                                        <i data-lucide="{{ $config['icon'] }}" class="w-3.5 h-3.5 text-white"></i>
                                    </div>

                                    <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">

                                        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                            <div class="min-w-0">
                                                <div class="flex flex-wrap items-center gap-2">
                                                    <span class="inline-flex items-center rounded-full border px-2.5 py-1 text-[11px] font-semibold {{ $config['pill'] }}">
                                                        {{ $config['label'] }}
                                                    </span>

                                                    @if($timelineHasRemarks)
                                                        <span class="inline-flex items-center gap-1 rounded-full border border-amber-200 bg-amber-50 px-2 py-0.5 text-[10px] font-semibold text-amber-700">
                                                            <i data-lucide="message-square-text" class="w-3 h-3"></i>
                                                            With remarks
                                                        </span>
                                                    @endif
                                                </div>

                                                <div class="mt-2 text-xs text-slate-500">
                                                    {{ $t->user->name ?? 'System' }}
                                                </div>
                                            </div>

                                            <div class="shrink-0 text-left sm:text-right">
                                                <div class="text-[11px] font-medium text-slate-500">
                                                    {{ $t->created_at?->format('M d, Y') }}
                                                </div>

                                                <div class="mt-0.5 text-[10px] text-slate-400">
                                                    {{ $t->created_at?->format('h:i A') }}
                                                </div>
                                            </div>
                                        </div>

                                        @if(!empty($t->old_status) || !empty($t->new_status))
                                            <div class="mt-3">
                                                <div class="inline-flex flex-wrap items-center gap-2 rounded-xl border border-blue-200 bg-blue-50 px-3 py-2 text-xs font-medium text-blue-700">
                                                    <span>{{ $t->old_status ? ucwords(str_replace('_', ' ', $t->old_status)) : '—' }}</span>
                                                    <i data-lucide="arrow-right" class="w-3 h-3"></i>
                                                    <span>{{ $t->new_status ? ucwords(str_replace('_', ' ', $t->new_status)) : '—' }}</span>
                                                </div>
                                            </div>
                                        @endif

                                        @if($timelineHasRemarks)
                                            <div class="mt-3 rounded-xl border border-slate-200 bg-slate-50 p-3">
                                                <div class="mb-1.5 flex items-center gap-1.5 text-[11px] font-semibold uppercase tracking-wide text-slate-500">
                                                    <i data-lucide="message-square-text" class="w-3 h-3"></i>
                                                    Remarks
                                                </div>

                                                <div class="whitespace-pre-line break-words text-sm leading-6 text-slate-700">
                                                    {!! $t->remarks !!}
                                                </div>
                                            </div>
                                        @endif

                                    </div>

                                </div>
                            @endforeach
                        </div>
                    </div>

                @else

                    <div class="py-12 text-center">
                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl border border-slate-200 bg-slate-50 text-slate-400">
                            <i data-lucide="history" class="w-6 h-6"></i>
                        </div>

                        <div class="mt-3 text-sm font-semibold text-slate-800">
                            No timeline records available
                        </div>

                        <div class="mt-1 text-xs text-slate-500">
                            Activity history will appear here once this document moves through the workflow.
                        </div>
                    </div>

                @endif

            </div>
        </div>
    </div>
</div>