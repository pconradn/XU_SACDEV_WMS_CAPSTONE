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
        $isAuditor = $orgRole === 'auditor';

        $isProjectHead = \App\Models\ProjectAssignment::query()
            ->where('user_id', $user->id)
            ->whereNull('archived_at')
            ->whereHas('project', function ($q) use ($syId) {
                $q->where('organization_id', session('active_org_id'))
                  ->where('school_year_id', $syId);
            })
            ->exists();

        $canManageProjects = $isPresident;
        $canViewProjects = $isPresident || $isModerator || $isTreasurer || $isAuditor || $isProjectHead;
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
            {{-- Modals --}}
            @include('org.projects.partials._create_modal')
            @include('org.projects.partials._edit_modal')
            @include('org.projects.partials._assign_head_modal')
        </div>
    </div>
</x-app-layout>