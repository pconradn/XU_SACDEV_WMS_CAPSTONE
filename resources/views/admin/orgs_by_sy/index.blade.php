<x-app-layout>


    {{-- jQuery + DataTables JS only --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    @include('admin.orgs_by_sy.partials._index-styles')

    <div class="mx-auto max-w-6xl space-y-5 px-4 py-6">



        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold leading-tight text-slate-800">
                    Organizations
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    View organizations registered by school year
                </p>
            </div>

            @if($activeSy)
                <span class="inline-flex items-center rounded-full bg-emerald-50 px-3 py-1 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-200">
                    Active SY: {{ $activeSy->name }}
                </span>
            @endif
        </div>



        {{-- top filter card --}}
        <div class="org-page-card px-5 py-4">
            <form method="POST" action="{{ route('admin.orgs_by_sy.set_sy') }}">
                @csrf

                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                    {{-- LEFT: FILTER --}}
                    <div class="flex items-center gap-3">

                        {{-- LABEL --}}
                        <span class="text-sm font-medium text-slate-600 whitespace-nowrap">
                            School Year
                        </span>

                        {{-- SELECT WITH ICON --}}
                        <div class="relative">

                            {{-- ICON --}}
                            <div class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-slate-400">
                                <i data-lucide="calendar" class="w-4 h-4"></i>
                            </div>

                            {{-- SELECT --}}
                            <select
                                name="school_year_id"
                                onchange="this.form.submit()"
                                class="h-10 min-w-[200px] pl-9 pr-3 rounded-xl border border-slate-300 bg-white text-sm text-slate-700
                                    focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-100 transition"
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

                    {{-- RIGHT: STATUS --}}
                    <div class="flex items-center gap-2 text-sm">

                        @if($selectedSy)
                            <span class="text-slate-500">Showing:</span>

                            <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-3 py-1 text-slate-700 font-medium">
                                <i data-lucide="calendar-days" class="w-3.5 h-3.5 text-slate-400"></i>
                                {{ $selectedSy->name }}
                            </span>
                        @else
                            <span class="text-slate-400">No school year selected</span>
                        @endif

                    </div>

                </div>

                {{-- ERROR --}}
                @error('school_year_id')
                    <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                @enderror

            </form>
        </div>

        {{-- table card --}}
        <div class="org-page-card overflow-hidden">
            <div class="overflow-x-auto">
                <table id="orgTable" class="min-w-full text-sm">
                    <thead>
                        <tr>
                            <th class="px-5 py-4 text-left">Organization</th>
                            <th class="px-5 py-4 text-left">President</th>
                            <th class="px-5 py-4 text-left">Status</th>
                            <th class="px-5 py-4 text-right">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($orgSys as $row)
                            <tr>
                                <td class="px-5 py-4">
                                    <div class="font-semibold text-slate-800">
                                        {{ $row->organization->name }}
                                    </div>
                                    <div class="mt-1 text-xs text-slate-400">
                                        {{ $row->organization->acronym ?? '—' }}
                                    </div>
                                </td>

                                <td class="px-5 py-4 text-slate-700">
                                    {{ optional($row->president)->name ?? '—' }}
                                </td>

                                <td class="px-5 py-4">
                                    @if($row->president_confirmed_at)
                                        <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-200">
                                            Confirmed
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-amber-50 px-2.5 py-1 text-xs font-medium text-amber-700 ring-1 ring-inset ring-amber-200">
                                            Pending
                                        </span>
                                    @endif
                                </td>

                                <td class="px-5 py-4 text-right">
                                    <a
                                        href="{{ route('admin.orgs_by_sy.show', $row->organization_id) }}"
                                        class="inline-flex items-center text-sm font-semibold text-blue-600 transition hover:text-blue-800"
                                    >
                                        Open
                                        <span class="ml-1">→</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-10 text-center text-sm text-slate-500">
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