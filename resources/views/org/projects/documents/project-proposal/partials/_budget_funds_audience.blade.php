<div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

        {{-- Proposed Budget --}}
        <div>
            <div class="text-sm font-semibold text-slate-900">Proposed Budget</div>
            <label class="mt-3 block text-sm font-medium text-slate-700">Total amount</label>
            <input type="number" step="0.01" min="0"
                   name="total_budget"
                   value="{{ old('total_budget') }}"
                   class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm"
                   placeholder="0.00">
        </div>

        {{-- Sources of Funds --}}
        <div>
            <div class="text-sm font-semibold text-slate-900">Sources of Funds</div>

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
                $src = old('source_of_funds');
            @endphp

            <select name="source_of_funds"
                    id="sourceFunds"
                    class="mt-3 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm"
                    required>
                <option value="" disabled @selected(!$src)>Select...</option>
                @foreach($sources as $s)
                    <option value="{{ $s }}" @selected($src === $s)>{{ $s }}</option>
                @endforeach
            </select>

            <div class="mt-4 hidden" id="counterpartWrap">
                <label class="block text-sm font-medium text-slate-700">
                    If with counterpart, how much are you collecting from each participant?
                </label>
                <input type="number" step="0.01" min="0"
                       name="counterpart_amount"
                       value="{{ old('counterpart_amount') }}"
                       class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm"
                       placeholder="0.00">
            </div>
        </div>

    </div>

    <div class="mt-6 border-t border-slate-200 pt-5">
        <div class="text-sm font-semibold text-slate-900">Target Audience / Participants / Beneficiaries</div>

        @php
            $aud = old('audience_type');
        @endphp

        <div class="mt-3 grid grid-cols-1 gap-4 lg:grid-cols-2">
            <div class="space-y-2">
                <label class="flex items-center gap-2 text-sm text-slate-700">
                    <input type="radio" name="audience_type" value="xu_community"
                           class="rounded border-slate-300"
                           @checked($aud === 'xu_community')>
                    XU Community
                </label>

                <div class="ml-6 mt-2 space-y-2" id="xuSubWrap">
                    @php $xuSubs = old('xu_subtypes', []); @endphp
                    @foreach(['Officers','Org Members','Non-Org Members','Faculty/Staff'] as $s)
                        <label class="flex items-center gap-2 text-sm text-slate-700">
                            <input type="checkbox" name="xu_subtypes[]" value="{{ $s }}"
                                   class="rounded border-slate-300"
                                   @checked(in_array($s, $xuSubs, true))>
                            {{ $s }}
                        </label>
                    @endforeach
                </div>

                <label class="flex items-center gap-2 text-sm text-slate-700">
                    <input type="radio" name="audience_type" value="non_xu_community"
                           class="rounded border-slate-300"
                           @checked($aud === 'non_xu_community')>
                    Non-XU Community
                </label>

                <label class="flex items-center gap-2 text-sm text-slate-700">
                    <input type="radio" name="audience_type" value="beneficiaries"
                           class="rounded border-slate-300"
                           @checked($aud === 'beneficiaries')>
                    Beneficiaries
                </label>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">
                    Specify details (for Non-XU / Beneficiaries)
                </label>
                <textarea name="audience_details"
                          class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm"
                          rows="3"
                          placeholder="If non-XU or beneficiaries, specify...">{{ old('audience_details') }}</textarea>
            </div>
        </div>

        <div class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label class="block text-sm font-medium text-slate-700">
                    Expected # of XU Participants (optional)
                </label>
                <input type="number" min="0"
                       name="expected_xu_participants"
                       value="{{ old('expected_xu_participants') }}"
                       class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">
                    Expected # of Non-XU Participants (optional)
                </label>
                <input type="number" min="0"
                       name="expected_non_xu_participants"
                       value="{{ old('expected_non_xu_participants') }}"
                       class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm">
            </div>
        </div>

    </div>
</div>