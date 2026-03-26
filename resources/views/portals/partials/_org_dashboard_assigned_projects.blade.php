<div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

    {{-- HEADER --}}
    <div class="px-5 py-4 border-b flex items-center justify-between">

        <h3 class="text-sm font-semibold text-slate-900">
            Your Projects
        </h3>

        <span class="text-xs text-slate-400">
            {{ $assignedProjects->count() }} assigned
        </span>

    </div>

    {{-- BODY --}}
    <div class="divide-y">

        @forelse($assignedProjects as $project)

            <div class="px-5 py-4 flex items-center justify-between">

                {{-- LEFT --}}
                <div class="space-y-1">

                    {{-- TITLE --}}
                    <p class="text-sm font-semibold text-slate-900">
                        {{ $project->title }}
                    </p>

                    {{-- DATE --}}
                    <p class="text-xs text-slate-500">
                        @if($project->implementation_start_date)
                            Target: {{ \Carbon\Carbon::parse($project->implementation_start_date)->format('M d, Y') }}
                        @else
                            No schedule set
                        @endif
                    </p>

                    {{-- STATUS --}}
                    <span class="inline-flex items-center px-2 py-0.5 text-xs rounded-full ring-1 {{ $project->workflow_status_badge_class }}">
                        {{ $project->workflow_status_label }}
                    </span>

                </div>

                {{-- ACTION --}}
                <div>
                    <a href="{{ route('org.projects.documents.hub', $project) }}"
                       class="text-xs px-3 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Open
                    </a>
                </div>

            </div>

        @empty

            <div class="px-5 py-6 text-sm text-slate-500 text-center">
                You are not assigned to any projects yet.
            </div>

        @endforelse

    </div>

</div>