<div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm overflow-hidden">

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
            'submitted_to_sacdev' => [
                'label' => 'Under SACDEV Review',
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
            'draft' => 'You can continue editing and submit once all officers are complete.',
            'submitted_to_sacdev' => 'This is currently under SACDEV review.',
            'returned_by_sacdev' => 'Review remarks, update entries, then resubmit.',
            'approved_by_sacdev' => 'This officers list is fully approved.',
            default => null,
        };
    @endphp

    {{-- TOP --}}
    <div class="bg-gradient-to-r {{ $config['accent'] }}">
        <div class="p-5 sm:p-6">
            <div class="flex flex-col gap-5 xl:flex-row xl:items-start xl:justify-between">

                {{-- LEFT --}}
                <div class="space-y-4 min-w-0">

                    <div class="space-y-2">
                        <div class="flex items-center gap-2 text-[11px] uppercase tracking-[0.14em] text-slate-500 font-medium">
                            <i data-lucide="users" class="h-3.5 w-3.5"></i>
                            Registration Form B-3
                        </div>

                        <div class="space-y-1">
                            <h1 class="text-lg sm:text-xl font-semibold text-slate-900">
                                Officers List
                            </h1>
                            <p class="text-xs text-slate-500">
                                Manage and review your organization’s official officers.
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-2 text-xs text-slate-600">

                        <div class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 shadow-sm">
                            <i data-lucide="calendar-range" class="h-3.5 w-3.5 text-slate-500"></i>
                            <span>Target SY:</span>
                            <span class="font-semibold text-slate-700">
                                {{ $schoolYear?->name ?? ('SY #' . (int) $targetSyId) }}
                            </span>
                        </div>


                    </div>

                </div>

                {{-- RIGHT --}}
                <div class="flex flex-col gap-3 xl:min-w-[260px]">

                    {{-- STATUS --}}
                    <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                        <div class="flex items-start justify-between gap-3">

                            <div class="space-y-2">
                                <div class="text-[11px] uppercase tracking-[0.14em] text-slate-500 font-medium">
                                    Status
                                </div>

                                <span class="inline-flex items-center gap-2 rounded-full px-3 py-1.5 text-xs font-semibold {{ $config['badge'] }}">
                                    <i data-lucide="{{ $config['icon'] }}" class="h-3.5 w-3.5"></i>
                                    {{ $config['label'] }}
                                </span>
                            </div>

                            <div class="hidden sm:flex h-10 w-10 items-center justify-center rounded-xl bg-white ring-1 ring-slate-200 shadow-sm">
                                <i data-lucide="{{ $config['icon'] }}" class="h-4 w-4 text-slate-600"></i>
                            </div>

                        </div>
                    </div>

                    {{-- ACTION BUTTONS --}}
                    <div class="flex flex-wrap gap-2 xl:justify-end">
                        @include('partials.timeline_remarks._timeline_button')
                        @include('partials.timeline_remarks._remarks_button', [
                            'submission' => $submission
                        ])
                    </div>

                    {{-- VIEW / EDIT MODE --}}
                    @if(!$canEdit)
                        <div class="rounded-xl border border-blue-200 bg-blue-50 px-3 py-2 text-xs text-blue-700 flex items-center gap-2">
                            <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                            View Only Mode
                        </div>
                    @else
                        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-3 py-2 text-xs text-emerald-700 flex items-center gap-2">
                            <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                            Editing Enabled (President)
                        </div>
                    @endif

                </div>

            </div>
        </div>
    </div>

    {{-- NEXT STEP --}}
    @if($nextAction)
    <div class="border-t border-slate-200 px-5 py-4 sm:px-6">
        <div class="flex items-start gap-3 rounded-2xl border px-4 py-3 text-xs shadow-sm {{ $config['panel'] }}">
            <i data-lucide="sparkles" class="h-4 w-4 mt-0.5"></i>
            <div>
                <div class="font-semibold">Next Step</div>
                <div class="mt-1">{{ $nextAction }}</div>
            </div>
        </div>
    </div>
    @endif

    {{-- MODALS --}}
    @include('partials.timeline_remarks._remarks_modal', [
        'submission' => $submission
    ])

    @include('partials.timeline_remarks._timeline_modal', [
        'submission' => $submission
    ])

</div>