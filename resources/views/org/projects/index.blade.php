<x-app-layout>
@php
    $user = auth()->user();

    $orgRole = \App\Models\OrgMembership::query()
        ->where('user_id', $user->id)
        ->where('organization_id', session('active_org_id'))
        ->where('school_year_id', $syId)
        ->whereNull('archived_at')
        ->value('role');

    $isProjectHead = \App\Models\ProjectAssignment::query()
        ->where('user_id', $user->id)
        ->whereNull('archived_at')
        ->whereHas('project', function ($q) use ($syId) {
            $q->where('organization_id', session('active_org_id'))
              ->where('school_year_id', $syId);
        })
        ->exists();



    $effectiveRole = $orgRole;

    if ($orgRole === 'member') {
        $effectiveRole = $isProjectHead ? 'project_head' : 'member';
    }

    /**
     * EXISTING FLAGS (DO NOT BREAK)
     */
    $isPresident = $orgRole === 'president';
    $isModerator = $orgRole === 'moderator';
    $isTreasurer = $orgRole === 'treasurer';
    $isFinance_Officer = $orgRole === 'finance_officer';

    $canManageProjects = $isPresident;
    $canViewProjects = $isPresident || $isModerator || $isTreasurer || $isFinance_Officer || $isProjectHead;
@endphp

    <div
        x-data="{
            openCreateModal: false,
            openEditModal: false,
            openAssignHeadModal: false,
            selectedProject: null
        }"
        class="py-8"
    >
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- ================= HEADER ================= --}}
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-slate-900">
                        Projects
                    </h2>
                    <p class="mt-1 text-sm text-slate-500">
                        Manage organization projects for the selected school year.
                    </p>
                </div>

                @include('org.projects.partials._toolbar', [
                    'isPresident' => $isPresident,
                ])
            </div>


            {{-- ================= GUIDANCE CARD ================= --}}
            <div class="rounded-2xl border border-blue-200 bg-gradient-to-b from-blue-50 to-white shadow-sm p-4 flex items-start gap-3">

                <i data-lucide="info" class="w-4 h-4 text-blue-600 mt-0.5"></i>

                <div class="text-xs text-slate-700 space-y-1">
                    <p class="font-medium text-blue-700">
                        How project management works
                    </p>

                    <p>
                        Select a project below to start managing its documents and workflow.
                        Each project goes through a structured process:
                    </p>

                    <ul class="list-disc ml-4 space-y-1">
                        <li>Submit <span class="font-medium">Project Proposal</span></li>
                        <li>Complete required forms (e.g. off-campus, reports)</li>
                        <li>Wait for approvals from assigned roles</li>
                        <li>Finalize and complete the project workflow</li>
                    </ul>
                </div>
            </div>


            {{-- ================= MAIN GRID ================= --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- LEFT: PROJECT TABLE --}}
                <div class="lg:col-span-2 space-y-6">

                    @if($canViewProjects)
                        @include('org.projects.partials._table', [
                            'projects' => $projects,
                            'isPresident' => $isPresident,
                        ])
                    @else
                        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-8 text-center">
                            <p class="text-sm text-slate-500">
                                You do not have access to view projects for this organization and school year.
                            </p>
                        </div>
                    @endif

                </div>


                {{-- RIGHT: ACTION + STATUS PANEL --}}
                <div class="space-y-6">

                    {{-- QUICK ACTIONS --}}
                    <div class="rounded-2xl border border-emerald-200 bg-gradient-to-b from-emerald-50 to-white shadow-sm p-4 space-y-3">

                        <div class="text-xs font-semibold text-emerald-700 uppercase tracking-wide">
                            Quick Actions
                        </div>

                        <div class="space-y-2 text-xs text-slate-600">

                            @if($isPresident)
                                <button
                                    @click="openCreateModal = true"
                                    class="w-full text-left px-3 py-2 rounded-lg border border-emerald-200 hover:bg-emerald-50 transition"
                                >
                                    Create New Project
                                </button>
                            @endif

                            <div class="px-3 py-2 rounded-lg border border-slate-200 bg-white">
                                Select a project to view details
                            </div>

                        </div>
                    </div>


                    {{-- ROLE INFO --}}
                    <div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm p-4 space-y-3">

                        <div class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                            Your Role
                        </div>

                        <div class="text-xs text-slate-700">
                            @if($orgRole)
                                You are assigned as 
                                <span class="font-medium text-slate-900">
                                    {{ ucfirst(str_replace('_', ' ', $effectiveRole)) }}
                                </span>
                                in this organization.
                            @else
                                No active role assigned.
                            @endif
                        </div>

                        <div class="text-[11px] text-slate-500">
                            Your role determines what actions you can perform in project workflows.
                        </div>
                    </div>




                </div>

            </div>


            {{-- ================= MODALS ================= --}}
            @include('org.projects.partials._create_modal')
            @include('org.projects.partials._edit_modal')
            @include('org.projects.partials._assign_head_modal')

        </div>
    </div>
</x-app-layout>