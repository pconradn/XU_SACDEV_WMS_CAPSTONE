<div class="border border-slate-300">

    {{-- Implementation Dates --}}
    <div class="flex justify-start px-4 pt-1">
        <div class="text-[12px] font-medium text-slate-700">
            Implementation Date(s)
        </div>
    </div>

    <div class="px-4 pb-2">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-4">

            <div>
                <label class="block text-[10px] font-medium text-blue-900 italic">
                    Start Date
                </label>

                <input type="date"
                    name="implementation_start_date"
                    value="{{ old('implementation_start_date', $prefill['implementation_start_date'] ?? '') }}"
                    class="mt-1 w-full border border-slate-300 bg-white px-3 py-0.5 text-[12px]">
            </div>

            <div>
                <label class="block text-[10px] font-medium text-blue-900 italic">
                    End Date
                </label>

                <input type="date"
                    name="implementation_end_date"
                    value="{{ old('implementation_end_date', $prefill['implementation_end_date'] ?? '') }}"
                    class="mt-1 w-full border border-slate-300 bg-white px-3 py-0.5 text-[12px]">
            </div>

            <div>
                <label class="block text-[10px] font-medium text-blue-900 italic">
                    Start Time
                </label>

                <input type="time"
                    name="implementation_start_time"
                    value="{{ old('implementation_start_time', isset($prefill['implementation_start_time']) && $prefill['implementation_start_time'] ? \Carbon\Carbon::parse($prefill['implementation_start_time'])->format('H:i') : '') }}"
                    class="mt-1 w-full border border-slate-300 bg-white px-3 py-0.5 text-[12px]">
            </div>

            <div>
                <label class="block text-[10px] font-medium text-blue-900 italic">
                    End Time
                </label>

                <input type="time"
                    name="implementation_end_time"
                    value="{{ old('implementation_end_time', isset($prefill['implementation_end_time']) && $prefill['implementation_end_time'] ? \Carbon\Carbon::parse($prefill['implementation_end_time'])->format('H:i') : '') }}"
                    class="mt-1 w-full border border-slate-300 bg-white px-3 py-0.5 text-[12px]">
            </div>

        </div>
    </div>


    {{-- Divider --}}
    <div class="border-t border-slate-300"></div>


    {{-- Venue --}}
    <div class="px-4 pb-3 pt-2">

        <div class="text-[12px] font-medium text-slate-700">
            Venue
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 items-start">

            <div>
                <label class="block text-[10px] font-medium text-blue-900 italic">
                    On Campus
                </label>

                <input type="text"
                    name="on_campus_venue"
                    value="{{ old('on_campus_venue', $prefill['on_campus_venue'] ?? '') }}"
                    class="mt-1 w-full border border-slate-300 bg-white px-3 py-0.5 text-[12px]">
            </div>

            <div>
                <label class="block text-[10px] font-medium text-blue-900 italic">
                    Off Campus
                </label>

                <input type="text"
                    name="off_campus_venue"
                    value="{{ old('off_campus_venue', $prefill['off_campus_venue'] ?? '') }}"
                    class="mt-1 w-full border border-slate-300 bg-white px-3 py-0.5 text-[12px]">
            </div>

        </div>

        <div class="text-[10px] text-slate-500 mt-1 italic">
            At least one venue must be provided.
        </div>

    </div>


    {{-- Divider --}}
    <div class="border-t border-slate-300"></div>


    {{-- Nature of Engagement (Display Only) --}}
    <div class="px-4 py-3">

        <div class="text-[12px] font-medium text-slate-700 mb-2">
            Nature of Engagement
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-[12px]">

            <div>
                <label class="block text-[10px] italic text-blue-900">
                    Engagement Type
                </label>

                <div class="mt-1 border border-slate-300 bg-slate-50 px-3 py-1 text-[12px] capitalize">
                    {{ $proposal->engagement_type ?? '-' }}
                </div>
            </div>

            <div>
                <label class="block text-[10px] italic text-blue-900">
                    Main Organizer
                </label>

                <div class="mt-1 border border-slate-300 bg-slate-50 px-3 py-1 text-[12px]">
                    {{ $proposal->main_organizer ?? '-' }}
                </div>
            </div>

        </div>

    </div>


    {{-- Divider --}}
    <div class="border-t border-slate-300"></div>


    {{-- Project Description --}}
    <div class="px-4 py-3">

        <div class="text-[12px] font-medium text-slate-700 mb-2">
            Brief Description of the Project
        </div>

        <textarea name="description"
            rows="3"
            class="w-full border border-slate-300 px-3 py-1 text-[12px]">{{ old('description', $prefill['description'] ?? '') }}</textarea>

    </div>

</div>