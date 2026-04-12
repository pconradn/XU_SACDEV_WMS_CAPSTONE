@php
    $totalExpenses = old('total_expenses', $report->total_expenses ?? '');
    $totalAdvanced = old('total_advanced', $report->total_advanced ?? '');
    $balance = old('balance', $report->balance ?? '');
@endphp

<div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm overflow-hidden">

    <div class="h-1 bg-blue-500"></div>

    <div class="p-4 space-y-6">

        <div class="flex items-start gap-3">
            <div class="w-9 h-9 rounded-xl bg-blue-50 border border-blue-200 flex items-center justify-center">
                <i data-lucide="calculator" class="w-4 h-4 text-blue-600"></i>
            </div>

            <div>
                <h2 class="text-sm font-semibold text-slate-900 tracking-wide">
                    Financial Summary
                </h2>
                <p class="text-xs text-blue-700 mt-1">
                    Provide a summary of total expenses, funds received, and any remaining balances or returns.
                </p>
            </div>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-4 space-y-6 hover:bg-slate-50 transition">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <div>
                    <label class="block text-[11px] font-medium text-slate-600 mb-1">
                        Total Expenses (PHP)
                    </label>

                    <input
                        type="text"
                        name="total_expenses"
                        id="totalExpenses"
                        data-money
                        readonly
                        value="{{ $totalExpenses }}"
                        placeholder="0.00"
                        class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-semibold text-right">

                    <p class="text-[11px] text-slate-400 mt-1">
                        Automatically calculated from all expense entries.
                    </p>
                </div>

                <div>
                    <label class="block text-[11px] font-medium text-slate-600 mb-1">
                        Total Funds (PHP)
                    </label>

                    <input
                        type="text"
                        name="total_advanced"
                        id="totalAdvanced"
                        data-money
                        value="{{ $totalAdvanced }}"
                        placeholder="0.00"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-xs focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-[11px] font-medium text-slate-600 mb-1">
                        Balance (PHP)
                    </label>

                    <input
                        type="text"
                        name="balance"
                        id="balance"
                        data-money
                        readonly
                        value="{{ $balance }}"
                        placeholder="0.00"
                        class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-semibold text-right">

                    <p class="text-[11px] text-slate-400 mt-1">
                        Automatically calculated (Advanced - Expenses).
                    </p>
                </div>

            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <div>
                    <label class="block text-[11px] font-medium text-slate-600 mb-1">
                        Amount to be Returned (Cluster A)
                    </label>

                    <input
                        type="text"
                        name="cluster_a_return"
                        id="clusterAReturn"
                        data-money
                        value="{{ old('cluster_a_return', $report->cluster_a_return ?? '') }}"
                        placeholder="0.00"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-xs focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-[11px] font-medium text-slate-600 mb-1">
                        Amount to be Returned (Cluster B)
                    </label>

                    <input
                        type="text"
                        name="cluster_b_return"
                        id="clusterBReturn"
                        data-money
                        value="{{ old('cluster_b_return', $report->cluster_b_return ?? '') }}"
                        placeholder="0.00"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-xs focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>

            </div>

            <div>
                <p id="returnWarning" class="text-xs text-rose-600 hidden">
                    Cluster A + Cluster B must equal the Balance.
                </p>
            </div>

        </div>

    </div>

</div>