<x-app-layout>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

@include('admin.orgs_by_sy.partials._index-styles')

<div class="mx-auto max-w-6xl px-4 py-6 space-y-5">

    {{-- HEADER --}}
    <div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm p-5">

        <div class="flex items-center justify-between">

            <div>
                <h2 class="text-lg font-semibold text-slate-900">
                    Registered Organizations
                </h2>
                <p class="text-xs text-slate-500 mt-1">
                    Monitor organizations, project activity, and access their hubs.
                </p>
            </div>

            @if($activeSy)
                <span class="inline-flex items-center rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 border border-emerald-200">
                    {{ $activeSy->name }}
                </span>
            @endif

        </div>

    </div>


    {{-- FILTER --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-4">

        <form method="POST" action="{{ route('admin.orgs_by_sy.set_sy') }}">
            @csrf

            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">

                <div class="flex items-center gap-3">

                    <span class="text-[11px] font-semibold text-slate-600 uppercase tracking-wide">
                        School Year
                    </span>

                    <div class="relative">

                        <div class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-slate-400">
                            <i data-lucide="calendar" class="w-4 h-4"></i>
                        </div>

                        <select
                            name="school_year_id"
                            onchange="this.form.submit()"
                            class="h-9 min-w-[220px] pl-9 pr-3 rounded-xl border border-slate-300 bg-white text-sm text-slate-700
                                   focus:border-slate-400 focus:ring-2 focus:ring-slate-200 transition"
                        >
                            <option value="">Select school year...</option>
                            @foreach($schoolYears as $sy)
                                <option value="{{ $sy->id }}" @selected(optional($selectedSy)->id === $sy->id)>
                                    {{ $sy->name }}
                                </option>
                            @endforeach
                        </select>

                    </div>

                </div>

                <div class="text-xs text-slate-500">
                    @if($selectedSy)
                        Showing <span class="font-medium text-slate-700">{{ $selectedSy->name }}</span>
                    @else
                        No school year selected
                    @endif
                </div>

            </div>

            @error('school_year_id')
                <div class="mt-2 text-xs text-rose-600">{{ $message }}</div>
            @enderror

        </form>

    </div>


    {{-- TABLE --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

        <div class="px-5 py-4 border-b border-slate-200 flex items-center justify-between">

            <div>
                <div class="text-sm font-semibold text-slate-900">
                    Organization List
                </div>
                <div class="text-xs text-slate-500">
                    Overview of organizations and their project activity
                </div>
            </div>

            <span class="text-[10px] font-medium px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 border border-slate-200">
                {{ $orgSys->count() }}
            </span>

        </div>


        <div class="overflow-x-auto">

            <table id="orgTable" class="min-w-[950px] w-full text-sm">

                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr class="text-left text-[11px] uppercase tracking-wide text-slate-500">

                        <th class="px-5 py-3">Organization</th>
                        <th class="px-5 py-3">President</th>
                        <th class="px-5 py-3">Projects</th>
                        <th class="px-5 py-3">Pending</th>
                        <th class="px-5 py-3 text-right">Action</th>

                    </tr>
                </thead>


                <tbody class="divide-y divide-slate-100 bg-white">

                    @forelse($orgSys as $row)

                    @php
                        $projectCount = $row->organization->projects()->count();

                        $pendingCount = $row->organization->projects()
                            ->whereIn('workflow_status', ['submitted','under_review','returned'])
                            ->count();
                    @endphp

                    <tr class="hover:bg-slate-50 transition">

                        {{-- ORG --}}
                        <td class="px-5 py-4">

                            <div class="flex items-center gap-3">

                                {{-- LOGO --}}
                                @if(!empty($row->organization->logo_path))
                                    <img 
                                        src="{{ asset('storage/' . $row->organization->logo_path) }}"
                                        alt="logo"
                                        class="w-9 h-9 rounded-xl object-cover border border-slate-200 bg-white">
                                @else
                                    <div class="w-9 h-9 rounded-xl flex items-center justify-center
                                                bg-slate-100 text-slate-600 text-xs font-semibold border border-slate-200">
                                        {{ strtoupper(substr($row->organization->acronym ?? $row->organization->name, 0, 2)) }}
                                    </div>
                                @endif

                                {{-- TEXT --}}
                                <div class="flex flex-col leading-tight">

                                    <div class="font-semibold text-slate-900">
                                        {{ $row->organization->name }}
                                    </div>

                                    <div class="text-[11px] text-slate-400">
                                        {{ $row->organization->acronym ?? '—' }}
                                    </div>

                                </div>

                            </div>

                        </td>


                        {{-- PRESIDENT --}}
                        <td class="px-5 py-4 text-slate-700 text-sm">
                            {{ optional($row->president)->name ?? '—' }}
                        </td>


                        {{-- PROJECT COUNT --}}
                        <td class="px-5 py-4">

                            <span class="text-xs font-semibold px-2 py-1 rounded-full bg-blue-50 text-blue-700 border border-blue-200">
                                {{ $projectCount }}
                            </span>

                        </td>


                        {{-- PENDING --}}
                        <td class="px-5 py-4">

                            @if($pendingCount > 0)
                                <span class="text-xs font-semibold px-2 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-200">
                                    {{ $pendingCount }} pending
                                </span>
                            @else
                                <span class="text-xs text-slate-400">
                                    —
                                </span>
                            @endif

                        </td>


                        {{-- ACTION --}}
                        <td class="px-5 py-4 text-right">

                            <a
                                href="{{ route('admin.orgs_by_sy.show', $row->organization_id) }}"
                                class="inline-flex items-center gap-1 px-3 py-1.5 text-[11px] font-medium rounded-lg
                                       border border-slate-200 bg-white text-slate-700
                                       hover:bg-slate-100 transition"
                            >
                                Open
                                <i data-lucide="arrow-right" class="w-3 h-3"></i>
                            </a>

                        </td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="5" class="px-5 py-12 text-center text-sm text-slate-500">
                            No organizations found
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@include('admin.orgs_by_sy.partials._index-scripts')

</x-app-layout>