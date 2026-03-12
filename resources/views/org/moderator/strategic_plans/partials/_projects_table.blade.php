<div class="bg-white shadow-sm rounded-2xl border border-slate-200 p-5">
    <div class="flex items-start justify-between gap-4">
        <div>
            <h2 class="text-base font-semibold text-slate-900">Projects</h2>
            <p class="text-sm text-slate-500 mt-1">
                Table shows the basics only, click <span class="font-medium">View</span> to see full details.
            </p>
        </div>

        <div class="text-sm text-slate-600">
            Total projects: <span class="font-semibold text-slate-900">{{ $submission->projects->count() }}</span>
        </div>
    </div>

    <div class="mt-4 overflow-x-auto rounded-lg border border-slate-200">
        <table class="min-w-full w-full text-sm">
            <thead class="bg-slate-50 text-slate-600">
                <tr class="text-left">
                    <th class="px-3 py-2 w-56">Category</th>
                    <th class="px-3 py-2 w-44">Target Date</th>
                    <th class="px-3 py-2">Project Title</th>
                    <th class="px-3 py-2 w-32">Action</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100">
                @forelse($submission->projects as $p)
                    <tr class="align-top">
                        <td class="px-3 py-2 text-slate-700">
                            <span class="inline-flex items-center rounded-full border border-slate-200 bg-white px-2.5 py-1 text-xs font-semibold text-slate-700">
                                {{ $p->category }}
                            </span>
                        </td>

                        <td class="px-3 py-2 text-slate-700">
                            {{ optional($p->target_date)->format('Y-m-d') ?? '—' }}
                        </td>

                        <td class="px-3 py-2">
                            <div class="font-semibold text-slate-900">
                                {{ $p->title ?? 'Untitled project' }}
                            </div>
                            <div class="mt-1 text-xs text-slate-500">
                                Budget: ₱{{ number_format((float)$p->budget, 2) }}
                                @if(!empty($p->implementing_body))
                                    <span class="mx-1">•</span>
                                    {{ $p->implementing_body }}
                                @endif
                            </div>
                        </td>

                        <td class="px-3 py-2">
                            <button type="button"
                                class="inline-flex w-full items-center justify-center rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-800 hover:bg-slate-50"
                                @click="openProject({{ $p->id }})">
                                View
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-3 py-4 text-slate-500">No projects.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
