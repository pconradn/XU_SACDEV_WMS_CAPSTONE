<div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden mb-6">

    {{-- HEADER --}}
    <div class="bg-slate-50 border-b border-slate-200 px-6 py-3 text-center">
        <h3 class="text-sm font-semibold text-slate-800 tracking-wide uppercase">
            Sources of Funds
        </h3>
    </div>

    <div class="px-6 py-6">

        {{-- TABLE HEADER --}}
        <div class="grid grid-cols-12 text-xs font-semibold text-slate-500 border-b border-slate-200 pb-3 mb-4">

            <div class="col-span-5">
                Source
            </div>

            <div class="col-span-2 text-right">
                Amount
            </div>

            <div class="col-span-2 text-center">
                Pax
            </div>

            <div class="col-span-3 text-right">
                Total
            </div>

        </div>

        {{-- ROWS --}}
        <div class="space-y-3">

            {{-- Counterpart --}}
            <div class="grid grid-cols-12 gap-3 items-center">

                <div class="col-span-5 text-sm text-slate-700">
                    Counterpart Contribution
                </div>

                <div class="col-span-2">
                    <input
                        type="number"
                        step="0.01"
                        name="counterpart_amount_per_pax"
                        id="counterpart_amount_per_pax"
                        value="{{ $budget->counterpart_amount_per_pax ?? '' }}"
                        class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm text-right focus:ring-2 focus:ring-slate-200"
                    >
                </div>

                <div class="col-span-2">
                    <input
                        type="number"
                        name="counterpart_pax"
                        id="counterpart_pax"
                        value="{{ $budget->counterpart_pax ?? '' }}"
                        class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm text-center focus:ring-2 focus:ring-slate-200"
                    >
                </div>

                <div class="col-span-3 text-right font-semibold text-slate-900 tabular-nums">
                    ₱ <span id="counterpart_total_display">
                        {{ number_format($budget->counterpart_total ?? 0, 2) }}
                    </span>

                    <input type="hidden" name="counterpart_total" id="counterpart_total"
                        value="{{ $budget->counterpart_total ?? 0 }}">
                </div>

            </div>

            {{-- PTA --}}
            <div class="grid grid-cols-12 gap-3 items-center">

                <div class="col-span-5 text-sm text-slate-700">
                    PTA Contribution
                </div>

                <div class="col-span-2">
                    <input
                        type="number"
                        step="0.01"
                        name="pta_amount"
                        id="pta_amount"
                        value="{{ $budget->pta_amount ?? '' }}"
                        class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm text-right focus:ring-2 focus:ring-slate-200"
                    >
                </div>

                <div class="col-span-2 text-center text-slate-400 text-sm">
                    —
                </div>

                <div class="col-span-3 text-right font-semibold text-slate-900 tabular-nums">
                    ₱ <span id="pta_total_display">
                        {{ number_format($budget->pta_amount ?? 0, 2) }}
                    </span>
                </div>

            </div>

            {{-- Raised Funds --}}
            <div class="grid grid-cols-12 gap-3 items-center">

                <div class="col-span-5 text-sm text-slate-700">
                    Raised Funds
                    <div class="text-xs text-slate-400">
                        (Solicitation, Selling, Ticket-Selling, etc.)
                    </div>
                </div>

                <div class="col-span-2">
                    <input
                        type="number"
                        step="0.01"
                        name="raised_funds"
                        id="raised_funds"
                        value="{{ $budget->raised_funds ?? '' }}"
                        class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm text-right focus:ring-2 focus:ring-slate-200"
                    >
                </div>

                <div class="col-span-2 text-center text-slate-400 text-sm">
                    —
                </div>

                <div class="col-span-3 text-right font-semibold text-slate-900 tabular-nums">
                    ₱ <span id="raised_total_display">
                        {{ number_format($budget->raised_funds ?? 0, 2) }}
                    </span>
                </div>

            </div>

        </div>

        {{-- FINAL TOTAL --}}
        <div class="border-t border-slate-200 mt-6 pt-4">

            <div class="flex justify-end">
                <div class="text-right">

                    <div class="text-xs text-slate-500">
                        Total Amount Charged to the Org
                    </div>

                    <div class="text-lg font-bold text-slate-900 tabular-nums mt-1">
                        ₱ <span id="org_total_display">
                            {{ number_format($budget->org_total ?? 0, 2) }}
                        </span>
                    </div>

                    <input type="hidden" name="org_total" id="org_total"
                        value="{{ $budget->org_total ?? 0 }}">

                </div>
            </div>

        </div>

    </div>

</div>