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
        'forms' => $pre,
        'sectionKey' => 'pre',
        'sectionCounts' => $sectionCounts
    ])


    @if($preSubmitted)

        @if(optional($project->proposalDocument?->proposalData)->off_campus_venue)

            {{-- OFF CAMPUS --}}
            @include('org.projects.documents.v2.partials.section', [
                'title' => 'Off-Campus Requirements',
                'forms' => $sections['offcampus'] ?? collect(),
                'sectionKey' => 'offcampus',
                'sectionCounts' => $sectionCounts
            ])

        @endif

        {{-- NOTICES --}}
        @include('org.projects.documents.v2.partials.section', [
            'title' => 'Notices / Adjustments',
            'forms' => $notices,
            'sectionKey' => 'notices',
            'sectionCounts' => $sectionCounts
        ])

        {{-- OTHER --}}
        @include('org.projects.documents.v2.partials.section', [
            'title' => 'Supporting Documents',
            'forms' => $other,
            'sectionKey' => 'other',
            'sectionCounts' => $sectionCounts
        ])

        {{-- POST --}}
        @include('org.projects.documents.v2.partials.section', [
            'title' => 'Post-Implementation Documents',
            'forms' => $post,
            'sectionKey' => 'post',
            'sectionCounts' => $sectionCounts
        ])

    @else

        <div class="bg-white border rounded-2xl p-6 text-center text-sm text-slate-500">
            Other documents will be available once the Project Proposal and Budget Proposal are submitted.
        </div>

    @endif


    @if($needsAgreement)

    <div 
        x-data="{ open: true, agreed: false, countdown: 5 }"
        x-init="let timer = setInterval(() => { if(countdown > 0) countdown--; else clearInterval(timer) }, 1000)"
        x-show="open"
        class="fixed inset-0 z-[999] flex items-center justify-center bg-black/60"
    >

        <div class="bg-white rounded-2xl shadow-xl max-w-2xl w-full p-6">

            {{-- HEADER --}}
            <div class="mb-4 border-b pb-3">
                <h2 class="text-xl font-bold text-slate-900">
                    STUDENT AGREEMENT FORM
                </h2>
                <p class="text-xs text-slate-500">
                    (Digital acknowledgment required before proceeding)
                </p>
            </div>

            {{-- BODY --}}
            <div class="text-sm text-slate-700 space-y-4 max-h-[400px] overflow-y-auto pr-2">

                <p><strong>1. Acknowledgment of Responsibilities</strong></p>
                <p>
                    I acknowledge that I am responsible for submitting all post-documentation requirements for the project that I head. 
                    Failure to do so may result in not being cleared by the Office of Student Affairs.
                </p>

                <p><strong>2. Understanding of Consequences</strong></p>
                <ul class="list-disc pl-5 space-y-1">
                    <li>Inability to take examinations</li>
                    <li>Inability to obtain official documents</li>
                    <li>Organization restrictions on future projects</li>
                </ul>

                <p><strong>3. Commitment to Compliance</strong></p>
                <p>
                    I commit to fulfilling all requirements on time and understand that this affects my academic standing.
                </p>

                <p><strong>4. Acceptance of Terms</strong></p>
                <p>
                    By proceeding, I confirm that I have read, understood, and agreed to all terms stated above.
                </p>

                {{-- WARNING --}}
                <div class="bg-amber-50 border border-amber-200 text-amber-800 text-xs p-3 rounded-lg">
                    ⚠ Important: You must read and agree before continuing. This action is recorded in the system.
                </div>

            </div>

            {{-- FOOTER --}}
            <div class="mt-6 space-y-3">

                {{-- CHECKBOX --}}
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" x-model="agreed" class="rounded border-slate-300">
                    I have read and agree to the terms above
                </label>

                {{-- ACTION --}}
                <form method="POST" action="{{ route('org.projects.agreement.accept', $project) }}">
                    @csrf

                    <button 
                        type="submit"
                        :disabled="!agreed || countdown > 0"
                        class="w-full px-4 py-2 rounded-lg text-white transition"
                        :class="(!agreed || countdown > 0) 
                            ? 'bg-slate-400 cursor-not-allowed' 
                            : 'bg-emerald-600 hover:bg-emerald-700'"
                    >
                        <template x-if="countdown > 0">
                            <span>Please wait <span x-text="countdown"></span>s...</span>
                        </template>

                        <template x-if="countdown === 0">
                            <span>I Agree and Continue</span>
                        </template>
                    </button>

                </form>

            </div>

        </div>
    </div>

    @endif







</div>



</x-app-layout>