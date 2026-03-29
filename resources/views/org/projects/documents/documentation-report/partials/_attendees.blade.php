<div>

    {{-- SECTION HEADER --}}
    <div class="mb-4">
        <h3 class="text-sm font-semibold text-slate-900 tracking-wide">
            Participants / Attendees
        </h3>
        <p class="text-xs text-slate-500 mt-1">
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


    {{-- TABLE --}}
    <div class="overflow-x-auto border border-slate-200 rounded-xl">

        <table class="min-w-full text-sm">

            <thead class="bg-slate-50 text-slate-600 text-xs uppercase tracking-wide">
                <tr>
                    <th class="px-3 py-2 text-left">Full Name / Group Name</th>
                    <th class="px-3 py-2 text-left">Affiliation</th>
                    <th class="px-3 py-2 text-left">Role / Designation</th>
                    @if(!$isReadOnly)
                    <th class="px-3 py-2 text-center w-[90px]">Action</th>
                    @endif
                </tr>
            </thead>

            <tbody id="attendeesWrap" class="divide-y divide-slate-200">

                @foreach($attendees as $i => $person)
                <tr class="hover:bg-slate-50 attendee-row">

                    {{-- NAME / GROUP --}}
                    <td class="px-2 py-2">
                        <input type="text"
                            name="attendees[{{ $i }}][name]"
                            value="{{ $person['name'] ?? '' }}"
                            placeholder="e.g. Juan Dela Cruz or BSIT Students"
                            class="w-full rounded-md border border-slate-200 px-2 py-1 text-sm">
                    </td>


                    {{-- AFFILIATION --}}
                    <td class="px-2 py-2">
                        <input type="text"
                            name="attendees[{{ $i }}][affiliation]"
                            value="{{ $person['affiliation'] ?? '' }}"
                            placeholder="e.g. Xavier University"
                            class="w-full rounded-md border border-slate-200 px-2 py-1 text-sm">
                    </td>


                    {{-- DESIGNATION --}}
                    <td class="px-2 py-2">
                        <input type="text"
                            name="attendees[{{ $i }}][designation]"
                            value="{{ $person['designation'] ?? '' }}"
                            placeholder="e.g. Participants, Volunteers"
                            class="w-full rounded-md border border-slate-200 px-2 py-1 text-sm">
                    </td>


                    {{-- ACTION --}}
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


    {{-- ADD BUTTON --}}
    @if(!$isReadOnly)
    <button type="button"
        id="addAttendeeBtn"
        class="mt-3 text-xs font-medium text-blue-600 hover:text-blue-700">
        + Add Participant
    </button>
    @endif


    {{-- HELPER --}}
    <p class="text-[11px] text-slate-400 mt-2">
        Add multiple entries if your project involved different participants or groups.
    </p>

</div>