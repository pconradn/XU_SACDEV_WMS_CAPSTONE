<div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm p-4 space-y-5">

    {{-- HEADER --}}
    <div class="flex items-start gap-3">
        <div class="w-9 h-9 rounded-xl bg-purple-50 border border-purple-200 flex items-center justify-center">
            <i data-lucide="map-pin" class="w-4 h-4 text-purple-600"></i>
        </div>
        <div>
            <h3 class="text-sm font-semibold text-slate-900 tracking-wide">
                Activity Information
            </h3>
            <p class="text-xs text-slate-500 mt-1">
                Provide key details about the off-campus activity including schedule, location, and organizing body.
            </p>
        </div>
    </div>

    @php
        $activityName = old('activity_name', optional($activity)->activity_name ?? $project->title);
        $inclusiveDates = old('inclusive_dates', optional($activity)->inclusive_dates ?? '');
        $venue = old('venue_destination', optional($activity)->venue_destination ?? '');
    @endphp

    {{-- GRID --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        {{-- ORGANIZATION --}}
        <div class="md:col-span-2 rounded-xl border border-slate-200 bg-white p-4 hover:bg-slate-50 transition">
            <div class="flex items-center gap-2 mb-2">
                <i data-lucide="building-2" class="w-3.5 h-3.5 text-slate-500"></i>
                <span class="text-[11px] font-semibold text-slate-600 uppercase tracking-wide">
                    Organization
                </span>
            </div>

            <input type="hidden"
                   name="organization_name"
                   value="{{ $project->organization->name }}">

            <input type="text"
                   value="{{ $project->organization->name }}"
                   disabled
                   class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-medium text-slate-700">

            <p class="text-[11px] text-slate-400 mt-2">
                Automatically populated based on the selected project.
            </p>
        </div>

        {{-- ACTIVITY NAME --}}
        <div class="md:col-span-2 rounded-xl border border-slate-200 bg-white p-4 hover:bg-slate-50 transition">
            <div class="flex items-center gap-2 mb-2">
                <i data-lucide="clipboard-list" class="w-3.5 h-3.5 text-purple-600"></i>
                <span class="text-[11px] font-semibold text-slate-600 uppercase tracking-wide">
                    Name of Activity <span class="text-rose-500">*</span>
                </span>
            </div>

            <input
                type="text"
                name="activity_name"
                value="{{ $activityName }}"
                required
                placeholder="Enter activity name"
                class="w-full rounded-lg border px-3 py-2 text-xs
                       {{ $errors->has('activity_name') ? 'border-rose-500 focus:ring-rose-200' : 'border-slate-300 focus:ring-purple-500' }}
                       focus:ring-2 focus:outline-none"
                @if($isReadOnly) disabled @endif>

            <p class="text-[11px] text-slate-400 mt-2">
                This will be used as the official name of the off-campus activity.
            </p>

            @error('activity_name')
                <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- DATES --}}
        <div class="rounded-xl border border-slate-200 bg-white p-4 hover:bg-slate-50 transition">
            <div class="flex items-center gap-2 mb-2">
                <i data-lucide="calendar-days" class="w-3.5 h-3.5 text-blue-600"></i>
                <span class="text-[11px] font-semibold text-slate-600 uppercase tracking-wide">
                    Inclusive Date(s) <span class="text-rose-500">*</span>
                </span>
            </div>

            <input
                type="text"
                name="inclusive_dates"
                required
                value="{{ $inclusiveDates }}"
                placeholder="e.g. March 15–17, 2026"
                class="w-full rounded-lg border px-3 py-2 text-xs
                       {{ $errors->has('inclusive_dates') ? 'border-rose-500 focus:ring-rose-200' : 'border-slate-300 focus:ring-blue-500' }}
                       focus:ring-2 focus:outline-none"
                @if($isReadOnly) disabled @endif>

            <p class="text-[11px] text-slate-400 mt-2">
                Indicate the full duration of the activity.
            </p>

            @error('inclusive_dates')
                <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- VENUE --}}
        <div class="rounded-xl border border-purple-200 bg-white p-4 hover:bg-purple-50 transition">
            <div class="flex items-center gap-2 mb-2">
                <i data-lucide="map" class="w-3.5 h-3.5 text-purple-600"></i>
                <span class="text-[11px] font-semibold text-slate-600 uppercase tracking-wide">
                    Venue / Destination <span class="text-rose-500">*</span>
                </span>
            </div>

            <input
                type="text"
                name="venue_destination"
                required
                value="{{ $project->proposalDocument->proposalData->off_campus_venue }}"
                placeholder="Enter location or destination"
                class="w-full rounded-lg border px-3 py-2 text-xs
                       {{ $errors->has('venue_destination') ? 'border-rose-500 focus:ring-rose-200' : 'border-slate-300 focus:ring-purple-500' }}
                       focus:ring-2 focus:outline-none"
                @if($isReadOnly) disabled @endif>

            <p class="text-[11px] text-purple-600 mt-2">
                Off-campus location. Ensure accuracy for clearance.
            </p>

            @error('venue_destination')
                <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

    </div>

</div>