{{-- ========================= --}}
{{-- TIMELINE MODAL --}}
{{-- ========================= --}}
<div x-show="openTimeline"
     x-cloak
     x-transition
     class="fixed inset-0 z-50 flex items-center justify-center p-4">

    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"
         @click="openTimeline = false"></div>

    <div class="relative w-full max-w-2xl overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl">

        <div class="flex items-start justify-between gap-4 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white px-5 py-4">
            <div>
                <div class="flex items-center gap-2 text-lg font-semibold text-slate-900">
                    <i data-lucide="history" class="w-5 h-5 text-indigo-600"></i>
                    Submission Timeline
                </div>

                <div class="mt-1 text-xs text-slate-500">
                    Track submission actions, status changes, and reviewer remarks.
                </div>
            </div>

            <button type="button"
                    @click="openTimeline = false"
                    class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 transition hover:bg-slate-50 hover:text-slate-800">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <div class="max-h-[70vh] overflow-y-auto p-5">

            @if($submission->timelines->count())

                <div class="relative">

                    <div class="absolute left-4 top-2 bottom-2 w-px bg-slate-200"></div>

                    <div class="space-y-5">

                        @foreach($submission->timelines as $t)

                            @php
                                $config = match($t->action) {
                                    'submitted_to_moderator' => [
                                        'dot' => 'bg-slate-500',
                                        'icon' => 'send',
                                        'badge' => 'border-slate-200 bg-slate-50 text-slate-700',
                                        'label' => 'Submitted to Moderator',
                                    ],
                                    'returned_by_moderator' => [
                                        'dot' => 'bg-rose-500',
                                        'icon' => 'rotate-ccw',
                                        'badge' => 'border-rose-200 bg-rose-50 text-rose-700',
                                        'label' => 'Returned by Moderator',
                                    ],
                                    'forwarded_to_sacdev' => [
                                        'dot' => 'bg-blue-500',
                                        'icon' => 'forward',
                                        'badge' => 'border-blue-200 bg-blue-50 text-blue-700',
                                        'label' => 'Forwarded to SACDEV',
                                    ],
                                    'returned_by_sacdev' => [
                                        'dot' => 'bg-rose-600',
                                        'icon' => 'file-warning',
                                        'badge' => 'border-rose-200 bg-rose-50 text-rose-700',
                                        'label' => 'Returned by SACDEV',
                                    ],
                                    'approved_by_sacdev' => [
                                        'dot' => 'bg-emerald-600',
                                        'icon' => 'check-circle-2',
                                        'badge' => 'border-emerald-200 bg-emerald-50 text-emerald-700',
                                        'label' => 'Approved by SACDEV',
                                    ],
                                    'approval_reverted' => [
                                        'dot' => 'bg-amber-500',
                                        'icon' => 'undo-2',
                                        'badge' => 'border-amber-200 bg-amber-50 text-amber-700',
                                        'label' => 'Approval Reverted',
                                    ],
                                    default => [
                                        'dot' => 'bg-slate-400',
                                        'icon' => 'circle-dot',
                                        'badge' => 'border-slate-200 bg-slate-50 text-slate-700',
                                        'label' => ucwords(str_replace('_', ' ', $t->action)),
                                    ],
                                };
                            @endphp

                            <div class="relative pl-11">

                                <div class="absolute left-0 top-0.5 flex h-8 w-8 items-center justify-center rounded-full border-4 border-white shadow-sm {{ $config['dot'] }}">
                                    <i data-lucide="{{ $config['icon'] }}" class="w-3.5 h-3.5 text-white"></i>
                                </div>

                                <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">

                                    <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                                        <div>
                                            <span class="inline-flex items-center rounded-full border px-2.5 py-1 text-[11px] font-semibold {{ $config['badge'] }}">
                                                {{ $config['label'] }}
                                            </span>

                                            <div class="mt-2 text-xs text-slate-500">
                                                {{ $t->user->name ?? 'System' }}
                                            </div>
                                        </div>

                                        <div class="text-left sm:text-right">
                                            <div class="text-[11px] font-medium text-slate-500">
                                                {{ $t->created_at->format('M d, Y') }}
                                            </div>

                                            <div class="mt-0.5 text-[10px] text-slate-400">
                                                {{ $t->created_at->format('h:i A') }}
                                            </div>
                                        </div>
                                    </div>

                                    @if($t->old_status && $t->new_status)
                                        <div class="mt-3 inline-flex flex-wrap items-center gap-2 rounded-xl border border-blue-200 bg-blue-50 px-3 py-2 text-xs font-medium text-blue-700">
                                            <span>{{ ucwords(str_replace('_', ' ', $t->old_status)) }}</span>
                                            <i data-lucide="arrow-right" class="w-3 h-3"></i>
                                            <span>{{ ucwords(str_replace('_', ' ', $t->new_status)) }}</span>
                                        </div>
                                    @endif

                                    @if($t->remarks)
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
                        No timeline entries yet
                    </div>

                    <div class="mt-1 text-xs text-slate-500">
                        Submission activity will appear here once actions are recorded.
                    </div>
                </div>

            @endif

        </div>

    </div>
</div>