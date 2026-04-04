<x-app-layout>

<div x-data="{ openAgreement: @json($needsAgreement) }">

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
        $required = $sections['required'] ?? collect();
        $optional = $sections['optional'] ?? collect();
        $workflow = $sections['workflow'] ?? collect();

        //$proposalDoc = $project->proposalDocument;
        //$budgetDoc = $project->budgetDocument ?? null;

        $preSubmitted =
            $proposalDoc && $proposalDoc->status !== 'draft'
            &&
            $budgetDoc && $budgetDoc->status !== 'draft';
    @endphp

    
    @php
        $requiredCount = $required->count();
        $completedRequired = $required->filter(fn($f) => $f['document'] && $f['document']->status === 'approved_by_sacdev')->count();
    @endphp

    @php
    function groupFormsByPhase($collection) {
        $phaseOrder = [
            'other',
            'off-campus',
            'post_implementation',
            'notice',
        ];

        $grouped = $collection->groupBy(function ($form) {
            return $form['phase'] ?? $form['document']?->formType->phase ?? 'other';
        });

        return collect($phaseOrder)
            ->mapWithKeys(fn($phase) => [$phase => $grouped[$phase] ?? collect()])
            ->filter(fn($group) => $group->isNotEmpty());
    }

    $phaseLabels = [
        'other' => 'Supporting Documents',
        'off-campus' => 'Off-Campus Requirements',
        'post_implementation' => 'Post-Implementation',
        'notice' => 'Notices / Adjustments',
    ];
    @endphp



    <div class="bg-white border rounded-2xl p-4">
        <div class="text-sm font-semibold text-slate-700">
            Required Documents Progress
        </div>

        <div class="mt-2 text-xs text-slate-500">
            {{ $completedRequired }} / {{ $requiredCount }} completed
        </div>

        <div class="mt-2 w-full bg-slate-100 rounded-full h-2">
            <div class="bg-emerald-500 h-2 rounded-full"
                style="width: {{ $requiredCount > 0 ? ($completedRequired / $requiredCount) * 100 : 0 }}%">
            </div>
        </div>
    </div>

    @if($required->contains(fn($f) => !$f['document']))
        <div class="bg-amber-50 border border-amber-200 text-amber-800 text-xs p-3 rounded-lg">
            ⚠ Some required documents are still missing. Complete them before proceeding.
        </div>
    @endif



    <div class="space-y-6">

        {{-- ================= PRE IMPLEMENTATION (COMBINED) ================= --}}
        <div class="bg-white border rounded-2xl p-5 shadow-sm">

            <div class="flex justify-between items-center mb-4">
                <h2 class="text-sm font-semibold text-slate-800">
                    Pre-Implementation
                </h2>
            </div>

            @include('org.projects.documents.v2.partials.combined-pre-card')

        </div>


        @if($preSubmitted)

            {{-- ================= REQUIRED ================= --}}
            <div class="bg-white border rounded-2xl p-5 shadow-sm">

                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-sm font-semibold text-slate-800">
                        Required Forms
                    </h2>
                    <span class="text-xs text-slate-400">
                        {{ count($required) }} items
                    </span>
                </div>

                @foreach(groupFormsByPhase($required) as $phase => $forms)

                    {{-- PHASE TITLE --}}
                    <div class="mb-2 mt-4 text-xs font-semibold text-slate-500 uppercase tracking-wide">
                        {{ $phaseLabels[$phase] ?? ucfirst(str_replace('_',' ', $phase)) }}
                    </div>

                    {{-- CARDS --}}
                    <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-4">

                        @foreach($forms as $form)
                            @include('org.projects.documents.v2.partials.form-card', [
                                'form' => $form,
                                'type' => 'required'
                            ])
                        @endforeach

                    </div>

                @endforeach

            </div>


            {{-- ================= OPTIONAL ================= --}}
            <div class="bg-white border rounded-2xl p-5 shadow-sm">

                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-sm font-semibold text-slate-800">
                        Optional Documents
                    </h2>
                </div>

                @foreach(groupFormsByPhase($optional) as $phase => $forms)

                    <div class="mb-2 mt-4 text-xs font-semibold text-slate-500 uppercase tracking-wide">
                        {{ $phaseLabels[$phase] ?? ucfirst(str_replace('_',' ', $phase)) }}
                    </div>

                    <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-4">

                        @foreach($forms as $form)
                            @include('org.projects.documents.v2.partials.form-card', [
                                'form' => $form,
                                'type' => 'optional'
                            ])
                        @endforeach

                    </div>

                @endforeach

            </div>


            {{-- ================= APPROVED ================= --}}
            <div class="bg-white border rounded-2xl p-5 shadow-sm">

                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-sm font-semibold text-slate-800">
                        Approved Documents
                    </h2>
                </div>

                @php
                    $approvedForms = $required->filter(fn($f) =>
                        $f['document'] && $f['document']->status === 'approved_by_sacdev'
                    );
                @endphp

                @foreach(groupFormsByPhase($approvedForms) as $phase => $forms)

                    <div class="mb-2 mt-4 text-xs font-semibold text-slate-500 uppercase tracking-wide">
                        {{ $phaseLabels[$phase] ?? ucfirst(str_replace('_',' ', $phase)) }}
                    </div>

                    <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-4">

                        @foreach($forms as $form)
                            @include('org.projects.documents.v2.partials.form-card', [
                                'form' => $form,
                                'type' => 'approved'
                            ])
                        @endforeach

                    </div>

                @endforeach

            </div>

        @else

            <div class="bg-white border rounded-2xl p-6 text-center text-sm text-slate-500">
                Other documents will be available once the Project Proposal and Budget Proposal are submitted.
            </div>

        @endif

    </div>

    @if(true)

    <div 
        x-data="{ 
            agreed: false, 
            countdown: 5 
        }"
        x-init="let timer = setInterval(() => { if(countdown > 0) countdown--; else clearInterval(timer) }, 1000)"
        x-show="openAgreement"
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
            @if($needsAgreement)

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

            @else

                {{-- VIEW ONLY MODE --}}
                <div class="flex justify-end">
                    <button 
                        @click="openAgreement = false"
                        class="px-4 py-2 text-sm bg-slate-200 text-slate-700 rounded-lg hover:bg-slate-300"
                    >
                        Close
                    </button>
                </div>

            @endif

            </div>

        </div>

        </div>

    @endif

</div>

</x-app-layout>