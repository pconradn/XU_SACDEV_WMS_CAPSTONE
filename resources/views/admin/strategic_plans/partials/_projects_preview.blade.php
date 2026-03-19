<div class="rounded-2xl border border-blue-200 bg-blue-50 shadow-sm">

    <div class="px-5 py-4 border-b border-blue-200">
        <div class="text-sm font-semibold text-blue-900">
            Project Creation Preview
        </div>

        <div class="text-xs text-blue-700 mt-1">
            When approved, the following projects will be created for this organization and school year.
            This allows the president to assign project heads immediately.
            Project submissions remain restricted until organization registration is completed.
        </div>
    </div>

    <div class="p-5 space-y-3">

        @forelse($submission->projects as $project)

            @php
                $existing = \App\Models\Project::where(
                    'source_strategic_plan_project_id',
                    $project->id
                )->first();
            @endphp

            <div class="flex items-center justify-between rounded-lg border border-blue-200 bg-white px-4 py-3">

                <div>
                    <div class="text-sm font-semibold text-slate-900">
                        {{ $project->title }}
                    </div>

                    <div class="text-xs text-slate-500 mt-1">
                        Category: {{ $project->category ?? '—' }}
                        • Target: {{ $project->target_date ?? '—' }}
                    </div>
                </div>

                @if($existing)

                    <span class="text-xs font-semibold px-3 py-1 rounded-full
                        bg-emerald-50 border border-emerald-200 text-emerald-700">
                        Already Created
                    </span>

                @else

                    <span class="text-xs font-semibold px-3 py-1 rounded-full
                        bg-blue-100 border border-blue-200 text-blue-800">
                        Will be Created on Approval
                    </span>

                @endif

            </div>

        @empty

            <div class="text-sm text-slate-500">
                No projects found.
            </div>

        @endforelse

    </div>

</div>