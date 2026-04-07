<x-app-layout>

<div class="space-y-6">

    {{-- HEADER --}}
    <div>
        <h1 class="text-lg font-semibold text-slate-900">
            Search
        </h1>

        @if($q)
            <p class="text-sm text-slate-500 mt-1">
                Showing results for 
                "<span class="font-medium text-slate-700">{{ $q }}</span>"
            </p>
        @endif
    </div>


    {{-- PROJECTS --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

        {{-- HEADER --}}
        <div class="flex items-center justify-between px-5 py-3 border-b">
            <h2 class="text-sm font-semibold text-slate-700">
                Projects
            </h2>

            <span class="text-xs text-slate-400">
                {{ $projects->count() }} results
            </span>
        </div>


        {{-- CONTENT --}}
        <div class="p-4 space-y-3">

            @forelse($projects as $project)
                <a href="{{ route('admin.projects.documents.hub', $project->id) }}"
                   class="block rounded-xl border border-slate-200 p-4 hover:border-blue-400 hover:shadow-sm transition">

                    <div class="flex items-start justify-between gap-3">

                        <div>
                            <div class="font-medium text-slate-900">
                                {{ $project->title }}
                            </div>

                            @if($project->description)
                                <div class="text-xs text-slate-500 mt-1">
                                    {{ \Illuminate\Support\Str::limit($project->description, 100) }}
                                </div>
                            @endif
                        </div>

                        <span class="text-[10px] px-2 py-0.5 rounded-full bg-blue-50 text-blue-600 font-medium">
                            Project
                        </span>

                    </div>

                </a>
            @empty
                <div class="text-sm text-slate-500 px-2 py-4">
                    No projects found
                </div>
            @endforelse

        </div>

    </div>


    {{-- ORGANIZATIONS --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

        {{-- HEADER --}}
        <div class="flex items-center justify-between px-5 py-3 border-b">
            <h2 class="text-sm font-semibold text-slate-700">
                Organizations
            </h2>

            <span class="text-xs text-slate-400">
                {{ $organizations->count() }} results
            </span>
        </div>


        {{-- CONTENT --}}
        <div class="p-4 space-y-3">

            @forelse($organizations as $org)
                <div class="rounded-xl border border-slate-200 p-4 hover:border-emerald-400 hover:shadow-sm transition">

                    <div class="flex items-center justify-between">

                        <div class="font-medium text-slate-900">
                            {{ $org->name }}
                        </div>

                        <span class="text-[10px] px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-600 font-medium">
                            Organization
                        </span>

                    </div>

                </div>
            @empty
                <div class="text-sm text-slate-500 px-2 py-4">
                    No organizations found
                </div>
            @endforelse

        </div>

    </div>

</div>

</x-app-layout>