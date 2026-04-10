<x-app-layout>

<div class="bg-white shadow-sm rounded-2xl border border-slate-200 overflow-hidden">

    <div class="h-1.5 bg-gradient-to-r from-amber-400 via-orange-400 to-amber-500"></div>

    <div class="p-4 sm:p-6">

        <div x-data="{ openTimeline: false, openRemarks: false }">

            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">

                <div class="space-y-3">

                    <div class="flex items-center gap-2 flex-wrap">
                        <h1 class="text-base sm:text-lg font-semibold text-slate-900">
                            Officers List
                        </h1>

                        <span class="text-[10px] sm:text-xs font-medium text-amber-700 bg-amber-50 border border-amber-200 px-2 py-0.5 rounded-md">
                            Form B-3
                        </span>
                    </div>

                    <div class="flex flex-wrap items-center gap-2 text-[11px] sm:text-xs">

                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-amber-50 border border-amber-200 text-amber-800">
                            <i data-lucide="building-2" class="w-3.5 h-3.5 text-amber-500"></i>
                            <span class="font-medium">
                                {{ $submission->organization->name ?? ('Org #' . $submission->organization_id) }}
                            </span>
                        </span>

                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-orange-50 border border-orange-200 text-orange-800">
                            <i data-lucide="calendar" class="w-3.5 h-3.5 text-orange-500"></i>
                            <span class="font-medium">
                                {{ $submission->targetSchoolYear->name ?? '—' }}
                            </span>
                        </span>

                    </div>

                    <div>
                        <a href="{{ route('admin.rereg.hub', $submission->organization->id) }}"
                           class="inline-flex items-center gap-1.5 text-xs font-medium text-slate-500 hover:text-amber-600 transition">

                            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                            Back to Re-registration Hub
                        </a>
                    </div>

                </div>

                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 sm:justify-end">

                    @php
                        $status = $submission->status;

                        $config = match($status) {
                            'draft' => [
                                'label' => 'Draft',
                                'class' => 'bg-slate-50 text-slate-700 border-slate-200'
                            ],
                            'submitted_to_sacdev' => [
                                'label' => 'For Review',
                                'class' => 'bg-blue-50 text-blue-700 border-blue-200'
                            ],
                            'returned_by_sacdev' => [
                                'label' => 'Returned',
                                'class' => 'bg-rose-50 text-rose-700 border-rose-200'
                            ],
                            'approved_by_sacdev' => [
                                'label' => 'Approved',
                                'class' => 'bg-emerald-50 text-emerald-700 border-emerald-200'
                            ],
                            default => [
                                'label' => ucwords(str_replace('_', ' ', $status)),
                                'class' => 'bg-slate-50 text-slate-700 border-slate-200'
                            ],
                        };
                    @endphp

                    <div class="flex items-center gap-2">

                        <span class="inline-flex items-center text-[10px] sm:text-xs px-3 py-1 rounded-full border font-semibold {{ $config['class'] }}">
                            {{ $config['label'] }}
                        </span>

                        <div class="flex items-center gap-1">

                            @include('partials.timeline_remarks._remarks_button', [
                                'submission' => $submission
                            ])

                            @include('partials.timeline_remarks._timeline_button')

                        </div>

                    </div>

                </div>

            </div>

            <div class="mt-5 border-t border-slate-100 pt-4 text-[11px] text-slate-500">
                Manage and review organization officers assigned for this academic year. Ensure roles are complete and aligned with project head assignments.
            </div>

            @include('partials.timeline_remarks._remarks_modal', [
                'submission' => $submission
            ])

            @include('partials.timeline_remarks._timeline_modal', [
                'submission' => $submission
            ])

        </div>

    </div>

</div>

</x-app-layout>