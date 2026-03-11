<div class="border border-slate-300 bg-white mb-6">

    <div class="bg-slate-50 border-b border-slate-300 px-4 py-2">
        <div class="text-[12px] font-semibold text-slate-900 tracking-wide">
            Postponement Details
        </div>
    </div>

    <div class="px-4 py-4 grid grid-cols-1 md:grid-cols-2 gap-4">

        <div>
            <label class="block text-[10px] font-medium text-blue-900 italic">
                New Date
            </label>

            <input
            type="date"
            name="new_date"
            value="{{ old('new_date', $data->new_date ?? '') }}"
            class="mt-1 w-full border border-slate-300 px-3 py-1 text-[12px]">
        </div>

        <div>
            <label class="block text-[10px] font-medium text-blue-900 italic">
                Venue
            </label>

            <input
            type="text"
            name="venue"
            value="{{ old('venue', $data->venue ?? '') }}"
            class="mt-1 w-full border border-slate-300 px-3 py-1 text-[12px]">
        </div>

        <div>
            <label class="block text-[10px] font-medium text-blue-900 italic">
                New Start Time
            </label>

            <input
            type="time"
            name="new_start_time"
            value="{{ old('new_start_time', $data->new_start_time ?? '') }}"
            class="mt-1 w-full border border-slate-300 px-3 py-1 text-[12px]">
        </div>

        <div>
            <label class="block text-[10px] font-medium text-blue-900 italic">
                New End Time
            </label>

            <input
            type="time"
            name="new_end_time"
            value="{{ old('new_end_time', $data->new_end_time ?? '') }}"
            class="mt-1 w-full border border-slate-300 px-3 py-1 text-[12px]">
        </div>

        <div class="md:col-span-2">
            <label class="block text-[10px] font-medium text-blue-900 italic">
                Reason for Postponement (Optional)
            </label>

            <textarea
            name="reason"
            rows="4"
            class="mt-1 w-full border border-slate-300 px-3 py-1 text-[12px]">{{ old('reason', $data->reason ?? '') }}</textarea>
        </div>

    </div>

</div>


