<x-app-layout>

@include('admin.rereg.partials._success_toast')

<div class="mx-auto max-w-7xl px-4 py-6 space-y-6">

    <div class="org-page-card px-6 py-5">
        @include('admin.rereg.partials._header', [
            'organization' => $organization,
            'schoolYears' => $schoolYears,
            'encodeSyId' => $encodeSyId,
            'allApproved' => $allApproved,
            'alreadyActivated' => $alreadyActivated
        ])
    </div>


    <div class="org-page-card px-6 py-6 space-y-6">

        @if(!$encodeSyId)

            {{-- EMPTY STATE --}}
            <div class="text-center py-10">
                <div class="text-sm text-slate-500">
                    Please select a target school year to view submissions.
                </div>
            </div>

        @else

            <div class="space-y-2">

                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-semibold text-slate-800">
                            Submission Forms
                        </h3>
                        <p class="text-xs text-slate-500">
                            Review requirements for this organization
                        </p>
                    </div>

                    {{-- OPTIONAL: progress indicator (future-ready) --}}
                    {{-- <span class="text-xs text-slate-400">3 / 5 Approved</span> --}}
                </div>

                <div class="border border-slate-200 rounded-xl p-4 bg-slate-50/40">
                    @include('admin.rereg.partials._forms_grid', [
                        'forms' => $forms
                    ])
                </div>

            </div>

        @endif

    </div>

</div>

@includeWhen(
    $encodeSyId && $allApproved && !$alreadyActivated,
    'admin.rereg.partials._activate_modal',
    ['organization' => $organization]
)

</x-app-layout>