<div>

    {{-- SECTION HEADER --}}
    <div class="mb-4">
        <h3 class="text-sm font-semibold text-slate-900 tracking-wide">
            Tax Deduction
        </h3>
        <p class="text-xs text-slate-500 mt-1">
            Specify any applicable tax deduction. This amount will be subtracted from the total disbursement.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

        {{-- TAX AMOUNT --}}
        <div>
            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide">
                Tax Amount (₱)
            </label>

            <input
                type="number"
                step="0.01"
                id="taxInput"
                name="tax_amount"
                value="{{ old('tax_amount', 0) }}"
                class="mt-2 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none text-right"
            >

            <p class="text-[11px] text-slate-400 mt-1">
                Enter withholding tax or deductions, if applicable.
            </p>
        </div>

    </div>


    {{-- NET TOTAL --}}
    <div class="mt-5 text-right">
        <div class="text-xs text-slate-500 uppercase tracking-wide">
            Net Disbursement Amount
        </div>

        <div id="dvNetTotal"
             class="text-lg font-semibold text-slate-900">
            ₱ 0.00
        </div>
    </div>

</div>