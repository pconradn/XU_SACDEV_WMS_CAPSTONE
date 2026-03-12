<div class="border border-slate-300">

    <div class="flex justify-start px-4 pt-1">
        <div class="text-[12px] font-medium text-slate-700">
            Who attended your project?
        </div>
    </div>

    <div class="px-4 pb-3 pt-2">

        <label class="block text-[10px] font-medium text-blue-900 italic">
            Participants / Attendees
        </label>

        @php
            $attendees = old('attendees')
                ?? ($report?->attendees?->toArray() ?? []);

            if(empty($attendees)) {
                $attendees = [
                    ['name' => '', 'affiliation' => '', 'designation' => '']
                ];
            }
        @endphp

        <div id="attendeesWrap" class="space-y-2">

            @foreach($attendees as $i => $person)
            <div class="grid grid-cols-1 gap-2 md:grid-cols-4 attendee-row">

                <input type="text"
                       name="attendees[{{ $i }}][name]"
                       value="{{ $person['name'] ?? '' }}"
                       class="border border-slate-300 px-3 py-1 text-[12px]"
                       placeholder="Full Name">

                <input type="text"
                       name="attendees[{{ $i }}][affiliation]"
                       value="{{ $person['affiliation'] ?? '' }}"
                       class="border border-slate-300 px-3 py-1 text-[12px]"
                       placeholder="Affiliation">

                <input type="text"
                       name="attendees[{{ $i }}][designation]"
                       value="{{ $person['designation'] ?? '' }}"
                       class="border border-slate-300 px-3 py-1 text-[12px]"
                       placeholder="Designation">

                <button type="button"
                        class="remove-btn text-red-600 text-[12px] px-2">
                    ✕
                </button>

            </div>
            @endforeach

        </div>

        <button type="button"
                id="addAttendeeBtn"
                class="mt-2 text-[10px] text-blue-700 underline">
            + Add Attendee
        </button>

    </div>

</div>