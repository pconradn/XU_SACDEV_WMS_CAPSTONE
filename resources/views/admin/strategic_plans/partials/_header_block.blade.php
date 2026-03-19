{{-- TOP WRAPPER --}}
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        {{-- LEFT SIDE (MAIN INFO) --}}
        <div class="lg:col-span-8 space-y-4">

            <div class="flex items-start justify-between gap-6">

                {{-- LEFT: TITLE + ORG --}}
                <div class="space-y-2">

                    <h1 class="text-xl font-semibold text-slate-900">
                        Strategic Plan Submission
                    </h1>

                    <div class="text-sm text-slate-600">
                        <span class="text-slate-500">Organization</span>
                        <div class="font-semibold text-slate-900">
                            {{ $submission->organization->name ?? ($submission->org_name ?? '—') }}

                            @if(!empty($submission->org_acronym))
                                <span class="text-slate-400 font-normal">
                                    ({{ $submission->org_acronym }})
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="text-sm text-slate-600">
                        <span class="text-slate-500">School Year</span>
                        <div class="font-semibold text-slate-900">
                            {{ $submission->targetSchoolYear->name ?? '—' }}
                        </div>
                    </div>

                </div>


                {{-- RIGHT: STATUS + PEOPLE 🔥 --}}
                <div class="text-right space-y-3 min-w-[200px]">

                    {{-- STATUS --}}
                    <div>
                        <span class="inline-flex items-center gap-2 text-xs px-3 py-1.5 rounded-full border font-medium capitalize
                            @if($submission->status === 'approved_by_sacdev')
                                bg-emerald-50 border-emerald-200 text-emerald-700
                            @elseif(str_contains($submission->status, 'returned'))
                                bg-rose-50 border-rose-200 text-rose-700
                            @elseif(in_array($submission->status, ['submitted_to_moderator','forwarded_to_sacdev']))
                                bg-amber-50 border-amber-200 text-amber-700
                            @else
                                bg-slate-100 border-slate-200 text-slate-700
                            @endif
                        ">
                            ● {{ str_replace('_',' ', $submission->status) }}
                        </span>
                    </div>

                    {{-- PEOPLE --}}
                    <div class="space-y-2 text-sm">

                        <div>
                            <div class="text-slate-500 text-xs">Submitted by</div>
                            <div class="font-semibold text-slate-900">
                                {{ $submission->submittedBy?->name ?? '—' }}
                            </div>
                        </div>

                        <div>
                            <div class="text-slate-500 text-xs">Moderator</div>
                            <div class="font-semibold text-slate-900">
                                {{ $submission->moderatorReviewedBy?->name ?? '—' }}
                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- RIGHT SIDE (TIMELINE) --}}
        <div class="lg:col-span-4">

            <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 lg:sticky lg:top-6">

                @include('admin.strategic_plans.partials._timeline', [
                    'submission' => $submission,
                    'compact' => true
                ])

            </div>

        </div>

    </div>

</div>