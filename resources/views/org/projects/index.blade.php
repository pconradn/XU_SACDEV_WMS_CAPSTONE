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

<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 ">

        <div class="lg:col-span-2 space-y-6">
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

        <div class="space-y-6">

            @if($isPresident)
            <div class="rounded-2xl border border-emerald-200 bg-gradient-to-b from-emerald-50 to-white shadow-sm p-4 space-y-3">
                <div class="text-xs font-semibold text-emerald-700 uppercase tracking-wide">
                    Quick Actions
                </div>

                <div class="space-y-2 text-xs text-slate-600">
                    <button
                        @click="openCreateModal = true"
                        class="w-full text-left px-3 py-2 rounded-lg border border-emerald-200 hover:bg-emerald-50 transition">
                        Create New Project
                    </button>

                    <div class="px-3 py-2 rounded-lg border border-slate-200 bg-white">
                        Select a project below
                    </div>
                </div>
            </div>
            @endif

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

    </div>

    <div
        x-show="showHelpModal"
        x-transition
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/40"
        style="display: none;"
    >
        <div @click.outside="showHelpModal = false"
            class="w-full max-w-md rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm">

            <div class="px-5 py-4 border-b border-slate-200 flex items-start gap-3">
                <div class="mt-0.5 flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600">
                    <i data-lucide="info" class="w-4 h-4"></i>
                </div>

                <div class="space-y-1">
                    <h2 class="text-sm font-semibold text-slate-900">
                        Project Workflow Guide
                    </h2>
                    <p class="text-[11px] text-slate-500">
                        Overview of how project management and approvals work.
                    </p>
                </div>
            </div>

            <div class="p-4 space-y-3 text-xs text-slate-700">

                <div class="space-y-2">

                    <div class="flex items-start gap-2">
                        <span class="mt-1 h-1.5 w-1.5 rounded-full bg-blue-500"></span>
                        <span>Create or manage a project</span>
                    </div>

                    <div class="flex items-start gap-2">
                        <span class="mt-1 h-1.5 w-1.5 rounded-full bg-blue-500"></span>
                        <span>Assign a project head to handle submissions</span>
                    </div>

                    <div class="flex items-start gap-2">
                        <span class="mt-1 h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                        <span>Required documents go through approval workflow</span>
                    </div>

                    <div class="flex items-start gap-2">
                        <span class="mt-1 h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                        <span>Track progress and complete post-implementation reports</span>
                    </div>

                </div>

            </div>

            <div class="px-5 py-3 border-t border-slate-200 flex items-center justify-end">
                <button
                    @click="showHelpModal = false"
                    class="inline-flex items-center gap-1.5 rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-50 transition">
                    Close
                </button>
            </div>

        </div>
    </div>

    @include('org.projects.partials._create_modal')
    @include('org.projects.partials._edit_modal')
    @include('org.projects.partials._assign_head_modal')

</div>
</div>
</x-app-layout>