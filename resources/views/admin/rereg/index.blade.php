<x-app-layout>

    {{-- DataTables --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    @include('admin.orgs_by_sy.partials._index-styles')

    <div class="mx-auto max-w-6xl space-y-5 px-4 py-6">

        {{-- ================= HEADER CARD ================= --}}
        <div class="rounded-2xl border border-slate-200 border-t-4 border-t-blue-500 bg-gradient-to-b from-slate-50 to-white shadow-sm overflow-hidden">

            <div class="px-6 py-6 border-b border-slate-200 flex flex-col gap-5">

                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

                    {{-- LEFT --}}
                    <div>
                        <h1 class="text-xl md:text-2xl font-semibold text-slate-900 flex items-center gap-2">
                            <i data-lucide="building-2" class="w-5 h-5 text-slate-400"></i>
                            Select Organization
                        </h1>
                        <p class="mt-1 text-xs text-slate-500 max-w-xl">
                            Review organization submissions, track pending tasks, and manage registration status.
                        </p>
                    </div>

                    {{-- RIGHT CONTEXT --}}
                    <div class="min-w-[240px] rounded-xl border border-slate-200 bg-white px-4 py-3 shadow-sm">

                        <div class="text-[10px] font-semibold uppercase tracking-wide text-slate-500">
                            Current Context
                        </div>

                        <div class="mt-1 text-sm font-semibold text-slate-900">
                            {{ $selectedSy?->name ?? 'No school year selected' }}
                        </div>

                        <div class="mt-1 text-[11px] text-slate-500">
                            {{ $selectedSy
                                ? 'Filtered by selected school year.'
                                : 'Select a school year to begin.'
                            }}
                        </div>

                    </div>

                </div>

                {{-- ================= SY SELECTOR ================= --}}
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
                                class="inline-flex items-center gap-2 rounded-full px-3 py-1.5 text-xs font-semibold transition-all duration-200
                                {{ $isSelected
                                    ? 'bg-slate-900 text-white shadow-sm scale-[1.02]'
                                    : 'bg-slate-100 text-slate-700 hover:bg-slate-200'
                                }}">
                                {{ $sy->name }}

                                @if($count > 0)
                                    <span class="rounded-full bg-rose-500 px-1.5 py-0.5 text-[10px] text-white">
                                        {{ $count }}
                                    </span>
                                @endif
                            </button>
                        </form>
                    @endforeach

                    {{-- MORE --}}
                    <button type="button"
                        onclick="document.getElementById('syModal').classList.remove('hidden')"
                        class="inline-flex items-center gap-1 rounded-full border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-blue-600 hover:bg-slate-50 hover:text-blue-800 transition">
                        <i data-lucide="more-horizontal" class="w-3.5 h-3.5"></i>
                        More
                    </button>

                </div>

            </div>

        </div>

        {{-- ================= TABLE SECTION ================= --}}
        @if(!$encodeSyId)

            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm px-6 py-10 text-center text-xs text-slate-500">
                Select a school year to continue.
            </div>

        @else

            <div class="rounded-2xl border border-slate-200 border-t-4 border-t-slate-300 bg-white shadow-sm overflow-hidden">

                {{-- HEADER --}}
                <div class="px-6 py-4 border-b text-xs font-semibold text-slate-600 flex items-center justify-between">
                    <span>Organizations</span>
                    <span class="text-[11px] text-slate-400">Click row to review</span>
                </div>

                <div class="overflow-x-auto">
                    <table id="reregTable" class="min-w-full text-xs">

                        <thead class="bg-slate-50 text-slate-600">
                            <tr>
                                <th class="px-5 py-3 text-left font-semibold">Organization</th>
                                <th class="px-5 py-3 text-left font-semibold">Status</th>
                                <th class="px-5 py-3 text-center font-semibold">Action</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y">

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
                                    ? 'bg-rose-50/40 hover:bg-rose-50 border-l-4 border-rose-400'
                                    : ($isReady
                                        ? 'bg-emerald-50/30 hover:bg-emerald-50 border-l-4 border-emerald-400'
                                        : ($isActivated
                                            ? 'bg-blue-50/30 hover:bg-blue-50 border-l-4 border-blue-400'
                                            : 'hover:bg-slate-50 border-l-4 border-transparent'
                                        )
                                    );
                            @endphp

                                <tr
                                onclick="window.location='{{ route('rereg.hub', $org->id) }}'"
                                class="cursor-pointer transition-all duration-150 {{ $rowColor }} hover:scale-[1.005] border-b">

                                {{-- ORG --}}
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">

                                        <div class="w-9 h-9 rounded-lg bg-slate-100 overflow-hidden flex items-center justify-center text-xs text-slate-400">
                                            @if($org->logo_path)
                                                <img src="{{ asset('storage/' . $org->logo_path) }}"
                                                    class="w-full h-full object-cover">
                                            @else
                                                {{ strtoupper(substr($org->name, 0, 1)) }}
                                            @endif
                                        </div>

                                        <div class="flex flex-col">

                                            <div class="flex items-center gap-2">
                                                <span class="font-semibold text-slate-800">
                                                    {{ $org->name }}
                                                </span>

                                                @if($pending > 0)
                                                    <span class="text-[10px] px-2 py-0.5 rounded-full bg-rose-100 text-rose-700 font-semibold">
                                                        {{ $pending }} pending
                                                    </span>
                                                @endif
                                            </div>

                                            <span class="text-[10px] text-slate-400">
                                                Review submissions
                                            </span>

                                        </div>

                                    </div>
                                </td>

                                {{-- STATUS --}}
                                <td class="px-5 py-4">
                                    @if($isActivated)
                                        <span class="inline-flex items-center gap-1 rounded-full bg-blue-50 px-2 py-0.5 text-[10px] font-semibold text-blue-700 ring-1 ring-blue-200">
                                            ● Registered
                                        </span>
                                    @elseif($isReady)
                                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2 py-0.5 text-[10px] font-semibold text-emerald-700 ring-1 ring-emerald-200">
                                            ● Ready
                                        </span>
                                    @elseif($pending > 0)
                                        <span class="inline-flex items-center gap-1 rounded-full bg-rose-600 px-2 py-0.5 text-[10px] font-semibold text-white">
                                            ● Needs Attention
                                        </span>
                                    @else
                                        <span class="text-slate-400 text-[10px]">
                                            No submissions
                                        </span>
                                    @endif
                                </td>

                                {{-- ACTION --}}
                                <td class="px-5 py-4 text-center">
                                    <span class="inline-flex items-center gap-1 text-[10px] font-semibold text-slate-500 group-hover:text-slate-800">
                                        Open
                                        <i data-lucide="chevron-right" class="w-3 h-3"></i>
                                    </span>
                                </td>

                            </tr>

                        @empty
                            <tr>
                                <td colspan="3" class="px-5 py-16 text-center text-xs text-slate-500">
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

    {{-- ================= MODAL ================= --}}
    <div id="syModal" class="hidden fixed inset-0 z-50">

        <div class="absolute inset-0 bg-black/40"
            onclick="document.getElementById('syModal').classList.add('hidden')"></div>

        <div class="relative mx-auto mt-20 w-full max-w-lg px-4">
            <div class="rounded-2xl bg-white shadow-xl border border-slate-200 overflow-hidden">

                <div class="px-6 py-4 border-b flex justify-between items-center">
                    <div>
                        <div class="text-sm font-semibold text-slate-900">Select School Year</div>
                        <div class="text-xs text-slate-500">Choose context</div>
                    </div>

                    <button onclick="document.getElementById('syModal').classList.add('hidden')">
                        ✕
                    </button>
                </div>

                <div class="px-6 py-4 max-h-[60vh] overflow-y-auto space-y-2 text-xs">
                    @foreach($allSchoolYears as $sy)
                        <form method="POST" action="{{ route('rereg.setSy') }}">
                            @csrf
                            <input type="hidden" name="encode_school_year_id" value="{{ $sy->id }}">

                            <button type="submit"
                                class="w-full text-left px-4 py-2 rounded-lg border border-slate-200 hover:bg-slate-50 transition">
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