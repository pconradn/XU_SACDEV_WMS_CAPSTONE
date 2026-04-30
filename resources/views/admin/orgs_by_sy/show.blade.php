<x-app-layout>

@php
    $orgName = $orgInfo['name'] ?? $organization->name;
    $orgAcronym = $orgInfo['acronym'] ?: 'Organization';
    $mission = $orgInfo['mission'] ?? null;
    $vision = $orgInfo['vision'] ?? null;
    $logoUrl = $orgInfo['logoUrl'] ?? null;
    $clusterName = $orgInfo['cluster_name'] ?? null;

    $presidentName = $orgMeta['president_name'] ?? null;
    $moderatorName = $orgMeta['moderator_name'] ?? null;
    $isActiveSy = $orgMeta['isActiveSy'] ?? false;
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

    <nav class="text-xs text-slate-500">
        <ol class="flex items-center gap-1.5">
            <li>
                <a href="{{ route('admin.orgs_by_sy.index') }}" class="font-medium text-slate-600 hover:text-slate-900 transition">
                    Organizations by School Year
                </a>
            </li>

            <li class="text-slate-300">/</li>
            <li class="font-medium text-slate-700 truncate max-w-[180px] sm:max-w-none">
                {{ $orgAcronym }}
            </li>

            <li class="text-slate-300">/</li>
            <li class="text-slate-400">
                {{ $selectedSy?->name ?? 'Selected School Year' }}
            </li>
        </ol>
    </nav>

    <div class="rounded-2xl border border-slate-200 bg-gradient-to-br from-slate-50 via-white to-indigo-50 shadow-sm overflow-hidden">

        <div class="p-6 flex flex-col lg:flex-row gap-6 lg:items-start">

            <div class="shrink-0">
                @if($logoUrl)
                    <img src="{{ $logoUrl }}"
                         class="w-24 h-24 rounded-2xl object-cover border border-slate-200 shadow-sm bg-white">
                @else
                    <div class="w-24 h-24 rounded-2xl bg-slate-100 border border-slate-200 flex flex-col items-center justify-center text-slate-400 shadow-sm">
                        <i data-lucide="image" class="w-6 h-6"></i>
                        <span class="text-[10px] mt-1">No Logo</span>
                    </div>
                @endif
            </div>

            <div class="flex-1 min-w-0 space-y-5">

                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2 mb-2">
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-indigo-100 px-2.5 py-1 text-[11px] font-semibold text-indigo-700">
                                <i data-lucide="shield-check" class="w-3 h-3"></i>
                                Admin View
                            </span>

                            @if($isActiveSy)
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-2.5 py-1 text-[11px] font-semibold text-emerald-700">
                                    <i data-lucide="check-circle-2" class="w-3 h-3"></i>
                                    Active SY
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-2.5 py-1 text-[11px] font-semibold text-slate-600">
                                    <i data-lucide="archive" class="w-3 h-3"></i>
                                    Historical SY
                                </span>
                            @endif
                        </div>

                        <h1 class="text-2xl font-semibold text-slate-900 leading-tight">
                            {{ $orgName }}
                        </h1>

                        <div class="mt-2 flex flex-wrap items-center gap-2">
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-white border border-slate-200 px-2.5 py-1 text-[11px] font-semibold text-slate-600 shadow-sm">
                                <i data-lucide="badge" class="w-3 h-3"></i>
                                {{ $orgAcronym }}
                            </span>

                            <span class="inline-flex items-center gap-1.5 rounded-full bg-white border border-slate-200 px-2.5 py-1 text-[11px] font-semibold text-slate-600 shadow-sm">
                                <i data-lucide="calendar-range" class="w-3 h-3"></i>
                                {{ $selectedSy?->name ?? 'No School Year' }}
                            </span>

                            @if($clusterName)
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-white border border-slate-200 px-2.5 py-1 text-[11px] font-semibold text-slate-600 shadow-sm">
                                    <i data-lucide="network" class="w-3 h-3"></i>
                                    {{ $clusterName }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-2 sm:min-w-[300px]">
                        <a href="{{ $routes['members'] }}"
                           class="rounded-xl border border-blue-200 bg-blue-50 px-3 py-2 text-center shadow-sm transition hover:bg-blue-100">
                            <div class="text-lg font-semibold text-blue-800">
                                {{ $membersCount ?? 0 }}
                            </div>
                            <div class="text-[10px] uppercase tracking-wide text-blue-600">
                                Members
                            </div>
                        </a>

                        <a href="{{ $routes['officers'] }}"
                           class="rounded-xl border border-violet-200 bg-violet-50 px-3 py-2 text-center shadow-sm transition hover:bg-violet-100">
                            <div class="text-lg font-semibold text-violet-800">
                                {{ $officersCount ?? 0 }}
                            </div>
                            <div class="text-[10px] uppercase tracking-wide text-violet-600">
                                Officers
                            </div>
                        </a>

                        <a href="{{ $routes['projects'] }}"
                           class="rounded-xl border border-indigo-200 bg-indigo-50 px-3 py-2 text-center shadow-sm transition hover:bg-indigo-100">
                            <div class="text-lg font-semibold text-indigo-800">
                                {{ $projectsCount ?? 0 }}
                            </div>
                            <div class="text-[10px] uppercase tracking-wide text-indigo-600">
                                Projects
                            </div>
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

                    <div class="rounded-2xl border border-slate-200 bg-white/80 p-4 shadow-sm">
                        <div class="flex items-center gap-2 text-xs font-semibold text-slate-700 uppercase tracking-wide">
                            <i data-lucide="user-round-check" class="w-4 h-4 text-indigo-600"></i>
                            President
                        </div>

                        <div class="mt-2 text-sm text-slate-700 leading-relaxed">
                            {{ $presidentName ?: 'No president assigned.' }}
                        </div>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-white/80 p-4 shadow-sm">
                        <div class="flex items-center gap-2 text-xs font-semibold text-slate-700 uppercase tracking-wide">
                            <i data-lucide="user-cog" class="w-4 h-4 text-violet-600"></i>
                            Moderator
                        </div>

                        <div class="mt-2 text-sm text-slate-700 leading-relaxed">
                            {{ $moderatorName ?: 'No moderator assigned.' }}
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </div>

    <a href="{{ $routes['projects'] }}"
       class="group block rounded-2xl border border-indigo-200 bg-gradient-to-br from-indigo-50 via-white to-blue-50 p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-indigo-300 hover:shadow-md">

        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

            <div class="flex items-start gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-indigo-100 text-indigo-700 ring-1 ring-indigo-200">
                    <i data-lucide="folder-kanban" class="w-6 h-6"></i>
                </div>

                <div>
                    <div class="flex flex-wrap items-center gap-2">
                        <h2 class="text-base font-semibold text-slate-900">
                            Projects Module
                        </h2>

                        <span class="rounded-full bg-indigo-100 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-indigo-700">
                            Main Workflow
                        </span>

                        <span class="rounded-full bg-blue-100 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-blue-700">
                            Admin Monitoring
                        </span>
                    </div>

                    <p class="mt-1 text-sm leading-6 text-slate-600 max-w-3xl">
                        Monitor organization projects, review project documents, track approval progress, check submission requirements, and supervise the workflow from planning to completion.
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3 md:shrink-0">
                <div class="hidden sm:block rounded-xl border border-indigo-200 bg-white/80 px-3 py-2 text-center">
                    <div class="text-lg font-semibold text-indigo-800">
                        {{ $projectsCount ?? 0 }}
                    </div>
                    <div class="text-[10px] uppercase tracking-wide text-indigo-600">
                        Projects
                    </div>
                </div>

                <div class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2 text-xs font-semibold text-white shadow-sm transition group-hover:bg-indigo-700">
                    Open Projects
                    <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                </div>
            </div>

        </div>
    </a>

    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

        <div class="border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white px-5 py-4">
            <div class="flex items-center gap-2 text-sm font-semibold text-slate-900">
                <i data-lucide="layout-dashboard" class="w-4 h-4 text-slate-500"></i>
                Admin Organization Modules
            </div>
            <div class="mt-1 text-xs text-slate-500">
                Access registration records, organization roles, members, and project workflow monitoring.
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 p-5">

            <a href="{{ $routes['rereg'] }}"
               class="rounded-2xl border border-slate-200 bg-gradient-to-br from-white to-slate-50 p-4 shadow-sm transition hover:-translate-y-0.5 hover:border-emerald-200 hover:shadow-md">

                <div class="flex items-start justify-between gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                        <i data-lucide="clipboard-check" class="w-5 h-5"></i>
                    </div>

                    <div class="rounded-full bg-emerald-50 px-2 py-1 text-[11px] font-semibold text-emerald-700">
                        Review
                    </div>
                </div>

                <div class="mt-4">
                    <div class="text-sm font-semibold text-slate-900">
                        Re-Registration
                    </div>

                    <div class="mt-1 text-xs leading-5 text-slate-500">
                        Review school year registration requirements and organization activation status.
                    </div>
                </div>

                <div class="mt-4 inline-flex items-center gap-1 text-xs font-semibold text-emerald-600">
                    Open Hub
                    <i data-lucide="arrow-right" class="w-3 h-3"></i>
                </div>
            </a>

            <a href="{{ $routes['members'] }}"
               class="rounded-2xl border border-slate-200 bg-gradient-to-br from-white to-slate-50 p-4 shadow-sm transition hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-md">

                <div class="flex items-start justify-between gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                        <i data-lucide="users" class="w-5 h-5"></i>
                    </div>

                    <div class="rounded-full bg-blue-50 px-2 py-1 text-[11px] font-semibold text-blue-700">
                        {{ $membersCount ?? 0 }}
                    </div>
                </div>

                <div class="mt-4">
                    <div class="text-sm font-semibold text-slate-900">
                        Members
                    </div>

                    <div class="mt-1 text-xs leading-5 text-slate-500">
                        View organization members registered under the selected school year.
                    </div>
                </div>

                <div class="mt-4 inline-flex items-center gap-1 text-xs font-semibold text-blue-600">
                    Open Members
                    <i data-lucide="arrow-right" class="w-3 h-3"></i>
                </div>
            </a>

            <a href="{{ $routes['officers'] }}"
               class="rounded-2xl border border-slate-200 bg-gradient-to-br from-white to-slate-50 p-4 shadow-sm transition hover:-translate-y-0.5 hover:border-violet-200 hover:shadow-md">

                <div class="flex items-start justify-between gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-violet-50 text-violet-600">
                        <i data-lucide="shield-check" class="w-5 h-5"></i>
                    </div>

                    <div class="rounded-full bg-violet-50 px-2 py-1 text-[11px] font-semibold text-violet-700">
                        {{ $officersCount ?? 0 }}
                    </div>
                </div>

                <div class="mt-4">
                    <div class="text-sm font-semibold text-slate-900">
                        Officers
                    </div>

                    <div class="mt-1 text-xs leading-5 text-slate-500">
                        View officer records, assigned roles, and leadership information.
                    </div>
                </div>

                <div class="mt-4 inline-flex items-center gap-1 text-xs font-semibold text-violet-600">
                    Open Officers
                    <i data-lucide="arrow-right" class="w-3 h-3"></i>
                </div>
            </a>

            @php
                $canEditMajor = $orgMeta['isActiveSy'];
            @endphp

            <a href="{{ $canEditMajor ? route('admin.orgs_by_sy.major_officers', $organization->id) : '#' }}"
            class="rounded-2xl border border-slate-200 bg-gradient-to-br from-white to-slate-50 p-4 shadow-sm transition hover:-translate-y-0.5 hover:border-indigo-200 hover:shadow-md {{ !$canEditMajor ? 'opacity-50 pointer-events-none' : '' }}">

                <div class="flex items-start justify-between gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                        <i data-lucide="user-cog" class="w-5 h-5"></i>
                    </div>

                    <div class="rounded-full bg-indigo-50 px-2 py-1 text-[11px] font-semibold text-indigo-700">
                        Roles
                    </div>
                </div>

                <div class="mt-4">
                    <div class="text-sm font-semibold text-slate-900">
                        System Approver Roles
                    </div>

                    <div class="mt-1 text-xs leading-5 text-slate-500">
                        Assign system roles used for approval routing, such as Treasurer and Finance Officer.
                    </div>
                </div>

                <div class="mt-4 inline-flex items-center gap-1 text-xs font-semibold text-indigo-600">
                    Manage Roles
                    <i data-lucide="arrow-right" class="w-3 h-3"></i>
                </div>
            </a>

            @if(!$canEditMajor)
                <div class="rounded-xl border border-amber-200 bg-amber-50 px-3 py-2 text-xs text-amber-700">
                    System approver roles can only be managed in the active school year.
                </div>
            @endif

        </div>

    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-start gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100 text-slate-600">
                    <i data-lucide="target" class="w-5 h-5"></i>
                </div>

                <div class="min-w-0">
                    <div class="text-sm font-semibold text-slate-900">
                        Mission
                    </div>

                    <div class="mt-1 text-sm leading-6 text-slate-600 whitespace-pre-line">
                        {!! $mission ? nl2br(e($mission)) : 'No mission provided.' !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-start gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100 text-slate-600">
                    <i data-lucide="telescope" class="w-5 h-5"></i>
                </div>

                <div class="min-w-0">
                    <div class="text-sm font-semibold text-slate-900">
                        Vision
                    </div>

                    <div class="mt-1 text-sm leading-6 text-slate-600 whitespace-pre-line">
                        {!! $vision ? nl2br(e($vision)) : 'No vision provided.' !!}
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

</x-app-layout>