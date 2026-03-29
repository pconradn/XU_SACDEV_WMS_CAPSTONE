<div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

    {{-- HEADER --}}
    <div class="px-6 py-5 border-b border-slate-200">
        <h2 class="text-base font-semibold text-slate-900">
            Cash Received (Source of Funds)
        </h2>
        <p class="text-sm text-slate-500 mt-1">
            Enter all financial sources that contributed to the project. Ensure amounts match actual received funds.
        </p>
    </div>


    <div class="px-6 py-6 space-y-6">

        {{-- CLUSTERS --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- CLUSTER A --}}
            <div class="rounded-xl border border-slate-200 p-5 bg-slate-50/40">

                <div class="text-xs font-semibold text-slate-600 uppercase tracking-wide mb-4 text-center">
                    Cluster A (Internal Sources)
                </div>

                <div class="space-y-4">

                    {{-- XU FINANCE --}}
                    <div>
                        <label class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                            XU Finance
                        </label>
                        <input
                            type="number"
                            name="finance_amount"
                            step="0.01"
                            value="{{ old('finance_amount', $report->finance_amount ?? '') }}"
                            placeholder="0.00"
                            class="mt-2 w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        >
                        <p class="text-[11px] text-slate-400 mt-1">
                            Funds directly allocated by university finance.
                        </p>
                    </div>

                    {{-- FUND RAISING --}}
                    <div>
                        <label class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                            Fund Raising
                        </label>
                        <input
                            type="number"
                            name="fund_raising_amount"
                            step="0.01"
                            value="{{ old('fund_raising_amount', $report->fund_raising_amount ?? '') }}"
                            placeholder="0.00"
                            class="mt-2 w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        >
                        <p class="text-[11px] text-slate-400 mt-1">
                            Income generated through fundraising activities.
                        </p>
                    </div>

                    {{-- SACDEV --}}
                    <div>
                        <label class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                            SACDEV Support
                        </label>
                        <input
                            type="number"
                            name="sacdev_amount"
                            step="0.01"
                            value="{{ old('sacdev_amount', $report->sacdev_amount ?? '') }}"
                            placeholder="0.00"
                            class="mt-2 w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        >
                        <p class="text-[11px] text-slate-400 mt-1">
                            Financial assistance provided by SACDEV.
                        </p>
                    </div>

                </div>

            </div>


            {{-- CLUSTER B --}}
            <div class="rounded-xl border border-slate-200 p-5 bg-slate-50/40">

                <div class="text-xs font-semibold text-slate-600 uppercase tracking-wide mb-4 text-center">
                    Cluster B (External Sources)
                </div>

                <div>
                    <label class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                        PTA / College / Department
                    </label>

                    <input
                        type="number"
                        name="pta_amount"
                        step="0.01"
                        value="{{ old('pta_amount', $report->pta_amount ?? '') }}"
                        placeholder="0.00"
                        class="mt-2 w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    >

                    <p class="text-[11px] text-slate-400 mt-1">
                        Contributions from partner offices, departments, or external sponsors.
                    </p>
                </div>

            </div>

        </div>



        {{-- FUNDRAISING TYPES --}}
        <div class="pt-4 border-t border-slate-200">

            <div class="text-sm font-semibold text-slate-900 mb-2">
                Fundraising Breakdown
            </div>

            <p class="text-xs text-slate-500 mb-4">
                If "Fund Raising" has a value above, specify the methods used.
            </p>

            @php
                $fundraisingTypes = old(
                    'fundraising_types',
                    $report->fundraising_types ?? []
                );
            @endphp

            <div class="flex flex-wrap gap-3">

                @foreach([
                    'solicitation' => 'Solicitation',
                    'counterpart' => 'Counterpart',
                    'ticket_selling' => 'Ticket Selling',
                    'selling' => 'Selling'
                ] as $value => $label)

                <label class="flex items-center gap-2 text-sm border border-slate-200 rounded-lg px-3 py-2 hover:bg-slate-50 cursor-pointer">
                    <input type="checkbox"
                           name="fundraising_types[]"
                           value="{{ $value }}"
                           @checked(in_array($value, $fundraisingTypes))
                           class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">

                    {{ $label }}
                </label>

                @endforeach

            </div>

        </div>

    </div>

</div>