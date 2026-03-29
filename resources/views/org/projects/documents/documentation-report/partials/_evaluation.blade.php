<div>

    {{-- SECTION HEADER --}}
    <div class="mb-4">
        <h3 class="text-sm font-semibold text-slate-900 tracking-wide">
            Evaluation
        </h3>
        <p class="text-xs text-slate-500 mt-1">
            Assess how well the project achieved its objectives and overall implementation.
        </p>
    </div>


    {{-- ================= OBJECTIVES & INDICATORS ================= --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- OBJECTIVES --}}
        <div class="border border-slate-200 rounded-xl bg-white p-4">

            <div class="mb-2">
                <label class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                    Objectives
                </label>
                <p class="text-[11px] text-slate-400">
                    List the intended goals of the project.
                </p>
            </div>

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
                <div class="flex gap-2 items-center objective-row">

                    <input type="text"
                        name="objectives[]"
                        value="{{ $obj }}"
                        placeholder="Enter objective"
                        class="w-full rounded-md border border-slate-200 px-2 py-1 text-sm">

                    <button type="button"
                        class="remove-btn text-xs text-rose-600 hover:text-rose-800 whitespace-nowrap">
                        Remove
                    </button>

                </div>
                @endforeach

            </div>

            <button type="button"
                id="addReportObjectiveBtn"
                class="mt-3 text-xs font-medium text-blue-600 hover:text-blue-700">
                + Add Objective
            </button>

        </div>


        {{-- SUCCESS INDICATORS --}}
        <div class="border border-slate-200 rounded-xl bg-white p-4">

            <div class="mb-2">
                <label class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                    Success Indicators
                </label>
                <p class="text-[11px] text-slate-400">
                    Define measurable outcomes used to evaluate success.
                </p>
            </div>

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
                <div class="flex gap-2 items-center indicator-row">

                    <input type="text"
                        name="success_indicators[]"
                        value="{{ $ind }}"
                        placeholder="Enter success indicator"
                        class="w-full rounded-md border border-slate-200 px-2 py-1 text-sm">

                    <button type="button"
                        class="remove-btn text-xs text-rose-600 hover:text-rose-800 whitespace-nowrap">
                        Remove
                    </button>

                </div>
                @endforeach

            </div>

            <button type="button"
                id="addReportIndicatorBtn"
                class="mt-3 text-xs font-medium text-blue-600 hover:text-blue-700">
                + Add Indicator
            </button>

        </div>

    </div>


    {{-- ================= PERFORMANCE ================= --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">

        {{-- OBJECTIVES MET --}}
        <div class="border border-slate-200 rounded-xl bg-white p-4">

            <label class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                Were objectives met?
            </label>

            <p class="text-[11px] text-slate-400 mb-2">
                Indicate if the project achieved its intended goals.
            </p>

            @php
                $met = old('objectives_met',
                    isset($report) ? ($report->objectives_met ? 'yes' : 'no') : null
                );
            @endphp

            <div class="flex gap-6 text-sm mt-2">

                <label class="flex items-center gap-2">
                    <input type="radio" name="objectives_met" value="yes" @checked($met === 'yes')>
                    Yes
                </label>

                <label class="flex items-center gap-2">
                    <input type="radio" name="objectives_met" value="no" @checked($met === 'no')>
                    No
                </label>

            </div>

        </div>


        {{-- RATING --}}
        <div class="border border-slate-200 rounded-xl bg-white p-4">

            <label class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                Implementation Rating
            </label>

            <p class="text-[11px] text-slate-400 mb-2">
                Rate the execution of the project (5 = highest).
            </p>

            @php
                $rating = old('implementation_rating', $report->implementation_rating ?? null);
            @endphp

            <div class="flex gap-4 text-sm mt-2">

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


    {{-- ================= EXPLANATION ================= --}}
    <div class="mt-6 border border-slate-200 rounded-xl bg-white p-4">

        <label class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
            Contributing Factors
        </label>

        <p class="text-[11px] text-slate-400 mb-2">
            Explain what contributed to the success or challenges of the project.
        </p>

        <textarea name="contributing_factors"
            rows="4"
            placeholder="Describe key factors affecting the outcome..."
            class="w-full rounded-md border border-slate-200 px-3 py-2 text-sm">{{ old('contributing_factors', $report->contributing_factors ?? '') }}</textarea>

    </div>


    {{-- ================= PARTICIPANTS ================= --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">

        <div>
            <label class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                Expected Participants
            </label>

            <input type="number"
                name="expected_participants"
                value="{{ old('expected_participants', $report->expected_participants ?? '') }}"
                class="mt-1 w-full rounded-md border border-slate-200 px-3 py-1 text-sm">
        </div>

        <div>
            <label class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                Actual Participants
            </label>

            <input type="number"
                name="actual_participants"
                value="{{ old('actual_participants', $report->actual_participants ?? '') }}"
                class="mt-1 w-full rounded-md border border-slate-200 px-3 py-1 text-sm">
        </div>

    </div>

</div>