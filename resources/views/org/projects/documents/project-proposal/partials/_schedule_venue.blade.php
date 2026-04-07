<div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

    {{-- ACCENT LINE --}}
    <div class="h-1 bg-blue-500"></div>

    <div class="p-5 space-y-6">

        {{-- ================= SECTION HEADER ================= --}}
        <div class="flex flex-col">
            <h3 class="text-sm font-semibold text-slate-900">
                Schedule & Venue
            </h3>
            <p class="text-xs text-blue-700">
                Set the implementation date, time, and location of the project
            </p>
        </div>

        {{-- ================= DATES & TIME ================= --}}
        <div class="border border-slate-200 rounded-xl p-4">

            {{-- MAIN LABEL (BLACK) --}}
            <div class="text-xs font-semibold text-slate-900 uppercase tracking-wide mb-3">
                Implementation Schedule
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                {{-- Start Date --}}
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">
                        Start Date
                    </label>
                    <input type="date"
                        name="start_date"
                        value="{{ old(
                            'start_date',
                            optional($proposal)->start_date
                                ? \Carbon\Carbon::parse($proposal->start_date)->format('Y-m-d')
                                : (optional($project->sourceStrategicPlanProject)->target_date
                                    ? \Carbon\Carbon::parse($project->sourceStrategicPlanProject->target_date)->format('Y-m-d')
                                    : ''
                                )
                        ) }}"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                border {{ $errors->has('start_date') ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500' : 'border-slate-300 focus:ring-blue-500 focus:border-blue-500' }}
                               focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition"
                        required>
                        
                </div>

                {{-- End Date --}}
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">
                        End Date
                    </label>
                    <input type="date"
                        name="end_date"
                        value="{{ old(
                            'end_date',
                            optional($proposal)->end_date
                                ? \Carbon\Carbon::parse($proposal->end_date)->format('Y-m-d')
                                : (optional($project->sourceStrategicPlanProject)->target_date
                                    ? \Carbon\Carbon::parse($project->sourceStrategicPlanProject->target_date)->format('Y-m-d')
                                    : ''
                                )
                        ) }}"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                border {{ $errors->has('end_date') ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500' : 'border-slate-300 focus:ring-blue-500 focus:border-blue-500' }} 
                               focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition"
                        required>
                </div>

                {{-- Start Time --}}
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">
                        Start Time <span class="text-slate-400">(optional)</span>
                    </label>
                    <input type="time"
                        name="start_time"
                        value="{{ old('start_time', $proposal->start_time ?? '') }}"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm 
                                border {{ $errors->has('start_time') ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500' : 'border-slate-300 focus:ring-blue-500 focus:border-blue-500' }}
                               focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition">
                </div>

                {{-- End Time --}}
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">
                        End Time <span class="text-slate-400">(optional)</span>
                    </label>
                    <input type="time"
                        name="end_time"
                        value="{{ old('end_time', $proposal->end_time ?? '') }}"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm 
                                border {{ $errors->has('end_time') ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500' : 'border-slate-300 focus:ring-blue-500 focus:border-blue-500' }}
                               focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition">
                </div>

            </div>
        </div>

        {{-- ================= VENUE ================= --}}
        <div class="border border-slate-200 rounded-xl p-4">

            {{-- MAIN LABEL (BLACK) --}}
            <div class="text-xs font-semibold text-slate-900 uppercase tracking-wide mb-3">
                Venue
            </div>

            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-start">

                {{-- Instruction (BLUE SUBTEXT) --}}
                <div class="md:col-span-4 text-xs text-blue-700 leading-relaxed">
                    If conducted outside the university, an 
                    <span class="font-semibold text-blue-800">Off-Campus Activity Permit</span> 
                    will be required after approval.
                </div>

                {{-- On Campus --}}
                <div class="md:col-span-4">
                    <label class="block text-xs font-medium text-slate-600 mb-1">
                        On Campus
                    </label>
                    <input type="text"
                        name="on_campus_venue"
                        value="{{ old('on_campus_venue', $proposal->on_campus_venue ?? '') }}"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm 
                                border {{ $errors->has('on_campus_venue') ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500' : 'border-slate-300 focus:ring-blue-500 focus:border-blue-500' }}
                               focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition"
                        placeholder="e.g., XU Gym, AVR 1">
                </div>

                {{-- Off Campus --}}
                <div class="md:col-span-4">
                    <label class="block text-xs font-medium text-slate-600 mb-1">
                        Off Campus
                    </label>
                    <input type="text"
                        name="off_campus_venue"
                        value="{{ old('off_campus_venue', $proposal->off_campus_venue ?? '') }}"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm 
                                border {{ $errors->has('off_campus_venue') ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500' : 'border-slate-300 focus:ring-blue-500 focus:border-blue-500' }}
                               focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition"
                        placeholder="Barangay Hall, Community Center">
                </div>

            </div>

            {{-- SUBTEXT (BLUE) --}}
            <div class="text-xs text-blue-600 mt-3">
                At least one venue must be provided.
            </div>

        </div>

    </div>

</div>