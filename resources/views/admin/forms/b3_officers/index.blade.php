<x-app-layout>
    <div class="mx-auto max-w-6xl px-4 py-6">
        <div class="mb-4">
            <h2 class="text-xl font-semibold text-slate-900">B-3 Officers Submissions</h2>
            <p class="mt-1 text-sm text-slate-600">Review officer lists submitted by organizations.</p>
        </div>


        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm mb-4">
            <form method="GET" class="grid grid-cols-1 gap-3 sm:grid-cols-3">
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

                <div>
                    <label class="block text-sm font-medium text-slate-700">Status</label>
                    <select name="status"
                            class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm">
                        <option value="" @selected(!$status)>Submitted/Returned/Approved</option>
                        <option value="submitted_to_sacdev" @selected($status==='submitted_to_sacdev')>Submitted</option>
                        <option value="returned_by_sacdev" @selected($status==='returned_by_sacdev')>Returned</option>
                        <option value="approved_by_sacdev" @selected($status==='approved_by_sacdev')>Approved</option>
                    </select>
                </div>

                <div class="flex items-end gap-2">
                    <button class="inline-flex justify-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                        Filter
                    </button>

                    <a href="{{ route('admin.officer_submissions.index') }}"
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
                            <th class="py-3 px-4">Org</th>
                            <th class="py-3 px-4">Target SY</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4">Submitted</th>
                            <th class="py-3 px-4 text-right">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @forelse($submissions as $s)
                            @php
                                $badge = match ($s->status) {
                                    'submitted_to_sacdev' => 'bg-blue-50 text-blue-700 ring-blue-200',
                                    'returned_by_sacdev'  => 'bg-amber-50 text-amber-800 ring-amber-200',
                                    'approved_by_sacdev'  => 'bg-emerald-50 text-emerald-800 ring-emerald-200',
                                    default               => 'bg-slate-100 text-slate-800 ring-slate-200',
                                };
                            @endphp
                            <tr>
                                <td class="py-3 px-4">
                                    <div class="font-semibold text-slate-900">
                                        {{ $s->organization->name ?? ('Org #' . $s->organization_id) }}
                                    </div>
                                </td>

                                <td class="py-3 px-4">
                                    {{ $s->targetSchoolYear->label ?? $s->target_school_year_id }}
                                </td>

                                <td class="py-3 px-4">
                                    <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $badge }}">
                                        {{ $s->status }}
                                    </span>
                                </td>

                                <td class="py-3 px-4 text-slate-600">
                                    {{ $s->submitted_at ? $s->submitted_at->format('M d, Y') : '—' }}
                                </td>

                                <td class="py-3 px-4 text-right">
                                    <a href="{{ route('admin.officer_submissions.show', $s->id) }}"
                                       class="inline-flex justify-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-800 hover:bg-slate-50">
                                        Open
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="py-6 px-4 text-slate-600" colspan="5">No submissions found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4">
                {{ $submissions->links() }}
            </div>
        </div>
    </div>

    
</x-app-layout>
