<div class="bg-white shadow-sm rounded-2xl p-5 space-y-4">

    <div x-data="{ openTimeline: false, openRemarks: false }">

        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

            {{-- LEFT --}}
            <div>
                <h1 class="text-xl font-semibold text-slate-900">
                    Registration Form B-3: Officers List
                </h1>

                <div class="text-sm text-slate-500 mt-1 flex flex-wrap items-center gap-2">

                    {{-- ORG --}}
                    <span>
                        Organization:
                        <span class="font-semibold text-slate-800">
                            {{ $submission->organization->name ?? ('Org #' . $submission->organization_id) }}
                        </span>
                    </span>
                   

                    {{-- SCHOOL YEAR (FIXED INLINE) --}}
                    <span>
                        Target School Year:
                        <span class="font-semibold text-slate-800">
                            {{ $submission->targetSchoolYear->name ?? '—' }}
                        </span>
                    </span>

                    <span class="hidden sm:inline">•</span>

                    {{-- BACK LINK (ADDED) --}}
                    <a href="{{ route('rereg.hub', $submission->organization->id) }}"
                       class="text-blue-600 hover:underline font-medium">
                        Back to Re-Registration
                    </a>

                </div>
            </div>

            {{-- RIGHT: STATUS + ACTIONS --}}
            <div class="flex items-center gap-2 flex-wrap">

                {{-- STATUS --}}
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
                            'class' => 'bg-red-50 text-red-700 border-red-200'
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

                {{-- REMARKS --}}
                @include('partials.timeline_remarks._remarks_button', [
                    'submission' => $submission
                ])

                {{-- TIMELINE --}}
                @include('partials.timeline_remarks._timeline_button')

            </div>

        </div>

        {{-- MODALS --}}
        @include('partials.timeline_remarks._remarks_modal', [
            'submission' => $submission
        ])

        @include('partials.timeline_remarks._timeline_modal', [
            'submission' => $submission
        ])

    </div>

</div>