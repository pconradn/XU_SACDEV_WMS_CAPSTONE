<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

        {{-- ================= HEADER ================= --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 flex flex-col md:flex-row md:items-center gap-5">

            {{-- LOGO --}}
            <div class="flex-shrink-0">
                @if($organization->logo_path)
                    <img src="{{ asset('storage/'.$organization->logo_path) }}"
                         class="w-20 h-20 rounded-xl object-cover border border-slate-200">
                @else
                    <div class="w-20 h-20 rounded-xl bg-slate-100 flex items-center justify-center text-slate-400 text-sm">
                        No Logo
                    </div>
                @endif
            </div>

            {{-- INFO --}}
            <div class="flex-1 min-w-0">
                <div class="text-xl font-semibold text-slate-900">
                    {{ $organization->name }}
                </div>

                <div class="text-sm text-slate-500">
                    {{ $organization->acronym }}
                </div>



                <div class="mt-3 text-sm text-slate-600 leading-relaxed whitespace-pre-line">
                    {!! $organization->mission ? nl2br(e($organization->mission)) : 'No mission provided.' !!}
                </div>

                <div class="mt-4">
                    <div class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">
                        Vision
                    </div>

                    <div class="text-sm text-slate-600 leading-relaxed whitespace-pre-line">
                        {!! $organization->vision ? nl2br(e($organization->vision)) : 'No vision provided.' !!}
                    </div>
                </div>


            </div>

        </div>

        {{-- ================= STATS ================= --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            {{-- MEMBERS --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                <div class="text-xs text-slate-400 uppercase tracking-wide">
                    Members
                </div>
                <div class="text-2xl font-semibold text-slate-900 mt-1">
                    {{ $membersCount }}
                </div>
            </div>

            {{-- OFFICERS --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                <div class="text-xs text-slate-400 uppercase tracking-wide">
                    Officers
                </div>
                <div class="text-2xl font-semibold text-slate-900 mt-1">
                    {{ $officersCount }}
                </div>
            </div>

            {{-- PROJECTS --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                <div class="text-xs text-slate-400 uppercase tracking-wide">
                    Projects
                </div>
                <div class="text-2xl font-semibold text-slate-900 mt-1">
                    {{ $projectsCount }}
                </div>
            </div>

        </div>

        {{-- ================= NAVIGATION ================= --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

            <div class="px-5 py-4 border-b border-slate-200 bg-slate-50">
                <div class="text-sm font-semibold text-slate-900">
                    Organization Modules
                </div>
                <div class="text-xs text-slate-500">
                    Access and manage organization-related data
                </div>
            </div>

            <div class="divide-y">

                {{-- MEMBERS --}}
                <a href="{{ route('org.organization-members.index') }}"
                   class="flex items-center justify-between px-5 py-4 hover:bg-slate-50 transition">

                    <div>
                        <div class="text-sm font-medium text-slate-900">
                            Members
                        </div>
                        <div class="text-xs text-slate-500">
                            View and manage organization members
                        </div>
                    </div>

                    <div class="text-xs px-2 py-1 rounded-full bg-slate-100 text-slate-700">
                        {{ $membersCount }}
                    </div>
                </a>

                {{-- OFFICERS --}}
                <a href="{{ route('org.officers.index') }}"
                   class="flex items-center justify-between px-5 py-4 hover:bg-slate-50 transition">

                    <div>
                        <div class="text-sm font-medium text-slate-900">
                            Officers
                        </div>
                        <div class="text-xs text-slate-500">
                            View officer directory
                        </div>
                    </div>

                    <div class="text-xs px-2 py-1 rounded-full bg-slate-100 text-slate-700">
                        {{ $officersCount }}
                    </div>
                </a>

                {{-- PROJECTS --}}
                <a href="{{ route('org.projects.index') }}"
                   class="flex items-center justify-between px-5 py-4 hover:bg-slate-50 transition">

                    <div>
                        <div class="text-sm font-medium text-slate-900">
                            Projects
                        </div>
                        <div class="text-xs text-slate-500">
                            Manage organization projects
                        </div>
                    </div>

                    <div class="text-xs px-2 py-1 rounded-full bg-slate-100 text-slate-700">
                        {{ $projectsCount }}
                    </div>
                </a>

            </div>

        </div>

    </div>
</x-app-layout>