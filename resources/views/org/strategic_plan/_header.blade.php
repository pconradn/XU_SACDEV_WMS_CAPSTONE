<div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-white to-slate-50 shadow-sm overflow-hidden">

    @php
        $status = $submission->status;

        $config = match($status) {
            'draft' => [
                'label' => 'Draft',
                'badge' => 'bg-slate-100 text-slate-700 ring-1 ring-inset ring-slate-200',
                'panel' => 'border-slate-200 bg-slate-50 text-slate-700',
                'accent' => 'from-slate-100/60 to-white',
                'icon' => 'file-text',
            ],
            'submitted_to_moderator' => [
                'label' => 'Moderator Review',
                'badge' => 'bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-200',
                'panel' => 'border-amber-200 bg-amber-50 text-amber-700',
                'accent' => 'from-amber-100/60 to-white',
                'icon' => 'clock-3',
            ],
            'returned_by_moderator' => [
                'label' => 'Returned by Moderator',
                'badge' => 'bg-rose-50 text-rose-700 ring-1 ring-inset ring-rose-200',
                'panel' => 'border-rose-200 bg-rose-50 text-rose-700',
                'accent' => 'from-rose-100/60 to-white',
                'icon' => 'corner-up-left',
            ],
            'forwarded_to_sacdev' => [
                'label' => 'SACDEV Review',
                'badge' => 'bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-200',
                'panel' => 'border-blue-200 bg-blue-50 text-blue-700',
                'accent' => 'from-blue-100/60 to-white',
                'icon' => 'shield-check',
            ],
            'returned_by_sacdev' => [
                'label' => 'Returned by SACDEV',
                'badge' => 'bg-rose-50 text-rose-700 ring-1 ring-inset ring-rose-200',
                'panel' => 'border-rose-200 bg-rose-50 text-rose-700',
                'accent' => 'from-rose-100/60 to-white',
                'icon' => 'rotate-ccw',
            ],
            'approved_by_sacdev' => [
                'label' => 'Approved',
                'badge' => 'bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-200',
                'panel' => 'border-emerald-200 bg-emerald-50 text-emerald-700',
                'accent' => 'from-emerald-100/60 to-white',
                'icon' => 'badge-check',
            ],
            default => [
                'label' => ucwords(str_replace('_', ' ', $status)),
                'badge' => 'bg-slate-100 text-slate-700 ring-1 ring-inset ring-slate-200',
                'panel' => 'border-slate-200 bg-slate-50 text-slate-700',
                'accent' => 'from-slate-100/60 to-white',
                'icon' => 'info',
            ],
        };

        $nextAction = match($status) {
            'draft' => 'You can continue editing and submit this strategic plan once it is ready.',
            'submitted_to_moderator' => 'This strategic plan is waiting for moderator review.',
            'returned_by_moderator' => 'Review the moderator remarks, revise the content, then submit again.',
            'forwarded_to_sacdev' => 'This strategic plan is now under SACDEV review.',
            'returned_by_sacdev' => 'Review the SACDEV remarks, update the plan, then resubmit.',
            'approved_by_sacdev' => 'This strategic plan has completed the approval flow.',
            default => null,
        };
    @endphp

    <div class="bg-gradient-to-r {{ $config['accent'] }}">
        <div class="p-5 sm:p-6">
            <div class="flex flex-col gap-5 xl:flex-row xl:items-start xl:justify-between">

                <div class="min-w-0 space-y-4">
                    <div class="space-y-2">
                        <div class="flex items-center gap-2 text-[11px] font-medium uppercase tracking-[0.14em] text-slate-500">
                            <i data-lucide="clipboard-list" class="h-3.5 w-3.5"></i>
                            Re-Registration Document
                        </div>

                        <div class="space-y-1">
                            <h1 class="text-lg font-semibold tracking-tight text-slate-900 sm:text-xl">
                                Strategic Plan
                            </h1>
                            <p class="text-xs text-slate-500">
                                Review, update, and track the approval progress of your organization’s strategic plan.
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-2 text-xs text-slate-600">
                        <div class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white/80 backdrop-blur px-3 py-2 shadow-sm">
                            <i data-lucide="calendar-range" class="h-3.5 w-3.5 text-slate-500"></i>
                            <span>Target SY:</span>
                            <span class="font-semibold text-slate-700">{{ $schoolYear->name }}</span>
                        </div>

                    </div>
                </div>

                <div class="flex w-full flex-col gap-3 xl:w-auto xl:min-w-[280px] xl:max-w-[320px]">
                    <div class="rounded-2xl border border-slate-200 bg-white/80 backdrop-blur p-4 shadow-sm">
                        <div class="flex items-start justify-between gap-3">
                            <div class="space-y-2">
                                <div class="text-[11px] font-medium uppercase tracking-[0.14em] text-slate-500">
                                    Current Status
                                </div>

                                <span class="inline-flex items-center gap-2 rounded-full px-3 py-1.5 text-xs font-semibold {{ $config['badge'] }}">
                                    <i data-lucide="{{ $config['icon'] }}" class="h-3.5 w-3.5"></i>
                                    {{ $config['label'] }}
                                </span>
                            </div>

                            <div class="hidden sm:flex h-10 w-10 items-center justify-center rounded-2xl bg-white ring-1 ring-slate-200 shadow-sm">
                                <i data-lucide="{{ $config['icon'] }}" class="h-4 w-4 text-slate-600"></i>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-2 xl:justify-end">
                        @include('admin.strategic_plans.partials._timeline', [
                            'submission' => $submission,
                            'compact' => true
                        ])

                        @include('org.strategic_plan.partials._remarks', [
                            'submission' => $submission
                        ])
                    </div>
                </div>

            </div>
        </div>
    </div>

    @if($nextAction)
        <div class="border-t border-slate-200 px-5 py-4 sm:px-6">
            <div class="flex items-start gap-3 rounded-2xl border px-4 py-3 text-xs shadow-sm {{ $config['panel'] }}">
                <div class="mt-0.5 shrink-0">
                    <i data-lucide="sparkles" class="h-4 w-4"></i>
                </div>
                <div class="min-w-0">
                    <div class="font-semibold">
                        Next Step
                    </div>
                    <div class="mt-1 leading-5">
                        {{ $nextAction }}
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>