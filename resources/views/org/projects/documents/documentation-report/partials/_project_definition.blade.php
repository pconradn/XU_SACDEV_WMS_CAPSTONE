<div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

    <div class="h-1 bg-blue-500"></div>

    <div class="p-5 space-y-6">

        <div>
            <h3 class="text-sm font-semibold text-slate-900 tracking-wide">
                Project Definition
            </h3>
            <p class="text-xs text-blue-700 mt-1">
                Provide key details about the implementation schedule, venue, and overall project description.
            </p>
        </div>


        <div class="border border-slate-200 rounded-xl p-4 space-y-6">

            {{-- IMPLEMENTATION DATES --}}
            <div>

                <h4 class="text-xs font-semibold text-slate-600 uppercase tracking-wide mb-3">
                    Implementation Schedule
                </h4>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-5">

                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">
                            Start Date
                        </label>

                        <input type="date"
                            name="implementation_start_date"
                            value="{{ old('implementation_start_date', $prefill['implementation_start_date'] ?? '') }}"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">

                        <p class="text-[11px] text-slate-400 mt-1">
                            Date when the project started.
                        </p>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">
                            End Date
                        </label>

                        <input type="date"
                            name="implementation_end_date"
                            value="{{ old('implementation_end_date', $prefill['implementation_end_date'] ?? '') }}"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">

                        <p class="text-[11px] text-slate-400 mt-1">
                            Date when the project concluded.
                        </p>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">
                            Start Time
                        </label>

                        <input type="time"
                            name="implementation_start_time"
                            value="{{ old('implementation_start_time', isset($prefill['implementation_start_time']) && $prefill['implementation_start_time'] ? \Carbon\Carbon::parse($prefill['implementation_start_time'])->format('H:i') : '') }}"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">

                        <p class="text-[11px] text-slate-400 mt-1">
                            Time when the activity began.
                        </p>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">
                            End Time
                        </label>

                        <input type="time"
                            name="implementation_end_time"
                            value="{{ old('implementation_end_time', isset($prefill['implementation_end_time']) && $prefill['implementation_end_time'] ? \Carbon\Carbon::parse($prefill['implementation_end_time'])->format('H:i') : '') }}"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">

                        <p class="text-[11px] text-slate-400 mt-1">
                            Time when the activity ended.
                        </p>
                    </div>

                </div>

            </div>


            {{-- VENUE --}}
            <div>

                <h4 class="text-xs font-semibold text-slate-600 uppercase tracking-wide mb-3">
                    Venue
                </h4>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">
                            On Campus
                        </label>

                        <input type="text"
                            name="on_campus_venue"
                            value="{{ old('on_campus_venue', $project->proposalDocument->proposalData->on_campus_venue ?? '') }}"
                            placeholder="e.g. XU Gym, AVR Room"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">

                        <p class="text-[11px] text-slate-400 mt-1">
                            Location within the university (if applicable).
                        </p>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">
                            Off Campus
                        </label>

                        <input type="text"
                            name="off_campus_venue"
                            value="{{ old('off_campus_venue', $project->proposalDocument->proposalData->off_campus_venue ?? '') }}"
                            placeholder="e.g. External venue or community location"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">

                        <p class="text-[11px] text-slate-400 mt-1">
                            Provide if the activity was conducted outside campus.
                        </p>
                    </div>

                </div>

                <p class="text-[11px] text-blue-700 mt-2">
                    At least one venue must be provided.
                </p>

            </div>


            {{-- NATURE OF ENGAGEMENT --}}
            <div>

                <h4 class="text-xs font-semibold text-slate-600 uppercase tracking-wide mb-3">
                    Nature of Engagement
                </h4>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">
                            Engagement Type
                        </label>

                        <div class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm capitalize">
                            {{ $proposal->engagement_type ?? '-' }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">
                            Main Organizer
                        </label>

                        <div class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm">
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

    </div>

</div>