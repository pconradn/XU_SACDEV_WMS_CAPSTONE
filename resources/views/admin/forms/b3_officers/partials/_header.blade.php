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
            'draft' => 'Waiting for submission.',
            'submitted_to_sacdev' => 'Currently under review.',
            'returned_by_sacdev' => 'Waiting for organization revision.',
            'approved_by_sacdev' => 'Fully approved.',
            default => null,
        };
    @endphp

    <div class="bg-gradient-to-r {{ $config['accent'] }}">
        <div class="p-4 sm:p-6">
            <div class="flex flex-col gap-4 xl:flex-row xl:items-start xl:justify-between">

                <div class="space-y-3 min-w-0">

                    <div class="space-y-1">
                        <div class="flex items-center gap-2 text-[10px] uppercase tracking-[0.14em] text-slate-500 font-medium">
                            <i data-lucide="users" class="h-3.5 w-3.5"></i>
                            Form B-3
                        </div>

                        <h1 class="text-base sm:text-lg font-semibold text-slate-900">
                            Officers List
                        </h1>

                        <p class="text-[11px] text-slate-500">
                            Review submitted organization officers.
                        </p>
                    </div>

                    <div class="flex flex-wrap items-center gap-2 text-[11px]">

                        <div class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-2.5 py-1.5 shadow-sm">
                            <i data-lucide="building-2" class="h-3.5 w-3.5 text-slate-500"></i>
                            <span class="font-medium text-slate-700">
                                {{ $submission->organization->name ?? ('Org #' . $submission->organization_id) }}
                            </span>
                        </div>

                        <div class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-2.5 py-1.5 shadow-sm">
                            <i data-lucide="calendar-range" class="h-3.5 w-3.5 text-slate-500"></i>
                            <span class="font-medium text-slate-700">
                                {{ $submission->targetSchoolYear->name ?? '—' }}
                            </span>
                        </div>

                        <a href="{{ route('admin.rereg.hub', $submission->organization->id) }}"
                           class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-2.5 py-1.5 text-slate-600 hover:bg-slate-50 transition">
                            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                            Back
                        </a>

                    </div>

                </div>

                <div class="flex flex-col gap-2 xl:min-w-[240px]">

                    <div class="rounded-xl border border-slate-200 bg-white p-3 shadow-sm">
                        <div class="flex items-center justify-between gap-2">

                            <div class="space-y-1">
                                <div class="text-[10px] uppercase tracking-[0.14em] text-slate-500 font-medium">
                                    Status
                                </div>

                                <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-[11px] font-semibold {{ $config['badge'] }}">
                                    <i data-lucide="{{ $config['icon'] }}" class="h-3.5 w-3.5"></i>
                                    {{ $config['label'] }}
                                </span>
                            </div>

                            <div class="h-8 w-8 flex items-center justify-center rounded-lg bg-white ring-1 ring-slate-200">
                                <i data-lucide="{{ $config['icon'] }}" class="h-4 w-4 text-slate-600"></i>
                            </div>

                        </div>
                    </div>

                    <div class="flex gap-1.5 xl:justify-end">
                        @include('admin.strategic_plans.partials._timeline')
                        @include('org.strategic_plan.partials._remarks', [
                            'submission' => $submission
                        ])
                    </div>

                    <div class="rounded-lg border border-blue-200 bg-blue-50 px-2.5 py-1.5 text-[11px] text-blue-700 flex items-center gap-1.5">
                        <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                        Review Mode (Admin)
                    </div>

                </div>

            </div>
        </div>
    </div>

    @if($nextAction)
    <div class="border-t border-slate-200 px-4 py-3 sm:px-6">
        <div class="flex items-start gap-2 rounded-xl border px-3 py-2 text-[11px] shadow-sm {{ $config['panel'] }}">
            <i data-lucide="sparkles" class="h-3.5 w-3.5 mt-0.5"></i>
            <div>
                <div class="font-semibold">Status Insight</div>
                <div class="mt-0.5">{{ $nextAction }}</div>
            </div>
        </div>
    </div>
    @endif

    @include('partials.timeline_remarks._remarks_modal', [
        'submission' => $submission
    ])

    @include('partials.timeline_remarks._timeline_modal', [
        'submission' => $submission
    ])

</div>
