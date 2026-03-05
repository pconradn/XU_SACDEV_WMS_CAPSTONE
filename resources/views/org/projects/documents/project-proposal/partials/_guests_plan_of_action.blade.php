<div class="border border-slate-300">

    {{-- =========================
        GUESTS SECTION
    ========================== --}}
    <div class="px-4 pt-2">
        <div class="text-[12px] font-medium text-slate-700">
            Guests / Speakers / Dignitaries
        </div>
    </div>

    @php
        $hasGuests = old('has_guest_speakers');
        if ($hasGuests === null) {
            $hasGuests = isset($proposal) ? (string)($proposal->has_guest_speakers ? '1' : '0') : '0';
        }
    @endphp

    <div class="px-4 pt-2 text-[12px] text-slate-700 flex gap-6">
        <label class="flex items-center gap-2">
            <input type="radio"
                   name="has_guest_speakers"
                   value="1"
                   class="border-slate-300"
                   @checked($hasGuests === '1')>
            Yes
        </label>

        <label class="flex items-center gap-2">
            <input type="radio"
                   name="has_guest_speakers"
                   value="0"
                   class="border-slate-300"
                   @checked($hasGuests === '0')>
            No
        </label>
    </div>

    <div class="px-4 pb-3 pt-2 {{ $hasGuests === '1' ? '' : 'hidden' }}" id="guestListWrap">

        <div class="flex items-center justify-between">
            <div class="text-[11px] font-medium text-blue-900 italic">
                Guests List (Full Name, Affiliation, Designation)
            </div>

            <button type="button"
                    id="addGuestBtn"
                    class="text-[11px] text-blue-700 underline">
                + Add
            </button>
        </div>

        <div class="mt-2 space-y-2" id="guestsWrap">

            @php
                $oldGuests = old('guests')
                    ?? ($proposal?->guests?->map(fn($g) => [
                        'full_name' => $g->full_name,
                        'affiliation' => $g->affiliation,
                        'designation' => $g->designation,
                    ])->toArray() ?? []);

                if (empty($oldGuests)) {
                    $oldGuests = [['full_name'=>'','affiliation'=>'','designation'=>'']];
                }
            @endphp

            @foreach($oldGuests as $i => $g)
                <div class="grid grid-cols-1 gap-2 md:grid-cols-4 guest-row">

                    <input type="text"
                           name="guests[{{ $i }}][full_name]"
                           value="{{ $g['full_name'] ?? '' }}"
                           class="border border-slate-300 bg-white px-3 py-1 text-[12px]"
                           placeholder="Full Name">

                    <input type="text"
                           name="guests[{{ $i }}][affiliation]"
                           value="{{ $g['affiliation'] ?? '' }}"
                           class="border border-slate-300 bg-white px-3 py-1 text-[12px]"
                           placeholder="Affiliation">

                    <input type="text"
                           name="guests[{{ $i }}][designation]"
                           value="{{ $g['designation'] ?? '' }}"
                           class="border border-slate-300 bg-white px-3 py-1 text-[12px]"
                           placeholder="Designation">

                    <button type="button"
                            class="remove-btn text-red-600 text-[12px] px-2">
                        ✕
                    </button>
                </div>
            @endforeach

        </div>
    </div>

    {{-- Divider --}}
    <div class="border-t border-slate-300"></div>

    {{-- =========================
        PLAN OF ACTION
    ========================== --}}
    <div class="px-4 pt-2">
        <div class="text-[12px] font-medium text-slate-700">
            Plan of Action
        </div>
        <div class="text-[10px] text-blue-900 italic">
            For on-campus: program flow. For off-campus: itinerary then program flow.
        </div>
    </div>

    <div class="px-4 pb-4 pt-2">

        <div class="flex items-center justify-between">
            <div class="text-[11px] font-medium text-blue-900 italic">
                Rows
            </div>

            <button type="button"
                    id="addPlanBtn"
                    class="text-[11px] text-blue-700 underline">
                + Add
            </button>
        </div>

        <div class="mt-2 space-y-2" id="planWrap">

            @php
                $oldPlan = old('plan_of_actions')
                    ?? ($proposal?->planOfActions?->map(fn($p) => [
                        'date' => $p->date,
                        'time' => $p->time,
                        'activity' => $p->activity,
                        'venue' => $p->venue,
                    ])->toArray() ?? []);

                if (empty($oldPlan)) {
                    $oldPlan = [['date'=>'','time'=>'','activity'=>'','venue'=>'']];
                }
            @endphp

            @foreach($oldPlan as $i => $p)
                <div class="grid grid-cols-1 gap-2 md:grid-cols-7 plan-row">

                    <input type="date"
                           name="plan_of_actions[{{ $i }}][date]"
                           value="{{ isset($p['date']) ? \Carbon\Carbon::parse($p['date'])->format('Y-m-d') : '' }}"
                           class="border border-slate-300 bg-white px-3 py-1 text-[12px]">

                    <input type="time"
                           name="plan_of_actions[{{ $i }}][time]"
                           value="{{ optional(\Illuminate\Support\Carbon::make($p['time'] ?? null))->format('H:i') }}"
                           class="border border-slate-300 bg-white px-3 py-1 text-[12px]">

                    <input type="text"
                           name="plan_of_actions[{{ $i }}][activity]"
                           value="{{ $p['activity'] ?? '' }}"
                           class="border border-slate-300 bg-white px-3 py-1 text-[12px] md:col-span-2"
                           placeholder="Activity / Particulars">

                    <input type="text"
                           name="plan_of_actions[{{ $i }}][venue]"
                           value="{{ $p['venue'] ?? '' }}"
                           class="border border-slate-300 bg-white px-3 py-1 text-[12px] md:col-span-2"
                           placeholder="Venue">

                    <button type="button"
                            class="remove-btn text-red-600 text-[12px] px-2">
                        ✕
                    </button>
                </div>
            @endforeach

        </div>
    </div>

</div>