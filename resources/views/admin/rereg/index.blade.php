<x-app-layout>

    {{-- DataTables --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    @include('admin.orgs_by_sy.partials._index-styles')

    <div class="mx-auto max-w-6xl space-y-5 px-4 py-6">

        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

            {{-- TOP: TITLE + CONTEXT --}}
            <div class="px-6 py-6 border-b border-slate-200 flex flex-col gap-5">

                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

                    {{-- LEFT --}}
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold tracking-tight text-slate-900">
                            Select Organization to Review
                        </h1>
                        <p class="mt-1 text-sm text-slate-500 max-w-2xl">
                            Monitor organization submissions, prioritize pending tasks, and manage registration status per school year.
                        </p>
                    </div>

                    {{-- RIGHT BADGES --}}
                    <div class="flex items-center gap-2 flex-wrap">
                        {{-- RIGHT: CURRENT SELECTION --}}
                        <div class="min-w-[240px] rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">

                            <div class="text-[11px] font-semibold uppercase tracking-[0.12em] text-slate-500">
                                Current Context
                            </div>

                            <div class="mt-1 text-sm font-semibold text-slate-900">
                                {{ $selectedSy?->name ?? 'No school year selected' }}
                            </div>

                            <div class="mt-1 text-xs text-slate-500">
                                {{ $selectedSy
                                    ? 'Data is filtered to this school year.'
                                    : 'Select a school year to begin reviewing.'
                                }}
                            </div>

                        </div>

                    </div>

                </div>

                {{-- SCHOOL YEAR SELECTOR --}}
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

                    {{-- LEFT: SY BUTTONS --}}
                    <div class="flex flex-wrap items-center gap-2">

                        @foreach($schoolYears as $sy)
                            @php
                                $isSelected = $encodeSyId && (int)$encodeSyId === (int)$sy->id;
                                $count = (int)($syBadges[$sy->id] ?? 0);
                            @endphp

                            <form method="POST" action="{{ route('rereg.setSy') }}">
                                @csrf
                                <input type="hidden" name="encode_school_year_id" value="{{ $sy->id }}">

                                <button type="submit"
                                    class="inline-flex items-center gap-2 rounded-full px-3 py-1.5 text-xs font-semibold transition
                                    {{ $isSelected
                                        ? 'bg-slate-900 text-white shadow-sm'
                                        : 'bg-slate-100 text-slate-700 hover:bg-slate-200'
                                    }}">
                                    {{ $sy->name }}

                                    @if($count > 0)
                                        <span class="rounded-full bg-red-500 px-1.5 py-0.5 text-[10px] text-white">
                                            {{ $count }}
                                        </span>
                                    @endif
                                </button>
                            </form>
                        @endforeach

                        {{-- MORE --}}
                        <button type="button"
                            onclick="document.getElementById('syModal').classList.remove('hidden')"
                            class="inline-flex items-center rounded-full border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-blue-600 hover:bg-slate-50 hover:text-blue-800">
                            More
                        </button>

                    </div>



                </div>

            </div>

            {{-- BOTTOM: SUMMARY --}}
            @if($encodeSyId)
                <div class="px-6 py-5 bg-slate-50/50">
                    @include('admin.rereg.partials._summary-cards')
                </div>
            @endif

        </div>


        {{-- TABLE --}}
        @if(!$encodeSyId)

            <div class="org-page-card px-6 py-6 text-sm text-slate-600 text-center">
                Please select a school year to continue.
            </div>

        @else

            <div class="org-page-card overflow-hidden">

                <div class="overflow-x-auto">
                    <table id="reregTable" class="min-w-full text-sm">
                        <thead>
                            <tr>
                                <th class="px-5 py-4 text-left">Organization</th>
                                <th class="px-5 py-4 text-left">Status</th>
                                <th class="px-5 py-4 text-center">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                        @forelse($organizations as $org)

                            @php
                                $data = $orgData[$org->id] ?? [
                                    'pending' => (int)($orgBadges[$org->id] ?? 0),
                                    'is_ready' => in_array((int)$org->id, $readyOrgIds ?? [], true),
                                    'is_registered' => in_array((int)$org->id, $activatedOrgIds ?? [], true),
                                ];

                                $pending = $data['pending'];
                                $isReady = $data['is_ready'];
                                $isActivated = $data['is_registered'];

                                $rowColor = $pending > 0
                                    ? 'bg-red-50/40 hover:bg-red-50'
                                    : ($isReady ? 'bg-emerald-50/30 hover:bg-emerald-50' : 'hover:bg-slate-50');
                            @endphp

                            <tr
                                onclick="window.location='{{ route('rereg.hub', $org->id) }}'"
                                class="cursor-pointer transition {{ $rowColor }}">

                                {{-- ORG --}}
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">

                                        {{-- LOGO --}}
                                        <div class="w-9 h-9 rounded-lg bg-slate-100 overflow-hidden flex items-center justify-center text-xs text-slate-400">
                                            @if($org->logo_path)
                                                <img src="{{ asset('storage/' . $org->logo_path) }}"
                                                    class="w-full h-full object-cover">
                                            @else
                                                {{ strtoupper(substr($org->name, 0, 1)) }}
                                            @endif
                                        </div>

                                        {{-- NAME + META --}}
                                        <div class="flex flex-col">

                                            <div class="flex items-center gap-2">
                                                <span class="font-semibold text-slate-800">
                                                    {{ $org->name }}
                                                </span>

                                                @if($pending > 0)
                                                    <span class="text-[10px] px-2 py-0.5 rounded-full bg-red-100 text-red-700 font-semibold">
                                                        {{ $pending }} pending
                                                    </span>
                                                @endif
                                            </div>

                                            <span class="text-xs text-slate-400">
                                                Click to review submissions
                                            </span>

                                        </div>

                                    </div>
                                </td>

                                {{-- STATUS --}}
                                <td class="px-5 py-4">
                                    @if($isActivated)
                                        <span class="inline-flex items-center gap-1 rounded-full bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700 ring-1 ring-blue-200">
                                            ● Registered
                                        </span>
                                    @elseif($isReady)
                                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-200">
                                            ● Ready
                                        </span>
                                    @elseif($pending > 0)
                                        <span class="inline-flex items-center gap-1 rounded-full bg-red-600 px-2.5 py-1 text-xs font-semibold text-white">
                                            ● Needs Attention
                                        </span>
                                    @else
                                        <span class="text-slate-400 text-xs">
                                            No submissions yet
                                        </span>
                                    @endif
                                </td>

                                {{-- ACTION --}}
                                <td class="px-5 py-4 text-center">
                                    <span class="inline-flex items-center gap-1 text-xs font-semibold text-slate-500 group-hover:text-slate-800">
                                        Open
                                        <svg class="w-3 h-3 opacity-70" fill="none" stroke="currentColor" stroke-width="2"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </span>
                                </td>

                            </tr>

                        @empty
                            <tr>
                                <td colspan="3" class="px-5 py-16 text-center">

                                    <div class="flex flex-col items-center gap-2 text-slate-500">

                                        <div class="text-sm font-medium">
                                            No organizations found
                                        </div>

                                        <div class="text-xs text-slate-400">
                                            Try selecting a different school year or check your data.
                                        </div>

                                    </div>

                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                                            </table>
                </div>

            </div>

        @endif




    </div>


    {{-- MODAL (same, just cleaner spacing) --}}
    <div id="syModal" class="hidden fixed inset-0 z-50">
        <div class="absolute inset-0 bg-black/40"
            onclick="document.getElementById('syModal').classList.add('hidden')"></div>

        <div class="relative mx-auto mt-20 w-full max-w-xl px-4">
            <div class="rounded-2xl bg-white shadow-xl border border-slate-200 overflow-hidden">

                <div class="px-6 py-5 border-b border-slate-200 flex justify-between">
                    <div>
                        <div class="text-lg font-semibold text-slate-900">Select School Year</div>
                        <div class="text-sm text-slate-600">Choose a school year</div>
                    </div>

                    <button onclick="document.getElementById('syModal').classList.add('hidden')">
                        ✕
                    </button>
                </div>

                <div class="px-6 py-5 max-h-[60vh] overflow-y-auto space-y-2">
                    @foreach($allSchoolYears as $sy)
                        <form method="POST" action="{{ route('rereg.setSy') }}">
                            @csrf
                            <input type="hidden" name="encode_school_year_id" value="{{ $sy->id }}">

                            <button type="submit"
                                class="w-full text-left px-4 py-3 rounded-xl border hover:bg-slate-50">
                                {{ $sy->name }}
                            </button>
                        </form>
                    @endforeach
                </div>

            </div>
        </div>
    </div>


    {{-- DATATABLE --}}
    <script>
        $(function () {
            $('#reregTable').DataTable({
                pageLength: 10,
                lengthMenu: [10, 25, 50],
                ordering: true,
                info: true,
                autoWidth: false,
                language: {
                    search: '',
                    searchPlaceholder: 'Search organizations...',
                },
                dom:
                    "<'dt-top'<'dataTables_length'l><'dataTables_filter'f>>" +
                    "t" +
                    "<'dt-bottom'<'dataTables_info'i><'dataTables_paginate'p>>"
            });
        });
    </script>

</x-app-layout>