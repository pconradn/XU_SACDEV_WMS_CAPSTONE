<x-app-layout>

<div class=" pt-6 mb-6">
    <div class="max-w-7xl mx-auto px-4">
        <nav class="text-xs text-slate-500">
            <ol class="flex flex-wrap items-center gap-1.5">
                <li>
                    <a href="{{ route('admin.orgs_by_sy.index') }}"
                       class="font-medium text-slate-600 hover:text-slate-900 transition">
                        Organizations by School Year
                    </a>
                </li>

                <li class="text-slate-300">/</li>

                <li>
                    <a href="{{ route('admin.orgs_by_sy.show', [$project->organization_id, $project->school_year_id]) }}"
                       class="font-medium text-slate-600 hover:text-slate-900 transition">
                        {{ $project->organization?->acronym ?: $project->organization?->name }}
                    </a>
                </li>

                <li class="text-slate-300">/</li>

                <li>
                    <a href="{{ route('admin.org.projects.index', [$project->organization_id, $project->school_year_id]) }}"
                       class="font-medium text-slate-600 hover:text-slate-900 transition">
                        Projects
                    </a>
                </li>

                <li class="text-slate-300">/</li>

                <li class="font-medium text-indigo-700 truncate max-w-[220px]">
                    {{ $project->title }}
                </li>
            </ol>
        </nav>
    </div>
</div>

@include('admin.projects.documents.partials._hub-header')

<div class="bg-slate-50 py-6">

<div class="max-w-7xl mx-auto px-4 space-y-4"
     x-data="{ tab: 'documents' }">

    <div class="grid grid-cols-12 gap-4">

        {{-- LEFT --}}
        <div class="col-span-12 lg:col-span-8 space-y-4">

            {{-- TABS --}}
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-1 flex gap-1">

                <button
                    @click="tab = 'documents'"
                    :class="tab === 'documents'
                        ? 'bg-slate-900 text-white'
                        : 'text-slate-600 hover:bg-slate-100'"
                    class="flex-1 px-4 py-2 text-xs font-semibold rounded-xl transition">
                    Documents
                </button>

                <button
                    @click="tab = 'snapshot'"
                    :class="tab === 'snapshot'
                        ? 'bg-slate-900 text-white'
                        : 'text-slate-600 hover:bg-slate-100'"
                    class="flex-1 px-4 py-2 text-xs font-semibold rounded-xl transition">
                    Snapshot & Progress
                </button>

            </div>


            {{-- DOCUMENTS TAB --}}
            <div x-show="tab === 'documents'" x-cloak class="space-y-4">

                @include('admin.projects.documents.partials._documents-table-v2')

                @include('admin.projects.documents.partials._notices-table')

            </div>


            {{-- SNAPSHOT TAB --}}
            <div x-show="tab === 'snapshot'" x-cloak class="space-y-4">

                @include('admin.projects.documents.partials._snapshot-card')

                @include('admin.projects.documents.partials._progress-bar')

            </div>

        </div>


        {{-- RIGHT --}}
        <div class="col-span-12 lg:col-span-4 space-y-4 lg:sticky lg:top-4 h-fit">

            @include('admin.projects.documents.partials._pre-implementation-card')

            @include('admin.projects.documents.partials._clearance-panel')

            @include('admin.projects.documents.partials.external-packets-card')

        </div>

    </div>


    {{-- FLOATING ACTION BAR --}}
    @include('admin.projects.documents.partials._admin-actions')

</div>

</div>

</x-app-layout>