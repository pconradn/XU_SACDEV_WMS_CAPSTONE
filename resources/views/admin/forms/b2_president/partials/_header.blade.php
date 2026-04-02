<div class="bg-white shadow-sm rounded-2xl border border-slate-200 overflow-hidden">

    {{-- TOP ACCENT BAR --}}
    <div class="h-1.5 bg-gradient-to-r from-indigo-500 via-violet-500 to-purple-500"></div>

    <div class="p-6">

        <div x-data="{ openTimeline: false, openRemarks: false }">

            <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">

                {{-- LEFT --}}
                <div class="space-y-3">

                    {{-- TITLE --}}
                    <div class="flex items-center gap-3 flex-wrap">

                        <h1 class="text-lg font-semibold text-slate-900">
                            President Registration
                        </h1>

                        <span class="text-xs font-medium text-indigo-700 bg-indigo-50 border border-indigo-200 px-2.5 py-1 rounded-md">
                            Form B-2
                        </span>

                    </div>

                    {{-- META --}}
                    <div class="flex flex-wrap items-center gap-2 text-sm">

                        {{-- ORG --}}
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-indigo-50 border border-indigo-200 text-indigo-800">
                            <span class="text-indigo-400">Org</span>
                            <span class="font-medium">
                                {{ $registration->organization->name ?? '—' }}
                            </span>
                        </span>

                        {{-- SY --}}
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-violet-50 border border-violet-200 text-violet-800">
                            <span class="text-violet-400">SY</span>
                            <span class="font-medium">
                                {{ $registration->targetSchoolYear->name ?? '—' }}
                            </span>
                        </span>

                    </div>

                    {{-- BACK --}}
                    <div>
                        <a href="{{ route('rereg.hub', $registration->organization_id) }}"
                           class="inline-flex items-center gap-2 text-sm font-medium text-slate-600 hover:text-slate-900 transition">

                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>

                            Back to Re-registration Hub
                        </a>
                    </div>

                </div>


                {{-- RIGHT --}}
                <div class="flex items-center gap-2 flex-wrap lg:justify-end">

                    {{-- STATUS --}}
                    @php
                        $status = $registration->status;

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

                    <span class="inline-flex items-center text-xs px-3 py-1.5 rounded-full border font-semibold {{ $config['class'] }}">
                        {{ $config['label'] }}
                    </span>

                    {{-- ACTIONS --}}
                    <div class="flex items-center gap-1">

                        @include('partials.timeline_remarks._remarks_button', [
                            'submission' => $registration
                        ])

                        @include('partials.timeline_remarks._timeline_button')

                    </div>

                </div>

            </div>

            {{-- MODALS --}}
            @include('partials.timeline_remarks._remarks_modal', [
                'submission' => $registration
            ])

            @include('partials.timeline_remarks._timeline_modal', [
                'submission' => $registration
            ])

        </div>

    </div>

</div>