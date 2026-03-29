<div>

    

    {{-- SECTION HEADER --}}
    <div class="mb-4">
        <h3 class="text-sm font-semibold text-slate-900 tracking-wide">
            Project Definition
        </h3>
        <p class="text-xs text-slate-500 mt-1">
            Provide key details about the implementation schedule, venue, and overall project description.
        </p>
    </div>


    {{-- IMPLEMENTATION DATES --}}
    <div class="mb-6">

        <h4 class="text-xs font-semibold text-slate-600 uppercase tracking-wide mb-3">
            Implementation Schedule
        </h4>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-5">

            {{-- START DATE --}}
            <div>
                <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide">
                    Start Date
                </label>

                <input type="date"
                    name="implementation_start_date"
                    value="{{ old('implementation_start_date', $prefill['implementation_start_date'] ?? '') }}"
                    class="mt-2 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">

                <p class="text-[11px] text-slate-400 mt-1">
                    Date when the project started.
                </p>
            </div>


            {{-- END DATE --}}
            <div>
                <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide">
                    End Date
                </label>

                <input type="date"
                    name="implementation_end_date"
                    value="{{ old('implementation_end_date', $prefill['implementation_end_date'] ?? '') }}"
                    class="mt-2 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">

                <p class="text-[11px] text-slate-400 mt-1">
                    Date when the project concluded.
                </p>
            </div>


            {{-- START TIME --}}
            <div>
                <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide">
                    Start Time
                </label>

                <input type="time"
                    name="implementation_start_time"
                    value="{{ old('implementation_start_time', isset($prefill['implementation_start_time']) && $prefill['implementation_start_time'] ? \Carbon\Carbon::parse($prefill['implementation_start_time'])->format('H:i') : '') }}"
                    class="mt-2 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">

                <p class="text-[11px] text-slate-400 mt-1">
                    Time when the activity began.
                </p>
            </div>


            {{-- END TIME --}}
            <div>
                <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide">
                    End Time
                </label>

                <input type="time"
                    name="implementation_end_time"
                    value="{{ old('implementation_end_time', isset($prefill['implementation_end_time']) && $prefill['implementation_end_time'] ? \Carbon\Carbon::parse($prefill['implementation_end_time'])->format('H:i') : '') }}"
                    class="mt-2 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">

                <p class="text-[11px] text-slate-400 mt-1">
                    Time when the activity ended.
                </p>
            </div>

        </div>

    </div>


    {{-- VENUE --}}
    <div class="mb-6">

        <h4 class="text-xs font-semibold text-slate-600 uppercase tracking-wide mb-3">
            Venue
        </h4>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            {{-- ON CAMPUS --}}
            <div>
                <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide">
                    On Campus
                </label>

                <input type="text"
                    name="on_campus_venue"
                    value="{{ old('on_campus_venue', $project->proposalDocument->proposalData->on_campus_venue ?? '') }}"
                    placeholder="e.g. XU Gym, AVR Room"
                    class="mt-2 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">

                <p class="text-[11px] text-slate-400 mt-1">
                    Location within the university (if applicable).
                </p>
            </div>


            {{-- OFF CAMPUS --}}
            <div>
                <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide">
                    Off Campus
                </label>

                <input type="text"
                    name="off_campus_venue"
                    value="{{ old('off_campus_venue', $project->proposalDocument->proposalData->off_campus_venue ?? '') }}"
                    placeholder="e.g. External venue or community location"
                    class="mt-2 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">

                <p class="text-[11px] text-slate-400 mt-1">
                    Provide if the activity was conducted outside campus.
                </p>
            </div>

        </div>

        <p class="text-[11px] text-slate-400 mt-2">
            At least one venue must be provided.
        </p>

    </div>


    {{-- NATURE OF ENGAGEMENT --}}
    <div class="mb-6">

        <h4 class="text-xs font-semibold text-slate-600 uppercase tracking-wide mb-3">
            Nature of Engagement
        </h4>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            <div>
                <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide">
                    Engagement Type
                </label>

                <div class="mt-2 rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm capitalize">
                    {{ $proposal->engagement_type ?? '-' }}
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide">
                    Main Organizer
                </label>

                <div class="mt-2 rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm">
                    {{ $proposal->main_organizer ?? '-' }}
                </div>
            </div>

        </div>

    </div>


    {{-- DESCRIPTION --}}
    <div>

        <h4 class="text-xs font-semibold text-slate-600 uppercase tracking-wide mb-3">
            Project Description
        </h4>

        <textarea name="description"
            rows="4"
            placeholder="Provide a brief summary of the project, including objectives, activities conducted, and outcomes..."
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">{{ old('description', $prefill['description'] ?? '') }}</textarea>

        <p class="text-[11px] text-slate-400 mt-1">
            Summarize what the project accomplished and how it was implemented.
        </p>

    </div>

</div>