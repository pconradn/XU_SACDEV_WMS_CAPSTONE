        <div class="mt-4 rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <h3 class="text-base font-semibold text-slate-900">Leadership Involvement</h3>

            <div class="mt-4 overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead class="text-xs uppercase text-slate-500 border-b border-slate-200">
                        <tr>
                            <th class="py-2 px-2">Organization</th>
                            <th class="py-2 px-2">Position</th>
                            <th class="py-2 px-2">Address</th>
                            <th class="py-2 px-2">Years</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($submission->leaderships as $row)
                            <tr>
                                <td class="py-2 px-2">{{ $row->organization_name ?? '—' }}</td>
                                <td class="py-2 px-2">{{ $row->position ?? '—' }}</td>
                                <td class="py-2 px-2">{{ $row->organization_address ?? '—' }}</td>
                                <td class="py-2 px-2">{{ $row->inclusive_years ?? '—' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-6 px-2 text-slate-600">No entries.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>