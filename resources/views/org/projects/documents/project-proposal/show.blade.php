<x-layouts.form-only
    title="Project Proposal — {{ $project->title }}"
    :backRoute="route('org.projects.documents.hub', $project)"
>

<div class="mx-auto max-w-5xl space-y-6">

    {{-- STATUS BAR --}}
    <div class="rounded-2xl border border-slate-200 bg-white px-6 py-4 shadow-sm">

        <div class="flex items-center justify-between">

            <div>
                <div class="text-sm text-slate-500">
                    Proposal Status
                </div>

                <div class="text-lg font-semibold text-slate-900">
                    {{ ucfirst($document->status) }}
                </div>
            </div>

            @if($currentSignature && $currentSignature->status === 'pending')
                <span class="text-sm font-medium text-rose-600">
                    Awaiting Your Approval
                </span>
            @endif

        </div>

    </div>


    {{-- PROPOSAL CONTENT --}}
    <div class="space-y-6">

        @include('org.projects.documents.project-proposal.partials._schedule_venue')

        @include('org.projects.documents.project-proposal.partials._nature_sdg_area')

        @include('org.projects.documents.project-proposal.partials._description_link_cluster')

        @include('org.projects.documents.project-proposal.partials._multi_entries')

        @include('org.projects.documents.project-proposal.partials._budget_funds_audience')

        @include('org.projects.documents.project-proposal.partials._guests_plan_of_action')

    </div>


    {{-- APPROVAL ACTIONS --}}
    @if($currentSignature && $currentSignature->status === 'pending')

        <div class="sticky bottom-0 bg-white border-t border-slate-200 px-6 py-4 flex justify-end gap-3 shadow-lg">

            <form method="POST"
                  action="{{ route('org.projects.project-proposal.approve', $project) }}">
                @csrf
                <button
                    class="rounded-xl bg-emerald-600 px-5 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
                    Approve
                </button>
            </form>

            <form method="POST"
                  action="{{ route('org.projects.project-proposal.return', $project) }}">
                @csrf
                <button
                    class="rounded-xl bg-rose-600 px-5 py-2 text-sm font-semibold text-white hover:bg-rose-700">
                    Return with Remarks
                </button>
            </form>

        </div>

    @endif

</div>

</x-layouts.form-only>