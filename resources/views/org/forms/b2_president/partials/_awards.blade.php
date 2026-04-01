@php
    $rows = old('awards', $awards?->toArray() ?? []);
    if (count($rows) === 0) $rows = [[]];
@endphp

<div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm mb-4">
    <div class="flex items-center justify-between gap-3">
        <div>
            <h3 class="text-base font-semibold text-slate-900">Awards and Recognition <span class="text-xs text-slate-400"> (Optional)</span></h3>
            <p class="mt-1 text-sm text-slate-600">Add multiple awards if applicable.</p>
        </div>

        <button type="button"
                class="inline-flex items-center rounded-lg bg-slate-900 px-3 py-2 text-sm font-semibold text-white hover:bg-slate-800 disabled:opacity-50"
                onclick="addRow('awardsTable', awardTemplate())"
                {{ $isLocked ? 'disabled' : '' }}>
            Add row
        </button>
    </div>

    <div class="mt-4 overflow-x-auto">
        <table class="min-w-full text-left text-sm">
            <thead class="text-xs uppercase text-slate-500 border-b border-slate-200">
                <tr>
                    <th class="py-2 pr-3">Award</th>
                    <th class="py-2 pr-3">Description</th>
                    <th class="py-2 pr-3">Conferred By</th>
                    <th class="py-2 pr-3">Date</th>
                    <th class="py-2 text-right">Action</th>
                </tr>
            </thead>

            <tbody id="awardsTable" class="divide-y divide-slate-100">
                @foreach($rows as $i => $row)
                    <tr>
                        <td class="py-2 pr-3">
                            <input class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                                   name="awards[{{ $i }}][award_name]"
                                   value="{{ $row['award_name'] ?? '' }}"
                                   {{ $isLocked ? 'disabled' : '' }}>
                        </td>
                        <td class="py-2 pr-3">
                            <textarea rows="2" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                                      name="awards[{{ $i }}][award_description]"
                                      {{ $isLocked ? 'disabled' : '' }}>{{ $row['award_description'] ?? '' }}</textarea>
                        </td>
                        <td class="py-2 pr-3">
                            <input class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                                   name="awards[{{ $i }}][conferred_by]"
                                   value="{{ $row['conferred_by'] ?? '' }}"
                                   {{ $isLocked ? 'disabled' : '' }}>
                        </td>
                        <td class="py-2 pr-3">
                            <input type="date"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                                name="awards[{{ $i }}][date_received]"
                                value="{{ !empty($row['date_received']) ? \Carbon\Carbon::parse($row['date_received'])->format('Y-m-d') : '' }}"
                                {{ $isLocked ? 'disabled' : '' }}>
                        </td>
                        <td class="py-2 text-right">
                            <button type="button"
                                    class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 disabled:opacity-50"
                                    onclick="removeRow(this)"
                                    {{ $isLocked ? 'disabled' : '' }}>
                                Remove
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
