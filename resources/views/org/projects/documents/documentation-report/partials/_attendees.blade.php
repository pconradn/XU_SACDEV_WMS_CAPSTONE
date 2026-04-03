<div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

    <div class="h-1 bg-blue-500"></div>

    <div class="p-5 space-y-6">

        <div>
            <h3 class="text-sm font-semibold text-slate-900 tracking-wide">
                Participants / Attendees
            </h3>
            <p class="text-xs text-blue-700 mt-1">
                List individuals or participant groups involved in the project.
            </p>
            <p class="text-[11px] text-slate-400 mt-1">
                You may enter full names or group names depending on your project.
            </p>
        </div>

        @php
            $attendees = old('attendees')
                ?? ($report?->attendees?->toArray() ?? []);

            if (empty($attendees)) {
                $attendees = [
                    ['name' => '', 'affiliation' => '', 'designation' => '']
                ];
            }
        @endphp

        <div class="border border-slate-200 rounded-xl overflow-hidden">

            <div class="overflow-auto max-h-[400px]">

                <table class="min-w-[700px] w-full text-sm border border-slate-200">

                    <thead class="bg-slate-50 text-slate-600 text-xs uppercase tracking-wide sticky top-0 z-10">
                        <tr class="border-b border-slate-200">
                            <th class="px-3 py-2 text-left border-r">Full Name / Group Name</th>
                            <th class="px-3 py-2 text-left border-r">Affiliation</th>
                            <th class="px-3 py-2 text-left border-r">Role / Designation</th>
                            @if(!$isReadOnly)
                            <th class="px-3 py-2 text-center w-[90px]">Action</th>
                            @endif
                        </tr>
                    </thead>

                    <tbody id="attendeesWrap" class="divide-y divide-slate-200">

                        @foreach($attendees as $i => $person)
                        <tr class="hover:bg-slate-50 attendee-row">

                            <td class="px-2 py-2 border-r">
                                <input type="text"
                                    name="attendees[{{ $i }}][name]"
                                    value="{{ $person['name'] ?? '' }}"
                                    placeholder="e.g. Juan Dela Cruz or BSIT Students"
                                    class="w-full rounded-md px-2 py-1 text-sm
                                        {{ $errors->has("attendees.$i.name")
                                            ? 'border-rose-500 focus:ring-rose-500'
                                            : 'border-slate-300 focus:ring-blue-500' }}
                                        border focus:ring-2 focus:outline-none">
                            </td>

                            <td class="px-2 py-2 border-r">
                                <input type="text"
                                    name="attendees[{{ $i }}][affiliation]"
                                    value="{{ $person['affiliation'] ?? '' }}"
                                    placeholder="e.g. Xavier University"
                                    class="w-full rounded-md px-2 py-1 text-sm
                                        {{ $errors->has("attendees.$i.affiliation")
                                            ? 'border-rose-500 focus:ring-rose-500'
                                            : 'border-slate-300 focus:ring-blue-500' }}
                                        border focus:ring-2 focus:outline-none">
                            </td>

                            <td class="px-2 py-2 border-r">
                                <input type="text"
                                    name="attendees[{{ $i }}][designation]"
                                    value="{{ $person['designation'] ?? '' }}"
                                    placeholder="e.g. Participants, Volunteers"
                                    class="w-full rounded-md px-2 py-1 text-sm
                                        {{ $errors->has("attendees.$i.designation")
                                            ? 'border-rose-500 focus:ring-rose-500'
                                            : 'border-slate-300 focus:ring-blue-500' }}
                                        border focus:ring-2 focus:outline-none">
                            </td>

                            @if(!$isReadOnly)
                            <td class="px-2 py-2 text-center">
                                <button type="button"
                                    onclick="removeAttendeeRow(this)"
                                    class="text-xs text-rose-600 hover:text-rose-800 font-medium">
                                    Remove
                                </button>
                            </td>
                            @endif

                        </tr>
                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

        @if(!$isReadOnly)
        <button type="button"
            id="addAttendeeBtn"
            class="text-xs font-semibold text-blue-600 hover:text-blue-700">
            + Add Participant
        </button>
        @endif

        <p class="text-[11px] text-slate-400">
            Add multiple entries if your project involved different participants or groups.
        </p>

    </div>

</div>