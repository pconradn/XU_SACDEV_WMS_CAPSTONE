<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">

    {{-- Pending Tasks --}}
    <div class="rounded-2xl border border-red-200 bg-red-50 shadow-sm p-5">
        <div class="text-xs font-semibold uppercase tracking-wide text-red-700">
            Pending Tasks
        </div>

        <div class="mt-2 text-3xl font-bold text-red-800">
            {{ $pendingCount ?? 0 }}
        </div>

        <p class="mt-2 text-sm text-red-700/90">
            Documents currently waiting for your approval or action.
        </p>
    </div>

    {{-- Assigned Projects --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-5">
        <div class="text-xs font-semibold uppercase tracking-wide text-slate-500">
            Assigned Projects
        </div>

        <div class="mt-2 text-3xl font-bold text-slate-900">
            {{ $assignedProjects->count() }}
        </div>

        <p class="mt-2 text-sm text-slate-500">
            Projects where you currently have a direct responsibility.
        </p>
    </div>

    {{-- Total Projects --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-5">
        <div class="text-xs font-semibold uppercase tracking-wide text-slate-500">
            Organization Projects
        </div>

        <div class="mt-2 text-3xl font-bold text-slate-900">
            {{ $projectCount ?? 0 }}
        </div>

        <p class="mt-2 text-sm text-slate-500">
            Total tracked projects for the selected organization and school year.
        </p>
    </div>

    {{-- Total Documents --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-5">
        <div class="text-xs font-semibold uppercase tracking-wide text-slate-500">
            Project Documents
        </div>

        <div class="mt-2 text-3xl font-bold text-slate-900">
            {{ $documentCount ?? 0 }}
        </div>

        <p class="mt-2 text-sm text-slate-500">
            All created project-related forms and submissions in this context.
        </p>
    </div>

</div>