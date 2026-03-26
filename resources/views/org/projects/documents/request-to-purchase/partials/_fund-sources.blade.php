<div>

    {{-- HEADER --}}
    <div class="mb-4">
        <h3 class="text-sm font-semibold text-slate-900 tracking-wide">
            Source of Funds
        </h3>
        <p class="text-xs text-slate-500 mt-1">
            Specify where the funding for this purchase will be sourced.
        </p>
    </div>


    @php
        $xu = old('xu_finance_amount', $data->xu_finance_amount ?? '');
        $membership = old('membership_fee_amount', $data->membership_fee_amount ?? '');
        $pta = old('pta_amount', $data->pta_amount ?? '');
        $solicitations = old('solicitations_amount', $data->solicitations_amount ?? '');
        $othersLabel = old('others_label', $data->others_label ?? '');
        $othersAmount = old('others_amount', $data->others_amount ?? '');
    @endphp


    <div class="overflow-x-auto border border-slate-200 rounded-xl">

        <table class="min-w-full text-sm">

            <thead class="bg-slate-50 text-slate-600 text-xs uppercase tracking-wide">
                <tr>
                    <th class="px-4 py-2 text-left">Fund Source</th>
                    <th class="px-4 py-2 text-left w-[200px]">Amount (₱)</th>
                </tr>
            </thead>

            <tbody class="divide-y">

                {{-- XU FINANCE --}}
                <tr>
                    <td class="px-4 py-2 text-slate-800">XU Finance</td>
                    <td class="px-4 py-2">
                        <input type="number" step="0.01"
                            name="xu_finance_amount"
                            value="{{ $xu }}"
                            oninput="updateFundTotal()"
                            class="w-full rounded border border-slate-200 px-3 py-2 text-sm"
                            placeholder="0.00">
                    </td>
                </tr>

                {{-- MEMBERSHIP --}}
                <tr>
                    <td class="px-4 py-2 text-slate-800">Membership Fee</td>
                    <td class="px-4 py-2">
                        <input type="number" step="0.01"
                            name="membership_fee_amount"
                            value="{{ $membership }}"
                            oninput="updateFundTotal()"
                            class="w-full rounded border border-slate-200 px-3 py-2 text-sm"
                            placeholder="0.00">
                    </td>
                </tr>

                {{-- PTA --}}
                <tr>
                    <td class="px-4 py-2 text-slate-800">PTA</td>
                    <td class="px-4 py-2">
                        <input type="number" step="0.01"
                            name="pta_amount"
                            value="{{ $pta }}"
                            oninput="updateFundTotal()"
                            class="w-full rounded border border-slate-200 px-3 py-2 text-sm"
                            placeholder="0.00">
                    </td>
                </tr>

                {{-- SOLICITATIONS --}}
                <tr>
                    <td class="px-4 py-2 text-slate-800">Solicitations</td>
                    <td class="px-4 py-2">
                        <input type="number" step="0.01"
                            name="solicitations_amount"
                            value="{{ $solicitations }}"
                            oninput="updateFundTotal()"
                            class="w-full rounded border border-slate-200 px-3 py-2 text-sm"
                            placeholder="0.00">
                    </td>
                </tr>

                {{-- OTHERS --}}
                <tr>
                    <td class="px-4 py-2">

                        <div class="flex flex-col gap-1">
                            <span class="text-slate-800">Others</span>

                            <input type="text"
                                name="others_label"
                                value="{{ $othersLabel }}"
                                placeholder="Specify source"
                                class="rounded border border-slate-200 px-2 py-1 text-xs">
                        </div>

                    </td>

                    <td class="px-4 py-2">
                        <input type="number" step="0.01"
                            name="others_amount"
                            value="{{ $othersAmount }}"
                            oninput="updateFundTotal()"
                            class="w-full rounded border border-slate-200 px-3 py-2 text-sm"
                            placeholder="0.00">
                    </td>
                </tr>

            </tbody>


            {{-- TOTAL --}}
            <tfoot class="bg-slate-50 border-t">
                <tr>
                    <td class="px-4 py-2 text-right font-semibold text-slate-700">
                        Total Funds
                    </td>
                    <td class="px-4 py-2">
                        <input type="text"
                            id="fundTotal"
                            readonly
                            class="w-full rounded border border-slate-200 px-3 py-2 text-sm font-semibold">
                    </td>
                </tr>
            </tfoot>

        </table>

    </div>

</div>