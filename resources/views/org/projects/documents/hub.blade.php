<x-app-layout>

<div class="max-w-7xl mx-auto px-6 py-6 space-y-6">

    <div class="max-w-7xl mx-auto px-6 py-6">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- LEFT: MAIN CONTENT --}}
            <div class="lg:col-span-2 space-y-6">

                @include('org.projects.documents.v2.partials.header', ['header' => $header])

                @include('org.projects.documents.v2.partials.snapshot')

                @include('org.projects.documents.v2.partials.milestone', [
                    'milestones' => $milestones,
                    'currentStage' => $currentStage
                ])

            </div>


            {{-- RIGHT: SIDEBAR --}}
            <div class="lg:col-span-1 space-y-6">

                @include('org.projects.documents.v2.partials.actions')

                @include('org.projects.documents.v2.partials.clearance-card')

            </div>

        </div>

    </div>



    @php
        $pre = $sections['pre'] ?? collect();
        $notices = $sections['notices'] ?? collect();
        $other = $sections['other'] ?? collect();
        $post = $sections['post'] ?? collect();

        $proposal = $pre->firstWhere('code', 'PROJECT_PROPOSAL');
        $budget = $pre->firstWhere('code', 'BUDGET_PROPOSAL');

        $preSubmitted =
            $proposal && $proposal['document'] && $proposal['document']->status !== 'draft'
            &&
            $budget && $budget['document'] && $budget['document']->status !== 'draft';
    @endphp


    @include('org.projects.documents.v2.partials.section', [
        'title' => 'Pre-Implementation Documents',
        'forms' => $pre
    ])


    @if($preSubmitted)

        @include('org.projects.documents.v2.partials.section', [
            'title' => 'Notices / Adjustments',
            'forms' => $notices
        ])

        @include('org.projects.documents.v2.partials.section', [
            'title' => 'Supporting Documents',
            'forms' => $other
        ])

        @include('org.projects.documents.v2.partials.section', [
            'title' => 'Post-Implementation Documents',
            'forms' => $post
        ])

    @else

        <div class="bg-white border rounded-2xl p-6 text-center text-sm text-slate-500">
            Other documents will be available once the Project Proposal and Budget Proposal are submitted.
        </div>

    @endif


</div>

</x-app-layout>