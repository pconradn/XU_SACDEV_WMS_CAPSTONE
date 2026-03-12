<div class="border border-slate-300">

    <div class="flex justify-start px-4 pt-1">
        <div class="text-[12px] font-medium text-slate-700">
            Evaluation
        </div>
    </div>

    <div class="px-4 pb-3 pt-2 space-y-6">

        {{-- ROW 1 --}}
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

            {{-- Objectives --}}
            <div>
                <label class="block text-[10px] font-medium text-blue-900 italic">
                    Objectives
                </label>

                @php
                    $objectives = old('objectives')
                        ?? ($report?->objectives?->pluck('objective')->toArray() ?? []);

                    if(empty($objectives) && isset($proposal)){
                        $objectives = $proposal->objectives?->pluck('objective')->toArray() ?? [];
                    }

                    if(empty($objectives)) $objectives = [''];
                @endphp

                <div id="reportObjectivesWrap" class="space-y-2">

                    @foreach($objectives as $obj)
                    <div class="flex gap-2 objective-row dynamic-row">

                        <input type="text"
                               name="objectives[]"
                               value="{{ $obj }}"
                               class="w-full border border-slate-300 px-3 py-1 text-[12px]"
                               placeholder="Objective">

                        <button type="button"
                                class="remove-btn text-red-600 text-[12px] px-2">
                            ✕
                        </button>

                    </div>
                    @endforeach

                </div>

                <button type="button"
                        id="addReportObjectiveBtn"
                        class="mt-2 text-[10px] text-blue-700 underline">
                    + Add Objective
                </button>

            </div>


            {{-- Success Indicators --}}
            <div>

                <label class="block text-[10px] font-medium text-blue-900 italic">
                    Target / Success Indicators
                </label>

                @php
                    $indicators = old('success_indicators')
                        ?? ($report?->indicators?->pluck('indicator')->toArray() ?? []);

                    if(empty($indicators) && isset($proposal)){
                        $indicators = $proposal->indicators?->pluck('indicator')->toArray() ?? [];
                    }

                    if(empty($indicators)) $indicators = [''];
                @endphp

                <div id="reportIndicatorsWrap" class="space-y-2">

                    @foreach($indicators as $ind)
                    <div class="flex gap-2 indicator-row dynamic-row">

                        <input type="text"
                               name="success_indicators[]"
                               value="{{ $ind }}"
                               class="w-full border border-slate-300 px-3 py-1 text-[12px]"
                               placeholder="Success Indicator">

                        <button type="button"
                                class="remove-btn text-red-600 text-[12px] px-2">
                            ✕
                        </button>

                    </div>
                    @endforeach

                </div>

                <button type="button"
                        id="addReportIndicatorBtn"
                        class="mt-2 text-[10px] text-blue-700 underline">
                    + Add Indicator
                </button>

            </div>

        </div>

        <div class="border-t border-slate-300"></div>


        {{-- ROW 2 --}}
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 pt-4">

            {{-- Objectives Met --}}
            <div>

                <label class="block text-[10px] font-medium text-blue-900 italic">
                    Were your objectives met?
                </label>

                @php
                    $met = old('objectives_met',
                        isset($report) ? ($report->objectives_met ? 'yes' : 'no') : null
                    );
                @endphp

                <div class="mt-2 space-y-2 text-[12px]">

                    <label class="flex items-center gap-2">
                        <input type="radio"
                               name="objectives_met"
                               value="yes"
                               @checked($met === 'yes')>
                        Yes
                    </label>

                    <label class="flex items-center gap-2">
                        <input type="radio"
                               name="objectives_met"
                               value="no"
                               @checked($met === 'no')>
                        No
                    </label>

                </div>

            </div>


            {{-- Rating --}}
            <div>

                <label class="block text-[10px] font-medium text-blue-900 italic">
                    Rate how well you implemented your project (5 highest)
                </label>

                @php
                    $rating = old('implementation_rating', $report->implementation_rating ?? null);
                @endphp

                <div class="mt-2 flex gap-4 text-[12px]">

                    @for($i=1;$i<=5;$i++)
                    <label class="flex items-center gap-1">

                        <input type="radio"
                               name="implementation_rating"
                               value="{{ $i }}"
                               @checked($rating == $i)>

                        {{ $i }}

                    </label>
                    @endfor

                </div>

            </div>

        </div>

        <div class="border-t border-slate-300"></div>


        {{-- Attainment Explanation --}}
        <div>

            <label class="block text-[10px] font-medium text-blue-900 italic">
                What contributed to the attainment (or non-attainment) of your objectives?
            </label>

            <textarea name="contributing_factors"
                      rows="3"
                      class="w-full border border-slate-300 px-3 py-1 text-[12px]">{{ old('contributing_factors', $report->contributing_factors ?? '') }}</textarea>

        </div>


        <div class="border-t border-slate-300"></div>


        {{-- Audience Numbers --}}
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

            <div>

                <label class="block text-[10px] font-medium text-blue-900 italic">
                    Expected Number of Participants
                </label>

                <input type="number"
                       name="expected_participants"
                       value="{{ old('expected_participants', $report->expected_participants ?? '') }}"
                       class="w-full border border-slate-300 px-3 py-1 text-[12px]">

            </div>

            <div>

                <label class="block text-[10px] font-medium text-blue-900 italic">
                    Actual Number of Participants
                </label>

                <input type="number"
                       name="actual_participants"
                       value="{{ old('actual_participants', $report->actual_participants ?? '') }}"
                       class="w-full border border-slate-300 px-3 py-1 text-[12px]">

            </div>

        </div>

    </div>

</div>