<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                    {{ $organization->name }}
                    <span class="text-slate-400 font-normal">— Org Profile</span>
                </h2>

                <div class="text-sm text-slate-600 mt-1">
                    SY Context:
                    <span class="font-semibold text-slate-800">{{ $selectedSy->name ?? '—' }}</span>

                    @if($activeSy && $selectedSy && (int)$activeSy->id !== (int)$selectedSy->id)
                        <span class="ml-2 text-xs px-2 py-1 rounded-full bg-slate-100 border border-slate-200 text-slate-700">
                            Active SY is {{ $activeSy->name }}
                        </span>
                    @endif
                </div>
            </div>

            <a href="{{ route('admin.orgs_by_sy.index') }}"
               class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-800 hover:bg-slate-50">
                ← Back
            </a>
        </div>
    </x-slot>

    <div class="max-w-6xl mx-auto px-4 py-6 space-y-4">

        @if(session('status'))
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800">
                {{ session('status') }}
            </div>
        @endif

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <div class="text-xs text-slate-500">Acronym</div>
                    <div class="font-semibold text-slate-900">{{ $organization->acronym ?? '—' }}</div>
                </div>

                <div>
                    <div class="text-xs text-slate-500">President linked (OrganizationSchoolYear)</div>
                    <div class="font-semibold text-slate-900">
                        {{ optional($orgSy->president)->name ?? '—' }}
                    </div>
                </div>

                <div>
                    <div class="text-xs text-slate-500">President confirmed</div>
                    <div class="font-semibold text-slate-900">
                        {{ $orgSy->president_confirmed_at ? $orgSy->president_confirmed_at->format('M d, Y') : 'No' }}
                    </div>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <h3 class="text-base font-semibold text-slate-900">Actions (SY scoped)</h3>

            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">

                {{-- Re-Registration Hub (needs selected SY in session used by your rereg hub) --}}
                <a href="{{ route('rereg.hub', $organization->id) }}"
                   class="rounded-xl border border-slate-200 bg-white p-4 hover:bg-slate-50">
                    <div class="font-semibold text-slate-900">Re-Registration Hub</div>
                    <div class="text-sm text-slate-600 mt-1">Review B1–B5 for this org + SY</div>
                </a>

                {{-- Operational Officers (placeholder route name) --}}
                <a href="#"
                   class="rounded-xl border border-slate-200 bg-white p-4 hover:bg-slate-50">
                    <div class="font-semibold text-slate-900">Officers (Operational)</div>
                    <div class="text-sm text-slate-600 mt-1">View officers for this SY</div>
                    <div class="text-xs text-slate-500 mt-2">Wire route later</div>
                </a>

                {{-- Operational Projects (placeholder route name) --}}
                <a href="#"
                   class="rounded-xl border border-slate-200 bg-white p-4 hover:bg-slate-50">
                    <div class="font-semibold text-slate-900">Projects (Operational)</div>
                    <div class="text-sm text-slate-600 mt-1">View projects for this SY</div>
                    <div class="text-xs text-slate-500 mt-2">Wire route later</div>
                </a>

                {{-- Major Officer Roles (Active SY only) --}}
                @php
                    $canEditMajor = $activeSy && $selectedSy && (int)$activeSy->id === (int)$selectedSy->id;
                @endphp

                <a href="{{ $canEditMajor ? route('admin.orgs_by_sy.major_officers', $organization->id) : '#' }}"
                   class="rounded-xl border border-slate-200 bg-white p-4 {{ $canEditMajor ? 'hover:bg-slate-50' : 'opacity-50 pointer-events-none' }}">
                    <div class="font-semibold text-slate-900">Major Officer Roles</div>
                    <div class="text-sm text-slate-600 mt-1">
                        Replace president/VP/treasurer/auditor (Active SY only)
                    </div>
                </a>
            </div>

            @if(!$canEditMajor)
                <div class="mt-3 text-sm text-amber-700 bg-amber-50 border border-amber-200 rounded-lg px-4 py-3">
                    Major Officer Roles can only be edited when the selected SY is the Active SY.
                </div>
            @endif
        </div>
    </div>
</x-app-layout>