@php
    $rows = old('leaderships', $leaderships?->toArray() ?? []);
    if (count($rows) === 0) $rows = [[]];
@endphp

<div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm mb-4">
    <div class="flex items-center justify-between gap-3">
        <div>
            <h3 class="text-base font-semibold text-slate-900">Leadership Involvement</h3>
            <p class="mt-1 text-sm text-slate-600">Add as many as needed.</p>
        </div>

        <button type="button"
                class="inline-flex items-center rounded-lg bg-slate-900 px-3 py-2 text-sm font-semibold text-white hover:bg-slate-800 disabled:opacity-50"
                onclick="addRow('leadershipsTable', leadershipTemplate())"
                {{ $isLocked ? 'disabled' : '' }}>
            Add row
        </button>
    </div>

    <div class="mt-4 overflow-x-auto">
        <table class="min-w-full text-left text-sm">
            <thead class="text-xs uppercase text-slate-500 border-b border-slate-200">
                <tr>
                    <th class="py-2 pr-3">Organization</th>
                    <th class="py-2 pr-3">Position</th>
                    <th class="py-2 pr-3">Address</th>
                    <th class="py-2 pr-3">Inclusive Years</th>
                    <th class="py-2 text-right">Action</th>
                </tr>
            </thead>

            <tbody id="leadershipsTable" class="divide-y divide-slate-100">
                @foreach($rows as $i => $row)
                    <tr>
                        <td class="py-2 pr-3">
                            <input class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                                   name="leaderships[{{ $i }}][organization_name]"
                                   value="{{ $row['organization_name'] ?? '' }}"
                                   {{ $isLocked ? 'disabled' : '' }}>
                        </td>
                        <td class="py-2 pr-3">
                            <input class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                                   name="leaderships[{{ $i }}][position]"
                                   value="{{ $row['position'] ?? '' }}"
                                   {{ $isLocked ? 'disabled' : '' }}>
                        </td>
                        <td class="py-2 pr-3">
                            <input class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                                   name="leaderships[{{ $i }}][organization_address]"
                                   value="{{ $row['organization_address'] ?? '' }}"
                                   {{ $isLocked ? 'disabled' : '' }}>
                        </td>
                        <td class="py-2 pr-3">
                            <input class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                                   name="leaderships[{{ $i }}][inclusive_years]"
                                   placeholder="e.g., 2024-2025"
                                   value="{{ $row['inclusive_years'] ?? '' }}"
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
