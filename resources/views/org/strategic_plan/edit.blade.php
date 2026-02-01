<x-app-layout>
    @include('org.strategic_plan._header', ['submission' => $submission, 'schoolYear' => $schoolYear])

    @include('org.strategic_plan._alpine', ['submission' => $submission])

    <div class="space-y-6" x-data="strategicPlanForm(window.__SP_INIT__)">
        {{-- SAVE DRAFT --}}
        <form method="POST"
              action="{{ route('org.strategic_plan.draft') }}"
              enctype="multipart/form-data"
              class="space-y-6">
            @csrf

            @include('org.strategic_plan._identity', ['submission' => $submission])
            @include('org.strategic_plan._projects')
            @include('org.strategic_plan._funds')
            @include('org.strategic_plan._projects_modal')
            

            <div class="flex items-center gap-3">
                <button type="submit"
                        class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                    Save Draft
                </button>
                <p class="text-sm text-slate-500">You can save anytime. Submitting comes after saving.</p>
            </div>


            


        </form>

        {{-- SUBMIT --}}
        @include('org.strategic_plan._submit', ['submission' => $submission])
    </div>
</x-app-layout>
