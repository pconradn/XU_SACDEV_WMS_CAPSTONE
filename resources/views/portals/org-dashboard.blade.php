<x-app-layout>

    {{-- ================= INLINE OVERRIDES ================= --}}
    <style>
        .page-container {
            max-width: 1200px;
        }

        .card {
            border-radius: 1rem;
            border: 1px solid #e2e8f0;
            background: linear-gradient(to bottom, #f8fafc, #ffffff);
            box-shadow: 0 1px 2px rgba(0,0,0,0.04);
        }

        .card-solid {
            border-radius: 1rem;
            border: 1px solid #e2e8f0;
            background: #ffffff;
            box-shadow: 0 1px 2px rgba(0,0,0,0.04);
        }

        .card-header {
            font-size: 0.75rem;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .card-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: #0f172a;
        }

        .muted {
            font-size: 0.75rem;
            color: #64748b;
        }

        .hover-row:hover {
            background: #f8fafc;
        }
    </style>


    <div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm px-5 py-4 flex items-center justify-between">

        {{-- LEFT --}}
        <div class="flex items-start gap-3">

            {{-- ICON --}}
            <div class="mt-1 flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50 text-blue-600">
                <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
            </div>

            <div>
                <h2 class="text-base font-semibold text-slate-900">
                    Org Dashboard
                </h2>

                <p class="text-xs text-slate-500 mt-0.5">
                    Overview of responsibilities, approvals, and assigned projects.
                </p>
            </div>

        </div>


        {{-- RIGHT (optional future space) --}}
        <div class="hidden sm:flex items-center gap-2 text-[11px] text-slate-400">
            {{-- You can add filters / date / SY badge here later --}}
        </div>

    </div>

    <div class="py-6">
        <div class="page-container mx-auto px-5 space-y-5">

            {{-- FLASH --}}
            @if (session('status'))
                <div class="card border-amber-200 bg-amber-50 px-4 py-3 text-amber-900">
                    <div class="text-xs">{{ session('status') }}</div>
                </div>
            @endif


            {{-- ================= STATS ================= --}}
            @include('portals.partials._org_dashboard_stats')


            {{-- ================= MAIN GRID ================= --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

                {{-- ================= LEFT ================= --}}
                <div class="lg:col-span-2 space-y-5">

                    @include('portals.partials._org_dashboard_pending_tasks')


                    {{-- ================= ORG + ROLES ================= --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                        {{-- ORGANIZATION --}}
                        <div class="card p-4">
                            <div class="card-header">
                                Selected Organization
                            </div>

                            <div class="mt-1 card-title">
                                {{ $currentOrg?->name ?? '—' }}
                            </div>

                            <div class="mt-1 text-xs text-slate-600">
                                Acronym: {{ $currentOrg?->acronym ?? '—' }}
                            </div>

                            @if(!$currentOrg)
                                <div class="mt-3 rounded-lg border border-slate-200 bg-slate-50 p-2 text-xs text-slate-600">
                                    You are not currently assigned to an organization.
                                </div>
                            @endif
                        </div>


                        {{-- ROLES --}}
                        <div class="card p-4">
                            <div class="card-header">
                                Your Roles
                            </div>

                            <div class="mt-3 flex flex-wrap gap-1.5">
                                @forelse($roles as $r)
                                    <span class="inline-flex items-center rounded-full bg-emerald-50 px-2 py-0.5 text-[10px] font-semibold text-emerald-700 ring-1 ring-emerald-200">
                                        {{ ucfirst(str_replace('_', ' ', $r)) }}
                                    </span>
                                @empty
                                    <span class="text-xs text-slate-500">
                                        No role assigned.
                                    </span>
                                @endforelse
                            </div>

                            <div class="mt-4 text-xs text-slate-600">
                                Project head assignments:
                                <span class="font-semibold text-slate-900">
                                    {{ $projectHeadCount }}
                                </span>
                            </div>
                        </div>

                    </div>

                </div>


                {{-- ================= RIGHT ================= --}}
                <div class="space-y-5">

                    {{-- WARNING CARD --}}
                    @if($roles->contains('president') && $projectsWithoutHeadCount > 0)
                    <div class="card border-amber-200 bg-amber-50 p-4">

                        <div class="flex flex-col gap-3">

                            <div>
                                <div class="text-xs font-semibold text-amber-900">
                                    Project Setup Required
                                </div>

                                <div class="mt-1 text-xs text-amber-800">
                                    {{ $projectsWithoutHeadCount }} project{{ $projectsWithoutHeadCount > 1 ? 's' : '' }} need a project head.
                                </div>

                                <div class="mt-1 text-[11px] text-amber-700">
                                    Assign heads to enable submissions.
                                </div>
                            </div>

                            <a href="{{ route('org.projects.index') }}"
                               class="w-full text-center rounded-lg bg-amber-600 px-3 py-2 text-xs font-semibold text-white hover:bg-amber-700 transition">
                                Manage Projects
                            </a>

                        </div>

                    </div>
                    @endif


                    {{-- PROJECTS --}}
                    @include('portals.partials._org_dashboard_assigned_projects')


                    @php
                    $isProjectHead = \App\Models\ProjectAssignment::query()
                        ->where('user_id', auth()->id())
                        ->whereNull('archived_at')
                        ->exists();
                    @endphp


                    {{-- QUICK ACCESS --}}
                    <div class="card p-4">

                        {{-- HEADER --}}
                        <div>
                            <div class="flex items-center gap-2">
                                <i data-lucide="zap" class="w-4 h-4 text-slate-400"></i>
                                <div class="card-header">Quick Access</div>
                            </div>

                            <p class="mt-1 text-xs text-slate-500">
                                Common navigation shortcuts.
                            </p>
                        </div>


                        {{-- LINKS --}}
                        <div class="mt-3 flex flex-col gap-2">

                            @if($roles->contains('president'))

                                {{-- RE-REG --}}
                                <a href="{{ route('org.rereg.index') }}"
                                class="group flex items-center justify-between rounded-lg border border-blue-200 bg-gradient-to-b from-blue-50 to-white px-3 py-2 text-xs font-medium text-blue-800 transition hover:shadow-sm">

                                    <div class="flex items-center gap-2">
                                        <div class="w-1.5 h-6 rounded-full bg-blue-400"></div>
                                        <i data-lucide="refresh-cw" class="w-3.5 h-3.5 text-blue-500"></i>
                                        <span>Re-registration Hub</span>
                                    </div>

                                    <span class="text-[10px] text-blue-500 group-hover:text-blue-700">
                                        Open
                                    </span>
                                </a>


                                {{-- ASSIGN HEAD --}}
                                <a href="{{ route('org.assign-project-heads.index') }}"
                                class="group flex items-center justify-between rounded-lg border border-amber-200 bg-gradient-to-b from-amber-50 to-white px-3 py-2 text-xs font-medium text-amber-800 transition hover:shadow-sm">

                                    <div class="flex items-center gap-2">
                                        <div class="w-1.5 h-6 rounded-full bg-amber-400"></div>
                                        <i data-lucide="users" class="w-3.5 h-3.5 text-amber-500"></i>
                                        <span>Assign Project Heads</span>
                                    </div>

                                    <span class="text-[10px] text-amber-500 group-hover:text-amber-700">
                                        Open
                                    </span>
                                </a>

                            @endif


                            {{-- PROJECTS --}}
                            <a href="{{ route('org.projects.index') }}"
                            class="group flex items-center justify-between rounded-lg border border-emerald-200 bg-gradient-to-b from-emerald-50 to-white px-3 py-2 text-xs font-medium text-emerald-800 transition hover:shadow-sm">

                                <div class="flex items-center gap-2">
                                    <div class="w-1.5 h-6 rounded-full bg-emerald-400"></div>
                                    <i data-lucide="folder" class="w-3.5 h-3.5 text-emerald-500"></i>
                                    <span>Projects</span>
                                </div>

                                <span class="text-[10px] text-emerald-500 group-hover:text-emerald-700">
                                    Open
                                </span>
                            </a>

                        </div>


                        {{-- FOOTER INFO --}}
                        @if(!$isProjectHead && !$roles->contains('president'))
                            <div class="mt-3 text-[11px] text-slate-500">
                                Some modules are role-restricted.
                            </div>
                        @endif

                    </div>

                </div>

            </div>

        </div>
    </div>

</x-app-layout>