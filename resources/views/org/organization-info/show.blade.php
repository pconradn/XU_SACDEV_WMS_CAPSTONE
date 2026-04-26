<x-app-layout>

@php
    $orgId = session('active_org_id');
    $syId  = session('encode_sy_id');
    $isPresident = auth()->user()?->hasRoleInOrg($orgId, $syId, 'president');
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

    {{-- BREADCRUMB --}}
    <nav class="text-xs text-slate-500">
        <ol class="flex items-center gap-1.5">
            <li class="font-medium text-slate-600">Organization</li>
            <li class="text-slate-300">/</li>
            <li class="text-slate-400">{{ $organization->acronym ?: $organization->name }}</li>
        </ol>
    </nav>

    {{-- HEADER --}}
    <div class="rounded-2xl border border-slate-200 bg-gradient-to-br from-slate-50 via-white to-amber-50 shadow-sm overflow-hidden">

        <div class="p-6 flex flex-col lg:flex-row gap-6 lg:items-start">

            {{-- LOGO --}}
            <div class="shrink-0">
                @if($organization->logo_path)
                    <img src="{{ asset('storage/'.$organization->logo_path) }}"
                         class="w-24 h-24 rounded-2xl object-cover border border-slate-200 shadow-sm bg-white">
                @else
                    <div class="w-24 h-24 rounded-2xl bg-slate-100 border border-slate-200 flex flex-col items-center justify-center text-slate-400 shadow-sm">
                        <i data-lucide="image" class="w-6 h-6"></i>
                        <span class="text-[10px] mt-1">No Logo</span>
                    </div>
                @endif
            </div>

            {{-- INFO --}}
            <div class="flex-1 min-w-0 space-y-5">

                <div>
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                        <div>
                            <h1 class="text-2xl font-semibold text-slate-900 leading-tight">
                                {{ $organization->name }}
                            </h1>

                            <div class="mt-1 inline-flex items-center gap-1.5 rounded-full bg-amber-100 px-2.5 py-1 text-[11px] font-semibold text-amber-700">
                                <i data-lucide="badge" class="w-3 h-3"></i>
                                {{ $organization->acronym ?: 'Organization' }}
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-2 sm:min-w-[280px]">
                            <div class="rounded-xl border border-slate-200 bg-white/80 px-3 py-2 text-center shadow-sm">
                                <div class="text-lg font-semibold text-slate-900">{{ $membersCount }}</div>
                                <div class="text-[10px] uppercase tracking-wide text-slate-400">Members</div>
                            </div>

                            <div class="rounded-xl border border-slate-200 bg-white/80 px-3 py-2 text-center shadow-sm">
                                <div class="text-lg font-semibold text-slate-900">{{ $officersCount }}</div>
                                <div class="text-[10px] uppercase tracking-wide text-slate-400">Officers</div>
                            </div>

                            <div class="rounded-xl border border-amber-200 bg-amber-50 px-3 py-2 text-center shadow-sm">
                                <div class="text-lg font-semibold text-amber-800">{{ $projectsCount }}</div>
                                <div class="text-[10px] uppercase tracking-wide text-amber-600">Projects</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

                    <div class="rounded-2xl border border-slate-200 bg-white/80 p-4 shadow-sm">
                        <div class="flex items-center gap-2 text-xs font-semibold text-slate-700 uppercase tracking-wide">
                            <i data-lucide="target" class="w-4 h-4 text-blue-600"></i>
                            Mission
                        </div>

                        <div class="mt-2 text-sm text-slate-600 leading-relaxed whitespace-pre-line">
                            {!! $organization->mission ? nl2br(e($organization->mission)) : 'No mission provided.' !!}
                        </div>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-white/80 p-4 shadow-sm">
                        <div class="flex items-center gap-2 text-xs font-semibold text-slate-700 uppercase tracking-wide">
                            <i data-lucide="telescope" class="w-4 h-4 text-purple-600"></i>
                            Vision
                        </div>

                        <div class="mt-2 text-sm text-slate-600 leading-relaxed whitespace-pre-line">
                            {!! $organization->vision ? nl2br(e($organization->vision)) : 'No vision provided.' !!}
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>

    {{-- MAIN WORKFLOW EMPHASIS --}}
    <a href="{{ route('org.projects.index') }}"
       class="group block rounded-2xl border border-amber-200 bg-gradient-to-br from-amber-50 via-white to-orange-50 p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-amber-300 hover:shadow-md">

        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

            <div class="flex items-start gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-amber-100 text-amber-700 ring-1 ring-amber-200">
                    <i data-lucide="folder-kanban" class="w-6 h-6"></i>
                </div>

                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="text-base font-semibold text-slate-900">
                            Projects Module
                        </h2>

                        <span class="rounded-full bg-amber-100 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-amber-700">
                            Main Workflow
                        </span>
                    </div>

                    <p class="mt-1 text-sm leading-6 text-slate-600 max-w-3xl">
                        Use this module to manage organization projects, prepare project documents, track approvals, handle submission requirements, and continue the project workflow from planning to completion.
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3 md:shrink-0">
                <div class="hidden sm:block rounded-xl border border-amber-200 bg-white/80 px-3 py-2 text-center">
                    <div class="text-lg font-semibold text-amber-800">{{ $projectsCount }}</div>
                    <div class="text-[10px] uppercase tracking-wide text-amber-600">Projects</div>
                </div>

                <div class="inline-flex items-center gap-2 rounded-xl bg-amber-600 px-4 py-2 text-xs font-semibold text-white shadow-sm transition group-hover:bg-amber-700">
                    Open Projects
                    <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                </div>
            </div>

        </div>
    </a>

    {{-- MODULES --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

        <div class="border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white px-5 py-4">
            <div class="flex items-center gap-2 text-sm font-semibold text-slate-900">
                <i data-lucide="layout-grid" class="w-4 h-4 text-slate-500"></i>
                Organization Modules
            </div>
            <div class="mt-1 text-xs text-slate-500">
                Access organization records, roles, and setup tools.
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 p-5">

            {{-- MEMBERS --}}
            <a href="{{ route('org.organization-members.index') }}"
               class="rounded-2xl border border-slate-200 bg-gradient-to-br from-white to-slate-50 p-4 shadow-sm transition hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-md">

                <div class="flex items-start justify-between gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                        <i data-lucide="users" class="w-5 h-5"></i>
                    </div>

                    <div class="rounded-full bg-slate-100 px-2 py-1 text-[11px] font-semibold text-slate-600">
                        {{ $membersCount }}
                    </div>
                </div>

                <div class="mt-4">
                    <div class="text-sm font-semibold text-slate-900">
                        Members
                    </div>

                    <div class="mt-1 text-xs leading-5 text-slate-500">
                        View and manage organization members for the active school year.
                    </div>
                </div>

                <div class="mt-4 inline-flex items-center gap-1 text-xs font-semibold text-blue-600">
                    Open Members
                    <i data-lucide="arrow-right" class="w-3 h-3"></i>
                </div>
            </a>

            {{-- OFFICERS --}}
            <a href="{{ route('org.officers.index') }}"
               class="rounded-2xl border border-slate-200 bg-gradient-to-br from-white to-slate-50 p-4 shadow-sm transition hover:-translate-y-0.5 hover:border-purple-200 hover:shadow-md">

                <div class="flex items-start justify-between gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-purple-50 text-purple-600">
                        <i data-lucide="shield-check" class="w-5 h-5"></i>
                    </div>

                    <div class="rounded-full bg-slate-100 px-2 py-1 text-[11px] font-semibold text-slate-600">
                        {{ $officersCount }}
                    </div>
                </div>

                <div class="mt-4">
                    <div class="text-sm font-semibold text-slate-900">
                        Officers
                    </div>

                    <div class="mt-1 text-xs leading-5 text-slate-500">
                        View the current officer directory and assigned organization roles.
                    </div>
                </div>

                <div class="mt-4 inline-flex items-center gap-1 text-xs font-semibold text-purple-600">
                    Open Officers
                    <i data-lucide="arrow-right" class="w-3 h-3"></i>
                </div>
            </a>

            @if($isPresident)

                {{-- PROJECT HEAD ASSIGNMENT --}}
                <a href="{{ route('org.assign-project-heads.index') }}"
                   class="rounded-2xl border border-slate-200 bg-gradient-to-br from-white to-slate-50 p-4 shadow-sm transition hover:-translate-y-0.5 hover:border-emerald-200 hover:shadow-md">

                    <div class="flex items-start justify-between gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                            <i data-lucide="user-plus" class="w-5 h-5"></i>
                        </div>

                        <div class="rounded-full bg-emerald-50 px-2 py-1 text-[11px] font-semibold text-emerald-700">
                            Manage
                        </div>
                    </div>

                    <div class="mt-4">
                        <div class="text-sm font-semibold text-slate-900">
                            Assign Project Heads
                        </div>

                        <div class="mt-1 text-xs leading-5 text-slate-500">
                            Assign responsible project heads who will handle project document preparation.
                        </div>
                    </div>

                    <div class="mt-4 inline-flex items-center gap-1 text-xs font-semibold text-emerald-600">
                        Manage Assignments
                        <i data-lucide="arrow-right" class="w-3 h-3"></i>
                    </div>
                </a>

                {{-- APPROVER ASSIGNMENT --}}
                <a href="{{ route('org.approver-assignments.edit') }}"
                   class="rounded-2xl border border-slate-200 bg-gradient-to-br from-white to-slate-50 p-4 shadow-sm transition hover:-translate-y-0.5 hover:border-indigo-200 hover:shadow-md">

                    <div class="flex items-start justify-between gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                            <i data-lucide="workflow" class="w-5 h-5"></i>
                        </div>

                        <div class="rounded-full bg-indigo-50 px-2 py-1 text-[11px] font-semibold text-indigo-700">
                            Setup
                        </div>
                    </div>

                    <div class="mt-4">
                        <div class="text-sm font-semibold text-slate-900">
                            Assign Finance Approvers
                        </div>

                        <div class="mt-1 text-xs leading-5 text-slate-500">
                            Set the Treasurer and Finance Officer roles used in approval workflows.
                        </div>
                    </div>

                    <div class="mt-4 inline-flex items-center gap-1 text-xs font-semibold text-indigo-600">
                        Open Setup
                        <i data-lucide="arrow-right" class="w-3 h-3"></i>
                    </div>
                </a>

            @endif

        </div>

    </div>

</div>

</x-app-layout>