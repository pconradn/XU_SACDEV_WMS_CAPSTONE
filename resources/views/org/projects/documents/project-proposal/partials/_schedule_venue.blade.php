<div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm overflow-hidden">

    <div class="h-1 bg-gradient-to-r from-blue-500 to-blue-400"></div>

    <div class="p-4 space-y-5">

        <div class="flex items-center gap-3">
            <div class="p-2 rounded-xl bg-blue-50 border border-blue-100 text-blue-600">
                <i data-lucide="calendar-days" class="w-4 h-4"></i>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-slate-900">
                    Schedule & Venue
                </h3>
                <p class="text-[11px] text-slate-500">
                    Set implementation date, time, and location
                </p>
            </div>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-4">

            <div class="flex items-center gap-2 mb-3">
                <i data-lucide="clock" class="w-3.5 h-3.5 text-blue-600"></i>
                <span class="text-[11px] font-semibold text-slate-700 uppercase tracking-wide">
                    Implementation Schedule
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-3">

                <div>
                    <label class="block text-[11px] font-medium text-slate-600 mb-1">
                        Start Date
                    </label>
                    <input type="date"
                        name="start_date"
                        value="{{ old('start_date', optional($proposal)->start_date ? \Carbon\Carbon::parse($proposal->start_date)->format('Y-m-d') : (optional($project->sourceStrategicPlanProject)->target_date ? \Carbon\Carbon::parse($project->sourceStrategicPlanProject->target_date)->format('Y-m-d') : '')) }}"
                        class="w-full rounded-lg border px-3 py-2 text-xs
                        {{ $errors->has('start_date') ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500' : 'border-slate-300 focus:ring-blue-500 focus:border-blue-500' }}
                        focus:ring-2 focus:outline-none transition"
                        required>
                </div>

                <div>
                    <label class="block text-[11px] font-medium text-slate-600 mb-1">
                        End Date
                    </label>
                    <input type="date"
                        name="end_date"
                        value="{{ old('end_date', optional($proposal)->end_date ? \Carbon\Carbon::parse($proposal->end_date)->format('Y-m-d') : (optional($project->sourceStrategicPlanProject)->target_date ? \Carbon\Carbon::parse($project->sourceStrategicPlanProject->target_date)->format('Y-m-d') : '')) }}"
                        class="w-full rounded-lg border px-3 py-2 text-xs
                        {{ $errors->has('end_date') ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500' : 'border-slate-300 focus:ring-blue-500 focus:border-blue-500' }}
                        focus:ring-2 focus:outline-none transition"
                        required>
                </div>

                <div>
                    <label class="block text-[11px] font-medium text-slate-600 mb-1">
                        Start Time 
                    </label>
                    <input type="time"
                        name="start_time"
                        value="{{ old('start_time', $proposal->start_time ?? '') }}"
                        class="w-full rounded-lg border px-3 py-2 text-xs
                        {{ $errors->has('start_time') ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500' : 'border-slate-300 focus:ring-blue-500 focus:border-blue-500' }}
                        focus:ring-2 focus:outline-none transition">
                </div>

                <div>
                    <label class="block text-[11px] font-medium text-slate-600 mb-1">
                        End Time 
                    </label>
                    <input type="time"
                        name="end_time"
                        value="{{ old('end_time', $proposal->end_time ?? '') }}"
                        class="w-full rounded-lg border px-3 py-2 text-xs
                        {{ $errors->has('end_time') ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500' : 'border-slate-300 focus:ring-blue-500 focus:border-blue-500' }}
                        focus:ring-2 focus:outline-none transition">
                </div>

            </div>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-4">

            <div class="flex items-center gap-2 mb-3">
                <i data-lucide="map-pin" class="w-3.5 h-3.5 text-blue-600"></i>
                <span class="text-[11px] font-semibold text-slate-700 uppercase tracking-wide">
                    Venue
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-start">

                <div class="md:col-span-4 text-[11px] text-blue-700 leading-relaxed">
                    Off-campus activities require an
                    <span class="font-semibold text-blue-800">Off-campus activity form</span>
                    after approval.
                </div>

                <div class="md:col-span-4">
                    <label class="block text-[11px] font-medium text-slate-600 mb-0">
                        On Campus
                    </label>

                    <p class="text-[9px] text-blue-600 mb-2">
                       _
                    </p>

                    <input type="text"
                        name="on_campus_venue"
                        value="{{ old('on_campus_venue', $proposal->on_campus_venue ?? '') }}"
                        class="w-full rounded-lg border px-3 py-2 text-xs
                        {{ $errors->has('on_campus_venue') ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500' : 'border-slate-300 focus:ring-blue-500 focus:border-blue-500' }}
                        focus:ring-2 focus:outline-none transition"
                        placeholder="e.g., XU Gym">
                </div>

                <div class="md:col-span-4">
                    <label class="block text-[11px] font-medium text-slate-600 mb-0">
                        Off Campus 
                    </label>

                    <p class="text-[9px] text-blue-600 mb-2">
                        Leave blank if not off-campus
                    </p>

                    <input type="text"
                        name="off_campus_venue"
                        value="{{ old('off_campus_venue', $proposal->off_campus_venue ?? '') }}"
                        class="w-full rounded-lg border px-3 py-2 text-xs
                        {{ $errors->has('off_campus_venue') ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500' : 'border-slate-300 focus:ring-blue-500 focus:border-blue-500' }}
                        focus:ring-2 focus:outline-none transition"
                        placeholder="e.g., Barangay Hall">
                </div>

            </div>

            <div class="text-[11px] text-slate-500 mt-3">
                At least one venue must be provided.
            </div>

        </div>

    </div>

</div>