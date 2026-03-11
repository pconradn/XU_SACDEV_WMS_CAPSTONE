<div class="border border-slate-300">

    <div class="flex justify-start px-4 pt-1">
        <div class="text-[12px] font-medium text-slate-700">
            Financial Report
        </div>
    </div>

    <div class="px-4 py-3 grid grid-cols-1 gap-6 md:grid-cols-3">

        <div>
            <label class="block text-[10px] font-medium text-blue-900 italic">
                Proposed Budget
            </label>

            <input type="number"
                   step="0.01"
                   name="proposed_budget"
                   value="{{ old('proposed_budget', $report->proposed_budget ?? '') }}"
                   class="w-full border border-slate-300 px-3 py-1 text-[12px]">
        </div>

        <div>
            <label class="block text-[10px] font-medium text-blue-900 italic">
                Actual Budget Spent
            </label>

            <input type="number"
                   step="0.01"
                   name="actual_budget"
                   value="{{ old('actual_budget', $report->actual_budget ?? '') }}"
                   class="w-full border border-slate-300 px-3 py-1 text-[12px]">
        </div>

        <div>
            <label class="block text-[10px] font-medium text-blue-900 italic">
                Balance
            </label>

            <input type="number"
                   step="0.01"
                   name="balance"
                   value="{{ old('balance', $report->balance ?? '') }}"
                   class="w-full border border-slate-300 px-3 py-1 text-[12px]">
        </div>

    </div>

</div>