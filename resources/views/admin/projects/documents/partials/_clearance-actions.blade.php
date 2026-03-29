@if($project->requires_clearance)

<div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-6">

    <div class="flex items-center justify-between">

        <div>
            <h3 class="text-sm font-semibold text-slate-900">
                Clearance Actions
            </h3>
            <p class="text-xs text-slate-500 mt-1">
                Review submitted clearance documents
            </p>
        </div>

        <div class="flex gap-2">

            @if($project->clearance_status === 'uploaded')

                <form method="POST"
                      action="{{ route('admin.projects.clearance.verify', $project) }}">
                    @csrf

                    <button class="px-4 py-2 text-xs font-semibold rounded-xl bg-emerald-600 text-white hover:bg-emerald-700">
                        Verify
                    </button>
                </form>

                <form method="POST"
                      action="{{ route('admin.projects.clearance.reject', $project) }}">
                    @csrf

                    <button class="px-4 py-2 text-xs font-semibold rounded-xl bg-rose-600 text-white hover:bg-rose-700">
                        Return
                    </button>
                </form>

            @endif

        </div>

    </div>

</div>

@endif