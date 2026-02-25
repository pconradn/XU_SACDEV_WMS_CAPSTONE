<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                Organizations (by School Year)
            </h2>

            @if($activeSy)
                <span class="text-xs px-2.5 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700">
                    Active SY: {{ $activeSy->name }}
                </span>
            @endif
        </div>
    </x-slot>

    <div class="max-w-6xl mx-auto px-4 py-6 space-y-4">

        @if(session('status'))
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800">
                {{ session('status') }}
            </div>
        @endif

        @if(session('error'))
            <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-800">
                {{ session('error') }}
            </div>
        @endif

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
                <form method="POST" action="{{ route('admin.orgs_by_sy.set_sy') }}" class="flex flex-col gap-2">
                    @csrf
                    <label class="text-sm font-medium text-slate-700">School Year</label>
                    <div class="flex gap-2">
                        <select name="school_year_id" class="rounded-lg border border-slate-300 px-3 py-2 text-sm">
                            <option value="">-- Select --</option>
                            @foreach($schoolYears as $sy)
                                <option value="{{ $sy->id }}" @selected(optional($selectedSy)->id === $sy->id)>
                                    {{ $sy->name }} {{ $sy->is_active ? '(Active)' : '' }}
                                </option>
                            @endforeach
                        </select>
                        <button class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                            Load Orgs
                        </button>
                    </div>
                    @error('school_year_id')
                        <div class="text-sm text-red-600">{{ $message }}</div>
                    @enderror
                </form>

                <form method="GET" action="{{ route('admin.orgs_by_sy.index') }}" class="flex gap-2">
                    <input type="text" name="q" value="{{ $q }}" placeholder="Search org name / acronym..."
                           class="w-72 rounded-lg border border-slate-300 px-3 py-2 text-sm">
                    <button class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-800 hover:bg-slate-50">
                        Search
                    </button>
                </form>
            </div>

            <div class="mt-4 text-sm text-slate-600">
                @if($selectedSy)
                    Showing organizations registered for: <span class="font-semibold text-slate-800">{{ $selectedSy->name }}</span>
                @else
                    Select a school year to list registered organizations.
                @endif
            </div>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 text-slate-600 border-b border-slate-200">
                        <tr>
                            <th class="text-left px-4 py-3">Organization</th>
                            <th class="text-left px-4 py-3">President (if linked)</th>
                            <th class="text-left px-4 py-3">Confirmed</th>
                            <th class="text-right px-4 py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($orgSys as $row)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3">
                                    <div class="font-semibold text-slate-900">
                                        {{ $row->organization->name }}
                                    </div>
                                    <div class="text-xs text-slate-500">
                                        {{ $row->organization->acronym ? $row->organization->acronym : '—' }}
                                    </div>
                                </td>

                                <td class="px-4 py-3 text-slate-700">
                                    {{ optional($row->president)->name ?? '—' }}
                                </td>

                                <td class="px-4 py-3">
                                    @if($row->president_confirmed_at)
                                        <span class="text-xs px-2.5 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700">
                                            Yes
                                        </span>
                                    @else
                                        <span class="text-xs px-2.5 py-1 rounded-full bg-amber-50 border border-amber-200 text-amber-700">
                                            No
                                        </span>
                                    @endif
                                </td>

                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('admin.orgs_by_sy.show', $row->organization_id) }}"
                                       class="inline-flex items-center rounded-lg bg-slate-900 px-3 py-2 text-xs font-semibold text-white hover:bg-slate-800">
                                        Open
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-slate-600">
                                    No registered organizations found for this school year.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-4 py-3 border-t border-slate-200">
                {{ $orgSys->links() }}
            </div>
        </div>
    </div>
</x-app-layout>