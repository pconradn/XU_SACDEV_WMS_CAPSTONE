<div class="border border-slate-300 bg-white mb-6">

    <div class="bg-slate-50 border-b border-slate-300 px-4 py-2">
        <div class="text-[12px] font-semibold text-slate-900 tracking-wide">
            SOURCES OF FUNDS
        </div>
    </div>

    <div class="px-4 py-5">

        {{-- Header --}}
        <div class="grid grid-cols-12 text-[11px] font-semibold text-slate-600 border-b border-slate-200 pb-2 mb-3">

            <div class="col-span-5">
                Source of Funds
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


        {{-- Counterpart Contribution --}}
        <div class="grid grid-cols-12 gap-2 items-center mb-2">

            <div class="col-span-5 text-[12px]">
                Counterpart Contribution
            </div>

            <div class="col-span-2">
                <input
                    type="number"
                    step="0.01"
                    name="counterpart_amount_per_pax"
                    id="counterpart_amount_per_pax"
                    value="{{ $budget->counterpart_amount_per_pax ?? '' }}"
                    class="w-full border border-slate-300 px-2 py-1 text-right text-[12px]"
                >
            </div>

            <div class="col-span-2">
                <input
                    type="number"
                    name="counterpart_pax"
                    id="counterpart_pax"
                    value="{{ $budget->counterpart_pax ?? '' }}"
                    class="w-full border border-slate-300 px-2 py-1 text-center text-[12px]"
                >
            </div>

            <div class="col-span-3 text-right font-semibold tabular-nums">

                ₱ <span id="counterpart_total_display">
                    {{ number_format($budget->counterpart_total ?? 0, 2) }}
                </span>

                <input
                    type="hidden"
                    name="counterpart_total"
                    id="counterpart_total"
                    value="{{ $budget->counterpart_total ?? 0 }}"
                >

            </div>

        </div>


        {{-- PTA Contribution --}}
        <div class="grid grid-cols-12 gap-2 items-center mb-2">

            <div class="col-span-5 text-[12px]">
                PTA Contribution
            </div>

            <div class="col-span-2">
                <input
                    type="number"
                    step="0.01"
                    name="pta_amount"
                    id="pta_amount"
                    value="{{ $budget->pta_amount ?? '' }}"
                    class="w-full border border-slate-300 px-2 py-1 text-right text-[12px]"
                >
            </div>

            <div class="col-span-2 text-center text-slate-400">
                —
            </div>

            <div class="col-span-3 text-right font-semibold tabular-nums">

                ₱ <span id="pta_total_display">
                    {{ number_format($budget->pta_amount ?? 0, 2) }}
                </span>

            </div>

        </div>


        {{-- Raised Funds --}}
        <div class="grid grid-cols-12 gap-2 items-center mb-2">

            <div class="col-span-5 text-[12px]">
                Raised Funds
                <span class="text-slate-500 text-[11px]">
                    (Solicitation, Selling, Ticket-Selling, etc)
                </span>
            </div>

            <div class="col-span-2">
                <input
                    type="number"
                    step="0.01"
                    name="raised_funds"
                    id="raised_funds"
                    value="{{ $budget->raised_funds ?? '' }}"
                    class="w-full border border-slate-300 px-2 py-1 text-right text-[12px]"
                >
            </div>

            <div class="col-span-2 text-center text-slate-400">
                —
            </div>

            <div class="col-span-3 text-right font-semibold tabular-nums">

                ₱ <span id="raised_total_display">
                    {{ number_format($budget->raised_funds ?? 0, 2) }}
                </span>

            </div>

        </div>


        {{-- Total Other Sources --}}
        <div class="grid grid-cols-12 border-t border-slate-200 mt-4 pt-2 font-semibold">

  

        </div>


        {{-- Amount Charged to Org --}}
        <div class="grid grid-cols-12 mt-2 font-bold text-[13px]">

            <div class="col-span-9 text-right">

                Total Amount Charged to the Org
                <span class="text-[11px] font-normal text-slate-500">
                    (Grand Total − Other Sources)
                </span>

            </div>

            <div class="col-span-3 text-right tabular-nums">

                ₱ <span id="org_total_display">
                    {{ number_format($budget->org_total ?? 0, 2) }}
                </span>

                <input
                    type="hidden"
                    name="org_total"
                    id="org_total"
                    value="{{ $budget->org_total ?? 0 }}"
                >

            </div>

        </div>

    </div>

</div>