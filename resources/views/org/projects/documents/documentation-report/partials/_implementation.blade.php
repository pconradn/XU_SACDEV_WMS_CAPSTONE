<div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

    <div class="h-1 bg-blue-500"></div>

    <div class="p-5 space-y-6">

        <div>
            <h3 class="text-sm font-semibold text-slate-900 tracking-wide">
                Project Implementation
            </h3>
            <p class="text-xs text-blue-700 mt-1">
                Describe how the project was carried out across different stages.
            </p>
        </div>


        <div class="border border-slate-200 rounded-xl p-4 space-y-6">

            {{-- PRE-IMPLEMENTATION --}}
            <div>
                <label class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                    Pre-Implementation Stage
                </label>

                <p class="text-[11px] text-slate-400 mb-2">
                    Describe planning, preparation, coordination, and setup activities before execution.
                </p>

                <textarea name="pre_implementation_stage"
                    rows="4"
                    placeholder="e.g. planning meetings, coordination with partners, securing approvals..."
                    class="w-full rounded-lg px-3 py-2 text-sm border
                        {{ $errors->has('pre_implementation_stage')
                            ? 'border-rose-500 focus:ring-rose-500'
                            : 'border-slate-300 focus:ring-blue-500' }}
                        focus:ring-2 focus:outline-none">{{ old('pre_implementation_stage', $report->pre_implementation_stage ?? '') }}</textarea>
            </div>


            {{-- IMPLEMENTATION --}}
            <div>
                <label class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                    Implementation Stage
                </label>

                <p class="text-[11px] text-slate-400 mb-2">
                    Explain how the project was executed, including activities, participation, and flow.
                </p>

                <textarea name="implementation_stage"
                    rows="4"
                    placeholder="e.g. actual conduct of the event, activities performed, participant engagement..."
                    class="w-full rounded-lg px-3 py-2 text-sm border
                        {{ $errors->has('implementation_stage')
                            ? 'border-rose-500 focus:ring-rose-500'
                            : 'border-slate-300 focus:ring-blue-500' }}
                        focus:ring-2 focus:outline-none">{{ old('implementation_stage', $report->implementation_stage ?? '') }}</textarea>
            </div>


            {{-- POST-IMPLEMENTATION --}}
            <div>
                <label class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                    Post-Implementation Stage
                </label>

                <p class="text-[11px] text-slate-400 mb-2">
                    Include evaluation, documentation, liquidation, and follow-up actions after the project.
                </p>

                <textarea name="post_implementation_stage"
                    rows="4"
                    placeholder="e.g. evaluation meetings, submission of reports, financial liquidation..."
                    class="w-full rounded-lg px-3 py-2 text-sm border
                        {{ $errors->has('post_implementation_stage')
                            ? 'border-rose-500 focus:ring-rose-500'
                            : 'border-slate-300 focus:ring-blue-500' }}
                        focus:ring-2 focus:outline-none">{{ old('post_implementation_stage', $report->post_implementation_stage ?? '') }}</textarea>
            </div>


            {{-- RECOMMENDATIONS --}}
            <div>
                <label class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                    Recommendations
                </label>

                <p class="text-[11px] text-slate-400 mb-2">
                    Provide suggestions for improving future projects or addressing observed issues.
                </p>

                <textarea name="recommendations"
                    rows="4"
                    placeholder="e.g. improve coordination, adjust timeline, increase participant engagement..."
                    class="w-full rounded-lg px-3 py-2 text-sm border
                        {{ $errors->has('recommendations')
                            ? 'border-rose-500 focus:ring-rose-500'
                            : 'border-slate-300 focus:ring-blue-500' }}
                        focus:ring-2 focus:outline-none">{{ old('recommendations', $report->recommendations ?? '') }}</textarea>
            </div>

        </div>

    </div>

</div>