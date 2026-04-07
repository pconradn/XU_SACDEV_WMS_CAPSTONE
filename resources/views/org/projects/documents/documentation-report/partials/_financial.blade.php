<div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

    <div class="h-1 bg-blue-500"></div>

    <div class="p-5 space-y-6">

        <div>
            <h3 class="text-sm font-semibold text-slate-900 tracking-wide">
                Financial Report
            </h3>
            <p class="text-xs text-blue-700 mt-1">
                Summarize the financial performance of the project.
            </p>
        </div>

        @php
            $pproposal = $project->documents->where('form_type_id',1)->first()?->proposalData;
        @endphp

        <div class="border border-slate-200 rounded-xl p-4 space-y-6">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                {{-- PROPOSED BUDGET --}}
                <div>
                    <label class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                        Proposed Budget
                    </label>

                    <p class="text-[11px] text-slate-400 mb-1">
                        Total budget approved during proposal stage.
                    </p>

                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">₱</span>

                        <input type="text"
                            name="proposed_budget"
                            data-money
                            value="{{ old('proposed_budget', $report->proposed_budget ?? $pproposal->total_budget ?? '') }}"
                            placeholder="0.00"
                            class="pl-7 w-full rounded-lg px-3 py-2 text-sm border
                                {{ $errors->has('proposed_budget')
                                    ? 'border-rose-500 focus:ring-rose-500'
                                    : 'border-slate-300 focus:ring-blue-500' }}
                                focus:ring-2 focus:outline-none">
                    </div>
                </div>


                {{-- ACTUAL BUDGET --}}
                <div>
                    <label class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                        Actual Budget Spent
                    </label>

                    <p class="text-[11px] text-slate-400 mb-1">
                        Total amount actually used during implementation.
                    </p>

                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">₱</span>

                        <input type="text"
                            name="actual_budget"
                            data-money
                            value="{{ old('actual_budget', $report->actual_budget ?? '') }}"
                            placeholder="0.00"
                            class="pl-7 w-full rounded-lg px-3 py-2 text-sm border
                                {{ $errors->has('actual_budget')
                                    ? 'border-rose-500 focus:ring-rose-500'
                                    : 'border-slate-300 focus:ring-blue-500' }}
                                focus:ring-2 focus:outline-none">
                    </div>
                </div>


                {{-- BALANCE --}}
                <div>
                    <label class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                        Remaining Balance
                    </label>

                    <p class="text-[11px] text-slate-400 mb-1">
                        Difference between proposed and actual budget.
                    </p>

                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">₱</span>

                        <input type="text"
                            name="balance"
                            value="{{ old('balance', $report->balance ?? '') }}"
                            readonly
                            class="pl-7 w-full rounded-lg px-3 py-2 text-sm border border-slate-200 bg-slate-50 font-semibold text-slate-900">
                    </div>
                </div>

            </div>

            <p class="text-[11px] text-slate-400">
                Ensure that the financial values match your approved budget proposal and liquidation records.
            </p>

        </div>

    </div>

</div>


