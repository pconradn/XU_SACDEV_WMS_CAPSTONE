<div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">

    <div class="flex items-center justify-between">
        <h3 class="text-base font-semibold text-slate-900">
            Other Officers
        </h3>

        <button type="button"
                id="addOfficerBtn"
                class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-800 hover:bg-slate-50 disabled:opacity-50"
                {{ $isLocked ? 'disabled' : '' }}>
            + Add Officer
        </button>
    </div>

    <div class="mt-4 overflow-x-auto">

        <table class="min-w-full text-left text-sm">

            <thead class="text-xs uppercase text-slate-500 border-b border-slate-200">
                <tr>
                    <th class="py-2 px-2">Position</th>
                    <th class="py-2 px-2">Officer Name</th>
                    <th class="py-2 px-2">Student ID</th>
                    <th class="py-2 px-2 text-right">Actions</th>
                </tr>
            </thead>

            <tbody id="officerRows" class="divide-y divide-slate-100">

                @php
                    $oldItems = old('items');

                    $items = is_array($oldItems)
                        ? $oldItems
                        : ($registration->items?->map(fn($i) => $i->toArray())->toArray() ?? []);

                    $items = collect($items)
                        ->filter(fn($item) => empty($item['major_officer_role']))
                        ->values()
                        ->toArray();
                @endphp

                @forelse($items as $idx => $row)

                    @include('org.forms.b3_officers.partials._row', [
                        'idx' => $idx,
                        'row' => $row,
                        'isLocked' => $isLocked,
                    ])

                @empty

                    <tr id="emptyHint">
                        <td colspan="4" class="py-6 px-2 text-sm text-slate-600">
                            No officers yet. Click <span class="font-semibold">Add Officer</span>.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>


@include('org.forms.b3_officers.partials._modal', [
    'isLocked' => $isLocked,
])