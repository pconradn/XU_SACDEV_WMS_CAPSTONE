<div class="border border-slate-300">

    <div class="flex justify-start px-4 pt-1">
        <div class="text-[12px] font-medium text-slate-700">
            Partners / Sponsors
        </div>
    </div>

    <div class="px-4 pb-3 pt-2">

        <label class="block text-[10px] font-medium text-blue-900 italic">
            Organizations / Sponsors involved
        </label>

        @php
            $partners = old('partners')
                ?? ($report?->partners?->toArray() ?? []);

            if(empty($partners)) {
                $partners = [
                    ['name' => '', 'type' => '']
                ];
            }
        @endphp

        <div id="reportPartnersWrap" class="space-y-2">

            @foreach($partners as $i => $partner)
            <div class="grid grid-cols-1 gap-2 md:grid-cols-3 partner-row">

                <input type="text"
                       name="partners[{{ $i }}][name]"
                       value="{{ $partner['name'] ?? '' }}"
                       class="border border-slate-300 px-3 py-1 text-[12px]"
                       placeholder="Partner / Sponsor name">

                <input type="text"
                       name="partners[{{ $i }}][type]"
                       value="{{ $partner['type'] ?? '' }}"
                       class="border border-slate-300 px-3 py-1 text-[12px]"
                       placeholder="Type (optional)">

                <button type="button"
                        class="remove-btn text-red-600 text-[12px] px-2">
                    ✕
                </button>

            </div>
            @endforeach

        </div>

        <button type="button"
                id="addReportPartnerBtn"
                class="mt-2 text-[10px] text-blue-700 underline">
            + Add Partner / Sponsor
        </button>

    </div>

</div>