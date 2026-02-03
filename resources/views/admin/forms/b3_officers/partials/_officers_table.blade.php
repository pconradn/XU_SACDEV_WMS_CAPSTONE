<div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full text-left text-sm">
            <thead class="text-xs uppercase text-slate-500 border-b border-slate-200">
                <tr>
                    <th class="py-3 px-4">Position</th>
                    <th class="py-3 px-4">Officer Name</th>
                    <th class="py-3 px-4">Student ID</th>
                    <th class="py-3 px-4">Course & Year</th>
                    <th class="py-3 px-4">Latest QPI</th>
                    <th class="py-3 px-4">Mobile #</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100">
                @forelse($items as $row)
                    <tr>
                        <td class="py-3 px-4 font-medium text-slate-900">{{ $row->position }}</td>
                        <td class="py-3 px-4 text-slate-800">{{ $row->officer_name }}</td>
                        <td class="py-3 px-4 text-slate-800">{{ $row->student_id_number }}</td>
                        <td class="py-3 px-4 text-slate-800">{{ $row->course_and_year }}</td>
                        <td class="py-3 px-4 text-slate-800">{{ $row->latest_qpi ?? '—' }}</td>
                        <td class="py-3 px-4 text-slate-800">{{ $row->mobile_number }}</td>
                    </tr>
                @empty
                    <tr>
                        <td class="py-6 px-4 text-slate-600" colspan="6">No officer rows encoded.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
