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
@php
    $debugFinance = (float) str_replace(',', '', old('finance_amount', $report->finance_amount ?? 0));
    $debugFundRaising = (float) str_replace(',', '', old('fund_raising_amount', $report->fund_raising_amount ?? 0));
    $debugSacdev = (float) str_replace(',', '', old('sacdev_amount', $report->sacdev_amount ?? 0));
    $debugPta = (float) str_replace(',', '', old('pta_amount', $report->pta_amount ?? 0));

    $debugTotalExpenses = (float) str_replace(',', '', old('total_expenses', $report->total_expenses ?? 0));

    $debugClusterA = $debugFinance + $debugFundRaising + $debugSacdev;
    $debugClusterB = $debugPta;
    $debugTotalFunds = $debugClusterA + $debugClusterB;
    $debugBalance = $debugTotalFunds - $debugTotalExpenses;

    $debugClusterAReturn = $debugTotalFunds > 0 && $debugBalance > 0
        ? ($debugClusterA / $debugTotalFunds) * $debugBalance
        : 0;

    $debugClusterBReturn = $debugTotalFunds > 0 && $debugBalance > 0
        ? $debugBalance - $debugClusterAReturn
        : 0;
@endphp

@if(config('app.debug'))
    <div class="md:col-span-2 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-[11px] leading-5 text-amber-800">
        <div class="font-semibold mb-1">Return Calculation Debug</div>

        <div>Finance: ₱ {{ number_format($debugFinance, 2) }}</div>
        <div>Fund Raising: ₱ {{ number_format($debugFundRaising, 2) }}</div>
        <div>SACDEV: ₱ {{ number_format($debugSacdev, 2) }}</div>
        <div>PTA / College / Department: ₱ {{ number_format($debugPta, 2) }}</div>

        <div class="mt-1">Cluster A Funds: ₱ {{ number_format($debugClusterA, 2) }}</div>
        <div>Cluster B Funds: ₱ {{ number_format($debugClusterB, 2) }}</div>
        <div>Total Funds: ₱ {{ number_format($debugTotalFunds, 2) }}</div>
        <div>Total Expenses: ₱ {{ number_format($debugTotalExpenses, 2) }}</div>
        <div>Balance: ₱ {{ number_format($debugBalance, 2) }}</div>

        <div class="mt-1 font-semibold">
            Correct Cluster A Return: ₱ {{ number_format($debugClusterAReturn, 2) }}
        </div>
        <div class="font-semibold">
            Correct Cluster B Return: ₱ {{ number_format($debugClusterBReturn, 2) }}
        </div>

        <div class="mt-1 text-amber-700">
            DB Cluster A Return: ₱ {{ number_format((float) str_replace(',', '', $report->cluster_a_return ?? 0), 2) }}
        </div>
        <div class="text-amber-700">
            DB Cluster B Return: ₱ {{ number_format((float) str_replace(',', '', $report->cluster_b_return ?? 0), 2) }}
        </div>
    </div>
@endif
                <input
                    type="text"
                    name="cluster_a_return"
                    id="clusterAReturn"
                    data-money
                    readonly
                    value="{{ number_format($debugClusterAReturn, 2) }}"
                    placeholder="0.00"
                    class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-semibold text-right text-slate-700">
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
                    readonly
                    value="{{ old('cluster_b_return', $report->cluster_b_return ?? '') }}"
                    placeholder="0.00"
                    class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-semibold text-right text-slate-700">
                </div>

            </div>

        <div>
            <p class="text-[11px] text-slate-400">
                Return amounts are automatically divided based on each cluster’s share of the total funds.
            </p>

            <p id="returnWarning" class="mt-1 text-xs text-rose-600 hidden">
                Return amounts could not be calculated because total funds is zero.
            </p>
        </div>

        </div>

    </div>

</div>