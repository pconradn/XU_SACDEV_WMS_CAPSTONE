<x-app-layout>

<div class="mx-auto max-w-6xl px-4 py-6">

@include('org.projects.documents.partials.header', [
    'project' => $project,
    'proposalData' => $proposalData ?? null
])


{{-- PROJECT MILESTONE --}}
@include('org.projects.documents.partials.milestone-slider', [
    'stage' => $projectStage
])


{{-- PRE IMPLEMENTATION --}}
@include('org.projects.documents.partials.section', [
    'title' => 'Pre-Implementation Documents',
    'forms' => $preForms,
    'phase' => 'pre'
])


{{-- DV --}}
@if($budgetDocument)

@include('org.projects.documents.partials.dv-card', [
    'project' => $project
])

@endif

{{-- OFF CAMPUS --}}
@if($showOffCampus)

@include('org.projects.documents.partials.section', [
    'title' => 'Off-Campus Documents',
    'forms' => $offCampusForms ?? [],
    'phase' => 'offcampus'
])


{{-- CLEARANCE --}}
@if($showOffCampus)

@include('org.projects.documents.partials._clearance-card')

@endif

@endif


{{-- OTHER FORMS --}}
@if($showOtherForms)

@include('org.projects.documents.partials.section', [
    'title' => 'Other Forms',
    'forms' => $otherForms ?? [],
    'phase' => 'other'
])

@endif


{{-- NOTICES --}}
@include('org.projects.documents.partials.notice-section', [
    'project' => $project,
    'postponements' => $postponements,
    'cancellations' => $cancellations
])








{{-- POST IMPLEMENTATION --}}
@include('org.projects.documents.partials.section', [
    'title' => 'Post-Implementation Documents',
    'forms' => $postForms,
    'phase' => 'post'
])


{{-- PHYSICAL SUBMISSIONS --}}
<div class="mt-8 border border-slate-200 rounded-xl bg-white shadow-sm">

    <div class="px-5 py-4 border-b border-slate-200 flex items-center justify-between">

        <div>
            <div class="text-sm font-semibold text-slate-900">
                Physical Submissions
            </div>

            <div class="text-xs text-slate-500">
                Manage physical document packets submitted to SACDEV.
            </div>
        </div>

        <a href="{{ route('org.projects.packets.index', $project) }}"
           class="inline-flex items-center px-3 py-2 text-xs font-medium rounded-md bg-indigo-600 text-white hover:bg-indigo-700">
            Open Packets
        </a>

    </div>

    <div class="px-5 py-4 text-xs text-slate-600">

        Physical packets are used for submitting printed documents such as
        liquidation reports, official receipts, disbursement vouchers, and
        supporting documents required by the Finance Office.

    </div>

</div>


</div>

</x-app-layout>