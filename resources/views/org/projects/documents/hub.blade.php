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

</div>

</x-app-layout>