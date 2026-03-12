@php use Illuminate\Support\Str; @endphp

<div class="border border-slate-300">

    <div class="px-4 pt-2">
        <div class="text-[12px] font-medium text-slate-700">
            Proposed Budget
        </div>
    </div>

    <div class="px-4 pb-3 pt-1 grid grid-cols-1 gap-6 md:grid-cols-3">

        {{-- Total Budget --}}
        <div>
            <label class="block text-[10px] font-medium text-blue-900 italic">
                Total Estimated Amount:
            </label>
            <input type="number"
                step="0.01"
                min="0"
                name="total_budget"
                value="{{ old('total_budget', $proposal->total_budget ?? '') }}"
                class="mt-1 w-full border border-slate-300 bg-white px-3 py-1 text-[12px]"
                placeholder="0.00">
        </div>

        {{-- Sources of Funds --}}
        <div class="md:col-span-2">
            <label class="block text-[10px] font-medium text-blue-900 italic">
                Sources of Funds:
            </label>

            @php
                $sources = [
                    'Finance Office',
                    'PTA',
                    'OSA-SACDEV',
                    'Counterpart',
                    'Solicitation',
                    'Ticket-Selling',
                    'Others',
                ];

                $existingFunds = old('fund_sources') ?? 
                    ($proposal?->fundSources?->pluck('amount', 'source_name')->toArray() ?? []);
            @endphp

            <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-2">

                @foreach($sources as $source)

                    @php
                        $oldValue = $existingFunds[$source] ?? null;
                        $isChecked = $oldValue !== null;
                    @endphp

                    <div class="flex items-center justify-between border border-slate-200 px-3 py-2">

                        <label class="flex items-center gap-2 text-[12px] text-slate-700">
                            <input type="checkbox"
                                class="fund-source-checkbox"
                                data-target="amount-{{ Str::slug($source) }}"
                                @checked($isChecked)>
                            {{ $source }}
                        </label>

                        <input type="number"
                            step="0.01"
                            min="0"
                            name="fund_sources[{{ $source }}]"
                            value="{{ $oldValue }}"
                            placeholder="0.00"
                            id="amount-{{ Str::slug($source) }}"
                            class="fund-amount w-24 border border-slate-300 bg-white px-2 py-0 text-[12px] {{ $isChecked ? '' : 'hidden' }}">
                    </div>

                @endforeach

            </div>
        </div>

    </div>

    <div class="border-t border-slate-300"></div>

    {{-- Target Audience --}}
    <div class="px-4 pt-2">
        <div class="text-[12px] font-medium text-slate-700">
            Target Audience / Participants / Beneficiaries
        </div>
    </div>

    @php 
        $aud = old('audience_type', $proposal->audience_type ?? null); 
    @endphp

    <div class="px-4 pb-3 pt-2 grid grid-cols-1 gap-6 md:grid-cols-3">

        <div class="md:col-span-2">
            <div class="space-y-2 text-[12px] text-slate-700">

                <label class="flex items-center gap-2">
                    <input type="radio"
                        name="audience_type"
                        value="xu_community"
                        class="border-slate-300"
                        @checked($aud === 'xu_community')>
                    XU Community
                </label>

                @php 
                    $xuSubs = old('xu_subtypes') ?? 
                        (isset($proposal->xu_subtypes) ? explode(', ', $proposal->xu_subtypes) : []); 
                @endphp

                <div class="ml-6 grid grid-cols-1 md:grid-cols-2 gap-1 text-[11px]" id="xuSubWrap">
                    @foreach(['Officers','Org Members','Non-Org Members','Faculty/Staff'] as $s)
                        <label class="flex items-center gap-2">
                            <input type="checkbox"
                                name="xu_subtypes[]"
                                value="{{ $s }}"
                                class="border-slate-300"
                                @checked(in_array($s, $xuSubs, true))>
                            {{ $s }}
                        </label>
                    @endforeach
                </div>

                <label class="flex items-center gap-2">
                    <input type="radio"
                        name="audience_type"
                        value="non_xu_community"
                        class="border-slate-300"
                        @checked($aud === 'non_xu_community')>
                    Non-XU Community
                </label>

                <label class="flex items-center gap-2">
                    <input type="radio"
                        name="audience_type"
                        value="beneficiaries"
                        class="border-slate-300"
                        @checked($aud === 'beneficiaries')>
                    Beneficiaries
                </label>

            </div>
        </div>

        <div>
            <label class="block text-[10px] font-medium text-blue-900 italic">
                Specify details (for Non-XU / Beneficiaries):
            </label>
            <textarea name="audience_details"
                    class="mt-1 w-full border border-slate-300 bg-white px-3 py-1 text-[12px]"
                    rows="4"
                    placeholder="If non-XU or beneficiaries, specify...">{{ old('audience_details', $proposal->audience_details ?? '') }}</textarea>
        </div>

    </div>

    <div class="border-t border-slate-300"></div>

    <div class="px-4 pb-4 pt-3">

        <div class="flex items-center gap-8 text-[12px]">

            <div class="flex items-center gap-2">
                <label class="font-medium text-blue-900 italic whitespace-nowrap">
                    Expected # of XU Participants:
                </label>
                <input type="number"
                    min="0"
                    name="expected_xu_participants"
                    value="{{ old('expected_xu_participants', $proposal->expected_xu_participants ?? '') }}"
                    class="w-24 border border-slate-300 bg-white px-2 py-1 text-[12px]">
            </div>

            <div class="flex items-center gap-2">
                <label class="font-medium text-blue-900 italic whitespace-nowrap">
                    Expected # of Non-XU Participants:
                </label>
                <input type="number"
                    min="0"
                    name="expected_non_xu_participants"
                    value="{{ old('expected_non_xu_participants', $proposal->expected_non_xu_participants ?? '') }}"
                    class="w-24 border border-slate-300 bg-white px-2 py-1 text-[12px]">
            </div>

        </div>

    </div>

</div>