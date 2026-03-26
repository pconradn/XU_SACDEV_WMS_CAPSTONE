@php
    $totalExpenses = old('total_expenses', $report->total_expenses ?? '');
    $totalAdvanced = old('total_advanced', $report->total_advanced ?? '');
    $balance = old('balance', $report->balance ?? '');
@endphp

<div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

    {{-- HEADER --}}
    <div class="px-6 py-5 border-b border-slate-200">
        <h2 class="text-base font-semibold text-slate-900">
            Financial Summary
        </h2>
        <p class="text-sm text-slate-500 mt-1">
            Provide a summary of total expenses, funds received, and any remaining balances or returns.
        </p>
    </div>


    {{-- CONTENT --}}
    <div class="px-6 py-6 grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- TOTAL EXPENSES --}}
        <div>
            <label class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                Total Expenses
            </label>

            <input
                type="number"
                name="total_expenses"
                step="0.01"
                value="{{ $totalExpenses }}"
                placeholder="0.00"
                class="mt-2 w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            >

            <p class="text-[11px] text-slate-400 mt-1">
                Total amount spent based on all listed expenses.
            </p>
        </div>


        {{-- TOTAL ADVANCED --}}
        <div>
            <label class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                Total Amount Advanced
            </label>

            <input
                type="number"
                name="total_advanced"
                step="0.01"
                value="{{ $totalAdvanced }}"
                placeholder="0.00"
                class="mt-2 w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            >

            <p class="text-[11px] text-slate-400 mt-1">
                Total funds received before or during project implementation.
            </p>
        </div>


        {{-- BALANCE --}}
        <div>
            <label class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                Balance
            </label>

            <input
                type="number"
                name="balance"
                step="0.01"
                value="{{ $balance }}"
                placeholder="0.00"
                class="mt-2 w-full border border-slate-300 rounded-lg px-3 py-2 text-sm bg-slate-50"
            >

            <p class="text-[11px] text-slate-400 mt-1">
                Difference between total advanced funds and total expenses.
            </p>
        </div>


    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:col-span-2">

        {{-- CLUSTER A --}}
        <div>
            <label class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                Amount to be Returned (Cluster A)
            </label>

            <input
                type="number"
                name="cluster_a_return"
                step="0.01"
                value="{{ old('cluster_a_return', $report->cluster_a_return ?? '') }}"
                placeholder="0.00"
                class="mt-2 w-full border border-slate-300 rounded-lg px-3 py-2 text-sm"
            >

            <p class="text-[11px] text-slate-400 mt-1">
                Return amount for internal sources.
            </p>
        </div>


        {{-- CLUSTER B --}}
        <div>
            <label class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                Amount to be Returned (Cluster B)
            </label>

            <input
                type="number"
                name="cluster_b_return"
                step="0.01"
                value="{{ old('cluster_b_return', $report->cluster_b_return ?? '') }}"
                placeholder="0.00"
                class="mt-2 w-full border border-slate-300 rounded-lg px-3 py-2 text-sm"
            >

            <p class="text-[11px] text-slate-400 mt-1">
                Return amount for external sources.
            </p>
        </div>

    </div>

    </div>

</div>