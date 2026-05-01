@php
    $pendingCases = collect($pendingCases ?? []);
    $readyForActivation = collect($readyForActivation ?? []);

    $typeConfig = [
        'Strategic Plan' => [
            'icon' => 'target',
            'class' => 'border-blue-200 bg-blue-50 text-blue-700',
        ],
        'Officer Submission' => [
            'icon' => 'users-round',
            'class' => 'border-emerald-200 bg-emerald-50 text-emerald-700',
        ],
        'Officer Submission (Edit Request)' => [
            'icon' => 'message-square-warning',
            'class' => 'border-amber-200 bg-amber-50 text-amber-700',
        ],
        'President Registration' => [
            'icon' => 'user-check',
            'class' => 'border-purple-200 bg-purple-50 text-purple-700',
        ],
        'Moderator Submission' => [
            'icon' => 'shield-check',
            'class' => 'border-indigo-200 bg-indigo-50 text-indigo-700',
        ],
        'default' => [
            'icon' => 'file-text',
            'class' => 'border-slate-200 bg-slate-50 text-slate-700',
        ],
    ];

    $statusConfig = [
        'submitted_to_sacdev' => [
            'label' => 'Submitted to SACDEV',
            'icon' => 'send',
            'class' => 'border-blue-200 bg-blue-50 text-blue-700',
        ],
        'forwarded_to_sacdev' => [
            'label' => 'Forwarded to SACDEV',
            'icon' => 'forward',
            'class' => 'border-indigo-200 bg-indigo-50 text-indigo-700',
        ],
        'edit_requested' => [
            'label' => 'Edit Requested',
            'icon' => 'message-square-warning',
            'class' => 'border-amber-200 bg-amber-50 text-amber-700',
        ],
        'default' => [
            'label' => 'Needs Review',
            'icon' => 'clock',
            'class' => 'border-slate-200 bg-slate-50 text-slate-700',
        ],
    ];

    $groupedPendingCases = $pendingCases
        ->groupBy(fn($case) => ($case->organization_id ?? 'none') . '|' . ($case->school_year_id ?? 'none'))
        ->values();
@endphp

<div class="space-y-4">

    @if($readyForActivation->isNotEmpty())
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50/70 overflow-hidden">
            <div class="px-4 py-3 border-b border-emerald-200 bg-emerald-100/50">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <div class="flex items-center gap-2 text-sm font-semibold text-slate-900">
                            <i data-lucide="badge-check" class="w-4 h-4 text-emerald-700"></i>
                            Ready for Activation
                        </div>

                        <div class="mt-1 text-xs text-emerald-800/80">
                            Completed active school year requirements and awaiting activation.
                        </div>
                    </div>

                    <span class="inline-flex w-fit items-center gap-1.5 rounded-full border border-emerald-200 bg-white px-2.5 py-1 text-[11px] font-semibold text-emerald-700">
                        <i data-lucide="check-circle-2" class="w-3 h-3"></i>
                        {{ $readyForActivation->count() }} ready
                    </span>
                </div>
            </div>

            <div class="divide-y divide-emerald-100 max-h-[260px] overflow-y-auto">
                @foreach($readyForActivation->take(6) as $item)
                    <a href="{{ $item->route }}"
                       class="block bg-white/80 px-4 py-3 transition hover:bg-emerald-50">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <div class="flex items-start gap-3 min-w-0">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-700">
                                    <i data-lucide="building-2" class="w-5 h-5"></i>
                                </div>

                                <div class="min-w-0">
                                    <div class="text-sm font-semibold text-slate-900 truncate">
                                        {{ $item->organization->name ?? 'Organization' }}
                                    </div>

                                    <div class="mt-1 text-xs text-slate-500">
                                        {{ $item->school_year->name ?? 'Active School Year' }}
                                    </div>
                                </div>
                            </div>

                            <span class="inline-flex w-fit items-center gap-1.5 rounded-xl bg-emerald-600 px-3 py-2 text-xs font-semibold text-white">
                                Open
                                <i data-lucide="arrow-right" class="w-3 h-3"></i>
                            </span>
                        </div>
                    </a>
                @endforeach

                @if($readyForActivation->count() > 6)
                    <div class="bg-white/80 px-4 py-3 text-xs font-medium text-emerald-700">
                        + {{ $readyForActivation->count() - 6 }} more ready for activation
                    </div>
                @endif
            </div>
        </div>
    @endif

    @if($pendingCases->isNotEmpty())
        <div class="space-y-3 max-h-[430px] overflow-y-auto pr-1">
            @foreach($groupedPendingCases as $orgCases)
                @php
                    $first = $orgCases->first();
                    $caseCount = $orgCases->count();
                    $latestDate = $orgCases->max('created_at');
                @endphp

                <a href="{{ $first->route }}"
                   class="block rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition hover:border-amber-200 hover:bg-amber-50/40">
                    <div class="flex flex-col gap-3 lg:flex-row lg:items-start lg:justify-between">

                        <div class="flex items-start gap-3 min-w-0">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-amber-200 bg-amber-50 text-amber-700">
                                <i data-lucide="building-2" class="w-5 h-5"></i>
                            </div>

                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <div class="text-sm font-semibold text-slate-900 truncate">
                                        {{ $first->organization->name ?? 'Organization' }}
                                    </div>

                                    <span class="inline-flex items-center rounded-full border border-slate-200 bg-slate-50 px-2 py-0.5 text-[10px] font-semibold text-slate-600">
                                        {{ $caseCount }} task{{ $caseCount === 1 ? '' : 's' }}
                                    </span>
                                </div>

                                <div class="mt-1 text-xs text-slate-500">
                                    {{ $first->school_year->name ?? 'School Year' }}
                                </div>

                                <div class="mt-3 flex flex-wrap gap-2">
                                    @foreach($orgCases as $case)
                                        @php
                                            $type = $case->type ?? 'Requirement';
                                            $typeData = $typeConfig[$type] ?? $typeConfig['default'];

                                            $status = $case->status ?? 'pending';
                                            $statusData = $statusConfig[$status] ?? [
                                                'label' => ucwords(str_replace('_', ' ', $status)),
                                                'icon' => 'clock',
                                                'class' => 'border-slate-200 bg-slate-50 text-slate-700',
                                            ];
                                        @endphp

                                        <span class="inline-flex max-w-full items-center gap-1.5 rounded-full border px-2.5 py-1 text-[11px] font-semibold {{ $typeData['class'] }}">
                                            <i data-lucide="{{ $typeData['icon'] }}" class="w-3 h-3 shrink-0"></i>
                                            <span class="truncate">{{ $type }}</span>
                                        </span>

                                        <span class="inline-flex max-w-full items-center gap-1.5 rounded-full border px-2.5 py-1 text-[11px] font-semibold {{ $statusData['class'] }}">
                                            <i data-lucide="{{ $statusData['icon'] }}" class="w-3 h-3 shrink-0"></i>
                                            <span class="truncate">{{ $statusData['label'] }}</span>
                                        </span>
                                    @endforeach
                                </div>

                                @foreach($orgCases as $case)
                                    @if(!empty($case->edit_request_reason))
                                        <div class="mt-3 rounded-xl border border-amber-200 bg-amber-50 px-3 py-2 text-xs leading-5 text-amber-800">
                                            <div class="mb-1 flex items-center gap-1.5 font-semibold">
                                                <i data-lucide="message-square-warning" class="w-3.5 h-3.5"></i>
                                                Edit request reason
                                            </div>

                                            <div class="whitespace-pre-line">
                                                {{ $case->edit_request_reason }}
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        <div class="flex shrink-0 items-center justify-between gap-3 lg:block lg:text-right">
                            <div>
                                <div class="text-[11px] font-medium text-slate-500">
                                    Latest activity
                                </div>

                                <div class="mt-0.5 text-[10px] text-slate-400">
                                    {{ $latestDate ? \Carbon\Carbon::parse($latestDate)->diffForHumans() : 'No date' }}
                                </div>
                            </div>

                            <div class="lg:mt-3">
                                <span class="inline-flex items-center gap-1.5 rounded-xl bg-amber-600 px-3 py-2 text-xs font-semibold text-white">
                                    Review
                                    <i data-lucide="arrow-right" class="w-3 h-3"></i>
                                </span>
                            </div>
                        </div>

                    </div>
                </a>
            @endforeach
        </div>
    @else
        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-8 text-center">
            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-400">
                <i data-lucide="inbox" class="w-6 h-6"></i>
            </div>

            <div class="mt-3 text-sm font-semibold text-slate-800">
                No pending re-registration reviews
            </div>

            <div class="mt-1 text-xs text-slate-500">
                Submitted registration requirements will appear here when review is needed.
            </div>
        </div>
    @endif

</div>