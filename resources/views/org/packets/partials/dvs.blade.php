<div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm">

    {{-- HEADER --}}
    <div class="px-5 py-4 border-b border-slate-100 text-xs font-semibold text-slate-700 flex items-center gap-2">
        <i data-lucide="file-text" class="w-4 h-4 text-amber-600"></i>
        Disbursement Vouchers
    </div>

    <div class="px-5 py-4 text-xs text-slate-700 space-y-4">

        {{-- TABLE --}}
        @if($packet->dvs->count())

        <table class="w-full text-xs">
            <thead class="border-b text-slate-500">
                <tr>
                    <th class="text-left py-2 font-medium">Reference</th>
                    <th class="text-left py-2 font-medium">Description</th>
                    <th class="text-left py-2 font-medium">Amount</th>
                    <th class="text-right py-2 font-medium">Action</th>
                </tr>
            </thead>

            <tbody class="divide-y">

            @foreach($packet->dvs as $dv)

                <tr class="hover:bg-slate-50 transition">

                    <td class="py-2 font-medium text-slate-800">
                        {{ $dv->dv_reference }}
                    </td>

                    <td class="py-2 text-slate-600">
                        {{ $dv->dv_label }}
                    </td>

                    <td class="py-2 text-slate-700">
                        {{ number_format($dv->amount, 2) }}
                    </td>

                    <td class="text-right">

                        @if(!$locked)
                            <button type="button"
                                onclick="deleteItem('{{ route('org.projects.packets.dvs.destroy', [$project,$packet,$dv]) }}')"
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
                No disbursement vouchers added yet.
            </div>
        @endif


        {{-- FORM → NOW PART OF MAIN FORM --}}
        @if(!$locked)

        <div class="grid grid-cols-3 gap-3">

            <input
                type="text"
                name="dv_reference"
                value="{{ old('dv_reference') }}"
                placeholder="DV Reference"
                class="rounded-lg border border-slate-300 px-3 py-2 text-xs focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500">

            <input
                type="text"
                name="dv_label"
                value="{{ old('dv_label') }}"
                placeholder="Description"
                class="rounded-lg border border-slate-300 px-3 py-2 text-xs focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500">

            <input
                type="number"
                step="0.01"
                name="amount"
                value="{{ old('amount') }}"
                placeholder="Amount"
                class="rounded-lg border border-slate-300 px-3 py-2 text-xs focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500">

        </div>

        <button
            type="submit"
            name="add_dv"
            value="1"
            class="mt-2 px-3 py-2 text-xs bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition shadow-sm flex items-center gap-1">

            <i data-lucide="plus" class="w-3 h-3"></i>
            Add DV
        </button>

        @endif

    </div>

</div>