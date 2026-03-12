<x-app-layout>
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
        <div class="flex items-center justify-between">
            <h1 class="text-xl font-semibold text-slate-900">Strategic Plan Submissions</h1>
        </div>

        <div class="mt-4 overflow-x-auto border border-slate-200 rounded-lg">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 text-slate-600">
                    <tr class="text-left">
                        <th class="px-3 py-2">Org</th>
                        <th class="px-3 py-2">Target SY</th>
                        <th class="px-3 py-2">Status</th>
                        <th class="px-3 py-2">Updated</th>
                        <th class="px-3 py-2 w-28">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($submissions as $s)
                        <tr>
                            <td class="px-3 py-2">{{ $s->organization->name ?? '—' }}</td>
                            <td class="px-3 py-2">{{ $s->targetSchoolYear->name ?? '—' }}</td>
                            <td class="px-3 py-2">{{ $s->status }}</td>
                            <td class="px-3 py-2">{{ $s->updated_at->format('M j, Y') }}</td>
                            <td class="px-3 py-2">
                                <a class="text-blue-700 hover:underline"
                                   href="{{ route('admin.strategic_plans.show', $s) }}">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td class="px-3 py-4 text-slate-500" colspan="5">No submissions found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $submissions->links() }}
        </div>
    </div>
</x-app-layout>
