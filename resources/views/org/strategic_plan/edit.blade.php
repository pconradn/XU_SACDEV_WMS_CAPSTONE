<x-app-layout>

@php
    $isModerator = auth()->user()?->hasRoleInOrg($organization->id, $schoolYear->id, 'moderator') ?? false;
    $isAdmin = auth()->user()?->isSacdevAdmin() ?? false;
    $mode = $isAdmin ? 'admin' : ($isModerator ? 'moderator' : 'org');

    $canSubmitToModerator = $isPresident
        && !$isApproved
        && in_array($submission->status, ['draft', 'returned_by_moderator', 'returned_by_sacdev']);

    $canReviewAsModerator = $isModerator && $submission->status === 'submitted_to_moderator';
    $canSubmitToSacdev = $isModerator && $submission->status === 'approved_by_moderator';
    $canAdminAct = $isAdmin;

    $initialStep = (int) session('strategic_plan_step', 1);

    if ($initialStep < 1 || $initialStep > 4) {
        $initialStep = 1;
    }
@endphp

<style>
    [x-cloak] {
        display: none !important;
    }
</style>

<div x-data="moderatorActions()" x-init="init()" class="space-y-6">

    <nav class="px-5 sm:px-6 pt-4 mb-2 text-xs text-slate-500">
        <ol class="flex items-center gap-1">
            <li>
                <a href="{{ $isAdmin
                        ? route('admin.rereg.hub', ['organization' => $organization->id])
                        : route('org.rereg.index') }}"
                   class="hover:text-slate-700 transition">
                    {{ $isAdmin ? 'Re-Registration Hub' : 'Re-Registration' }}
                </a>
            </li>

            <li class="text-slate-400">/</li>

            <li class="text-slate-700 font-medium">
                Strategic Plan
            </li>
        </ol>
    </nav>

    @include('org.strategic_plan._header', [
        'submission' => $submission,
        'schoolYear' => $schoolYear
    ])

    <div class="space-y-6">

        @if($mode === 'org' && $canEdit)

            <div
                id="strategic-plan-stepper"
                x-data="strategicPlanStepper({{ $initialStep }})"
                x-ref="stepper"
                class="space-y-6 scroll-mt-6"
            >
                <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-4">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

                        <div>
                            <h2 class="text-sm font-semibold text-slate-900">
                                Strategic Plan Completion
                            </h2>
                            <p class="text-xs text-slate-500 mt-1">
                                Complete each section in order before submitting the Strategic Plan.
                            </p>
                        </div>

                        <div class="flex flex-wrap items-center gap-2 text-[11px] font-semibold">
                            <button type="button"
                                    @click="goToStep(1)"
                                    :class="step === 1 ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                                    class="px-3 py-1.5 rounded-lg transition">
                                1. Identity
                            </button>

                            <button type="button"
                                    @click="goToStep(2)"
                                    :class="step === 2 ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                                    class="px-3 py-1.5 rounded-lg transition">
                                2. Projects
                            </button>

                            <button type="button"
                                    @click="goToStep(3)"
                                    :class="step === 3 ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                                    class="px-3 py-1.5 rounded-lg transition">
                                3. Funds
                            </button>

                            <button type="button"
                                    @click="goToStep(4)"
                                    :class="step === 4 ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                                    class="px-3 py-1.5 rounded-lg transition">
                                4. Submit
                            </button>
                        </div>

                    </div>
                </div>

                <div x-show="step === 1" x-cloak>
                    @include('org.strategic_plan._identity', [
                        'submission' => $submission,
                        'canEdit' => $canEdit,
                        'mode' => $mode
                    ])

                    <div class="flex justify-end pt-4">
                        <button type="button"
                                @click="goToStep(2)"
                                class="inline-flex items-center gap-2 rounded-lg bg-slate-900 px-4 py-2 text-xs font-semibold text-white hover:bg-slate-800 transition">
                            Proceed to Projects
                        </button>
                    </div>
                </div>

                <div x-show="step === 2" x-cloak>
                    @include('org.strategic_plan._projects', [
                        'submission' => $submission,
                        'canEdit' => $canEdit,
                        'mode' => $mode
                    ])

                    <div class="flex justify-between pt-4">
                        <button type="button"
                                @click="goToStep(1)"
                                class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition">
                            Back
                        </button>

                        <button type="button"
                                @click="goToStep(3)"
                                class="inline-flex items-center gap-2 rounded-lg bg-slate-900 px-4 py-2 text-xs font-semibold text-white hover:bg-slate-800 transition">
                            Proceed to Funds
                        </button>
                    </div>
                </div>

                <div x-show="step === 3" x-cloak>
                    @include('org.strategic_plan._funds', [
                        'submission' => $submission,
                        'canEdit' => $canEdit,
                        'mode' => $mode
                    ])

                    <div class="flex justify-between pt-4">
                        <button type="button"
                                @click="goToStep(2)"
                                class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition">
                            Back
                        </button>

                        <button type="button"
                                @click="goToStep(4)"
                                class="inline-flex items-center gap-2 rounded-lg bg-slate-900 px-4 py-2 text-xs font-semibold text-white hover:bg-slate-800 transition">
                            Proceed to Review
                        </button>
                    </div>
                </div>

                <div x-show="step === 4" x-cloak>
                    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-5 space-y-2 mb-6">
                        <h2 class="text-sm font-semibold text-slate-900">
                            Review and Submit
                        </h2>
                        <p class="text-xs text-slate-500">
                            Review the previous sections before submitting the Strategic Plan for approval.
                        </p>
                    </div>

                    @include('org.strategic_plan._submit', [
                        'submission' => $submission,
                        'submitRoute' => 'org.rereg.b1.submit'
                    ])

                    <div class="flex justify-start pt-4">
                        <button type="button"
                                @click="goToStep(3)"
                                class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition">
                            Back
                        </button>
                    </div>
                </div>

            </div>

        @else

            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                @include('org.strategic_plan._identity', [
                    'submission' => $submission,
                    'canEdit' => $canEdit,
                    'mode' => $mode
                ])
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                @include('org.strategic_plan._projects', [
                    'submission' => $submission,
                    'canEdit' => $canEdit,
                    'mode' => $mode
                ])
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                @include('org.strategic_plan._funds', [
                    'submission' => $submission,
                    'canEdit' => $canEdit,
                    'mode' => $mode
                ])
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                @include('org.strategic_plan._submit', [
                    'submission' => $submission,
                    'submitRoute' => 'org.rereg.b1.submit'
                ])
            </div>

        @endif

        @include('org.strategic_plan.partials._modals', [
            'submission' => $submission,
            'canAdminAct' => $canAdminAct
        ])

    </div>

</div>

<script>
function strategicPlanStepper(initialStep) {
    return {
        step: initialStep,

        goToStep(targetStep) {
            this.step = targetStep;

            this.$nextTick(() => {
                const el = document.getElementById('strategic-plan-stepper');

                if (el) {
                    el.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }

                if (window.renderLucideIcons) {
                    window.renderLucideIcons();
                }
            });
        }
    }
}

function moderatorActions() {
    return {
        openReturn: false,
        openForward: false,
        openApprove: false,
        returnQuill: null,
        openRevert: false,

        init() {
            this.$nextTick(() => {
                if (document.getElementById('returnEditor')) {
                    this.returnQuill = new Quill('#returnEditor', {
                        theme: 'snow',
                        placeholder: 'Write remarks...'
                    });
                }
            });
        },

        submitReturn(e) {
            this.$refs.returnRemarks.value = this.returnQuill.root.innerHTML;
            e.target.submit();
        }
    }
}
</script>

</x-app-layout>