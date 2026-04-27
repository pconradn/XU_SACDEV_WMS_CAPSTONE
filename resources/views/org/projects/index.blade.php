<x-app-layout>

@php
    $user = auth()->user();

    $orgRole = \App\Models\OrgMembership::query()
        ->where('user_id', $user->id)
        ->where('organization_id', session('active_org_id'))
        ->where('school_year_id', $syId)
        ->whereNull('archived_at')
        ->value('role');

    $isPresident = $orgRole === 'president';
    $isModerator = $orgRole === 'moderator';
    $isTreasurer = $orgRole === 'treasurer';
    $isFinance_Officer = $orgRole === 'finance_officer';

    $hasProjectAssignment = \App\Models\ProjectAssignment::query()
        ->where('user_id', $user->id)
        ->whereNull('archived_at')
        ->whereIn('assignment_role', ['project_head', 'draftee'])
        ->whereHas('project', function ($q) use ($syId) {
            $q->where('organization_id', session('active_org_id'))
              ->where('school_year_id', $syId);
        })
        ->exists();

    $canViewProjects = $isPresident || $isModerator || $isTreasurer || $isFinance_Officer || $hasProjectAssignment;
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
                            These are the projects based on your submitted strategic plan. Each project serves as the main container for documents, approvals, assignments, and workflow tracking.
                        </p>
                    </div>

                    <div class="grid gap-3 sm:grid-cols-3">

                        <div class="rounded-xl border border-amber-200 bg-amber-50/70 p-3 {{ !$isPresident ? 'opacity-50' : '' }}">
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

                        <div class="rounded-xl border border-blue-100 bg-white/80 p-3">
                            <div class="text-xs font-semibold text-blue-700 flex items-center gap-1">
                                <i data-lucide="file-check" class="w-3.5 h-3.5"></i>
                                Manage Documents
                            </div>

                            <p class="text-[11px] text-slate-500 mt-1">
                                Open a project to handle required documents, submissions, and approval workflow.
                            </p>
                        </div>

                        <div class="rounded-xl border border-indigo-200 bg-indigo-50 p-3">
                            <div class="text-xs font-semibold text-indigo-700 flex items-center gap-1">
                                <i data-lucide="users" class="w-3.5 h-3.5"></i>
                                Draft Collaboration
                            </div>

                            <p class="text-[11px] text-slate-500 mt-1">
                                Draftees may help prepare draft documents, while project heads handle final submission.
                            </p>
                        </div>

                    </div>

                    <div class="text-[11px] text-slate-500">
                        Your organization role:
                        <span class="font-semibold text-slate-700">
                            {{ ucfirst(str_replace('_',' ', $orgRole ?? 'none')) }}
                        </span>
                        — project-specific roles are shown per row.
                    </div>

                </div>
            </div>

        </div>

        {{-- ================= MAIN CONTENT ================= --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- ================= TABLE ================= --}}
            <div class="lg:col-span-2 space-y-6">

                @if($canViewProjects)

                    <div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm overflow-hidden">

                        <div class="px-5 py-4 border-b border-slate-200 bg-white">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <i data-lucide="list-todo" class="w-4 h-4 text-slate-500"></i>
                                        <h3 class="text-sm font-semibold text-slate-800">
                                            Project List
                                        </h3>
                                    </div>
                                    <p class="mt-1 text-xs text-slate-500">
                                        View your accessible projects and open their document workspace.
                                    </p>
                                </div>

                                <span class="inline-flex items-center rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-[11px] font-semibold text-slate-600">
                                    {{ $projects->count() }} project{{ $projects->count() !== 1 ? 's' : '' }}
                                </span>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-[12px] leading-snug table-auto">

                                <thead class="bg-slate-50 border-b border-slate-200">
                                    <tr class="text-left text-[10px] uppercase tracking-wide text-slate-500">
                                        <th class="px-4 py-3 min-w-[240px]">Project</th>
                                        <th class="px-4 py-3 min-w-[180px]">Your Role</th>
                                        <th class="px-4 py-3 min-w-[160px]">Project Head</th>
                                        <th class="px-4 py-3 min-w-[120px]">Documents</th>
                                        <th class="px-4 py-3 min-w-[120px]">Workflow</th>
                                        <th class="px-4 py-3 min-w-[120px] text-right">Actions</th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-slate-200 bg-white">

                                    @forelse ($projects as $p)

                                        @php
                                            $projectRoles = [];

                                            foreach($p->assignments as $assignment){
                                                if((int) $assignment->user_id !== (int) $user->id){
                                                    continue;
                                                }

                                                if($assignment->assignment_role === 'project_head'){
                                                    $projectRoles[] = [
                                                        'label' => 'Project Head',
                                                        'class' => 'border-blue-200 bg-blue-50 text-blue-700',
                                                        'icon' => 'user-check',
                                                    ];
                                                }

                                                if($assignment->assignment_role === 'draftee'){
                                                    $projectRoles[] = [
                                                        'label' => 'Draftee',
                                                        'class' => 'border-indigo-200 bg-indigo-50 text-indigo-700',
                                                        'icon' => 'pencil-line',
                                                    ];
                                                }
                                            }

                                            if($isPresident){
                                                $projectRoles[] = [
                                                    'label' => 'President',
                                                    'class' => 'border-emerald-200 bg-emerald-50 text-emerald-700',
                                                    'icon' => 'badge-check',
                                                ];
                                            }

                                            if($isTreasurer){
                                                $projectRoles[] = [
                                                    'label' => 'Treasurer',
                                                    'class' => 'border-amber-200 bg-amber-50 text-amber-700',
                                                    'icon' => 'wallet',
                                                ];
                                            }

                                            if($isModerator){
                                                $projectRoles[] = [
                                                    'label' => 'Moderator',
                                                    'class' => 'border-purple-200 bg-purple-50 text-purple-700',
                                                    'icon' => 'shield-check',
                                                ];
                                            }

                                            if($isFinance_Officer){
                                                $projectRoles[] = [
                                                    'label' => 'Finance Officer',
                                                    'class' => 'border-rose-200 bg-rose-50 text-rose-700',
                                                    'icon' => 'landmark',
                                                ];
                                            }

                                            $projectRoles = collect($projectRoles)->unique('label')->values();

                                            $head = \App\Models\ProjectAssignment::query()
                                                ->with('user')
                                                ->where('project_id', $p->id)
                                                ->where('assignment_role', 'project_head')
                                                ->whereNull('archived_at')
                                                ->first();

                                            $workflow = $p->workflow_status ?? 'planning';

                                            $workflowMap = [
                                                'planning' => ['label' => 'Planning', 'class' => 'bg-slate-100 text-slate-700 border-slate-200'],
                                                'drafting' => ['label' => 'Drafting', 'class' => 'bg-blue-50 text-blue-700 border-blue-200'],
                                                'pre_implementation' => ['label' => 'Pre-Implementation', 'class' => 'bg-amber-50 text-amber-700 border-amber-200'],
                                                'post_implementation' => ['label' => 'Post-Implementation', 'class' => 'bg-indigo-50 text-indigo-700 border-indigo-200'],
                                                'completed' => ['label' => 'Completed', 'class' => 'bg-emerald-50 text-emerald-700 border-emerald-200'],
                                                'postponed' => ['label' => 'Postponed', 'class' => 'bg-orange-50 text-orange-700 border-orange-200'],
                                                'cancelled' => ['label' => 'Cancelled', 'class' => 'bg-rose-50 text-rose-700 border-rose-200'],
                                            ];

                                            $wf = $workflowMap[$workflow] ?? [
                                                'label' => ucfirst(str_replace('_', ' ', $workflow)),
                                                'class' => 'bg-slate-100 text-slate-700 border-slate-200',
                                            ];
                                        @endphp

                                        <tr class="hover:bg-slate-50/80 transition">

                                            {{-- PROJECT --}}
                                            <td class="px-4 py-4 align-top">
                                                <div class="flex items-start gap-3">
                                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-slate-50 text-slate-500">
                                                        <i data-lucide="folder-open" class="w-4 h-4"></i>
                                                    </div>

                                                    <div class="min-w-0">
                                                        <div class="font-semibold text-slate-900 break-words">
                                                            {{ $p->title }}
                                                        </div>

                                                        <div class="mt-1 flex flex-wrap items-center gap-2 text-[10px] text-slate-500">
                                                            <span class="inline-flex items-center gap-1">
                                                                <i data-lucide="file-text" class="w-3 h-3"></i>
                                                                {{ $p->documents_count }} document{{ $p->documents_count !== 1 ? 's' : '' }}
                                                            </span>

                                                            @if($p->implementation_start_date)
                                                                <span class="inline-flex items-center gap-1">
                                                                    <i data-lucide="calendar" class="w-3 h-3"></i>
                                                                    {{ \Carbon\Carbon::parse($p->implementation_start_date)->format('M d, Y') }}
                                                                </span>
                                                            @else
                                                                <span class="inline-flex items-center gap-1">
                                                                    <i data-lucide="calendar-x" class="w-3 h-3"></i>
                                                                    No date
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>

                                            {{-- YOUR ROLE --}}
                                            <td class="px-4 py-4 align-top">
                                                <div class="flex flex-wrap gap-1.5">
                                                    @forelse($projectRoles as $role)
                                                        <span class="inline-flex items-center gap-1 rounded-full border px-2 py-0.5 text-[10px] font-semibold {{ $role['class'] }}">
                                                            <i data-lucide="{{ $role['icon'] }}" class="w-3 h-3"></i>
                                                            {{ $role['label'] }}
                                                        </span>
                                                    @empty
                                                        <span class="inline-flex items-center rounded-full border border-slate-200 bg-slate-50 px-2 py-0.5 text-[10px] font-semibold text-slate-500">
                                                            Viewer
                                                        </span>
                                                    @endforelse
                                                </div>
                                            </td>

                                            {{-- PROJECT HEAD --}}
                                            <td class="px-4 py-4 align-top">
                                                @if($head && $head->user)
                                                    <div class="space-y-1">
                                                        <div class="font-medium text-slate-800 break-words">
                                                            {{ $head->user->name ?? 'Assigned User' }}
                                                        </div>

                                                        @if(!empty($head->user->email))
                                                            <div class="text-[10px] text-slate-500 break-words">
                                                                {{ $head->user->email }}
                                                            </div>
                                                        @endif

                                                        <span class="inline-flex w-fit rounded-full border border-emerald-200 bg-emerald-50 px-2 py-0.5 text-[10px] font-semibold text-emerald-700">
                                                            Assigned
                                                        </span>
                                                    </div>
                                                @else
                                                    <span class="inline-flex rounded-full border border-slate-200 bg-slate-50 px-2 py-0.5 text-[10px] font-semibold text-slate-500">
                                                        Not assigned
                                                    </span>
                                                @endif
                                            </td>

                                            {{-- DOCUMENTS --}}
                                            <td class="px-4 py-4 align-top">
                                                <div class="space-y-2">
                                                    <a href="{{ route('org.projects.documents.hub', $p) }}"
                                                       class="inline-flex items-center justify-center gap-1 rounded-lg border border-blue-200 bg-blue-50 px-3 py-1.5 text-[11px] font-semibold text-blue-700 hover:bg-blue-100 transition">
                                                        <i data-lucide="folder-input" class="w-3.5 h-3.5"></i>
                                                        Open Hub
                                                    </a>

                                                    @if(($p->pending_approvals ?? 0) > 0)
                                                        <div class="inline-flex items-center gap-1 rounded-full border border-rose-200 bg-rose-50 px-2 py-0.5 text-[10px] font-semibold text-rose-700">
                                                            <i data-lucide="circle-alert" class="w-3 h-3"></i>
                                                            {{ $p->pending_approvals }} pending
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>

                                            {{-- WORKFLOW --}}
                                            <td class="px-4 py-4 align-top">
                                                <span class="inline-flex rounded-full border px-2.5 py-1 text-[10px] font-semibold {{ $wf['class'] }}">
                                                    {{ $wf['label'] }}
                                                </span>
                                            </td>

                                            {{-- ACTIONS --}}
                                            <td class="px-4 py-4 text-right align-top">
                                                <div class="flex justify-end gap-2">
                                                    @if($isPresident)
                                                        <button
                                                            @click='selectedProject = @json($p); openEditModal = true'
                                                            class="inline-flex items-center gap-1 rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-[11px] font-semibold text-slate-700 hover:bg-slate-50 transition">
                                                            <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                                                            Edit
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>

                                        </tr>

                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-12 text-center">
                                                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl border border-slate-200 bg-slate-50 text-slate-400">
                                                    <i data-lucide="folder-search" class="w-5 h-5"></i>
                                                </div>

                                                <div class="mt-3 text-sm font-semibold text-slate-700">
                                                    No projects found
                                                </div>

                                                <div class="mt-1 text-xs text-slate-500">
                                                    There are no projects available for your current organization context.
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse

                                </tbody>
                            </table>
                        </div>

                    </div>

                @else
                    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-8 text-center">
                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl border border-slate-200 bg-slate-50 text-slate-400">
                            <i data-lucide="lock" class="w-5 h-5"></i>
                        </div>

                        <p class="mt-3 text-sm font-semibold text-slate-700">
                            You do not have access to view projects.
                        </p>

                        <p class="mt-1 text-xs text-slate-500">
                            Only officers with project access, project heads, and assigned draftees can view project workspaces.
                        </p>
                    </div>
                @endif

            </div>

            {{-- ================= SIDE ================= --}}
            <div class="space-y-6">

                <div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm p-4 space-y-3">
                    <div class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                        Role Information
                    </div>

                    <div class="text-xs text-slate-700">
                        Organization Role:
                        <span class="font-semibold text-slate-900">
                            {{ ucfirst(str_replace('_',' ', $orgRole ?? 'none')) }}
                        </span>
                    </div>

                    <div class="text-[11px] text-slate-500 leading-relaxed">
                        Project-specific roles are displayed in the project table. A user may be a project head in one project and a draftee in another.
                    </div>
                </div>

                <div class="rounded-2xl border border-indigo-200 bg-indigo-50 shadow-sm p-4 space-y-2">
                    <div class="flex items-center gap-2 text-xs font-semibold text-indigo-700 uppercase tracking-wide">
                        <i data-lucide="pencil-line" class="w-4 h-4"></i>
                        Draftee Access
                    </div>

                    <p class="text-[11px] text-slate-600 leading-relaxed">
                        Draftees can help prepare documents while they are still in draft status. Final submission remains under the project head.
                    </p>
                </div>

                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 shadow-sm p-4 space-y-2">
                    <div class="flex items-center gap-2 text-xs font-semibold text-emerald-700 uppercase tracking-wide">
                        <i data-lucide="shield-check" class="w-4 h-4"></i>
                        Document Approvals
                    </div>

                    <p class="text-[11px] text-slate-600 leading-relaxed">
                        Approvers are responsible for reviewing and approving project documents during the workflow process.
                    </p>

                    <div class="flex flex-wrap gap-1.5 pt-1">

                        <span class="inline-flex items-center gap-1 rounded-full border border-emerald-200 bg-emerald-100 px-2 py-0.5 text-[10px] font-semibold text-emerald-700">
                            <i data-lucide="badge-check" class="w-3 h-3"></i>
                            President
                        </span>

                        <span class="inline-flex items-center gap-1 rounded-full border border-amber-200 bg-amber-100 px-2 py-0.5 text-[10px] font-semibold text-amber-700">
                            <i data-lucide="wallet" class="w-3 h-3"></i>
                            Treasurer
                        </span>

                        <span class="inline-flex items-center gap-1 rounded-full border border-rose-200 bg-rose-100 px-2 py-0.5 text-[10px] font-semibold text-rose-700">
                            <i data-lucide="landmark" class="w-3 h-3"></i>
                            Budget and Finance Officer
                        </span>

                        <span class="inline-flex items-center gap-1 rounded-full border border-purple-200 bg-purple-100 px-2 py-0.5 text-[10px] font-semibold text-purple-700">
                            <i data-lucide="shield-check" class="w-3 h-3"></i>
                            Moderator
                        </span>



                    </div>

                    <p class="text-[10px] text-slate-500 pt-1">
                        Documents move through a structured approval flow, and only the current assigned approver may take action at each step.
                    </p>
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