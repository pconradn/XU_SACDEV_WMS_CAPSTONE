<div class="border border-slate-300">

    <div class="flex justify-start px-4 pt-1">
        <div class="text-[12px] font-medium text-slate-700">
            Project Implementation
        </div>
    </div>

    <div class="px-4 py-3 space-y-4">

        <div>
            <label class="block text-[10px] font-medium text-blue-900 italic">
                Pre-Implementation Stage
            </label>

            <textarea name="pre_implementation_stage"
                      rows="3"
                      class="w-full border border-slate-300 px-3 py-1 text-[12px]">{{ old('pre_implementation_stage', $report->pre_implementation_stage ?? '') }}</textarea>
        </div>


        <div>
            <label class="block text-[10px] font-medium text-blue-900 italic">
                Implementation Stage
            </label>

            <textarea name="implementation_stage"
                      rows="3"
                      class="w-full border border-slate-300 px-3 py-1 text-[12px]">{{ old('implementation_stage', $report->implementation_stage ?? '') }}</textarea>
        </div>


        <div>
            <label class="block text-[10px] font-medium text-blue-900 italic">
                Post-Implementation Stage
            </label>

            <textarea name="post_implementation_stage"
                      rows="3"
                      class="w-full border border-slate-300 px-3 py-1 text-[12px]">{{ old('post_implementation_stage', $report->post_implementation_stage ?? '') }}</textarea>
        </div>


        <div>
            <label class="block text-[10px] font-medium text-blue-900 italic">
                Recommendations
            </label>

            <textarea name="recommendations"
                      rows="3"
                      class="w-full border border-slate-300 px-3 py-1 text-[12px]">{{ old('recommendations', $report->recommendations ?? '') }}</textarea>
        </div>

    </div>

</div>