<div class="border border-slate-300">

    {{-- Top Section Label (Right Aligned, Subtle) --}}
    <div class="flex justify-start px-4 pt-1">
        <div class="text-[12px] font-medium text-slate-700">
            Proposed Implementation Date(s):
        </div>
    </div>

    {{-- Dates + Time --}}
    <div class="px-4 pb-2">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

            <div>
                <label class="block text-[10px] font-medium text-blue-900 italic">
                    Start Date:
                </label>
                <input type="date"
                       name="start_date"
                       value="{{ old('start_date') }}"
                       class="mt-1 w-full border border-slate-300 bg-white px-3 py-0.5 text-[12px]"
                       required>
            </div>

            <div>
                <label class="block text-[10px] font-medium text-blue-900 italic">
                    End Date:
                </label>
                <input type="date"
                       name="end_date"
                       value="{{ old('end_date') }}"
                       class="mt-1 w-full border border-slate-300 bg-white px-3 py-0.5 text-[12px]"
                       required>
            </div>

            <div>
                <label class="block text-[10px] font-medium text-blue-900 italic">
                    Time (optional):
                </label>
                <input type="time"
                       name="start_time"
                       value="{{ old('start_time') }}"
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

            <!-- Instruction Text -->
            <div class="md:col-span-5 text-[10px] text-blue-900 italic">
                For off-campus activities, please accomplish off-campus activity permit after approval of this proposal.
            </div>

            <!-- Venue Type (UNCHANGED LOGIC) -->
            <div class="md:col-span-3">
                <label class="block text-[12px] font-medium text-slate-700">
                    Venue Type:
                </label>

                <div class="mt-2 space-y-2 text-[10px] text-slate-700">
                    <label class="flex items-center gap-2">
                        <input type="radio"
                            name="venue_type"
                            value="on_campus"
                            class="border-slate-300"
                            @checked(old('venue_type', 'on_campus') === 'on_campus')>
                        On Campus
                    </label>

                    <label class="flex items-center gap-2">
                        <input type="radio"
                            name="venue_type"
                            value="off_campus"
                            class="border-slate-300"
                            @checked(old('venue_type') === 'off_campus')>
                        Off Campus
                    </label>
                </div>
            </div>

            <!-- Venue Name (UNCHANGED LOGIC) -->
            <div class="md:col-span-4">
                <label class="block text-[12px] font-medium text-slate-700">
                    Venue Name:
                </label>
                <input type="text"
                    name="venue_name"
                    value="{{ old('venue_name') }}"
                    class="mt-1 w-full border border-slate-300 bg-white px-3 py-1 text-[10px]"
                    placeholder="e.g., XU Gym, AVR 1, Barangay Hall, etc."
                    required>
            </div>

        </div>

    </div>

</div>