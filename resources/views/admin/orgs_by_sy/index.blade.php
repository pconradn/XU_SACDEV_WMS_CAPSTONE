<x-app-layout>

    {{-- JS --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    @include('admin.orgs_by_sy.partials._index-styles')

    <div class="mx-auto max-w-6xl space-y-6 px-4 py-6">

        {{-- ================= HEADER ================= --}}
        <div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm p-5">

            <div class="flex items-center justify-between">

                <div>
                    <h2 class="text-lg font-semibold text-slate-900">
                        Organizations by School Year
                    </h2>

                    <p class="text-xs text-slate-500 mt-1">
                        View organization registration status and access their project hubs.
                    </p>
                </div>

                @if($activeSy)
                    <span class="inline-flex items-center rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 border border-emerald-200">
                        Active: {{ $activeSy->name }}
                    </span>
                @endif

            </div>

        </div>


        {{-- ================= FILTER CARD ================= --}}
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-5">

            <form method="POST" action="{{ route('admin.orgs_by_sy.set_sy') }}">
                @csrf

                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                    {{-- LEFT --}}
                    <div class="flex items-center gap-3">

                        <span class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                            School Year
                        </span>

                        <div class="relative">

                            <div class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-slate-400">
                                <i data-lucide="calendar" class="w-4 h-4"></i>
                            </div>

                            <select
                                name="school_year_id"
                                onchange="this.form.submit()"
                                class="h-10 min-w-[220px] pl-9 pr-3 rounded-xl border border-slate-300 bg-white text-sm text-slate-700
                                    focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-200 transition"
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

                    {{-- RIGHT --}}
                    <div class="text-xs text-slate-500">

                        @if($selectedSy)
                            Showing:
                            <span class="font-medium text-slate-700">
                                {{ $selectedSy->name }}
                            </span>
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


        {{-- ================= TABLE ================= --}}
        <div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm">

            <div class="px-5 py-4 border-b border-slate-200">
                <div class="text-sm font-semibold text-slate-800">
                    Organization List
                </div>
                <div class="text-xs text-slate-500">
                    Review registration and access organization profiles.
                </div>
            </div>

            <div class="overflow-x-auto">

                <table id="orgTable" class="min-w-[850px] w-full text-sm">

                    {{-- HEADER --}}
                    <thead class="bg-white border-b border-slate-200">
                        <tr class="text-left text-[11px] uppercase tracking-wide text-slate-500">
                            <th class="px-5 py-4">Organization</th>
                            <th class="px-5 py-4">President</th>
                            <th class="px-5 py-4">Status</th>
                            <th class="px-5 py-4 text-right">Action</th>
                        </tr>
                    </thead>

                    {{-- BODY --}}
                    <tbody class="divide-y divide-slate-200 bg-white">

                        @forelse($orgSys as $row)

                        <tr class="hover:bg-slate-50 transition">

                            {{-- ORG --}}
                            <td class="px-5 py-5">

                                <div class="flex flex-col gap-1">

                                    <div class="font-semibold text-slate-900">
                                        {{ $row->organization->name }}
                                    </div>

                                    <div class="text-[11px] text-slate-400">
                                        {{ $row->organization->acronym ?? '—' }}
                                    </div>

                                </div>

                            </td>

                            {{-- PRESIDENT --}}
                            <td class="px-5 py-5 text-slate-700 text-sm">
                                {{ optional($row->president)->name ?? '—' }}
                            </td>

                            {{-- STATUS --}}
                            <td class="px-5 py-5">

                                @if($row->president_confirmed_at)
                                    <span class="inline-flex items-center gap-1 text-xs font-semibold px-2 py-1 rounded-md bg-emerald-50 text-emerald-700">
                                        ● Confirmed
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-xs font-semibold px-2 py-1 rounded-md bg-amber-50 text-amber-700">
                                        ● Pending
                                    </span>
                                @endif

                            </td>

                            {{-- ACTION --}}
                            <td class="px-5 py-5 text-right">

                                <a
                                    href="{{ route('admin.orgs_by_sy.show', $row->organization_id) }}"
                                    class="inline-flex items-center rounded-lg border border-blue-200 bg-blue-50 px-3 py-2 text-xs font-semibold text-blue-700 hover:bg-blue-100 transition"
                                >
                                    Open →
                                </a>

                            </td>

                        </tr>

                        @empty

                        <tr>
                            <td colspan="4"
                                class="px-5 py-12 text-center text-sm text-slate-500">
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