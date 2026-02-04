<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Org Portal
            </h2>
            <p class="text-sm text-gray-500">
                Manage your organization access and workflows per school year.
            </p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-4">

            {{-- Flash status --}}
            @if (session('status'))
                <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-amber-900">
                    <div class="text-sm">{{ session('status') }}</div>
                </div>
            @endif

            {{-- Context selector card --}}
            <div class="bg-white shadow-sm rounded-xl border border-slate-200 p-6">
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">

                    {{-- School year --}}
                    <div>
                        <div class="text-sm text-gray-500">Selected School Year</div>
                        <div class="text-lg font-semibold text-slate-900">
                            {{ $selectedSy?->name ?? '—' }}
                        </div>

                        <div class="mt-1">
                            <a href="{{ route('org.encode-sy.show') }}"
                               class="text-xs font-semibold text-blue-600 hover:underline">
                                Change school year
                            </a>
                        </div>

                        @if(isset($activeSy) && $activeSy && $selectedSy && (int)$activeSy->id !== (int)$selectedSy->id)
                            <div class="mt-2 text-xs text-slate-600">
                                System Active SY:
                                <span class="font-semibold">{{ $activeSy->name }}</span>
                                <span class="ml-2 inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-[11px] font-semibold text-slate-700">
                                    Viewing another SY
                                </span>
                            </div>
                        @endif
                    </div>

                    {{-- Organization switcher --}}
                    <div class="min-w-[280px]">
                        <div class="text-sm text-gray-500 mb-1">Current Organization</div>

                        @if($memberships->count() === 0)
                            <div class="rounded-lg border border-slate-200 bg-slate-50 p-3 text-sm text-slate-700">
                                No organization membership found for the selected school year.
                                <div class="mt-1 text-xs text-slate-500">
                                    Try changing the school year or ask an admin to assign you to an org for this SY.
                                </div>
                            </div>
                        @else
                            <form method="POST" action="{{ route('org.switch') }}" class="flex gap-2">
                                @csrf
                                <select name="organization_id"
                                        class="w-full border border-slate-300 rounded-lg p-2 text-sm focus:outline-none focus:border-slate-400">
                                    @foreach($memberships->unique('organization_id') as $m)
                                        <option value="{{ $m->organization_id }}"
                                            @selected($currentOrg && $currentOrg->id === $m->organization_id)>
                                            {{ $m->organization->name }}
                                            {{ $m->organization->acronym ? '(' . $m->organization->acronym . ')' : '' }}
                                        </option>
                                    @endforeach
                                </select>

                                <button class="px-4 py-2 rounded-lg bg-slate-900 text-white text-sm font-semibold hover:bg-slate-800">
                                    Switch
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Main cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                {{-- Selected organization --}}
                <div class="bg-white shadow-sm rounded-xl border border-slate-200 p-6">
                    <div class="text-sm text-gray-500">Selected Organization</div>
                    <div class="text-xl font-semibold text-slate-900 mt-1">
                        {{ $currentOrg?->name ?? '—' }}
                    </div>
                    <div class="text-sm text-slate-600 mt-2">
                        Acronym: {{ $currentOrg?->acronym ?? '—' }}
                    </div>

                    @if(!$currentOrg)
                        <div class="mt-3 rounded-lg border border-slate-200 bg-slate-50 p-3 text-sm text-slate-700">
                            Select an organization to see available actions and roles.
                        </div>
                    @endif
                </div>

                {{-- Access / roles --}}
                <div class="bg-white shadow-sm rounded-xl border border-slate-200 p-6">
                    <div class="text-sm text-gray-500">Your Access (Selected SY)</div>

                    <div class="mt-3">
                        <div class="text-sm text-slate-600">Roles:</div>
                        <div class="mt-2 flex flex-wrap gap-2">
                            @forelse($roles as $r)
                                <span class="px-2 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-semibold">
                                    {{ ucfirst(str_replace('_', ' ', $r)) }}
                                </span>
                            @empty
                                <span class="text-sm text-slate-500">No role found for selected org.</span>
                            @endforelse
                        </div>
                    </div>

                    <div class="mt-4 text-sm text-slate-600">
                        Project head assignments in this org:
                        <span class="font-semibold text-slate-900">{{ $projectHeadCount }}</span>
                    </div>
                </div>

            </div>

            {{-- Quick actions --}}
            <div class="bg-white shadow-sm rounded-xl border border-slate-200 p-6">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <div class="text-sm font-semibold text-slate-900">Quick Actions</div>
                        <div class="text-sm text-slate-600 mt-1">
                            Shortcuts based on your role for the selected school year.
                        </div>
                    </div>
                </div>

                @if(!$currentOrg)
                    <div class="mt-3 text-sm text-slate-600">
                        Select an organization first to unlock actions.
                    </div>
                @else
                    <div class="mt-4 flex flex-wrap gap-2">

                        {{-- Re-registration is role-based via routes anyway --}}
                        <a href="{{ route('org.rereg.index') }}"
                           class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-800 hover:bg-slate-50">
                            Re-registration Hub
                        </a>

                        {{-- Show management links if President role exists --}}
                        @if($roles->contains('president'))
                            <a href="{{ route('org.officers.index') }}"
                               class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-800 hover:bg-slate-50">
                                Officers
                            </a>

                            <a href="{{ route('org.projects.index') }}"
                               class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-800 hover:bg-slate-50">
                                Projects
                            </a>

                            <a href="{{ route('org.assign-roles.edit') }}"
                               class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-800 hover:bg-slate-50">
                                Assign Roles
                            </a>

                            <a href="{{ route('org.assign-project-heads.index') }}"
                               class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-800 hover:bg-slate-50">
                                Assign Project Heads
                            </a>
                        @endif
                    </div>

                    {{-- small hint when user is not president --}}
                    @if(!$roles->contains('president'))
                        <div class="mt-3 text-xs text-slate-500">
                            Some management actions (Officers/Projects/Assignments) only appear for the President role.
                        </div>
                    @endif
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
