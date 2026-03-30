<x-app-layout>

    {{-- DataTables --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    @include('admin.orgs_by_sy.partials._index-styles')

    <div class="mx-auto max-w-6xl space-y-5 px-4 py-6">

        {{-- HEADER --}}
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-slate-800">
                    Re-Registration Review
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    Review organization submissions per school year
                </p>
            </div>

            @if($activeSy)
                <span class="inline-flex items-center rounded-full bg-emerald-50 px-3 py-1 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-200">
                    Active SY: {{ $activeSy->name }}
                </span>
            @endif
        </div>

        {{-- SCHOOL YEAR SELECTOR --}}
        <div class="org-page-card px-5 py-4">

            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                {{-- LEFT --}}
                <div class="flex items-center gap-3 flex-wrap">

                    <span class="text-sm font-medium text-slate-600">
                        School Year
                    </span>

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
                                    ? 'bg-slate-900 text-white'
                                    : 'bg-slate-100 text-slate-700 hover:bg-slate-200'
                                }}">
                                {{ $sy->name }}

                                @if($count > 0)
                                    <span class="bg-red-500 text-white text-[10px] px-1.5 py-0.5 rounded-full">
                                        {{ $count }}
                                    </span>
                                @endif
                            </button>
                        </form>
                    @endforeach

                    {{-- MORE --}}
                    <button type="button"
                        onclick="document.getElementById('syModal').classList.remove('hidden')"
                        class="text-xs font-semibold text-blue-600 hover:text-blue-800">
                        More...
                    </button>

                </div>

                {{-- RIGHT --}}
                <div class="text-sm text-slate-500">
                    @if($selectedSy)
                        Showing: <span class="font-semibold text-slate-800">{{ $selectedSy->name }}</span>
                    @else
                        No school year selected
                    @endif
                </div>

            </div>
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
                                    $pending = (int)($orgBadges[$org->id] ?? 0);
                                    $isReady = in_array((int)$org->id, $readyOrgIds ?? [], true);
                                    $isActivated = in_array((int)$org->id, $activatedOrgIds ?? [], true);
                                @endphp

                                <tr>
                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-2">

                                            <span class="font-semibold text-slate-800">
                                                {{ $org->name }}
                                            </span>

                                            {{-- COUNTER --}}
                                            @if($pending > 0)
                                                <span class="inline-flex items-center rounded-full bg-red-100 text-red-700 px-2 py-0.5 text-xs font-semibold">
                                                    {{ $pending }}
                                                </span>
                                            @endif

                                        </div>
                                    </td>

                                    <td class="px-5 py-4">
                                        @if($isActivated)
                                            <span class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-200">
                                                Registered
                                            </span>
                                        @elseif($isReady)
                                            <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-200">
                                                Ready
                                            </span>
                                        @elseif($pending > 0)
                                            <span class="inline-flex items-center rounded-full bg-red-600 px-2.5 py-1 text-xs font-medium text-white">
                                                {{ $pending }} Pending
                                            </span>
                                        @else
                                            <span class="text-slate-400 text-xs">—</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 text-center">
                                        <a href="{{ route('rereg.hub', $org->id) }}"
                                        class="inline-flex items-center gap-1 rounded-lg border border-slate-200 
                                                px-3 py-1.5 text-xs font-semibold text-slate-700 
                                                bg-white hover:bg-slate-50 hover:border-slate-300 transition">

                                            Open

                                            <svg class="w-3 h-3 opacity-70" fill="none" stroke="currentColor" stroke-width="2"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M9 5l7 7-7 7"/>
                                            </svg>

                                        </a>
                                    </td>
                                </tr>

                            @empty
                                <tr>
                                    <td colspan="3" class="px-5 py-10 text-center text-slate-500">
                                        No organizations found
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