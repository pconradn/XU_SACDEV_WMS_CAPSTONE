<div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm">

    {{-- HEADER --}}
    <div class="px-5 py-4 border-b flex items-center justify-between">

        <div class="flex items-center gap-2">
            <i data-lucide="folder" class="w-4 h-4 text-slate-400"></i>

            <h3 class="text-sm font-semibold text-slate-900">
                Your Projects
            </h3>
        </div>

        <span class="text-xs text-slate-400">
            {{ $assignedProjects->count() }} assigned
        </span>

    </div>


    {{-- BODY --}}
    <div class="divide-y">

        @forelse($assignedProjects as $project)

            @php
                $pending = $project->pending_required_count ?? 0;

                // color logic (subtle)
                $color = $pending > 0 ? 'amber' : 'emerald';
            @endphp

            <div class="px-5 py-4 flex items-center justify-between hover:bg-slate-50 transition">

                {{-- LEFT --}}
                <div class="flex items-start gap-3">

                    {{-- LEFT INDICATOR --}}
                    <div class="mt-1 w-1.5 h-10 rounded-full 
                        @if($color === 'amber') bg-amber-400
                        @else bg-emerald-400
                        @endif
                    "></div>

                    <div class="space-y-1">

                        {{-- TITLE --}}
                        <div class="flex items-center gap-2 flex-wrap">

                            <p class="text-sm font-semibold text-slate-900">
                                {{ $project->title }}
                            </p>

                            @if($pending > 0)
                                <span class="text-[10px] px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 font-semibold">
                                    {{ $pending }} pending
                                </span>
                            @endif

                        </div>

                        {{-- DATE --}}
                        <p class="text-xs text-slate-500">
                            @if($project->implementation_start_date)
                                Target: {{ \Carbon\Carbon::parse($project->implementation_start_date)->format('M d, Y') }}
                            @else
                                No schedule set
                            @endif
                        </p>

                        {{-- STATUS --}}
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="inline-flex items-center px-2 py-0.5 text-[10px] rounded-full ring-1 {{ $project->workflow_status_badge_class }}">
                                {{ $project->workflow_status_label }}
                            </span>

                            @if($pending > 0)
                                <span class="text-[10px] font-semibold text-amber-700">
                                    Needs attention
                                </span>
                            @endif
                        </div>

                    </div>

                </div>


                {{-- ACTION --}}
                <div class="shrink-0">
                    <a href="{{ route('org.projects.documents.hub', $project) }}"
                       class="inline-flex items-center gap-1 text-[11px] px-3 py-2 rounded-lg font-semibold text-white bg-blue-600 hover:bg-blue-700 transition">
                        Open
                    </a>
                </div>

            </div>

        @empty

            <div class="px-5 py-8 text-center">

                <i data-lucide="folder-open" class="w-6 h-6 text-slate-300 mx-auto mb-2"></i>

                <div class="text-sm font-semibold text-slate-700">
                    No assigned projects
                </div>

                <div class="text-xs text-slate-500">
                    Projects will appear here once assigned to you.
                </div>

            </div>

        @endforelse

    </div>

</div>