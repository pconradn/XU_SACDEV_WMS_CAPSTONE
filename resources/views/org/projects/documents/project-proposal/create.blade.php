<x-layouts.form-only
    title="Project Proposal — {{ $project->title }}"
    :backRoute="route('org.projects.documents.hub', $project)"
>

    <div class="mx-auto max-w-5xl">

        @include('org.projects.documents.project-proposal.partials._header', [
            'project' => $project,
        ])

        @include('org.projects.documents.project-proposal.partials._flash')

        <form method="POST"
              action="{{ route('org.projects.project-proposal.store', $project) }}"
              class=""
              id="proposalForm">
            @csrf

            @include('org.projects.documents.project-proposal.partials._schedule_venue')

            @include('org.projects.documents.project-proposal.partials._nature_sdg_area')

            @include('org.projects.documents.project-proposal.partials._description_link_cluster')

            @include('org.projects.documents.project-proposal.partials._multi_entries')

            @include('org.projects.documents.project-proposal.partials._budget_funds_audience')

            @include('org.projects.documents.project-proposal.partials._guests_plan_of_action')

            @include('org.projects.documents.project-proposal.partials._actions')
        </form>

    </div>

    @include('org.projects.documents.project-proposal.partials._script')

</x-layouts.form-only>