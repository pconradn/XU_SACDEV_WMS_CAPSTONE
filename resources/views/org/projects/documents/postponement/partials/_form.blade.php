<div>

    {{-- SECTION HEADER --}}
    <div class="mb-4">
        <h3 class="text-sm font-semibold text-slate-900 tracking-wide">
            Postponement Details
        </h3>
        <p class="text-xs text-slate-500 mt-1">
            Provide the updated schedule and details for the postponed activity. Ensure all fields reflect the revised implementation plan.
        </p>
    </div>

    @php
        $newDate = old('new_date', $data->new_date ?? '');
        $venue = old('venue', $data->venue ?? '');
        $startTime = old('new_start_time', $data->new_start_time ?? '');
        $endTime = old('new_end_time', $data->new_end_time ?? '');
        $reason = old('reason', $data->reason ?? '');
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

        {{-- NEW DATE --}}
        <div>
            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide">
                New Date
            </label>

            <input
                type="date"
                name="new_date"
                value="{{ $newDate }}"
                class="mt-2 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
                @if($isReadOnly) disabled @endif
            >

            <p class="text-[11px] text-slate-400 mt-1">
                Updated date when the activity will be conducted.
            </p>
        </div>


        {{-- VENUE --}}
        <div>
            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide">
                Venue / Location
            </label>

            <input
                type="text"
                name="venue"
                value="{{ $venue }}"
                placeholder="Enter updated venue or location"
                class="mt-2 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
                @if($isReadOnly) disabled @endif
            >

            <p class="text-[11px] text-slate-400 mt-1">
                Specify where the activity will now take place.
            </p>
        </div>


        {{-- START TIME --}}
        <div>
            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide">
                New Start Time
            </label>

            <input
                type="time"
                name="new_start_time"
                value="{{ $startTime }}"
                class="mt-2 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
                @if($isReadOnly) disabled @endif
            >

            <p class="text-[11px] text-slate-400 mt-1">
                Updated starting time of the activity.
            </p>
        </div>


        {{-- END TIME --}}
        <div>
            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide">
                New End Time
            </label>

            <input
                type="time"
                name="new_end_time"
                value="{{ $endTime }}"
                class="mt-2 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
                @if($isReadOnly) disabled @endif
            >

            <p class="text-[11px] text-slate-400 mt-1">
                Updated ending time of the activity.
            </p>
        </div>


        {{-- REASON --}}
        <div class="md:col-span-2">
            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide">
                Reason for Postponement (Optional)
            </label>

            <textarea
                name="reason"
                rows="4"
                placeholder="Explain why the activity is being postponed (e.g., scheduling conflict, weather, resource issues, etc.)"
                class="mt-2 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
                @if($isReadOnly) disabled @endif
            >{{ $reason }}</textarea>

            <p class="text-[11px] text-slate-400 mt-1">
                Provide context to help SACDEV understand the reason for the change.
            </p>
        </div>

    </div>

</div>