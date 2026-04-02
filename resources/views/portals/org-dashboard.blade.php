<x-app-layout>

    <x-slot name="header">
        <div class="flex flex-col gap-1">
            <h2 class="font-semibold text-2xl text-slate-900 leading-tight">
                Org Dashboard
            </h2>
            <p class="text-sm text-slate-500">
                Overview of your responsibilities, approvals, and assigned projects.
            </p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-6 space-y-6">

            {{-- FLASH --}}
            @if (session('status'))
                <div class="rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-amber-900 shadow-sm">
                    <div class="text-sm">{{ session('status') }}</div>
                </div>
            @endif


            {{-- ================= STATS ROW ================= --}}
            @include('portals.partials._org_dashboard_stats')


            {{-- ================= MAIN GRID ================= --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- ================= LEFT (MAIN) ================= --}}
                <div class="lg:col-span-2 space-y-6">

                  
                    @include('portals.partials._org_dashboard_pending_tasks')


                    {{-- ================= ORG + ROLES ================= --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        {{-- ORGANIZATION --}}
                        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-6">
                            <div class="text-sm text-slate-500">
                                Selected Organization
                            </div>

                            <div class="mt-1 text-xl font-semibold text-slate-900">
                                {{ $currentOrg?->name ?? '—' }}
                            </div>

                            <div class="mt-2 text-sm text-slate-600">
                                Acronym: {{ $currentOrg?->acronym ?? '—' }}
                            </div>

                            @if(!$currentOrg)
                                <div class="mt-4 rounded-xl border border-slate-200 bg-slate-50 p-3 text-sm text-slate-700">
                                    You are not currently assigned to an organization.
                                </div>
                            @endif
                        </div>


                     
                        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-6">
                            <div class="text-sm text-slate-500">
                                Your Roles
                            </div>

                            <div class="mt-3 flex flex-wrap gap-2">
                                @forelse($roles as $r)
                                    <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-200">
                                        {{ ucfirst(str_replace('_', ' ', $r)) }}
                                    </span>
                                @empty
                                    <span class="text-sm text-slate-500">
                                        No role assigned.
                                    </span>
                                @endforelse
                            </div>

                            <div class="mt-5 text-sm text-slate-600">
                                Project head assignments:
                                <span class="font-semibold text-slate-900">
                                    {{ $projectHeadCount }}
                                </span>
                            </div>
                        </div>

                    </div>

                </div>


                {{-- ================= RIGHT SIDEBAR ================= --}}
                <div class="space-y-6">



                    @if($roles->contains('president') && $projectsWithoutHeadCount > 0)

                    <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5 shadow-sm">

                        <div class="flex items-center justify-between gap-4">

                            {{-- LEFT --}}
                            <div>
                                <div class="text-sm font-semibold text-amber-900">
                                    Project Setup Required
                                </div>

                                <div class="mt-1 text-sm text-amber-800/90">
                                    {{ $projectsWithoutHeadCount }} project{{ $projectsWithoutHeadCount > 1 ? 's' : '' }} still need a project head assigned.
                                </div>

                                <div class="mt-1 text-xs text-amber-700">
                                    Assign project heads so members can begin submitting project forms.
                                </div>
                            </div>

                            {{-- RIGHT --}}
                            <a href="{{ route('org.projects.index') }}"
                            class="inline-flex items-center justify-center rounded-lg 
                                    bg-amber-600 px-4 py-2 text-sm font-semibold text-white 
                                    hover:bg-amber-700 transition">

                                Manage Projects

                            </a>

                        </div>

                    </div>

                    @endif

                    @include('portals.partials._org_dashboard_assigned_projects')

                    @php
                    $isProjectHead = \App\Models\ProjectAssignment::query()
                        ->where('user_id', auth()->id())
                        ->whereNull('archived_at')
                        ->exists();
                    @endphp


                    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-6">

                        <div>
                            <h3 class="text-sm font-semibold text-slate-900">
                                Quick Access
                            </h3>
                            <p class="mt-1 text-sm text-slate-500">
                                Navigate to commonly used modules.
                            </p>
                        </div>

                        <div class="mt-4 flex flex-col gap-2">

                           
                            @if($roles->contains('president'))

                                <a href="{{ route('org.rereg.index') }}"
                                class="flex items-center justify-between rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50">
                                    <span>Re-registration Hub</span>
                                    <span class="text-xs text-slate-400">Open</span>
                                </a>

                                <a href="{{ route('org.assign-project-heads.index') }}"
                                class="flex items-center justify-between rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50">
                                    <span>Assign Project Heads</span>
                                    <span class="text-xs text-slate-400">Open</span>
                                </a>

                            @endif

                            
                            <a href="{{ route('org.projects.index') }}"
                            class="flex items-center justify-between rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50">
                                <span>Projects</span>
                                <span class="text-xs text-slate-400">Open</span>
                            </a>

                            {{-- PRESIDENT ONLY --}}
                            @if($roles->contains('president'))
                                <a href="{{ route('org.assign-project-heads.index') }}"
                                class="flex items-center justify-between rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50">
                                    <span>Assign Project Heads</span>
                                    <span class="text-xs text-slate-400">Open</span>
                                </a>
                            @endif

                        </div>

                        {{-- INFO MESSAGE --}}
                        @if(!$isProjectHead && !$roles->contains('president'))
                            <div class="mt-4 text-xs text-slate-500">
                                Some modules are only available based on your assigned role in the organization.
                            </div>
                        @endif

                    </div>

                </div>

            </div>

        </div>
    </div>

</x-app-layout>