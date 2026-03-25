@php
    $grouped = [
        'org_dev' => $submission->projects->where('category', 'org_dev'),
        'student_services' => $submission->projects->where('category', 'student_services'),
        'community_involvement' => $submission->projects->where('category', 'community_involvement'),
    ];

    function niceCategory($key) {
        return [
            'org_dev' => 'Organizational Development',
            'student_services' => 'Student Services',
            'community_involvement' => 'Community Involvement',
        ][$key] ?? $key;
    }
@endphp


<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-6">

    {{-- HEADER --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-base font-semibold text-slate-900">Projects</h2>

        </div>

        <div class="text-xs bg-slate-100 border border-slate-200 rounded-xl px-3 py-2">
            Total:
            <span class="font-semibold text-slate-900">
                {{ $submission->projects->count() }}
            </span>
        </div>
    </div>


    {{-- LOOP CATEGORIES --}}
    @foreach($grouped as $key => $projects)

        @if($projects->count())

        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm">

            {{-- CATEGORY HEADER --}}
            <div class="px-5 py-4 border-b border-slate-200 flex items-center justify-between">

                <div class="flex items-center gap-3">
                    <div class="w-2.5 h-2.5 rounded-full bg-slate-400"></div>

                    <h3 class="text-sm font-semibold text-slate-800">
                        {{ niceCategory($key) }}
                    </h3>
                </div>

                <div class="text-xs text-slate-500">
                    {{ $projects->count() }} project(s)
                </div>

            </div>


            {{-- TABLE --}}
            <div class="overflow-x-auto">

                <table class="min-w-full text-sm">

                    <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wide">
                        <tr class="text-left">
                            <th class="px-4 py-3 w-44">Date</th>
                            <th class="px-4 py-3">Project</th>
                            <th class="px-4 py-3 w-44">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">

                        @foreach($projects as $p)

                        <tr class="hover:bg-slate-50 transition">

                            {{-- DATE --}}
                            <td class="px-4 py-3">
                                <div class="font-medium text-slate-800">
                                    {{ optional($p->target_date)->format('M d, Y') ?: '—' }}
                                </div>

                                <div class="text-xs text-slate-400 mt-1 capitalize">
                                    {{ str_replace('_',' ', $p->category) }}
                                </div>
                            </td>


                            {{-- PROJECT --}}
                            <td class="px-4 py-3">

                                <div class="font-semibold text-slate-900">
                                    {{ $p->title ?: 'Untitled project' }}
                                </div>

                                {{-- META TAGS (neutral now) --}}
                                <div class="mt-2 flex flex-wrap gap-2 text-xs text-slate-600">

                                    <span class="inline-flex items-center rounded-full border px-2 py-0.5 bg-slate-50 border-slate-200">
                                        Objectives: {{ $p->objectives->count() }}
                                    </span>

                                    <span class="inline-flex items-center rounded-full border px-2 py-0.5 bg-slate-50 border-slate-200">
                                        Beneficiaries: {{ $p->beneficiaries->count() }}
                                    </span>

                                    <span class="inline-flex items-center rounded-full border px-2 py-0.5 bg-slate-50 border-slate-200">
                                        Deliverables: {{ $p->deliverables->count() }}
                                    </span>

                                    @if($p->partners->count())
                                        <span class="inline-flex items-center rounded-full border px-2 py-0.5 bg-slate-50 border-slate-200">
                                            Partners: {{ $p->partners->count() }}
                                        </span>
                                    @endif

                                </div>

                            </td>


                            {{-- ACTION --}}
                            <td class="px-4 py-3">

                                <div class="flex flex-col gap-2 items-start">

                                    <button type="button"
                                            class="rounded-lg bg-slate-900 text-white px-3 py-2 text-xs font-semibold hover:bg-slate-800"
                                            @click="openProject({{ (int) $p->id }})">
                                        View
                                    </button>

                                    <div class="text-xs text-slate-500">
                                        ₱{{ number_format((float)$p->budget, 2) }}
                                    </div>

                                </div>

                            </td>

                        </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

        @endif

    @endforeach

</div>