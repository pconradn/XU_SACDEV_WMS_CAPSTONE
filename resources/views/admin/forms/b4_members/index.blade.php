<x-app-layout>
    <div class="mx-auto max-w-6xl px-4 py-6">
        <div class="mb-4">
            <h2 class="text-xl font-semibold text-slate-900">B-4 Members Lists</h2>
            <p class="mt-1 text-sm text-slate-600">View the current member lists per organization and target School Year.</p>
        </div>

        @if(session('success'))
            <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-900">
                <div class="font-semibold">Success</div>
                <div class="text-sm mt-1">{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 rounded-xl border border-red-200 bg-red-50 p-4 text-red-900">
                <div class="font-semibold">Error</div>
                <div class="text-sm mt-1">{{ session('error') }}</div>
            </div>
        @endif

        <div class="mb-4 rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <form method="GET" class="grid grid-cols-1 gap-3 sm:grid-cols-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700">Target School Year</label>
                    <select name="target_school_year_id"
                            class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm">
                        <option value="0">All</option>
                        @foreach($schoolYears as $sy)
                            <option value="{{ $sy->id }}" @selected((int)$sy->id === (int)$targetSyId)>
                                {{ $sy->label ?? $sy->name ?? ('SY #' . $sy->id) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-slate-700">Search organization</label>
                    <input type="text" name="q" value="{{ $qText }}"
                           class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                           placeholder="e.g., Crusader Yearbook, CYB, etc.">
                </div>

                <div class="flex items-end gap-2">
                    <button class="inline-flex justify-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                        Filter
                    </button>
                    <a href="{{ route('admin.member_lists.index') }}"
                       class="inline-flex justify-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-800 hover:bg-slate-50">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead class="text-xs uppercase text-slate-500 border-b border-slate-200">
                        <tr>
                            <th class="py-3 px-4">Organization</th>
                            <th class="py-3 px-4">Target SY</th>
                            <th class="py-3 px-4">Last Updated</th>
                            <th class="py-3 px-4 text-right">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @forelse($lists as $l)
                            <tr>
                                <td class="py-3 px-4">
                                    <div class="font-semibold text-slate-900">
                                        {{ $l->organization->name ?? ('Org #' . $l->organization_id) }}
                                    </div>
                                </td>

                                <td class="py-3 px-4 text-slate-700">
                                    {{ $l->targetSchoolYear->label ?? $l->target_school_year_id }}
                                </td>

                                <td class="py-3 px-4 text-slate-600">
                                    {{ $l->updated_at?->format('M d, Y h:i A') ?? '—' }}
                                </td>

                                <td class="py-3 px-4 text-right">
                                    <a href="{{ route('admin.member_lists.show', $l->id) }}"
                                       class="inline-flex justify-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-800 hover:bg-slate-50">
                                        Open
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="py-6 px-4 text-slate-600" colspan="4">No member lists found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4">
                {{ $lists->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
