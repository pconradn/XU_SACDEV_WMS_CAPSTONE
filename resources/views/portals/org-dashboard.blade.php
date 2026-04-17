<x-app-layout>


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


    <div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm px-5 py-2 flex items-center justify-between">

  
        <div class="flex items-start gap-3">

      
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


        <div class="hidden sm:flex items-center gap-2 text-[11px] text-slate-400">
       
        </div>

    </div>

    <div class="py-6">
        <div class="page-container mx-auto px-0 sm:px-4 lg:px-5 space-y-5">

         
            @if (session('status'))
                <div class="card border-amber-200 bg-amber-50 px-4 py-3 text-amber-900">
                    <div class="text-xs">{{ session('status') }}</div>
                </div>
            @endif


     
            @include('portals.partials._org_dashboard_stats')


    
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

      
                <div class="lg:col-span-2 space-y-5">

                    <div
                        x-data="{
                            load() {
                                fetch('{{ route('org.dashboard.pending-tasks.partial') }}')
                                    .then(res => res.text())
                                    .then(html => {
                                        this.$refs.container.innerHTML = html
                                    })
                            }
                        }"
                        x-init="setInterval(() => load(), 30000)"
                    >
                        <div x-ref="container">
                            @include('portals.partials._org_dashboard_pending_tasks')
                        </div>
                    </div>


                  
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    


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


          
                <div class="space-y-5">

                    
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


             
                    @include('portals.partials._org_dashboard_assigned_projects')


                    @php
                    $isProjectHead = \App\Models\ProjectAssignment::query()
                        ->where('user_id', auth()->id())
                        ->whereNull('archived_at')
                        ->exists();
                    @endphp


            
                    <div class="card p-4">

                    
                        <div>
                            <div class="flex items-center gap-2">
                                <i data-lucide="zap" class="w-4 h-4 text-slate-400"></i>
                                <div class="card-header">Quick Access</div>
                            </div>

                            <p class="mt-1 text-xs text-slate-500">
                                Common navigation shortcuts.
                            </p>
                        </div>


                        <div class="mt-3 flex flex-col gap-2">

                            @if($roles->contains('president'))

                           
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

                            @elseif($roles->contains('moderator'))


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

                            @endif


                        
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