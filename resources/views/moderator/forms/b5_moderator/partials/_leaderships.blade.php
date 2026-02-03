@php
    $oldRows = old('leaderships');
    $rows = is_array($oldRows) ? $oldRows : $submission->leaderships->map(fn($r) => $r->toArray())->toArray();
    $rows = $rows ?: [];
@endphp

<div class="mt-4 rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-base font-semibold text-slate-900">Leadership Involvement</h3>
            <p class="mt-1 text-sm text-slate-600">Up to 4 entries (we’ll enforce max 4 in validation later).</p>
        </div>

        <button type="button" id="addLeadershipBtn"
                class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-800 hover:bg-slate-50 disabled:opacity-50"
                {{ $isLocked ? 'disabled' : '' }}>
            + Add row
        </button>
    </div>

    <div class="mt-4 overflow-x-auto">
        <table class="min-w-full text-left text-sm">
            <thead class="text-xs uppercase text-slate-500 border-b border-slate-200">
                <tr>
                    <th class="py-2 px-2">Organization</th>
                    <th class="py-2 px-2">Position</th>
                    <th class="py-2 px-2">Address</th>
                    <th class="py-2 px-2">Inclusive Years</th>
                    <th class="py-2 px-2 text-right">Remove</th>
                </tr>
            </thead>

            <tbody id="leadershipTbody" class="divide-y divide-slate-100">
                @foreach($rows as $idx => $row)
                    <tr class="leadership-row">
                        <td class="py-2 px-2">
                            <input type="text" name="leaderships[{{ $idx }}][organization_name]"
                                   value="{{ $row['organization_name'] ?? '' }}"
                                   class="w-56 rounded-lg border border-slate-300 px-3 py-2 text-sm"
                                   {{ $isLocked ? 'disabled' : '' }}>
                        </td>
                        <td class="py-2 px-2">
                            <input type="text" name="leaderships[{{ $idx }}][position]"
                                   value="{{ $row['position'] ?? '' }}"
                                   class="w-44 rounded-lg border border-slate-300 px-3 py-2 text-sm"
                                   {{ $isLocked ? 'disabled' : '' }}>
                        </td>
                        <td class="py-2 px-2">
                            <input type="text" name="leaderships[{{ $idx }}][organization_address]"
                                   value="{{ $row['organization_address'] ?? '' }}"
                                   class="w-56 rounded-lg border border-slate-300 px-3 py-2 text-sm"
                                   {{ $isLocked ? 'disabled' : '' }}>
                        </td>
                        <td class="py-2 px-2">
                            <input type="text" name="leaderships[{{ $idx }}][inclusive_years]"
                                   value="{{ $row['inclusive_years'] ?? '' }}"
                                   class="w-36 rounded-lg border border-slate-300 px-3 py-2 text-sm"
                                   placeholder="e.g., 2023-2025"
                                   {{ $isLocked ? 'disabled' : '' }}>
                        </td>
                        <td class="py-2 px-2 text-right">
                            <button type="button"
                                    class="removeLeadershipBtn inline-flex rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-xs font-semibold text-red-700 hover:bg-red-100 disabled:opacity-50"
                                    {{ $isLocked ? 'disabled' : '' }}>
                                Remove
                            </button>
                        </td>
                    </tr>
                @endforeach

                @if(count($rows) === 0)
                    <tr id="leadershipEmptyHint">
                        <td colspan="5" class="py-6 px-2 text-sm text-slate-600">
                            No entries yet. Click <span class="font-semibold">Add row</span> if applicable.
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
