<div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm mb-6">

    {{-- HEADER --}}
    <div class="px-5 py-4 border-b border-slate-100 text-xs font-semibold text-slate-700 flex items-center gap-2">
        <i data-lucide="receipt" class="w-4 h-4 text-amber-600"></i>
        Official Receipts Included
    </div>

    <div class="px-5 py-4 text-xs text-slate-700 space-y-4">

        {{-- TABLE --}}
        @if($packet->receipts->count())

        <table class="w-full text-xs">
            <thead class="border-b text-slate-500">
                <tr>
                    <th class="text-left py-2 font-medium">OR Number</th>
                    <th class="text-right py-2 font-medium">Action</th>
                </tr>
            </thead>

            <tbody class="divide-y">

            @foreach($packet->receipts as $receipt)

                <tr class="hover:bg-slate-50 transition">

                    <td class="py-2 text-slate-800 font-medium">
                        OR #{{ $receipt->or_number }}
                    </td>

                    <td class="text-right">

                        @if(!$locked)
                            <button type="button"
                                onclick="deleteItem('{{ route('org.projects.packets.receipts.destroy', [$project,$packet,$receipt]) }}')"
                                class="text-rose-600 hover:text-rose-800 transition flex items-center gap-1 justify-end">
                                <i data-lucide="trash-2" class="w-3 h-3"></i>
                                Remove
                            </button>
                        @endif

                    </td>

                </tr>

            @endforeach

            </tbody>
        </table>

        @else
            <div class="text-slate-400 text-center py-3">
                No receipts added yet.
            </div>
        @endif


        {{-- FORM → NOW PART OF MAIN FORM --}}
        @if(!$locked)

        <div class="flex gap-2 items-center">

            <input
                type="text"
                name="or_number"
                value="{{ old('or_number') }}"
                placeholder="Enter OR Number"
                class="w-48 rounded-lg border border-slate-300 px-3 py-2 text-xs focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500">

            <button
                type="submit"
                name="add_receipt"
                value="1"
                class="px-3 py-2 text-xs bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition shadow-sm flex items-center gap-1">

                <i data-lucide="plus" class="w-3 h-3"></i>
                Add Receipt
            </button>

        </div>

        @endif

    </div>

</div>