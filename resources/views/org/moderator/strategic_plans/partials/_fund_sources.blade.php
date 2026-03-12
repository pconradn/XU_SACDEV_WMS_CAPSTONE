<div class="bg-white shadow-sm rounded-2xl border border-slate-200 p-5">
    <div class="flex items-start justify-between gap-4">
        <div>
            <h2 class="text-base font-semibold text-slate-900">Fund Sources</h2>
            <p class="text-sm text-slate-500 mt-1">Read-only funding breakdown submitted with the Strategic Plan.</p>
        </div>
    </div>

    <div class="mt-4 overflow-x-auto rounded-lg border border-slate-200">
        <table class="min-w-full w-full text-sm">
            <thead class="bg-slate-50 text-slate-600">
                <tr class="text-left">
                    <th class="px-3 py-2 w-56">Type</th>
                    <th class="px-3 py-2">Label</th>
                    <th class="px-3 py-2 w-44 text-right">Amount</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100">
                @forelse($submission->fundSources as $fs)
                    <tr>
                        <td class="px-3 py-2 text-slate-700">{{ $fs->type }}</td>
                        <td class="px-3 py-2 text-slate-700">{{ $fs->label ?? '—' }}</td>
                        <td class="px-3 py-2 text-slate-900 font-semibold text-right">
                            ₱{{ number_format((float)$fs->amount, 2) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-3 py-4 text-slate-500">No fund sources recorded.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
