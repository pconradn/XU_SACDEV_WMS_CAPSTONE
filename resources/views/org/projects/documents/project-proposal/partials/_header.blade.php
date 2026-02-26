<div class="mb-6">
    <div class="flex items-start justify-between gap-4">

        <div>
            <h2 class="text-xl font-semibold text-slate-900">
                Project Proposal
            </h2>

            <div class="mt-1 text-sm text-slate-600">
                {{ $project->title }}
            </div>

            <div class="mt-1 text-xs text-slate-500">
                Fill out the proposal details and save as draft. You can continue editing later.
            </div>
        </div>

        <a href="{{ route('org.projects.documents.hub', $project) }}"
           class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-800 hover:bg-slate-50">
            Back to Hub
        </a>

    </div>
</div>