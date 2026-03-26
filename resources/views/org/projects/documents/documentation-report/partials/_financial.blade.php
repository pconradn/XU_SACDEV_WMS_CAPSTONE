<div>

    {{-- SECTION HEADER --}}
    <div class="mb-4">
        <h3 class="text-sm font-semibold text-slate-900 tracking-wide">
            Financial Report
        </h3>
        <p class="text-xs text-slate-500 mt-1">
            Summarize the financial performance of the project.
        </p>
    </div>


    <div class="border border-slate-200 rounded-xl bg-white p-4">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            {{-- PROPOSED BUDGET --}}
            <div>
                <label class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                    Proposed Budget
                </label>

                <p class="text-[11px] text-slate-400 mb-1">
                    Total budget approved during proposal stage.
                </p>

                <input type="number"
                    step="0.01"
                    name="proposed_budget"
                    value="{{ old('proposed_budget', $report->proposed_budget ?? '') }}"
                    placeholder="0.00"
                    class="w-full rounded-md border border-slate-200 px-3 py-2 text-sm">
            </div>


            {{-- ACTUAL BUDGET --}}
            <div>
                <label class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                    Actual Budget Spent
                </label>

                <p class="text-[11px] text-slate-400 mb-1">
                    Total amount actually used during implementation.
                </p>

                <input type="number"
                    step="0.01"
                    name="actual_budget"
                    value="{{ old('actual_budget', $report->actual_budget ?? '') }}"
                    placeholder="0.00"
                    class="w-full rounded-md border border-slate-200 px-3 py-2 text-sm">
            </div>


            {{-- BALANCE --}}
            <div>
                <label class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                    Remaining Balance
                </label>

                <p class="text-[11px] text-slate-400 mb-1">
                    Difference between proposed and actual budget.
                </p>

                <input type="number"
                    step="0.01"
                    name="balance"
                    value="{{ old('balance', $report->balance ?? '') }}"
                    placeholder="Auto-computed"
                    class="w-full rounded-md border border-slate-200 px-3 py-2 text-sm bg-slate-50">
            </div>

        </div>


        {{-- HELPER --}}
        <p class="text-[11px] text-slate-400 mt-4">
            Ensure that the financial values match your approved budget proposal and liquidation records.
        </p>

    </div>

</div>