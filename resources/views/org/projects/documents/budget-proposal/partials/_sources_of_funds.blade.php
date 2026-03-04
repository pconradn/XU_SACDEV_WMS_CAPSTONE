<div class="border border-slate-300 bg-white mb-6">


    <div class="bg-slate-50 border-b border-slate-300 px-4 py-2">
        <div class="text-[12px] font-semibold text-slate-900 tracking-wide">
            SOURCES OF FUNDS
        </div>
    </div>


    <div class="px-4 py-5">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">


            <div>
                <label class="text-[11px] font-semibold text-slate-600 uppercase tracking-wide">
                    Counterpart Amount per Person
                </label>

                <input
                    type="number"
                    step="0.01"
                    name="counterpart_amount"
                    id="counterpart_amount"
                    class="mt-1 w-full border border-slate-300 px-3 py-2 text-[12px]"
                >
            </div>


            <div>
                <label class="text-[11px] font-semibold text-slate-600 uppercase tracking-wide">
                    Number of Persons
                </label>

                <input
                    type="number"
                    name="counterpart_pax"
                    id="counterpart_pax"
                    class="mt-1 w-full border border-slate-300 px-3 py-2 text-[12px]"
                >
            </div>


            <div>
                <label class="text-[11px] font-semibold text-slate-600 uppercase tracking-wide">
                    PTA Contribution
                </label>

                <input
                    type="number"
                    step="0.01"
                    name="pta"
                    id="pta"
                    class="mt-1 w-full border border-slate-300 px-3 py-2 text-[12px]"
                >
            </div>


            <div>
                <label class="text-[11px] font-semibold text-slate-600 uppercase tracking-wide">
                    Raised Funds
                </label>

                <input
                    type="number"
                    step="0.01"
                    name="raised_funds"
                    id="raised_funds"
                    class="mt-1 w-full border border-slate-300 px-3 py-2 text-[12px]"
                >
            </div>


        </div>


        <div class="mt-6 border-t border-slate-200 pt-4">

            <div class="flex justify-end items-center gap-4">

                <div class="text-[11px] font-semibold text-slate-600 uppercase tracking-wide">
                    Counterpart Total
                </div>

                <div class="text-[12px] font-semibold text-slate-900 tabular-nums">
                    ₱ <span id="counterpart_total">0.00</span>
                </div>

            </div>

        </div>

    </div>

</div>
