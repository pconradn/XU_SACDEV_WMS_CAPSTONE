<x-app-layout>
   
    <div x-data="{ openRemarks: false }">
    @include('org.strategic_plan._header', ['submission' => $submission, 'schoolYear' => $schoolYear])

    <div class="space-y-6" x-data="strategicPlanForm(window.__SP_INIT__)">
        {{-- SAVE DRAFT --}}


            @include('org.strategic_plan._identity', ['submission' => $submission])
            @include('org.strategic_plan._projects')
            @include('org.strategic_plan._funds')
            @include('org.strategic_plan._projects_modal')


        {{-- SUBMIT --}}
        @include('org.strategic_plan._submit', ['submission' => $submission, 'submitRoute' => 'org.rereg.b1.submit'])
    </div>
</x-app-layout>
