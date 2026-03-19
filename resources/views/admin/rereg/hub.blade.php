<x-app-layout>

@include('admin.rereg.partials._success_toast')

<div class="mx-auto max-w-7xl px-4 py-6 space-y-6">


    <div class="grid grid-cols-1 lg:grid-cols-12 gap-2">

        {{-- ORG HEADER --}}
        <div class="lg:col-span-7">33
            <div class="org-page-card px-5 py-5 h-full">
                @include('admin.rereg.partials._header', [
                    'organization' => $organization
                ])
            </div>
        </div>

        {{-- SY SELECTOR --}}
        <div class="lg:col-span-5">
            <div class="org-page-card px-5 py-5 h-full">
                @include('admin.rereg.partials._sy_selector', [
                    'schoolYears' => $schoolYears,
                    'encodeSyId' => $encodeSyId
                ])
            </div>
        </div>

    </div>


    @if(!$encodeSyId)

        <div class="org-page-card px-6 py-8 text-center">
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
                        Review B-1 to B-5 requirements for this organization
                    </p>
                </div>
            </div>

            <div class="org-page-card px-5 py-5">
                @include('admin.rereg.partials._forms_grid', [
                    'forms' => $forms
                ])
            </div>
        </div>

        <div class="space-y-2">
            <div>
                <h3 class="text-sm font-semibold text-slate-800">
                    Registration Status
                </h3>
                <p class="text-xs text-slate-500">
                    Final check before activating organization for the selected SY
                </p>
            </div>

            <div class="org-page-card px-5 py-5">
                @include('admin.rereg.partials._readiness_panel', [
                    'organization' => $organization,
                    'allApproved' => $allApproved,
                    'alreadyActivated' => $alreadyActivated
                ])
            </div>
        </div>

    @endif

</div>

@includeWhen(
    $encodeSyId && $allApproved && !$alreadyActivated,
    'admin.rereg.partials._activate_modal',
    ['organization' => $organization]
)

</x-app-layout>