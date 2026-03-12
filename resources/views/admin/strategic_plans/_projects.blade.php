<div class="bg-white shadow-sm rounded-2xl border border-slate-200 p-5">
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
        <div>
            <h2 class="text-base font-semibold text-slate-900">Projects</h2>
            <p class="mt-1 text-sm text-slate-500">
                Table view shows only the key info, click <span class="font-medium">View</span> to see full details.
            </p>
        </div>

        <div class="shrink-0 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-xs text-slate-700">
            Total projects:
            <span class="font-semibold text-slate-900">{{ $submission->projects->count() }}</span>
        </div>
    </div>

    <div class="mt-4 overflow-x-auto rounded-xl border border-slate-200">
        <table class="min-w-full w-full text-sm">
            <thead class="bg-slate-50 text-slate-600">
                <tr class="text-left">
                    <th class="px-3 py-2 w-48">Target Date</th>
                    <th class="px-3 py-2">Project Title</th>
                    <th class="px-3 py-2 w-48">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100">
                @forelse($submission->projects as $p)
                    <tr class="align-top">
                        <td class="px-3 py-2">
                            <div class="font-medium text-slate-800">
                                {{ optional($p->target_date)->format('Y-m-d') ?: '—' }}
                            </div>
                            <div class="mt-1 text-xs text-slate-500">
                                {{ $p->category }}
                            </div>
                        </td>

                        <td class="px-3 py-2">
                            <div class="font-semibold text-slate-900">
                                {{ $p->title ?: 'Untitled project' }}
                            </div>

                            <div class="mt-1 flex flex-wrap gap-2 text-xs">
                                <span class="inline-flex items-center rounded-full border px-2 py-0.5
                                    {{ $p->objectives->count() ? 'bg-emerald-50 border-emerald-200 text-emerald-700' : 'bg-rose-50 border-rose-200 text-rose-700' }}">
                                    Objectives: {{ $p->objectives->count() }}
                                </span>
                                <span class="inline-flex items-center rounded-full border px-2 py-0.5
                                    {{ $p->beneficiaries->count() ? 'bg-emerald-50 border-emerald-200 text-emerald-700' : 'bg-rose-50 border-rose-200 text-rose-700' }}">
                                    Beneficiaries: {{ $p->beneficiaries->count() }}
                                </span>
                                <span class="inline-flex items-center rounded-full border px-2 py-0.5
                                    {{ $p->deliverables->count() ? 'bg-emerald-50 border-emerald-200 text-emerald-700' : 'bg-rose-50 border-rose-200 text-rose-700' }}">
                                    Deliverables: {{ $p->deliverables->count() }}
                                </span>

                                @if($p->partners->count())
                                    <span class="inline-flex items-center rounded-full border px-2 py-0.5 bg-slate-50 border-slate-200 text-slate-700">
                                        Partners: {{ $p->partners->count() }}
                                    </span>
                                @endif
                            </div>
                        </td>

                        <td class="px-3 py-2">
                            <div class="flex gap-2">
                                <button type="button"
                                        class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50"
                                        @click="openProject({{ (int) $p->id }})">
                                    View
                                </button>
                            </div>

                            <div class="mt-2 text-xs text-slate-500">
                                Budget: <span class="font-medium text-slate-700">₱{{ number_format((float)$p->budget, 2) }}</span>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-3 py-6 text-slate-500 text-center">
                            No projects found for this submission.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
