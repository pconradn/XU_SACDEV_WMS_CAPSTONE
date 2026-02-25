<x-app-layout>

@include('admin.rereg.partials._success_toast')

<div class="mx-auto max-w-7xl px-4 py-6 space-y-6">

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 mb-6">

        <div class="lg:col-span-7">
            @include('admin.rereg.partials._header', [
                'organization' => $organization
            ])
        </div>

        <div class="lg:col-span-5">
            @include('admin.rereg.partials._sy_selector', [
                'schoolYears' => $schoolYears,
                'encodeSyId' => $encodeSyId
            ])
        </div>

    </div>


    @if(!$encodeSyId)

        <div class="rounded-xl border border-slate-200 bg-white p-6 text-slate-700">
            Please select a target school year to view submissions.
        </div>

    @else

        @include('admin.rereg.partials._forms_grid', [
            'forms' => $forms
        ])

        @include('admin.rereg.partials._readiness_panel', [
            'organization' => $organization,
            'allApproved' => $allApproved,
            'alreadyActivated' => $alreadyActivated
        ])

    @endif

</div>

@includeWhen(
    $encodeSyId && $allApproved && !$alreadyActivated,
    'admin.rereg.partials._activate_modal',
    ['organization' => $organization]
)

</x-app-layout>