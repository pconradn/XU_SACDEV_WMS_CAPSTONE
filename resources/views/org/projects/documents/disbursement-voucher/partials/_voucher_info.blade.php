<div>

    {{-- SECTION HEADER --}}
    <div class="mb-4">
        <h3 class="text-sm font-semibold text-slate-900 tracking-wide">
            Voucher Information
        </h3>
        <p class="text-xs text-slate-500 mt-1">
            Provide basic details for the disbursement voucher including payment classification and transaction date.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

        {{-- PROJECT --}}
        <div>
            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide">
                Project
            </label>

            <div class="mt-2 text-sm font-medium text-slate-900">
                {{ $project->title }}
            </div>

            <p class="text-[11px] text-slate-400 mt-1">
                Associated project for this disbursement.
            </p>
        </div>


        {{-- PROJECT HEAD --}}
        <div>
            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide">
                Project Head
            </label>

            <div class="mt-2 text-sm font-medium text-slate-900">
                {{ $projectHead?->name ?? '—' }}
            </div>

            <p class="text-[11px] text-slate-400 mt-1">
                Responsible person for the project.
            </p>
        </div>


        {{-- DATE --}}
        <div>
            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide">
                Date
            </label>

            <input
                type="date"
                name="dv_date"
                value="{{ old('dv_date', now()->toDateString()) }}"
                class="mt-2 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
            >

            <p class="text-[11px] text-slate-400 mt-1">
                Date when the voucher is issued.
            </p>
        </div>


        {{-- PAYMENT TYPE --}}
        <div>
            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide">
                Payment Type
            </label>

            <select
                name="payment_type"
                class="mt-2 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
            >
                <option value="reimbursement">Reimbursement Refund</option>
                <option value="goods_services">Payment for Goods / Services</option>
                <option value="honoraria">Payroll Item / Honoraria</option>
                <option value="advance">Advance for Liquidation</option>
                <option value="others">Others</option>
            </select>

            <p class="text-[11px] text-slate-400 mt-1">
                Select the classification of the payment.
            </p>
        </div>


        {{-- PAYMENT MODE --}}
        <div>
            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide">
                Payment Mode
            </label>

            <select
                name="payment_mode"
                class="mt-2 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
            >
                <option value="cash">Cash</option>
                <option value="check">Check</option>
                <option value="fund_transfer">Fund Transfer</option>
                <option value="payroll_credit">Payroll Credit</option>
            </select>

            <p class="text-[11px] text-slate-400 mt-1">
                Choose how the payment will be released.
            </p>
        </div>

    </div>

</div>