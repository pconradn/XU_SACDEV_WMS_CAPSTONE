<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Org Portal
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            @if (session('status'))
                <div class="mb-4 p-3 rounded bg-yellow-100 text-yellow-800">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white shadow rounded p-6 mb-4">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <div class="text-sm text-gray-500">Active School Year</div>
                        <div class="text-lg font-semibold">{{ $activeSy->name }}</div>
                    </div>

                    <div class="min-w-[260px]">
                        <div class="text-sm text-gray-500 mb-1">Current Organization</div>

                        @if($memberships->count() === 0)
                            <div class="text-gray-600">No organization membership found.</div>
                        @else
                            <form method="POST" action="{{ route('org.switch') }}" class="flex gap-2">
                                @csrf
                                <select name="organization_id" class="w-full border rounded p-2">
                                    @foreach($memberships->unique('organization_id') as $m)
                                        <option value="{{ $m->organization_id }}"
                                            @selected($currentOrg && $currentOrg->id === $m->organization_id)>
                                            {{ $m->organization->name }} {{ $m->organization->acronym ? '(' . $m->organization->acronym . ')' : '' }}
                                        </option>
                                    @endforeach
                                </select>

                                <button class="px-4 py-2 bg-gray-800 !text-white rounded">
                                    Switch
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div class="bg-white shadow rounded p-6">
                    <div class="text-sm text-gray-500">Selected Organization</div>
                    <div class="text-xl font-semibold">
                        {{ $currentOrg?->name ?? '—' }}
                    </div>
                    <div class="text-sm text-gray-600 mt-2">
                        Acronym: {{ $currentOrg?->acronym ?? '—' }}
                    </div>
                </div>

                <div class="bg-white shadow rounded p-6">
                    <div class="text-sm text-gray-500">Your Access (Active SY)</div>

                    <div class="mt-2">
                        <div class="text-sm text-gray-600">Roles:</div>
                        <div class="mt-1 flex flex-wrap gap-2">
                            @forelse($roles as $r)
                                <span class="px-2 py-1 rounded bg-emerald-100 text-emerald-800 text-sm">
                                    {{ ucfirst(str_replace('_', ' ', $r)) }}
                                </span>
                            @empty
                                <span class="text-gray-500">No role found for selected org.</span>
                            @endforelse
                        </div>
                    </div>

                    <div class="mt-4 text-sm text-gray-600">
                        Project head assignments in this org: <span class="font-semibold">{{ $projectHeadCount }}</span>
                    </div>
                </div>

            </div>

            {{-- President-only quick actions placeholders (we’ll add in later steps) --}}
            <div class="bg-white shadow rounded p-6 mt-4">
                <div class="text-sm text-gray-500 mb-2">Next Actions (Sprint 1)</div>
                @if($roles->contains('president') && Route::has('org.encode-sy.show'))
                    <a href="{{ route('org.encode-sy.show') }}"
                    class="inline-flex items-center px-3 py-2 bg-blue-600 !text-white rounded text-sm">
                        Select School Year to Encode
                    </a>
                @endif

                <ul class="list-disc pl-5 text-gray-700 text-sm space-y-1">
                    @if($roles->contains('president'))
                        <div class="flex flex-wrap gap-2 mt-2">
                            <a href="{{ route('org.encode-sy.show') }}" class="px-3 py-2 bg-blue-600 !text-white rounded text-sm">
                                Select SY to Encode
                            </a>
                            <a href="{{ route('org.officers.index') }}" class="px-3 py-2 bg-gray-800 !text-white rounded text-sm">
                                Officers
                            </a>
                            <a href="{{ route('org.projects.index') }}" class="px-3 py-2 bg-gray-800 !text-white rounded text-sm">
                                Projects
                            </a>
                            <a href="{{ route('org.assign-roles.edit') }}" class="px-3 py-2 bg-gray-800 !text-white rounded text-sm">
                                Assign Treasurer/Moderator
                            </a>

                            <a href="{{ route('org.assign-project-heads.index') }}" class="px-3 py-2 bg-gray-800 !text-white rounded text-sm">
                                Assign Project Heads
                            </a>
                        </div>

                    @endif
                    <li>Assign Treasurer/Moderator (Step 6)</li>
                    <li>Assign Project Heads (Step 6)</li>
                </ul>
            </div>

        </div>
    </div>
</x-app-layout>
