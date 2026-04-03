<div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

    <div class="h-1 bg-blue-500"></div>

    <div class="p-5 space-y-6">

        <div>
            <h3 class="text-sm font-semibold text-slate-900 tracking-wide">
                Partners / Sponsors
            </h3>
            <p class="text-xs text-blue-700 mt-1">
                List organizations or sponsors that supported or collaborated in the project.
            </p>
        </div>

        @php
            $partners = old('partners')
                ?? ($report?->partners?->toArray() ?? []);

            if (empty($partners)) {
                $partners = [
                    ['name' => '', 'type' => '']
                ];
            }
        @endphp

        <div class="border border-slate-200 rounded-xl overflow-hidden">

            <div class="overflow-auto max-h-[400px]">

                <table class="min-w-[600px] w-full text-sm border border-slate-200">

                    <thead class="bg-slate-50 text-slate-600 text-xs uppercase tracking-wide sticky top-0 z-10">
                        <tr class="border-b border-slate-200">
                            <th class="px-3 py-2 text-left border-r">Organization / Sponsor</th>
                            <th class="px-3 py-2 text-left border-r">Type / Role</th>
                            @if(!$isReadOnly)
                            <th class="px-3 py-2 text-center w-[90px]">Action</th>
                            @endif
                        </tr>
                    </thead>

                    <tbody id="reportPartnersWrap" class="divide-y divide-slate-200">

                        @foreach($partners as $i => $partner)
                        <tr class="hover:bg-slate-50 partner-row">

                            <td class="px-2 py-2 border-r">
                                <input type="text"
                                    name="partners[{{ $i }}][name]"
                                    value="{{ $partner['name'] ?? '' }}"
                                    placeholder="e.g. Red Cross, Local Barangay"
                                    class="w-full rounded-md px-2 py-1 text-sm
                                        {{ $errors->has("partners.$i.name")
                                            ? 'border-rose-500 focus:ring-rose-500'
                                            : 'border-slate-300 focus:ring-blue-500' }}
                                        border focus:ring-2 focus:outline-none">
                            </td>

                            <td class="px-2 py-2 border-r">
                                <input type="text"
                                    name="partners[{{ $i }}][type]"
                                    value="{{ $partner['type'] ?? '' }}"
                                    placeholder="e.g. Sponsor, Partner Organization"
                                    class="w-full rounded-md px-2 py-1 text-sm
                                        {{ $errors->has("partners.$i.type")
                                            ? 'border-rose-500 focus:ring-rose-500'
                                            : 'border-slate-300 focus:ring-blue-500' }}
                                        border focus:ring-2 focus:outline-none">

                                <p class="text-[10px] text-slate-400 mt-1">
                                    Optional: describe their role or contribution.
                                </p>
                            </td>

                            @if(!$isReadOnly)
                            <td class="px-2 py-2 text-center">
                                <button type="button"
                                    onclick="removePartnerRow(this)"
                                    class="text-xs text-rose-600 hover:text-rose-800 font-medium">
                                    Remove
                                </button>
                            </td>
                            @endif

                        </tr>
                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

        @if(!$isReadOnly)
        <button type="button"
            id="addReportPartnerBtn"
            class="text-xs font-semibold text-blue-600 hover:text-blue-700">
            + Add Partner / Sponsor
        </button>
        @endif

        <p class="text-[11px] text-slate-400">
            Include all external partners or sponsors that contributed to the success of the project.
        </p>

    </div>

</div>