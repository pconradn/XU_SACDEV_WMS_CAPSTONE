<x-app-layout>

<div class="max-w-7xl mx-auto px-4 py-6 space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

        <div class="flex items-center gap-4">

            {{-- LOGO --}}
            <div class="w-14 h-14 rounded-xl bg-slate-100 flex items-center justify-center overflow-hidden border">
                @if($orgInfo['hasLogo'])
                    <img src="{{ $orgInfo['logoUrl'] }}" class="w-full h-full object-cover">
                @else
                    <span class="text-sm text-slate-400 font-semibold">
                        {{ strtoupper(substr($orgInfo['acronym'] ?? 'ORG', 0, 3)) }}
                    </span>
                @endif
            </div>

            <div>
                <h1 class="text-xl font-semibold text-slate-900 leading-tight">
                    {{ $orgInfo['name'] }}
                </h1>

                <div class="text-sm text-slate-500">
                    {{ $orgInfo['acronym'] ?? '—' }}
                </div>

                <div class="text-xs text-slate-500 mt-1">
                    SY:
                    <span class="font-medium text-slate-700">
                        {{ $selectedSy->name ?? '—' }}
                    </span>

                    @if($orgMeta['isActiveSy'] === false)
                        <span class="ml-2 px-2 py-0.5 text-[10px] rounded-full bg-amber-100 text-amber-700 border">
                            Active: {{ $activeSy->name }}
                        </span>
                    @endif
                </div>
            </div>

        </div>

        <a href="{{ route('admin.orgs_by_sy.index') }}"
           class="inline-flex items-center px-4 py-2 rounded-xl border border-slate-200 bg-white text-sm font-medium text-slate-700 hover:bg-slate-50">
            ← Back
        </a>

    </div>

    {{-- STATUS --}}
    @if(session('status'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
            {{ session('status') }}
        </div>
    @endif

    {{-- MAIN GRID --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- LEFT --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- MISSION / VISION --}}
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-5 space-y-4">

                <div>
                    <div class="text-xs text-slate-500 mb-1">Mission</div>
                    <div class="text-sm text-slate-800 leading-relaxed">
                        {{ $orgInfo['mission'] ?? 'No mission provided.' }}
                    </div>
                </div>

                <div>
                    <div class="text-xs text-slate-500 mb-1">Vision</div>
                    <div class="text-sm text-slate-800 leading-relaxed">
                        {{ $orgInfo['vision'] ?? 'No vision provided.' }}
                    </div>
                </div>

            </div>

            {{-- MODULES --}}
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-5">

                <div class="text-sm font-semibold text-slate-900 mb-4">
                    Organization Modules
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <a href="{{ $routes['rereg'] }}"
                       class="group p-4 rounded-xl border border-slate-200 hover:bg-slate-50 transition">

                        <div class="text-sm font-semibold text-slate-900">
                            Re-Registration
                        </div>

                        <div class="text-xs text-slate-500 mt-1">
                            Review B1–B5 submissions
                        </div>

                    </a>

                    <a href="{{ $routes['officers'] }}"
                       class="group p-4 rounded-xl border border-slate-200 hover:bg-slate-50 transition">

                        <div class="text-sm font-semibold text-slate-900">
                            Officers
                        </div>

                        <div class="text-xs text-slate-500 mt-1">
                            Academic monitoring & roles
                        </div>

                    </a>

                    <a href="{{ $routes['members'] }}"
                       class="group p-4 rounded-xl border border-slate-200 hover:bg-slate-50 transition">

                        <div class="text-sm font-semibold text-slate-900">
                            Members
                        </div>

                        <div class="text-xs text-slate-500 mt-1">
                            Organization roster
                        </div>

                    </a>

                    <a href="{{ $routes['projects'] }}"
                       class="group p-4 rounded-xl border border-slate-200 hover:bg-slate-50 transition">

                        <div class="text-sm font-semibold text-slate-900">
                            Projects
                        </div>

                        <div class="text-xs text-slate-500 mt-1">
                            Track workflows & approvals
                        </div>

                    </a>

                </div>

            </div>

        </div>

        {{-- RIGHT --}}
        <div class="space-y-6">

            {{-- META CARD --}}
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-5 space-y-4">

                <div>
                    <div class="text-xs text-slate-500">President</div>
                    <div class="text-sm font-semibold text-slate-900">
                        {{ $orgMeta['president_name'] ?? '—' }}
                    </div>
                </div>

                <div>
                    <div class="text-xs text-slate-500">Confirmed</div>
                    <div class="text-sm font-semibold text-slate-900">
                        {{ $orgMeta['president_confirmed_at']
                            ? $orgMeta['president_confirmed_at']->format('M d, Y')
                            : 'No' }}
                    </div>
                </div>

                <div>
                    <div class="text-xs text-slate-500">Cluster</div>
                    <div class="text-sm font-semibold text-slate-900">
                        {{ $orgInfo['cluster_id'] ?? '—' }}
                    </div>
                </div>

            </div>

            {{-- SYSTEM INFO --}}
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-5 space-y-3">

                <div class="text-xs text-slate-500">Created</div>
                <div class="text-sm text-slate-700">
                    {{ optional($orgInfo['created_at'])->format('M d, Y') }}
                </div>

                <div class="text-xs text-slate-500">Last Updated</div>
                <div class="text-sm text-slate-700">
                    {{ optional($orgInfo['updated_at'])->format('M d, Y') }}
                </div>

                @if($orgInfo['archived_at'])
                    <div class="mt-2 text-xs px-2 py-1 rounded bg-red-100 text-red-700">
                        Archived
                    </div>
                @endif

            </div>

            {{-- MAJOR OFFICERS --}}
            @php
                $canEditMajor = $orgMeta['isActiveSy'];
            @endphp

            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-5 space-y-3">

                <div class="text-sm font-semibold text-slate-900">
                    Major Officers
                </div>

                <a href="{{ $canEditMajor ? route('admin.orgs_by_sy.major_officers', $organization->id) : '#' }}"
                   class="block text-center px-4 py-2 rounded-lg border border-slate-200 text-sm font-medium
                          {{ $canEditMajor ? 'hover:bg-slate-50' : 'opacity-50 pointer-events-none' }}">

                    Manage Roles
                </a>

                @if(!$canEditMajor)
                    <div class="text-xs text-amber-700 bg-amber-50 border border-amber-200 rounded px-3 py-2">
                        Only editable in active SY
                    </div>
                @endif

            </div>

        </div>

    </div>

</div>

</x-app-layout>