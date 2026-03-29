<div>

    {{-- SECTION HEADER --}}
    <div class="mb-4">
        <h3 class="text-sm font-semibold text-slate-900 tracking-wide">
            Select Budget Items
        </h3>
        <p class="text-xs text-slate-500 mt-1">
            Choose which approved budget items to include in this disbursement voucher. You may adjust the amount if needed.
        </p>
    </div>


    @if($budgetItems->count())

    <div class="overflow-x-auto border border-slate-200 rounded-xl">

        <table class="min-w-full text-sm">

            <thead class="bg-slate-50">
                <tr class="text-slate-600 text-xs uppercase tracking-wide">
                    <th class="px-3 py-3 text-left">Select</th>
                    <th class="px-3 py-3 text-left">Particulars</th>
                    <th class="px-3 py-3 text-left">Section</th>
                    <th class="px-3 py-3 text-right">Budget Amount</th>
                    <th class="px-3 py-3 text-left">Charge Account</th>
                </tr>
            </thead>

            <tbody class="divide-y">

                @foreach($budgetItems as $item)

                <tr class="hover:bg-slate-50 transition">

                    {{-- CHECKBOX --}}
                     <td class="px-3 py-3 text-center">
                     <input type="checkbox"
                            class="dv-item rounded border-slate-300"
                            name="items[]"
                            value="{{ $item->id }}"
                            data-amount="{{ $item->amount }}"
                            data-id="{{ $item->id }}">
                     </td>


                    {{-- PARTICULARS --}}
                    <td class="px-3 py-3 text-slate-800">
                        {{ $item->particulars }}
                    </td>


                    {{-- SECTION --}}
                    <td class="px-3 py-3 text-slate-600 capitalize">
                        {{ str_replace('_',' ', $item->section) }}
                    </td>


                    {{-- ORIGINAL AMOUNT --}}
                    <td class="px-3 py-3 text-right font-medium text-slate-900">
                        ₱ {{ number_format($item->amount,2) }}
                    </td>





                    {{-- CHARGE ACCOUNT --}}
                    <td class="px-3 py-3">
                        <input
                            type="text"
                            name="charge_account[{{ $item->id }}]"
                            class="w-full rounded-lg border border-slate-300 px-2 py-1 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
                            placeholder="Enter account"
                        >
                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

    </div>




    @else

    <div class="text-slate-500 text-sm">
        No budget proposal items found.
    </div>

    @endif

</div>