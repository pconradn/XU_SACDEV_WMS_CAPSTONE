<div>

    {{-- SECTION HEADER --}}
    <div class="mb-4">
        <h3 class="text-sm font-semibold text-slate-900 tracking-wide">
            Partners / Sponsors
        </h3>
        <p class="text-xs text-slate-500 mt-1">
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


    {{-- TABLE --}}
    <div class="overflow-x-auto border border-slate-200 rounded-xl">

        <table class="min-w-full text-sm">

            <thead class="bg-slate-50 text-slate-600 text-xs uppercase tracking-wide">
                <tr>
                    <th class="px-3 py-2 text-left">Organization / Sponsor</th>
                    <th class="px-3 py-2 text-left">Type / Role</th>
                    @if(!$isReadOnly)
                    <th class="px-3 py-2 text-center w-[90px]">Action</th>
                    @endif
                </tr>
            </thead>

            <tbody id="reportPartnersWrap" class="divide-y divide-slate-200">

                @foreach($partners as $i => $partner)
                <tr class="hover:bg-slate-50 partner-row">

                    {{-- NAME --}}
                    <td class="px-2 py-2">
                        <input type="text"
                            name="partners[{{ $i }}][name]"
                            value="{{ $partner['name'] ?? '' }}"
                            placeholder="e.g. Red Cross, Local Barangay"
                            class="w-full rounded-md border border-slate-200 px-2 py-1 text-sm">
                    </td>


                    {{-- TYPE --}}
                    <td class="px-2 py-2">
                        <input type="text"
                            name="partners[{{ $i }}][type]"
                            value="{{ $partner['type'] ?? '' }}"
                            placeholder="e.g. Sponsor, Partner Organization"
                            class="w-full rounded-md border border-slate-200 px-2 py-1 text-sm">

                        <p class="text-[10px] text-slate-400 mt-1">
                            Optional: describe their role or contribution.
                        </p>
                    </td>


                    {{-- ACTION --}}
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


    {{-- ADD BUTTON --}}
    @if(!$isReadOnly)
    <button type="button"
        id="addReportPartnerBtn"
        class="mt-3 text-xs font-medium text-blue-600 hover:text-blue-700">
        + Add Partner / Sponsor
    </button>
    @endif


    {{-- HELPER --}}
    <p class="text-[11px] text-slate-400 mt-2">
        Include all external partners or sponsors that contributed to the success of the project.
    </p>

</div>