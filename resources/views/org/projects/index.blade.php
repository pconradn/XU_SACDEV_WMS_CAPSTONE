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

    $isPresident = $orgRole === 'president';
    $isModerator = $orgRole === 'moderator';
    $isTreasurer = $orgRole === 'treasurer';
    $isFinance_Officer = $orgRole === 'finance_officer';

    $canViewProjects = $isPresident || $isModerator || $isTreasurer || $isFinance_Officer || $isProjectHead;
@endphp

<div x-data="{
    openCreateModal: false,
    openEditModal: false,
    openAssignHeadModal: false,
    showHelpModal: false,
    selectedProject: null
}" class="py-8">

<div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">

    {{-- ================= HEADER ================= --}}
    <div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm p-5 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">

        <div>
            <h2 class="text-xl font-semibold text-slate-900 flex items-center gap-2">
                <i data-lucide="folder-kanban" class="w-5 h-5 text-slate-700"></i>
                Project Management
            </h2>

            <p class="mt-1 text-xs text-slate-500">
                Create, manage, and track organization projects.
            </p>
        </div>

        <div class="flex items-center gap-2">

            {{-- HELP BUTTON --}}
            <button
                @click="showHelpModal = true"
                class="w-8 h-8 flex items-center justify-center rounded-full border border-slate-300 hover:bg-slate-100">
                <i data-lucide="help-circle" class="w-4 h-4 text-slate-600"></i>
            </button>

            @include('org.projects.partials._toolbar', [
                'isPresident' => $isPresident,
            ])
        </div>
    </div>


    {{-- ================= TOP PANELS ================= --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- QUICK ACTIONS --}}
        <div class="rounded-2xl border border-emerald-200 bg-gradient-to-b from-emerald-50 to-white shadow-sm p-4 space-y-3">

            <div class="text-xs font-semibold text-emerald-700 uppercase tracking-wide">
                Quick Actions
            </div>

            <div class="space-y-2 text-xs text-slate-600">

                @if($isPresident)
                    <button
                        @click="openCreateModal = true"
                        class="w-full text-left px-3 py-2 rounded-lg border border-emerald-200 hover:bg-emerald-50 transition">
                        Create New Project
                    </button>
                @endif

                <div class="px-3 py-2 rounded-lg border border-slate-200 bg-white">
                    Select a project below
                </div>

            </div>
        </div>


        {{-- ROLE --}}
        <div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm p-4 space-y-3">

            <div class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                Your Role
            </div>

            <div class="text-xs text-slate-700">
                @if($orgRole)
                    You are
                    <span class="font-medium text-slate-900">
                        {{ ucfirst(str_replace('_', ' ', $effectiveRole)) }}
                    </span>
                    in this organization.
                @else
                    No active role assigned.
                @endif
            </div>

            <div class="text-[11px] text-slate-500">
                Your permissions depend on your role.
            </div>
        </div>

    </div>


    {{-- ================= TABLE ================= --}}
    <div>
        @if($canViewProjects)
            @include('org.projects.partials._table', [
                'projects' => $projects,
                'isPresident' => $isPresident,
            ])
        @else
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-8 text-center">
                <p class="text-sm text-slate-500">
                    You do not have access to view projects.
                </p>
            </div>
        @endif
    </div>


    {{-- ================= HELP MODAL ================= --}}
    <div
        x-show="showHelpModal"
        x-transition
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/40"
        style="display: none;"
    >
        <div @click.outside="showHelpModal = false"
             class="w-full max-w-md bg-white rounded-2xl shadow-xl">

            <div class="px-5 py-4 border-b">
                <h2 class="text-sm font-semibold text-slate-900">
                    How Project Management Works
                </h2>
            </div>

            <div class="p-4 space-y-3 text-xs text-slate-700">

                <ul class="list-disc ml-4 space-y-2">
                    <li>Create a project or assign a project head</li>
                    <li>Project head submits required documents</li>
                    <li>Documents go through approval workflow</li>
                    <li>Track progress and complete reports</li>
                </ul>

            </div>

            <div class="px-5 py-3 border-t flex justify-end">
                <button
                    @click="showHelpModal = false"
                    class="px-3 py-1.5 text-xs rounded-lg border border-slate-300 hover:bg-slate-100">
                    Close
                </button>
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