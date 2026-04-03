@php
    $totalExpenses = old('total_expenses', $report->total_expenses ?? '');
    $totalAdvanced = old('total_advanced', $report->total_advanced ?? '');
    $balance = old('balance', $report->balance ?? '');
@endphp

<div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

    <div class="px-6 py-5 border-b border-slate-200">
        <h2 class="text-base font-semibold text-slate-900">
            Financial Summary
        </h2>
        <p class="text-sm text-slate-500 mt-1">
            Provide a summary of total expenses, funds received, and any remaining balances or returns.
        </p>
    </div>

    <div class="px-6 py-6 grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- TOTAL EXPENSES --}}
        <div>
            <label class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
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
                class="mt-2 w-full border border-slate-300 rounded-lg px-3 py-2 text-sm bg-slate-50 font-semibold"
            >

            <p class="text-[11px] text-slate-400 mt-1">
                Automatically calculated from all expense entries.
            </p>
        </div>

        {{-- TOTAL ADVANCED --}}
        <div>
            <label class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                Total Amount Advanced (PHP)
            </label>

            <input
                type="text"
                name="total_advanced"
                id="totalAdvanced"
                data-money
                value="{{ $totalAdvanced }}"
                placeholder="0.00"
                class="mt-2 w-full border border-slate-300 rounded-lg px-3 py-2 text-sm"
            >
        </div>

        {{-- BALANCE --}}
        <div>
            <label class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
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
                class="mt-2 w-full border border-slate-300 rounded-lg px-3 py-2 text-sm bg-slate-50 font-semibold"
            >

            <p class="text-[11px] text-slate-400 mt-1">
                Automatically calculated (Advanced - Expenses).
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:col-span-2">

            {{-- CLUSTER A --}}
            <div>
                <label class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                    Amount to be Returned (Cluster A)
                </label>

                <input
                    type="text"
                    name="cluster_a_return"
                    id="clusterAReturn"
                    data-money
                    value="{{ old('cluster_a_return', $report->cluster_a_return ?? '') }}"
                    placeholder="0.00"
                    class="mt-2 w-full border border-slate-300 rounded-lg px-3 py-2 text-sm"
                >
            </div>

            {{-- CLUSTER B --}}
            <div>
                <label class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                    Amount to be Returned (Cluster B)
                </label>

                <input
                    type="text"
                    name="cluster_b_return"
                    id="clusterBReturn"
                    data-money
                    value="{{ old('cluster_b_return', $report->cluster_b_return ?? '') }}"
                    placeholder="0.00"
                    class="mt-2 w-full border border-slate-300 rounded-lg px-3 py-2 text-sm"
                >
            </div>

        </div>

        {{-- VALIDATION MESSAGE --}}
        <div class="md:col-span-2">
            <p id="returnWarning" class="text-xs text-rose-600 hidden">
                Cluster A + Cluster B must equal the Balance.
            </p>
        </div>

    </div>

</div>