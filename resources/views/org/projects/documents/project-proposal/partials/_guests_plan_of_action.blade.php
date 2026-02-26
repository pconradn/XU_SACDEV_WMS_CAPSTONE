<div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
    <div class="text-sm font-semibold text-slate-900">Guests / Speakers / Dignitaries</div>

    @php $hasGuests = old('has_guest_speakers', '0'); @endphp

    <div class="mt-3 flex items-center gap-6 text-sm text-slate-700">
        <label class="flex items-center gap-2">
            <input type="radio" name="has_guest_speakers" value="1"
                   class="rounded border-slate-300"
                   @checked($hasGuests === '1')>
            Yes
        </label>
        <label class="flex items-center gap-2">
            <input type="radio" name="has_guest_speakers" value="0"
                   class="rounded border-slate-300"
                   @checked($hasGuests === '0')>
            No
        </label>
    </div>

    <div class="mt-4 hidden" id="guestListWrap">
        <div class="flex items-center justify-between gap-3">
            <div class="text-sm font-semibold text-slate-800">
                Guests list (Full Name, Affiliation, Designation)
            </div>
            <button type="button"
                    class="rounded-lg bg-slate-900 px-3 py-1.5 text-xs font-semibold text-white hover:bg-slate-800"
                    onclick="addGuestRow()">
                + Add
            </button>
        </div>

        <div class="mt-2 space-y-2" id="guests">
            @php $oldGuests = old('guests', [['full_name'=>'','affiliation'=>'','designation'=>'']]); @endphp
            @foreach($oldGuests as $i => $g)
                <div class="grid grid-cols-1 gap-2 lg:grid-cols-4">
                    <input type="text" name="guests[{{ $i }}][full_name]" value="{{ $g['full_name'] ?? '' }}"
                           class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm" placeholder="Full Name">
                    <input type="text" name="guests[{{ $i }}][affiliation]" value="{{ $g['affiliation'] ?? '' }}"
                           class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm" placeholder="Affiliation">
                    <input type="text" name="guests[{{ $i }}][designation]" value="{{ $g['designation'] ?? '' }}"
                           class="rounded-lg border border-slate-300 bg-white px=3 py-2 text-sm" placeholder="Designation">
                    <button type="button"
                            class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-600 hover:bg-slate-50"
                            onclick="removeRow(this)">
                        Remove
                    </button>
                </div>
            @endforeach
        </div>
    </div>

    <div class="mt-8 border-t border-slate-200 pt-5">
        <div class="text-sm font-semibold text-slate-900">Plan of Action</div>
        <div class="mt-1 text-xs text-slate-500">
            For on-campus: program flow. For off-campus: itinerary then program flow.
        </div>

        <div class="mt-3 flex items-center justify-between gap-3">
            <div class="text-sm font-semibold text-slate-800">Rows</div>
            <button type="button"
                    class="rounded-lg bg-slate-900 px-3 py-1.5 text-xs font-semibold text-white hover:bg-slate-800"
                    onclick="addPlanRow()">
                + Add
            </button>
        </div>

        <div class="mt-2 space-y-2" id="planRows">
            @php $oldPlan = old('plan', [['date'=>'','time'=>'','activity'=>'','venue'=>'']]); @endphp
            @foreach($oldPlan as $i => $p)
                <div class="grid grid-cols-1 gap-2 lg:grid-cols-5">
                    <input type="date" name="plan[{{ $i }}][date]" value="{{ $p['date'] ?? '' }}"
                           class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm">
                    <input type="time" name="plan[{{ $i }}][time]" value="{{ $p['time'] ?? '' }}"
                           class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm">
                    <input type="text" name="plan[{{ $i }}][activity]" value="{{ $p['activity'] ?? '' }}"
                           class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm lg:col-span-2"
                           placeholder="Activity / Particulars">
                    <input type="text" name="plan[{{ $i }}][venue]" value="{{ $p['venue'] ?? '' }}"
                           class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm"
                           placeholder="Venue">
                    <button type="button"
                            class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-600 hover:bg-slate-50"
                            onclick="removeRow(this)">
                        Remove
                    </button>
                </div>
            @endforeach
        </div>
    </div>

</div>