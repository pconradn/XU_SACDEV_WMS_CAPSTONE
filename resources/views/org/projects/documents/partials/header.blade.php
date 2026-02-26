<div class="mb-6">

    <div class="flex items-center justify-between">

        <div>

            <h2 class="text-xl font-semibold text-slate-900">
                {{ $project->title }}
            </h2>

            <div class="text-sm text-slate-600 mt-1">
                Project Documents Hub
            </div>

            <div class="text-xs text-slate-500 mt-1 space-x-2">

                <span>
                    Category: {{ $project->category ?? '—' }}
                </span>

                <span class="text-slate-300">|</span>

                <span>
                    Project Head:
                    @if($projectHead)
                        <span class="font-semibold text-slate-800">
                            {{ $projectHead->name }}
                        </span>
                    @else
                        <span class="font-semibold text-rose-600">
                            Not assigned
                        </span>
                    @endif
                </span>

            </div>

        </div>


        <a href="{{ route('org.projects.index') }}"
           class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-800 hover:bg-slate-50">

            Back to Projects

        </a>

    </div>

</div>