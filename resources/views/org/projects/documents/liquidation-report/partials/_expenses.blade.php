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

        {{-- TABLE --}}
        <div class="overflow-x-auto rounded-xl border border-slate-200">

            <table class="w-full text-sm" id="expensesTable">

                <thead class="bg-slate-50 text-slate-600 text-xs uppercase tracking-wide">
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

                <tbody id="expenseRows" class="divide-y divide-slate-100">

                    @php
                        $items = $report?->items ?? collect();
                        $grouped = $items->groupBy('section_label');
                        $index = 0;
                    @endphp

                    @foreach($grouped as $section => $rows)

                        {{-- SECTION HEADER --}}
                        <tr class="section-row bg-slate-100" data-section="{{ $section }}">
                            <td colspan="7" class="px-3 py-2 flex justify-between items-center">

                                <div class="text-sm font-semibold text-slate-700">
                                    {{ $section }}
                                </div>

                                <button type="button"
                                    class="text-xs text-red-500 hover:text-red-700 remove-section-btn">
                                    Remove Section
                                </button>

                            </td>
                        </tr>

                        {{-- ROWS --}}
                        @foreach($rows as $row)

                        <tr data-section="{{ $section }}" class="hover:bg-slate-50">

                            {{-- DATE --}}
                            <td class="px-2 py-1">
                                <input type="hidden"
                                    name="items[{{ $index }}][section_label]"
                                    value="{{ $section }}">

                                <input type="date"
                                    name="items[{{ $index }}][date]"
                                    value="{{ $row->date }}"
                                    class="w-full border border-slate-200 rounded-lg px-2 py-1 text-xs">
                            </td>

                            {{-- PARTICULARS --}}
                            <td class="px-2 py-1">
                                <input type="text"
                                    name="items[{{ $index }}][particulars]"
                                    value="{{ $row->particulars }}"
                                    placeholder="e.g. Food, Materials"
                                    class="w-full border border-slate-200 rounded-lg px-2 py-1 text-xs">
                            </td>

                            {{-- AMOUNT --}}
                            <td class="px-2 py-1">
                                <input type="number"
                                    step="0.01"
                                    name="items[{{ $index }}][amount]"
                                    value="{{ $row->amount }}"
                                    placeholder="0.00"
                                    class="w-full border border-slate-200 rounded-lg px-2 py-1 text-xs text-right">
                            </td>

                            {{-- TYPE --}}
                            <td class="px-2 py-1">
                                <select name="items[{{ $index }}][source_document_type]"
                                    class="w-full border border-slate-200 rounded-lg px-2 py-1 text-xs text-center">

                                    <option value=""></option>

                                    @foreach(['OR','SR','CI','SI','AR','PV'] as $type)
                                        <option value="{{ $type }}"
                                            @selected($row->source_document_type === $type)>
                                            {{ $type }}
                                        </option>
                                    @endforeach

                                </select>
                            </td>

                            {{-- DESCRIPTION --}}
                            <td class="px-2 py-1">
                                <input type="text"
                                    name="items[{{ $index }}][source_document_description]"
                                    value="{{ $row->source_document_description }}"
                                    placeholder="Optional details"
                                    class="w-full border border-slate-200 rounded-lg px-2 py-1 text-xs">
                            </td>

                            {{-- OR NUMBER --}}
                            <td class="px-2 py-1">
                                <input type="text"
                                    name="items[{{ $index }}][or_number]"
                                    value="{{ $row->or_number }}"
                                    placeholder="Receipt no."
                                    class="w-full border border-slate-200 rounded-lg px-2 py-1 text-xs">
                            </td>

                            {{-- ACTION --}}
                            <td class="px-2 py-1 text-center">
                                <button type="button"
                                    class="text-red-500 hover:text-red-700 text-sm remove-row-btn">
                                    ✕
                                </button>
                            </td>

                        </tr>

                        @php $index++; @endphp

                        @endforeach

                    @endforeach

                </tbody>

            </table>

        </div>


        {{-- ACTION BUTTONS --}}
        <div class="flex flex-wrap gap-3 pt-2">

            <button type="button"
                id="addSectionBtn"
                class="text-xs px-4 py-2 rounded-lg border border-slate-300 bg-white hover:bg-slate-50">
                + Add Section
            </button>

            <button type="button"
                id="addExpenseBtn"
                class="text-xs px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                + Add Expense
            </button>

        </div>


    </div>

</div>