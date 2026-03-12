<div class="border border-slate-300">

    {{-- Section Label --}}
    <div class="flex justify-start px-4 pt-1">
        <div class="text-[12px] font-medium text-slate-700">
            Proposed Implementation Date(s):
        </div>
    </div>


    {{-- Dates + Time --}}
    <div class="px-4 pb-2">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-4">

            {{-- Start Date --}}
            <div>
                <label class="block text-[10px] font-medium text-blue-900 italic">
                    Start Date:
                </label>

                <input type="date"
                       name="start_date"
                       value="{{ old('start_date', $proposal->start_date ?? '') }}"
                       class="mt-1 w-full border border-slate-300 bg-white px-3 py-0.5 text-[12px]"
                       required>
            </div>


            {{-- End Date --}}
            <div>
                <label class="block text-[10px] font-medium text-blue-900 italic">
                    End Date:
                </label>

                <input type="date"
                       name="end_date"
                       value="{{ old('end_date', $proposal->end_date ?? '') }}"
                       class="mt-1 w-full border border-slate-300 bg-white px-3 py-0.5 text-[12px]"
                       required>
            </div>


            {{-- Start Time --}}
            <div>
                <label class="block text-[10px] font-medium text-blue-900 italic">
                    Start Time (optional):
                </label>

                <input type="time"
                       name="start_time"
                       value="{{ old('start_time', $proposal->start_time ?? '') }}"
                       class="mt-1 w-full border border-slate-300 bg-white px-3 py-0.5 text-[12px]">
            </div>


            {{-- End Time --}}
            <div>
                <label class="block text-[10px] font-medium text-blue-900 italic">
                    End Time (optional):
                </label>

                <input type="time"
                       name="end_time"
                       value="{{ old('end_time', $proposal->end_time ?? '') }}"
                       class="mt-1 w-full border border-slate-300 bg-white px-3 py-0.5 text-[12px]">
            </div>

        </div>
    </div>


    {{-- Divider --}}
    <div class="border-t border-slate-300"></div>


    {{-- Venue Section --}}
    <div class="px-4 pb-3 pt-2">

        <div class="text-[12px] font-medium text-slate-700">
            Proposed Venue
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-12 items-start">

            {{-- Instruction --}}
            <div class="md:col-span-4 text-[10px] text-blue-900 italic">
                If the activity is conducted outside the university, an Off-Campus Activity Permit will be required after approval of this proposal.
            </div>


            {{-- On Campus Venue --}}
            <div class="md:col-span-4">
                <label class="block text-[10px] font-medium text-blue-900 italic">
                    On Campus
                </label>

                <input type="text"
                       name="on_campus_venue"
                       value="{{ old('on_campus_venue', $proposal->on_campus_venue ?? '') }}"
                       class="mt-1 w-full border border-slate-300 bg-white px-3 py-1 text-[11px]"
                       placeholder="e.g., XU Gym, AVR 1, Little Theatre">
            </div>


            {{-- Off Campus Venue --}}
            <div class="md:col-span-4">
                <label class="block text-[10px] font-medium text-blue-900 italic">
                    Off Campus
                </label>

                <input type="text"
                       name="off_campus_venue"
                       value="{{ old('off_campus_venue', $proposal->off_campus_venue ?? '') }}"
                       class="mt-1 w-full border border-slate-300 bg-white px-3 py-1 text-[11px]"
                       placeholder="Barangay Hall, Community Center, etc.">
            </div>

        </div>

        <div class="text-[10px] text-slate-500 mt-1 italic">
            At least one venue must be provided.
        </div>

    </div>

</div>