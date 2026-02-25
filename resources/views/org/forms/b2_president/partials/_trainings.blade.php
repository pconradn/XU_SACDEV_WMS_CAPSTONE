@php
    $rows = old('trainings', $trainings?->toArray() ?? []);
    if (count($rows) === 0) $rows = [[]];
@endphp

<div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm mb-4">
    <div class="flex items-center justify-between gap-3">
        <div>
            <h3 class="text-base font-semibold text-slate-900">Continuing Education</h3>
            <p class="mt-1 text-sm text-slate-600">Seminars / trainings attended.</p>
        </div>

        <button type="button"
                class="inline-flex items-center rounded-lg bg-slate-900 px-3 py-2 text-sm font-semibold text-white hover:bg-slate-800 disabled:opacity-50"
                onclick="addRow('trainingsTable', trainingTemplate())"
                {{ $isLocked ? 'disabled' : '' }}>
            Add row
        </button>
    </div>

    <div class="mt-4 overflow-x-auto">
        <table class="min-w-full text-left text-sm">
            <thead class="text-xs uppercase text-slate-500 border-b border-slate-200">
                <tr>
                    <th class="py-2 pr-3">Title</th>
                    <th class="py-2 pr-3">Organizer</th>
                    <th class="py-2 pr-3">Venue</th>
                    <th class="py-2 pr-3">From</th>
                    <th class="py-2 pr-3">To</th>
                    <th class="py-2 text-right">Action</th>
                </tr>
            </thead>

            <tbody id="trainingsTable" class="divide-y divide-slate-100">
                @foreach($rows as $i => $row)
                    <tr>
                        <td class="py-2 pr-3">
                            <input class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                                   name="trainings[{{ $i }}][seminar_title]"
                                   value="{{ $row['seminar_title'] ?? '' }}"
                                   {{ $isLocked ? 'disabled' : '' }}>
                        </td>
                        <td class="py-2 pr-3">
                            <input class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                                   name="trainings[{{ $i }}][organizer]"
                                   value="{{ $row['organizer'] ?? '' }}"
                                   {{ $isLocked ? 'disabled' : '' }}>
                        </td>
                        <td class="py-2 pr-3">
                            <input class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                                   name="trainings[{{ $i }}][venue]"
                                   value="{{ $row['venue'] ?? '' }}"
                                   {{ $isLocked ? 'disabled' : '' }}>
                        </td>
                        <td class="py-2 pr-3">
                            <input type="date"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                                name="trainings[{{ $i }}][date_from]"
                                value="{{ !empty($row['date_from']) ? \Carbon\Carbon::parse($row['date_from'])->format('Y-m-d') : '' }}"
                                {{ $isLocked ? 'disabled' : '' }}>
                        </td>

                        <td class="py-2 pr-3">
                            <input type="date"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                                name="trainings[{{ $i }}][date_to]"
                                value="{{ !empty($row['date_to']) ? \Carbon\Carbon::parse($row['date_to'])->format('Y-m-d') : '' }}"
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
