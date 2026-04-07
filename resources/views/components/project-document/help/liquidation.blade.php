<div class="bg-white w-full max-w-2xl rounded-2xl shadow-xl border border-slate-200">

        {{-- HEADER --}}
        <div class="flex items-center justify-between px-5 py-3 border-b">
            <h2 class="text-sm font-semibold text-slate-800">
                Liquidation Report Guide
            </h2>

            <button onclick="closeLiquidationHelp()"
                class="text-slate-400 hover:text-slate-600 text-lg">
                ✕
            </button>
        </div>

        {{-- BODY --}}
        <div class="p-5 space-y-4 text-xs text-slate-700 max-h-[70vh] overflow-y-auto">

            {{-- FINANCIAL SUMMARY --}}
            <div class="border border-slate-200 rounded-xl p-3 bg-slate-50">
                <div class="font-semibold text-slate-800 mb-1">Financial Summary</div>
                <p>
                    The system automatically calculates totals based on your inputs.
                </p>
                <ul class="list-disc pl-4 mt-2 space-y-1">
                    <li>Total Expenses = sum of all item amounts</li>
                    <li>Total Advanced = sum of all funding sources</li>
                    <li>Balance = Total Advanced − Total Expenses</li>
                    <li><span class="font-semibold text-red-600">Cluster A + Cluster B must equal the Balance</span></li>
                </ul>
            </div>

            {{-- HOW TO FILL --}}
            <div class="border border-slate-200 rounded-xl p-3">
                <div class="font-semibold text-slate-800 mb-1">How to Fill Up</div>

                <ul class="space-y-1">
                    <li>• Name should match the Project Proposal exactly</li>
                    <li>• Indicate the actual amount advanced, collected, or generated</li>
                    <li>• Attach supporting documents (OR, SR, CI, etc.)</li>
                </ul>

                <div class="mt-2 text-[11px] text-slate-600">
                    <div><strong>A.</strong> If from Finance Office, attach Cash Advance</div>
                    <div><strong>B.</strong> If from Solicitation, Counterpart, Ticket Selling, attach reports</div>
                </div>

                <div class="mt-2 text-[11px] text-red-600">
                    If reimbursement, the project proposal should already be approved.<br>
                    Leave blank if no reimbursement.
                </div>
            </div>

            {{-- ITEMS SECTION --}}
            <div class="border border-slate-200 rounded-xl p-3">
                <div class="font-semibold text-slate-800 mb-1">Expense Table Instructions</div>

                <ul class="space-y-1">
                    <li>• Group items by category (Food, Materials, Transportation, etc.)</li>
                    <li>• Sort entries by date</li>
                    <li>• One row = one receipt or expense entry</li>
                </ul>

                <div class="mt-2 text-[11px] text-slate-600">
                    <div><strong>Amount</strong> – price paid for the item</div>
                    <div><strong>Subtotal</strong> – total per receipt</div>
                </div>

                <div class="mt-2 text-[11px] text-slate-500">
                    Arrange receipts in the same order as listed.
                </div>
            </div>

            {{-- LEGEND --}}
            <div class="border border-blue-200 rounded-xl p-3 bg-blue-50">
                <div class="font-semibold text-blue-800 mb-1">Legend (Source Documents)</div>

                <div class="grid grid-cols-2 gap-x-4 gap-y-1 text-[11px] text-blue-700">
                    <div>OR – Official Receipt</div>
                    <div>SR – Subsidiary Receipt</div>
                    <div>CI – Cash Invoice</div>
                    <div>SI – Sales Invoice</div>
                    <div>AR – Acknowledgment Receipt</div>
                    <div>PV – Payment Voucher</div>
                </div>
            </div>

        </div>

    </div>