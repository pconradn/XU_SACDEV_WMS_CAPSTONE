<div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
    <div class="flex items-center justify-between">
        <h3 class="text-base font-semibold text-slate-900">List of Officers</h3>

        <button type="button"
                id="addRowBtn"
                class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-800 hover:bg-slate-50 disabled:opacity-50"
                {{ $isLocked ? 'disabled' : '' }}>
            + Add row
        </button>
    </div>

    <div class="mt-4 overflow-x-auto">
        <table class="min-w-full text-left text-sm">
            <thead class="text-xs uppercase text-slate-500 border-b border-slate-200">
                <tr>
                    <th class="py-2 px-2">Position</th>
                    <th class="py-2 px-2">Officer Name</th>
                    <th class="py-2 px-2">Student ID</th>
                    <th class="py-2 px-2">Course & Year</th>
                    <th class="py-2 px-2">Latest QPI</th>
                    <th class="py-2 px-2">Mobile #</th>
                    <th class="py-2 px-2 text-right">Remove</th>
                </tr>
            </thead>

            <tbody id="rowsTbody" class="divide-y divide-slate-100">
                @php
                    $oldItems = old('items');
                    $items = is_array($oldItems)
                        ? $oldItems
                        : ($registration->items?->map(fn($i) => $i->toArray())->toArray() ?? []);
                    $items = $items ?: [];
                @endphp

                @foreach($items as $idx => $row)
                    @include('org.forms.b3_officers.partials._row', [
                        'idx' => $idx,
                        'row' => $row,
                        'isLocked' => $isLocked,
                    ])
                @endforeach

                @if(count($items) === 0)
                    <tr id="emptyRowHint">
                        <td colspan="7" class="py-6 px-2 text-sm text-slate-600">
                            No officer rows yet. Click <span class="font-semibold">Add row</span> to start encoding.
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>