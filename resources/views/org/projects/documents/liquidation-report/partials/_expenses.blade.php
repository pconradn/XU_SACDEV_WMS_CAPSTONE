<div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

    {{-- HEADER --}}
    <div class="px-6 py-5 border-b border-slate-200">


        
        <h2 class="text-base font-semibold text-slate-900">
            Cash Spent (Expenses Breakdown)
        </h2>
        <p class="text-sm text-slate-500 mt-1">
            Record all expenses incurred during the project. Group them by category for better tracking and review.
        </p>
    </div>

    <div class="px-6 py-6 space-y-4">

        {{-- HELPER --}}
        <p class="text-[11px] text-slate-400">
            Enter all expenses. Amount fields will automatically format with commas. Scroll horizontally if needed on smaller screens.
        </p>


        {{-- RECEIPT INPUT SYSTEM --}}
        <div class="space-y-4">

            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-semibold text-slate-900">
                        Expense Receipts
                    </h3>
                    <p class="text-xs text-slate-500">
                        Add expenses by receipt. The table below will be generated automatically.
                    </p>
                </div>

                <button type="button"
                    id="addReceiptBtn"
                    class="text-xs px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                    + Add Receipt
                </button>
            </div>

            {{-- RECEIPT LIST --}}
            <div id="receiptList"
                class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-3">
            </div>

        </div>









        {{-- TABLE --}}
        <div class="overflow-x-auto overflow-y-auto max-h-[500px] rounded-xl border border-slate-200">

            <table class="min-w-[900px] w-full text-sm" id="expensesTable">

                <thead class="bg-slate-50 text-slate-600 text-xs uppercase tracking-wide sticky top-0 z-10">
                    <tr>
                        <th class="px-3 py-2 text-left">Date</th>
                        <th class="px-3 py-2 text-left">Particulars</th>
                        <th class="px-3 py-2 text-right">Amount (PHP)</th>
                        <th class="px-3 py-2 text-center">Type</th>
                        <th class="px-3 py-2 text-left">Description</th>
                        <th class="px-3 py-2 text-left">Reference No.</th>
                        <th class="px-3 py-2"></th>
                    </tr>
                </thead>

                <tbody id="expenseRows" class="divide-y divide-slate-100 bg-white">
                    {{-- JS will render rows --}}
                </tbody>

            </table>



        </div>

        {{-- ACTION BUTTONS --}}
        <div class="flex flex-wrap gap-3 pt-2">

            <button type="button"
                id="addSectionBtn" style="display:none"
                class="text-xs px-4 py-2 rounded-lg border border-slate-300 bg-white hover:bg-slate-50">
                + Add Section
            </button>

            <button type="button"
                id="addExpenseBtn" style="display:none"
                class="text-xs px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                + Add Expense
            </button>

        </div>

    </div>

</div>


