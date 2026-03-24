<div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm space-y-6">

    {{-- ================= HEADER ================= --}}
    <div>
        <h3 class="text-sm font-semibold text-slate-900">
            Guests & Plan of Action
        </h3>
        <p class="text-xs text-slate-500">
            Add guest speakers and define the program flow or itinerary
        </p>
    </div>

    {{-- ================= GUESTS ================= --}}
    <div>
        <div class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-3">
            Guests / Speakers / Dignitaries
        </div>

        @php
            $hasGuests = old('has_guest_speakers');
            if ($hasGuests === null) {
                $hasGuests = isset($proposal) ? (string)($proposal->has_guest_speakers ? '1' : '0') : '0';
            }
        @endphp

        <div class="flex gap-6 text-sm text-slate-700 mb-3">
            <label class="flex items-center gap-2">
                <input type="radio" name="has_guest_speakers" value="1"
                    class="border-slate-300"
                    @checked($hasGuests === '1')>
                Yes
            </label>

            <label class="flex items-center gap-2">
                <input type="radio" name="has_guest_speakers" value="0"
                    class="border-slate-300"
                    @checked($hasGuests === '0')>
                No
            </label>
        </div>

        {{-- Guest List --}}
        <div id="guestListWrap" class="{{ $hasGuests === '1' ? '' : 'hidden' }}">

            <div class="flex items-center justify-between mb-2">
                <span class="text-xs text-slate-500">
                    Full Name • Affiliation • Designation
                </span>

                <button type="button"
                        id="addGuestBtn"
                        class="text-xs text-slate-600 hover:text-slate-900">
                    + Add
                </button>
            </div>

            <div id="guestsWrap" class="space-y-2">

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
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-2 items-center guest-row">

                        <input type="text"
                               name="guests[{{ $i }}][full_name]"
                               value="{{ $g['full_name'] ?? '' }}"
                               class="rounded-lg border border-slate-300 px-3 py-2 text-sm"
                               placeholder="Full Name">

                        <input type="text"
                               name="guests[{{ $i }}][affiliation]"
                               value="{{ $g['affiliation'] ?? '' }}"
                               class="rounded-lg border border-slate-300 px-3 py-2 text-sm"
                               placeholder="Affiliation">

                        <input type="text"
                               name="guests[{{ $i }}][designation]"
                               value="{{ $g['designation'] ?? '' }}"
                               class="rounded-lg border border-slate-300 px-3 py-2 text-sm"
                               placeholder="Designation">

                        <button type="button"
                                class="remove-btn text-slate-400 hover:text-red-600 text-sm">
                            ✕
                        </button>

                    </div>
                @endforeach

            </div>
        </div>
    </div>

    {{-- ================= PLAN OF ACTION ================= --}}
    <div class="border-t border-slate-200 pt-4">

        <div class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">
            Plan of Action
        </div>

        <p class="text-xs text-slate-400 mb-3">
            On-campus: program flow • Off-campus: itinerary then program flow
        </p>

        <div class="flex items-center justify-between mb-2">
            <span class="text-xs text-slate-500">
                Schedule Entries
            </span>

            <button type="button"
                    id="addPlanBtn"
                    class="text-xs text-slate-600 hover:text-slate-900">
                + Add
            </button>
        </div>

        <div id="planWrap" class="space-y-2">

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
                <div class="grid grid-cols-1 md:grid-cols-7 gap-2 items-center plan-row">

                    <input type="date"
                           name="plan_of_actions[{{ $i }}][date]"
                           value="{{ isset($p['date']) ? \Carbon\Carbon::parse($p['date'])->format('Y-m-d') : '' }}"
                           class="rounded-lg border border-slate-300 px-2 py-2 text-sm">

                    <input type="time"
                           name="plan_of_actions[{{ $i }}][time]"
                           value="{{ optional(\Illuminate\Support\Carbon::make($p['time'] ?? null))->format('H:i') }}"
                           class="rounded-lg border border-slate-300 px-2 py-2 text-sm">

                    <input type="text"
                           name="plan_of_actions[{{ $i }}][activity]"
                           value="{{ $p['activity'] ?? '' }}"
                           class="rounded-lg border border-slate-300 px-3 py-2 text-sm md:col-span-2"
                           placeholder="Activity">

                    <input type="text"
                           name="plan_of_actions[{{ $i }}][venue]"
                           value="{{ $p['venue'] ?? '' }}"
                           class="rounded-lg border border-slate-300 px-3 py-2 text-sm md:col-span-2"
                           placeholder="Venue">

                    <button type="button"
                            class="remove-btn text-slate-400 hover:text-red-600 text-sm">
                        ✕
                    </button>

                </div>
            @endforeach

        </div>
    </div>

</div>