@php
    $document = $document ?? null;

    $timelines = $document?->timelines ?? collect();

    $status = $document->status ?? 'draft';

    $statusConfig = match ($status) {
        'draft' => [
            'label' => 'Draft',
            'badge' => 'bg-slate-100 text-slate-700 border-slate-200',
        ],
        'submitted' => [
            'label' => 'Submitted',
            'badge' => 'bg-blue-100 text-blue-700 border-blue-200',
        ],
        'returned' => [
            'label' => 'Returned',
            'badge' => 'bg-rose-100 text-rose-700 border-rose-200',
        ],
        'approved' => [
            'label' => 'Approved',
            'badge' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
        ],
        'approved_by_sacdev' => [
            'label' => 'Approved by SACDEV',
            'badge' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
        ],
        'submitted_to_sacdev' => [
            'label' => 'Submitted to SACDEV',
            'badge' => 'bg-indigo-100 text-indigo-700 border-indigo-200',
        ],
        'forwarded_to_sacdev' => [
            'label' => 'Forwarded to SACDEV',
            'badge' => 'bg-violet-100 text-violet-700 border-violet-200',
        ],
        default => [
            'label' => ucwords(str_replace('_', ' ', $status)),
            'badge' => 'bg-slate-100 text-slate-700 border-slate-200',
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
                ],

                'forwarded_to_sacdev',
                'endorsed_to_sacdev' => [
                    'label' => 'Forwarded to SACDEV',
                    'dot'   => 'bg-indigo-500',
                    'pill'  => 'bg-indigo-100 text-indigo-700 border-indigo-200',
                ],

                'returned',
                'returned_by_treasurer',
                'returned_by_president',
                'returned_by_moderator',
                'returned_by_sacdev' => [
                    'label' => 'Returned',
                    'dot'   => 'bg-rose-500',
                    'pill'  => 'bg-rose-100 text-rose-700 border-rose-200',
                ],

                'approved',
                'approved_by_treasurer',
                'approved_by_president',
                'approved_by_moderator',
                'approved_by_sacdev' => [
                    'label' => 'Approved',
                    'dot'   => 'bg-emerald-500',
                    'pill'  => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                ],

                'approval_reverted',
                'reverted_to_submitted',
                'retracted_approval' => [
                    'label' => 'Approval Reverted',
                    'dot'   => 'bg-amber-500',
                    'pill'  => 'bg-amber-100 text-amber-700 border-amber-200',
                ],

                'edit_requested' => [
                    'label' => 'Edit Requested',
                    'dot'   => 'bg-yellow-500',
                    'pill'  => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                ],

                'edit_granted' => [
                    'label' => 'Edit Granted',
                    'dot'   => 'bg-cyan-500',
                    'pill'  => 'bg-cyan-100 text-cyan-700 border-cyan-200',
                ],

                'resubmitted',
                'resubmitted_after_return',
                'resubmitted_after_edit' => [
                    'label' => 'Resubmitted',
                    'dot'   => 'bg-sky-500',
                    'pill'  => 'bg-sky-100 text-sky-700 border-sky-200',
                ],

                'cancelled',
                'voided' => [
                    'label' => 'Cancelled',
                    'dot'   => 'bg-slate-500',
                    'pill'  => 'bg-slate-100 text-slate-700 border-slate-200',
                ],

                default => [
                    'label' => ucwords(str_replace('_', ' ', (string) $action)),
                    'dot'   => 'bg-slate-400',
                    'pill'  => 'bg-slate-100 text-slate-700 border-slate-200',
                ],
            };
        }
    }
@endphp

<div
    x-data="{ openTimeline: false, openRemarks: false }"
    class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden"
>
    {{-- BAR --}}
    <div class="px-4 py-3 md:px-5 md:py-4 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        {{-- LEFT --}}
        <div class="flex items-center gap-2 flex-wrap">

            @if($hasRemarks)
                <button
                    type="button"
                    @click="openRemarks = true"
                    class="inline-flex items-center gap-2 rounded-lg
                        bg-amber-100 text-amber-800 border border-amber-200
                        px-3 py-1.5 text-xs font-semibold
                        hover:bg-amber-200 transition"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v4m0 4h.01M10.29 3.86l-7.5 13A1 1 0 003.66 18h16.68a1 1 0 00.87-1.5l-7.5-13a1 1 0 00-1.74 0z"/>
                    </svg>
                    <span>Remarks</span>
                </button>
            @endif

            <button
                type="button"
                @click="openTimeline = true"
                class="inline-flex items-center gap-2 rounded-lg
                    border border-slate-300 bg-white
                    px-3 py-1.5 text-xs font-medium text-slate-700
                    hover:bg-slate-50 hover:border-slate-400
                    transition"
            >
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M8 7V3m8 4V3m-9 8h10m-11 8h12a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <span>View Timeline</span>
            </button>
        </div>

        {{-- RIGHT --}}
        <div class="flex items-center gap-2 flex-wrap md:justify-end">
            <span class="text-xs font-medium text-slate-500">Status</span>
            <span class="inline-flex items-center rounded-full border px-2.5 py-1 text-xs font-semibold {{ $statusConfig['badge'] }}">
                {{ $statusConfig['label'] }}
            </span>
        </div>
    </div>

    {{-- ========================= --}}
    {{-- REMARKS MODAL --}}
    {{-- ========================= --}}
    <div
        x-show="openRemarks"
        x-cloak
        x-transition.opacity
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
    >
        {{-- BACKDROP --}}
        <div class="absolute inset-0 bg-slate-900/50" @click="openRemarks = false"></div>

        {{-- MODAL --}}
        <div class="relative w-full max-w-2xl rounded-2xl bg-white border border-slate-200 shadow-xl overflow-hidden">
            {{-- HEADER --}}
            <div class="flex items-center justify-between px-5 py-4 border-b border-slate-200 bg-amber-50">
                <div>
                    <h3 class="text-sm font-semibold text-slate-900">
                        Latest Remarks
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Most recent remarks saved on this document
                    </p>
                </div>

                <button
                    type="button"
                    @click="openRemarks = false"
                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-white/80 transition"
                >
                    ✕
                </button>
            </div>

            {{-- CONTENT --}}
            <div class="p-5 h-[45vh] overflow-y-auto pr-2 scroll-smooth">
                @if($hasRemarks)
                    <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="inline-flex items-center rounded-full border border-amber-200 bg-white px-2.5 py-1 text-[11px] font-semibold text-amber-700">
                                Document Remarks
                            </span>
                        </div>

                        <div class="prose prose-sm max-w-none text-slate-700">
                            {!! $documentRemarks !!}
                        </div>
                    </div>
                @else
                    <div class="h-full flex items-center justify-center text-center text-sm text-slate-400">
                        No remarks available.
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ========================= --}}
    {{-- TIMELINE MODAL --}}
    {{-- ========================= --}}
    <div
        x-show="openTimeline"
        x-cloak
        x-transition.opacity
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
    >
        {{-- BACKDROP --}}
        <div class="absolute inset-0 bg-slate-900/50" @click="openTimeline = false"></div>

        {{-- MODAL --}}
        <div class="relative w-full max-w-3xl rounded-2xl bg-white border border-slate-200 shadow-xl overflow-hidden">
            {{-- HEADER --}}
            <div class="flex items-center justify-between px-5 py-4 border-b border-slate-200">
                <div>
                    <h3 class="text-lg font-semibold text-slate-900">
                        Submission Timeline
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Activity history for this document
                    </p>
                </div>

                <button
                    type="button"
                    @click="openTimeline = false"
                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition"
                >
                    ✕
                </button>
            </div>

            {{-- CONTENT --}}
            <div class="p-5 h-[65vh] overflow-y-auto pr-2 scroll-smooth">
                @if($timelines->count())
                    <div class="relative">
                        {{-- VERTICAL LINE --}}
                        <div class="absolute left-3 top-0 bottom-0 w-px bg-slate-200"></div>

                        <div class="space-y-6">
                            @foreach($timelines as $t)
                                @php
                                    $config = timelineActionConfig($t->action);
                                    $timelineHasRemarks = filled(trim(strip_tags((string) $t->remarks)));
                                @endphp

                                <div class="relative pl-10">
                                    {{-- DOT --}}
                                    <div class="absolute left-1.5 top-1 w-4 h-4 rounded-full border-2 border-white shadow {{ $config['dot'] }}"></div>

                                    {{-- TOP --}}
                                    <div class="flex flex-col gap-2 md:flex-row md:items-start md:justify-between">
                                        <div class="min-w-0">
                                            <div class="flex items-center gap-2 flex-wrap">
                                                <div class="text-sm font-semibold text-slate-800">
                                                    {{ $config['label'] }}
                                                </div>

                                                <span class="inline-flex items-center rounded-full border px-2 py-0.5 text-[11px] font-semibold {{ $config['pill'] }}">
                                                    {{ $config['label'] }}
                                                </span>
                                            </div>

                                            <div class="text-xs text-slate-500 mt-1">
                                                {{ $t->user->name ?? 'System' }}
                                                •
                                                {{ $t->created_at?->format('M d, Y h:i A') }}
                                            </div>
                                        </div>
                                    </div>

                                    {{-- STATUS CHANGE --}}
                                    @if(!empty($t->old_status) || !empty($t->new_status))
                                        <div class="mt-2">
                                            <span class="inline-flex items-center gap-2 rounded-lg bg-slate-50 border border-slate-200 px-2.5 py-1 text-xs text-slate-600">
                                                <span>{{ $t->old_status ?: '—' }}</span>
                                                <span>→</span>
                                                <span>{{ $t->new_status ?: '—' }}</span>
                                            </span>
                                        </div>
                                    @endif

                                    {{-- REMARKS INSIDE TIMELINE --}}
                                    @if($timelineHasRemarks)
                                        <div class="mt-3 rounded-xl border border-slate-200 bg-slate-50 p-3">
                                            <div class="text-[11px] font-semibold uppercase tracking-wide text-slate-500 mb-1">
                                                Remarks
                                            </div>
                                            <div class="prose prose-sm max-w-none text-slate-700">
                                                {!! $t->remarks !!}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="h-full flex items-center justify-center text-center text-sm text-slate-400">
                        No timeline records available.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>