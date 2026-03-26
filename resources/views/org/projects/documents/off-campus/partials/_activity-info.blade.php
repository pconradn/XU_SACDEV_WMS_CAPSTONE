<div>

    {{-- SECTION HEADER --}}
    <div class="mb-4">
        <h3 class="text-sm font-semibold text-slate-900 tracking-wide">
            Activity Information
        </h3>
        <p class="text-xs text-slate-500 mt-1">
            Provide key details about the off-campus activity including schedule, location, and organizing body.
        </p>
    </div>


    @php
        $activityName = old('activity_name', optional($activity)->activity_name ?? $project->title);
        $inclusiveDates = old('inclusive_dates', optional($activity)->inclusive_dates ?? '');
        $venue = old('venue_destination', optional($activity)->venue_destination ?? '');
    @endphp


    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

        {{-- ORGANIZATION --}}
        <div class="md:col-span-2">
            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide">
                Organization
            </label>

            {{-- Hidden input for saving --}}
            <input type="hidden"
                   name="organization_name"
                   value="{{ $project->organization->name }}">

            <input type="text"
                   value="{{ $project->organization->name }}"
                   disabled
                   class="mt-2 w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm font-medium text-slate-700">

            <p class="text-[11px] text-slate-400 mt-1">
                Automatically populated based on the selected project.
            </p>
        </div>


        {{-- ACTIVITY NAME --}}
        <div class="md:col-span-2">
            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide">
                Name of Activity
            </label>

            <input
                type="text"
                name="activity_name"
                value="{{ $activityName }}"
                placeholder="Enter activity name"
                class="mt-2 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500"
                @if($isReadOnly) disabled @endif>

            <p class="text-[11px] text-slate-400 mt-1">
                This will be used as the official name of the off-campus activity.
            </p>
        </div>


        {{-- INCLUSIVE DATES --}}
        <div>
            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide">
                Inclusive Date(s)
            </label>

            <input
                type="text"
                name="inclusive_dates"
                value="{{ $inclusiveDates }}"
                placeholder="e.g. March 15–17, 2026"
                class="mt-2 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500"
                @if($isReadOnly) disabled @endif>

            <p class="text-[11px] text-slate-400 mt-1">
                Indicate the full duration of the activity, including start and end dates.
            </p>
        </div>


        {{-- VENUE --}}
        <div>
            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide">
                Venue / Destination
            </label>

            <input
                type="text"
                name="venue_destination"
                value="{{ $venue }}"
                placeholder="Enter location or destination"
                class="mt-2 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500"
                @if($isReadOnly) disabled @endif>

            <p class="text-[11px] text-slate-400 mt-1">
                Specify where the activity will take place.
            </p>
        </div>

    </div>

</div>