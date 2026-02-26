<div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
    <div class="text-sm font-semibold text-slate-900">Proposed Implementation</div>
    <div class="mt-1 text-xs text-slate-500">Dates and time of the activity.</div>

    <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2">
        <div>
            <label class="block text-sm font-medium text-slate-700">Start Date</label>
            <input type="date" name="start_date" value="{{ old('start_date') }}"
                   class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700">End Date</label>
            <input type="date" name="end_date" value="{{ old('end_date') }}"
                   class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700">Start Time (optional)</label>
            <input type="time" name="start_time" value="{{ old('start_time') }}"
                   class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm">
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700">End Time (optional)</label>
            <input type="time" name="end_time" value="{{ old('end_time') }}"
                   class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm">
        </div>
    </div>

    <div class="mt-6 border-t border-slate-200 pt-5">
        <div class="text-sm font-semibold text-slate-900">Proposed Venue</div>

        <div class="mt-3 grid grid-cols-1 gap-4 md:grid-cols-3">
            <div class="md:col-span-1">
                <label class="block text-sm font-medium text-slate-700">Venue Type</label>

                <div class="mt-2 space-y-2">
                    <label class="flex items-center gap-2 text-sm text-slate-700">
                        <input type="radio" name="venue_type" value="on_campus"
                               class="rounded border-slate-300"
                               @checked(old('venue_type', 'on_campus') === 'on_campus')>
                        On Campus
                    </label>

                    <label class="flex items-center gap-2 text-sm text-slate-700">
                        <input type="radio" name="venue_type" value="off_campus"
                               class="rounded border-slate-300"
                               @checked(old('venue_type') === 'off_campus')>
                        Off Campus
                    </label>
                </div>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700">Venue Name</label>
                <input type="text" name="venue_name" value="{{ old('venue_name') }}"
                       class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm"
                       placeholder="e.g., XU Gym, AVR 1, Barangay Hall, etc."
                       required>
            </div>
        </div>
    </div>
</div>