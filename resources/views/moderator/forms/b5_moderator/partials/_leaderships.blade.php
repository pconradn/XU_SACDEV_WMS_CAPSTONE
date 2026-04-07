@php
    $oldRows = old('leaderships');
    $rows = is_array($oldRows) ? $oldRows : $submission->leaderships->map(fn($r) => $r->toArray())->toArray();
    $rows = $rows ?: [];
@endphp

<div class="mt-5 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-base font-semibold text-slate-900">
                Leadership Involvement
            </h3>
            <p class="text-xs text-slate-500 mt-1">
                Optional — you may add up to 4 entries
            </p>
        </div>

        <button type="button"
                id="addLeadershipBtn"
                class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-800 hover:bg-slate-50 disabled:opacity-50"
                {{ $isLocked ? 'disabled' : '' }}>
            + Add Row
        </button>
    </div>

    <div class="mt-5 overflow-x-auto">

        <table class="min-w-full text-left text-sm">

            <thead class="text-[11px] uppercase text-slate-500 border-b border-slate-200">
                <tr>
                    <th class="py-2 px-2">Organization</th>
                    <th class="py-2 px-2">Position</th>
                    <th class="py-2 px-2">Address</th>
                    <th class="py-2 px-2">Inclusive Years</th>
                    <th class="py-2 px-2 text-right">Action</th>
                </tr>
            </thead>

            <tbody id="leadershipTbody" class="divide-y divide-slate-100">

                @foreach($rows as $idx => $row)
                    <tr class="leadership-row">

                        <td class="py-2 px-2">
                            <input type="text"
                                   name="leaderships[{{ $idx }}][organization_name]"
                                   value="{{ $row['organization_name'] ?? '' }}"
                                   placeholder="Organization name"
                                   class="w-56 rounded-lg border px-3 py-2 text-sm
                                          {{ $errors->has("leaderships.$idx.organization_name") ? 'border-rose-400' : 'border-slate-300' }}"
                                   {{ $isLocked ? 'disabled' : '' }}>
                        </td>

                        <td class="py-2 px-2">
                            <input type="text"
                                   name="leaderships[{{ $idx }}][position]"
                                   value="{{ $row['position'] ?? '' }}"
                                   placeholder="Position"
                                   class="w-44 rounded-lg border px-3 py-2 text-sm
                                          {{ $errors->has("leaderships.$idx.position") ? 'border-rose-400' : 'border-slate-300' }}"
                                   {{ $isLocked ? 'disabled' : '' }}>
                        </td>

                        <td class="py-2 px-2">
                            <input type="text"
                                   name="leaderships[{{ $idx }}][organization_address]"
                                   value="{{ $row['organization_address'] ?? '' }}"
                                   placeholder="Address"
                                   class="w-56 rounded-lg border px-3 py-2 text-sm
                                          {{ $errors->has("leaderships.$idx.organization_address") ? 'border-rose-400' : 'border-slate-300' }}"
                                   {{ $isLocked ? 'disabled' : '' }}>
                        </td>

                        <td class="py-2 px-2">
                            <input type="text"
                                   name="leaderships[{{ $idx }}][inclusive_years]"
                                   value="{{ $row['inclusive_years'] ?? '' }}"
                                   placeholder="e.g., 2023-2025"
                                   class="w-36 rounded-lg border px-3 py-2 text-sm
                                          {{ $errors->has("leaderships.$idx.inclusive_years") ? 'border-rose-400' : 'border-slate-300' }}"
                                   {{ $isLocked ? 'disabled' : '' }}>
                        </td>

                        <td class="py-2 px-2 text-right">
                            <button type="button"
                                    class="removeLeadershipBtn inline-flex rounded-lg border border-red-200 bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-700 hover:bg-red-100 disabled:opacity-50"
                                    {{ $isLocked ? 'disabled' : '' }}>
                                Remove
                            </button>
                        </td>

                    </tr>
                @endforeach

                @if(count($rows) === 0)
                    <tr id="leadershipEmptyHint">
                        <td colspan="5" class="py-6 px-2 text-sm text-slate-500 text-center">
                            No entries yet. Click <span class="font-semibold text-slate-700">Add Row</span> to start.
                        </td>
                    </tr>
                @endif

            </tbody>

        </table>

    </div>

</div>