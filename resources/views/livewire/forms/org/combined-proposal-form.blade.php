<div class="max-w-6xl mx-auto space-y-6">

    @if (session()->has('success'))
        <div
            x-data="{ show: true }"
            x-init="setTimeout(() => show = false, 3000)"
            x-show="show"
            x-transition
            class="fixed top-12 left-1/2 -translate-x-1/2 z-50 w-full max-w-md px-4"
        >
            <div class="rounded-2xl border border-emerald-200/50 bg-emerald-50/30 backdrop-blur-lg px-4 py-3 text-sm text-emerald-900 shadow-lg">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div
            x-data="{ show: true }"
            x-init="setTimeout(() => show = false, 4000)"
            x-show="show"
            x-transition
            class="fixed top-28 left-1/2 -translate-x-1/2 z-50 w-full max-w-md px-4"
        >
            <div class="rounded-2xl border border-rose-200/60 bg-rose-50/60 backdrop-blur-md px-4 py-3 text-sm text-rose-800 shadow-lg">
                Please fix the highlighted fields.
            </div>
        </div>
    @endif

    @include('components.document.status-bar', ['document' => $proposalDocument])

    @include('org.projects.documents.project-proposal.partials._header', [
        'project' => $project,
        'document' => $proposalDocument
    ])

    @if($isReadOnly)
    <fieldset disabled class="space-y-6">
    @endif

    {{-- ================= PROPOSAL ================= --}}
    <div class="space-y-6">

        {{-- SCHEDULE & VENUE --}}
        @include('org.projects.documents.project-proposal.partials._schedule_venue')


        @include('org.projects.documents.project-proposal.partials._nature_sdg_area')
        @include('org.projects.documents.project-proposal.partials._description_link_cluster')
        @include('org.projects.documents.project-proposal.partials._multi_entries')

        @include('org.projects.documents.project-proposal.partials._budget_funds_audience')


        @include('livewire.forms.org.partials.funding-summary')
        @include('livewire.forms.org.partials.budget-items')


        <div class="mt-10 border-t pt-6 space-y-6">

            <div id="budgetSectionsWrapper">

            </div>



        </div>

        @include('org.projects.documents.project-proposal.partials._guests_plan_of_action')
    
    
    
    
    </div>

    @if($isReadOnly)
    </fieldset>
    @endif

    {{-- ================= STUDENT AGREEMENT ================= --}}
    @include('org.projects.documents.project-proposal.partials._student_agreement')

    {{-- ================= SIGNATURES ================= --}}
    @include('org.projects.documents.project-proposal.partials._signatures', [
        'document' => $proposalDocument
    ])

    {{-- ================= LIVEWIRE ACTION BAR ================= --}}
    @php
        $status = $proposalDocument->status ?? 'draft';

        $isDraft = $status === 'draft';
        $isSubmitted = $status === 'submitted';
        $isApprovedBySacdev = $status === 'approved_by_sacdev';

        $editRequested = $proposalDocument->edit_requested ?? false;
        $editMode = $proposalDocument->edit_mode ?? false;

        $signatures = collect($proposalDocument->signatures ?? [])->sortBy('id')->values();

        $onlySacdevLeft = $signatures->where('status', 'pending')->count() === 1
            && optional($signatures->firstWhere('status', 'pending'))->role === 'sacdev_admin';
    @endphp

    @if($isProjectHead)

    <div class="sticky bottom-0 bg-white border-t border-slate-200 p-4 flex justify-between items-center">

        <div class="text-xs text-slate-600">
            @if($isDraft)
                Draft mode. You can save or submit this document.
            @elseif($isSubmitted)
                This document is currently under review.
            @elseif($isApprovedBySacdev)
                This document has been fully approved.
            @endif

            @if($editRequested && !$editMode)
                <span class="text-amber-600 ml-2">Edit request pending approval.</span>
            @endif

            @if($editMode)
                <span class="text-blue-600 ml-2">Edit mode enabled. Submit your revisions.</span>
            @endif
        </div>

        <div class="flex flex-wrap gap-2 justify-end">

            {{-- RETURN --}}
            <a href="{{ route('org.projects.documents.hub', $project->id) }}"
            class="px-4 py-2 text-xs font-medium border border-slate-300 bg-white hover:bg-slate-50 rounded-lg">
                Return to Hub
            </a>

            {{-- DRAFT / SUBMIT --}}
            @if($isProjectHead && (!$proposalDocument || $isDraft) && !$editMode)

                <button wire:click="saveDraft"
                    type="button"
                    class="px-4 py-2 text-xs font-semibold bg-slate-200 hover:bg-slate-300 text-slate-800 rounded-lg">
                    Save Draft
                </button>

                <button wire:click="submit"
                    type="button"
                    class="px-4 py-2 text-xs font-semibold bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                    Submit for Approval
                </button>

            @endif

            {{-- RESUBMIT --}}
            @if($isProjectHead && $isSubmitted && !$onlySacdevLeft && !$editMode)

                <button wire:click="submit"
                    type="button"
                    class="px-4 py-2 text-xs font-semibold bg-amber-500 hover:bg-amber-600 text-white rounded-lg">
                    Resubmit with Changes
                </button>

            @endif

            {{-- REQUEST EDIT --}}
            @if($isProjectHead && ($isApprovedBySacdev || $onlySacdevLeft) && !$editRequested && !$editMode)

                <button wire:click="requestEdit"
                    type="button"
                    class="px-4 py-2 text-xs font-semibold bg-amber-500 hover:bg-amber-600 text-white rounded-lg">
                    Request Edit
                </button>

            @endif

            {{-- EDIT REQUESTED --}}
            @if($editRequested && !$editMode)

                <div class="px-4 py-2 text-xs font-medium bg-amber-100 text-amber-800 border border-amber-200 rounded-lg">
                    Edit request sent
                </div>

            @endif

            {{-- EDIT MODE --}}
            @if($isProjectHead && $editMode)

                <button wire:click="submit"
                    type="button"
                    class="px-4 py-2 text-xs font-semibold bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                    Submit Revisions
                </button>

                <div class="px-4 py-2 text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200 rounded-lg">
                    Edit mode: Changes will be submitted directly for approval
                </div>

            @endif

        </div>

    </div>
    @endif


</div>