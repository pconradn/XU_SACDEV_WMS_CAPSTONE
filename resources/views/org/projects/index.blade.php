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

    $effectiveRole = $orgRole === 'member'
        ? ($isProjectHead ? 'project_head' : 'member')
        : $orgRole;

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
}" class="py-6">

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

    {{-- ================= BREADCRUMB ================= --}}
    <nav class="text-xs text-slate-500">
        <ol class="flex items-center gap-1.5">
            <li>
                <a href="{{ route('org.organization-info.show') }}"
                   class="font-medium text-slate-600 hover:text-slate-900 transition">
                    Organization
                </a>
            </li>
            <li class="text-slate-300">/</li>
            <li class="text-slate-400">Projects</li>
        </ol>
    </nav>

    {{-- ================= HEADER ================= --}}
    <div class="rounded-2xl border border-slate-200 bg-gradient-to-br from-amber-50 via-white to-slate-50 shadow-sm p-5 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

        <div class="space-y-1">
            <h2 class="text-xl font-semibold text-slate-900 flex items-center gap-2">
                <i data-lucide="folder-kanban" class="w-5 h-5 text-amber-600"></i>
                Project Management
            </h2>

            <p class="text-xs text-slate-500 max-w-xl">
                Manage organization projects, handle document workflows, assign responsibilities, and track project progress from planning to completion.
            </p>
        </div>

        <div class="flex items-center gap-2">

            @if($isPresident)
                <button
                    @click="openCreateModal = true"
                    class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2 text-xs font-semibold text-white shadow-sm hover:bg-emerald-700 transition">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    Create Project
                </button>
            @endif

            <button
                @click="showHelpModal = true"
                class="w-8 h-8 flex items-center justify-center rounded-xl border border-slate-300 hover:bg-slate-100 transition">
                <i data-lucide="help-circle" class="w-4 h-4 text-slate-600"></i>
            </button>

            @include('org.projects.partials._toolbar', [
                'isPresident' => $isPresident,
            ])
        </div>
    </div>

    {{-- ================= INSTRUCTIONS ================= --}}
    <div class="rounded-2xl border border-blue-200 bg-gradient-to-br from-blue-50 via-white to-slate-50 p-5 shadow-sm">

        <div class="flex items-start gap-3">
            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-700">
                <i data-lucide="info" class="w-4 h-4"></i>
            </div>

            <div class="space-y-3">

                <div>
                    <div class="text-sm font-semibold text-slate-900">
                        How to Use Projects Module
                    </div>

                    <p class="mt-1 text-xs text-slate-600 leading-relaxed">
                        These are the projects based on your submitted strategic plan. You can also create additional projects as needed for new or unplanned activities. Each project serves as the main container for documents, approvals, and workflow tracking.
                    </p>
                </div>

                <div class="grid gap-3 sm:grid-cols-3">

                    {{-- CREATE PROJECT --}}
                    <div class="rounded-xl border border-amber-200 bg-amber-50/70 p-3
                        {{ !$isPresident ? 'opacity-50' : '' }}">

                        <div class="text-xs font-semibold text-slate-700 flex items-center gap-1">
                            <i data-lucide="plus-circle" class="w-3.5 h-3.5"></i>
                            Create Project
                        </div>

                        <p class="text-[11px] text-slate-500 mt-1">
                            Add new projects for upcoming activities.
                        </p>

                        @if(!$isPresident)
                            <div class="text-[10px] text-slate-400 mt-1">
                                President only
                            </div>
                        @endif
                    </div>

                    {{-- MANAGE DOCUMENTS --}}
                    <div class="rounded-xl border border-blue-100 bg-white/80 p-3">

                        <div class="text-xs font-semibold text-blue-700 flex items-center gap-1">
                            <i data-lucide="file-check" class="w-3.5 h-3.5"></i>
                            Manage Documents
                        </div>

                        <p class="text-[11px] text-slate-500 mt-1">
                            Open a project to handle required documents, submissions, and approval workflow.
                        </p>
                    </div>
                {{-- VIEW / TRACK --}}
                <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-3">

                    <div class="text-xs font-semibold text-emerald-700 flex items-center gap-1">
                        <i data-lucide="activity" class="w-3.5 h-3.5"></i>
                        Track Workflow
                    </div>

                    <p class="text-[11px] text-slate-500 mt-1">
                        Monitor project status from drafting to completion.
                    </p>

                </div>

                </div>

                <div class="text-[11px] text-slate-500">
                    Your role:
                    <span class="font-semibold text-slate-700">
                        {{ ucfirst(str_replace('_',' ', $effectiveRole ?? 'none')) }}
                    </span>
                    — determines available actions.
                </div>

            </div>
        </div>

    </div>

    {{-- ================= MAIN CONTENT ================= --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- TABLE --}}
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

        {{-- SIDE --}}
        <div class="space-y-6">

            <div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm p-4 space-y-2">
                <div class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                    Role Information
                </div>

                <div class="text-xs text-slate-700">
                    You are 
                    <span class="font-semibold text-slate-900">
                        {{ ucfirst(str_replace('_',' ', $effectiveRole ?? 'none')) }}
                    </span>
                </div>

                <div class="text-[11px] text-slate-500">
                    Access to features depends on your assigned role.
                </div>
            </div>

        </div>

    </div>

    {{-- KEEP MODALS --}}
    @include('org.projects.partials._create_modal')
    @include('org.projects.partials._edit_modal')
    @include('org.projects.partials._assign_head_modal')

</div>
</div>

</x-app-layout>