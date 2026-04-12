<x-app-layout>

<div class="bg-slate-50 py-6">
<div class="max-w-7xl mx-auto px-4 space-y-6">

    {{-- PAGE HEADER --}}
    <div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm overflow-hidden">

        <div class="px-5 py-5 flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">

            <div class="flex items-start gap-4 min-w-0">

                <div class="w-16 h-16 rounded-2xl bg-white border border-slate-200 shadow-sm flex items-center justify-center overflow-hidden shrink-0">
                    @if($orgInfo['hasLogo'])
                        <img src="{{ $orgInfo['logoUrl'] }}" alt="Organization Logo" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-slate-100 text-slate-500 text-sm font-semibold">
                            {{ strtoupper(substr($orgInfo['acronym'] ?? 'ORG', 0, 3)) }}
                        </div>
                    @endif
                </div>

                <div class="min-w-0">
                    <div class="flex flex-wrap items-center gap-2">
                        <h1 class="text-xl font-semibold text-slate-900 leading-tight truncate">
                            {{ $orgInfo['name'] }}
                        </h1>

                        @if($orgInfo['archived_at'])
                            <span class="inline-flex items-center rounded-full border border-rose-200 bg-rose-50 px-2.5 py-1 text-[10px] font-semibold text-rose-700">
                                Archived
                            </span>
                        @endif
                    </div>

                    <div class="text-sm text-slate-500 mt-1">
                        {{ $orgInfo['acronym'] ?? '—' }}
                    </div>

                    <div class="mt-2 flex flex-wrap items-center gap-2 text-[11px] text-slate-500">
                        <span class="inline-flex items-center rounded-full border border-slate-200 bg-white px-2.5 py-1 font-medium text-slate-700">
                            School Year: {{ $selectedSy->name ?? '—' }}
                        </span>

                        @if($orgMeta['isActiveSy'] === true)
                            <span class="inline-flex items-center rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-1 font-medium text-emerald-700">
                                Active School Year
                            </span>
                        @elseif($activeSy)
                            <span class="inline-flex items-center rounded-full border border-amber-200 bg-amber-50 px-2.5 py-1 font-medium text-amber-700">
                                Active: {{ $activeSy->name }}
                            </span>
                        @endif
                    </div>
                </div>

            </div>

            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('admin.orgs_by_sy.index') }}"
                   class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 transition">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    Back to Organizations
                </a>
            </div>

        </div>

    </div>


    {{-- STATUS --}}
    @if(session('status'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 shadow-sm">
            {{ session('status') }}
        </div>
    @endif


    {{-- MAIN GRID --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        {{-- LEFT --}}
        <div class="lg:col-span-8 space-y-6">

            {{-- OVERVIEW --}}
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

                <div class="px-5 py-4 border-b border-slate-200 bg-slate-50/70">
                    <div class="text-sm font-semibold text-slate-900">
                        Organization Overview
                    </div>
                    <div class="text-xs text-slate-500">
                        Core identity and purpose of the organization
                    </div>
                </div>

                <div class="p-5 grid grid-cols-1 xl:grid-cols-2 gap-5">

                    <div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white p-4">
                        <div class="text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">
                            Mission
                        </div>
                        <div class="text-sm text-slate-800 leading-relaxed">
                            {{ $orgInfo['mission'] ?? 'No mission provided.' }}
                        </div>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white p-4">
                        <div class="text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">
                            Vision
                        </div>
                        <div class="text-sm text-slate-800 leading-relaxed">
                            {{ $orgInfo['vision'] ?? 'No vision provided.' }}
                        </div>
                    </div>

                </div>

            </div>


            {{-- MODULES --}}
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

                <div class="px-5 py-4 border-b border-slate-200 bg-slate-50/70">
                    <div class="text-sm font-semibold text-slate-900">
                        Organization Modules
                    </div>
                    <div class="text-xs text-slate-500">
                        Open administrative areas for this organization
                    </div>
                </div>

                <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-4">

                    <a href="{{ $routes['rereg'] }}"
                       class="group rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white p-4 hover:border-rose-200 hover:bg-rose-50/50 transition">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <div class="text-sm font-semibold text-slate-900">
                                    Re-Registration
                                </div>
                                <div class="text-xs text-slate-500 mt-1">
                                    Review B1–B5 submissions and registration records
                                </div>
                            </div>
                            <div class="w-9 h-9 rounded-xl border border-rose-200 bg-rose-50 text-rose-700 flex items-center justify-center shrink-0">
                                <i data-lucide="clipboard-check" class="w-4 h-4"></i>
                            </div>
                        </div>
                    </a>

                    <a href="{{ $routes['officers'] }}"
                       class="group rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white p-4 hover:border-blue-200 hover:bg-blue-50/50 transition">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <div class="text-sm font-semibold text-slate-900">
                                    Officers
                                </div>
                                <div class="text-xs text-slate-500 mt-1">
                                    Review officer roles and academic monitoring
                                </div>
                            </div>
                            <div class="w-9 h-9 rounded-xl border border-blue-200 bg-blue-50 text-blue-700 flex items-center justify-center shrink-0">
                                <i data-lucide="shield-check" class="w-4 h-4"></i>
                            </div>
                        </div>
                    </a>

                    <a href="{{ $routes['members'] }}"
                       class="group rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white p-4 hover:border-emerald-200 hover:bg-emerald-50/50 transition">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <div class="text-sm font-semibold text-slate-900">
                                    Members
                                </div>
                                <div class="text-xs text-slate-500 mt-1">
                                    View the current organization roster
                                </div>
                            </div>
                            <div class="w-9 h-9 rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-700 flex items-center justify-center shrink-0">
                                <i data-lucide="users" class="w-4 h-4"></i>
                            </div>
                        </div>
                    </a>

                    <a href="{{ $routes['projects'] }}"
                       class="group rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white p-4 hover:border-amber-200 hover:bg-amber-50/50 transition">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <div class="text-sm font-semibold text-slate-900">
                                    Projects
                                </div>
                                <div class="text-xs text-slate-500 mt-1">
                                    Track project workflows, forms, and approvals
                                </div>
                            </div>
                            <div class="w-9 h-9 rounded-xl border border-amber-200 bg-amber-50 text-amber-700 flex items-center justify-center shrink-0">
                                <i data-lucide="folder-kanban" class="w-4 h-4"></i>
                            </div>
                        </div>
                    </a>

                </div>

            </div>

        </div>


        {{-- RIGHT --}}
        <div class="lg:col-span-4 space-y-6">

            {{-- PROFILE / META --}}
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

                <div class="px-5 py-4 border-b border-slate-200 bg-slate-50/70">
                    <div class="text-sm font-semibold text-slate-900">
                        Organization Details
                    </div>
                    <div class="text-xs text-slate-500">
                        Administrative information for this school year
                    </div>
                </div>

                <div class="p-5 space-y-4">

                    <div class="rounded-xl border border-slate-200 bg-slate-50/70 px-4 py-3">
                        <div class="text-[11px] uppercase tracking-wide text-slate-500 mb-1">
                            President
                        </div>
                        <div class="text-sm font-semibold text-slate-900">
                            {{ $orgMeta['president_name'] ?? '—' }}
                        </div>
                    </div>

                    <div class="rounded-xl border border-slate-200 bg-slate-50/70 px-4 py-3">
                        <div class="text-[11px] uppercase tracking-wide text-slate-500 mb-1">
                            President Confirmation
                        </div>
                        <div class="text-sm font-semibold text-slate-900">
                            {{ $orgMeta['president_confirmed_at'] ? $orgMeta['president_confirmed_at']->format('M d, Y') : 'Not yet confirmed' }}
                        </div>
                    </div>

                    <div class="rounded-xl border border-slate-200 bg-slate-50/70 px-4 py-3">
                        <div class="text-[11px] uppercase tracking-wide text-slate-500 mb-1">
                            Cluster
                        </div>
                        <div class="text-sm font-semibold text-slate-900">
                            {{ $orgInfo['cluster_id'] ?? '—' }}
                        </div>
                    </div>

                </div>

            </div>


            {{-- SYSTEM INFO --}}
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

                <div class="px-5 py-4 border-b border-slate-200 bg-slate-50/70">
                    <div class="text-sm font-semibold text-slate-900">
                        System Info
                    </div>
                    <div class="text-xs text-slate-500">
                        Record lifecycle details
                    </div>
                </div>

                <div class="p-5 space-y-4">

                    <div class="flex items-start justify-between gap-3">
                        <div class="text-xs text-slate-500">
                            Created
                        </div>
                        <div class="text-sm font-medium text-slate-800 text-right">
                            {{ optional($orgInfo['created_at'])->format('M d, Y') ?? '—' }}
                        </div>
                    </div>

                    <div class="flex items-start justify-between gap-3">
                        <div class="text-xs text-slate-500">
                            Last Updated
                        </div>
                        <div class="text-sm font-medium text-slate-800 text-right">
                            {{ optional($orgInfo['updated_at'])->format('M d, Y') ?? '—' }}
                        </div>
                    </div>

                    @if($orgInfo['archived_at'])
                        <div class="rounded-xl border border-rose-200 bg-rose-50 px-3 py-2 text-xs font-medium text-rose-700">
                            This organization record is archived.
                        </div>
                    @endif

                </div>

            </div>


            {{-- MAJOR OFFICERS --}}
            @php
                $canEditMajor = $orgMeta['isActiveSy'];
            @endphp

            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

                <div class="px-5 py-4 border-b border-slate-200 bg-slate-50/70">
                    <div class="text-sm font-semibold text-slate-900">
                        Major Officers
                    </div>
                    <div class="text-xs text-slate-500">
                        Assign and manage key organization roles
                    </div>
                </div>

                <div class="p-5 space-y-3">

                    <a href="{{ $canEditMajor ? route('admin.orgs_by_sy.major_officers', $organization->id) : '#' }}"
                       class="w-full inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition
                              {{ $canEditMajor ? 'hover:bg-slate-50' : 'opacity-50 pointer-events-none' }}">
                        <i data-lucide="user-cog" class="w-4 h-4"></i>
                        Manage Roles
                    </a>

                    @if(!$canEditMajor)
                        <div class="rounded-xl border border-amber-200 bg-amber-50 px-3 py-2 text-xs text-amber-700">
                            Major officers can only be managed in the active school year.
                        </div>
                    @endif

                </div>

            </div>

        </div>

    </div>

</div>
</div>

</x-app-layout>