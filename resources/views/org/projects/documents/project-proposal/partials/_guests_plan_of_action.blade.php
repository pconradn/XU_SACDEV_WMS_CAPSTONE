<div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm overflow-hidden">

    <div class="h-1 bg-gradient-to-r from-blue-500 to-blue-400"></div>

    <div class="p-4 space-y-5">

        <div class="flex items-center gap-3">
            <div class="p-2 rounded-xl bg-blue-50 border border-blue-100 text-blue-600">
                <i data-lucide="users-2" class="w-4 h-4"></i>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-slate-900">
                    Guests & Plan of Action
                </h3>
                <p class="text-[11px] text-slate-500">
                    Add guest speakers and define the program flow or itinerary
                </p>
            </div>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-4 space-y-4">

            <div class="flex items-center gap-2">
                <i data-lucide="mic" class="w-3.5 h-3.5 text-blue-600"></i>
                <span class="text-[11px] font-semibold text-slate-700 uppercase tracking-wide">
                    Guests / Speakers / Dignitaries
                </span>
            </div>

            <p class="text-[11px] text-blue-600">
                Indicate if your project will involve invited guests or speakers
            </p>

            @php
                $hasGuests = old('has_guest_speakers');
                if ($hasGuests === null) {
                    $hasGuests = isset($proposal) ? (string)($proposal->has_guest_speakers ? '1' : '0') : '0';
                }
            @endphp

            <div class="flex gap-6 text-xs text-slate-700">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="has_guest_speakers" value="1"
                        class="border-slate-300 focus:ring-blue-500"
                        @checked($hasGuests === '1')>
                    Yes
                </label>

                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="has_guest_speakers" value="0"
                        class="border-slate-300 focus:ring-blue-500"
                        @checked($hasGuests === '0')>
                    No
                </label>
            </div>

            <div id="guestListWrap" class="{{ $hasGuests === '1' ? '' : 'hidden' }} space-y-3">

                <div class="flex items-center justify-between">
                    <span class="text-[11px] text-blue-600">
                        Full Name • Affiliation • Designation
                    </span>

                    <button type="button"
                        id="addGuestBtn"
                        class="text-[11px] font-semibold text-blue-600 hover:text-blue-800 transition">
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
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-2 items-center border border-slate-200 rounded-xl p-2 bg-white hover:bg-slate-50 transition guest-row">

                            <input type="text"
                                name="guests[{{ $i }}][full_name]"
                                value="{{ $g['full_name'] ?? '' }}"
                                class="md:col-span-4 rounded-lg border border-slate-300 px-3 py-2 text-xs 
                                       focus:ring-2 focus:ring-blue-500"
                                placeholder="Full Name">

                            <input type="text"
                                name="guests[{{ $i }}][affiliation]"
                                value="{{ $g['affiliation'] ?? '' }}"
                                class="md:col-span-3 rounded-lg border border-slate-300 px-3 py-2 text-xs 
                                       focus:ring-2 focus:ring-blue-500"
                                placeholder="Affiliation">

                            <input type="text"
                                name="guests[{{ $i }}][designation]"
                                value="{{ $g['designation'] ?? '' }}"
                                class="md:col-span-3 rounded-lg border border-slate-300 px-3 py-2 text-xs 
                                       focus:ring-2 focus:ring-blue-500"
                                placeholder="Designation">

                            <div class="md:col-span-2 flex justify-end">
                                <button type="button"
                                    class="remove-btn text-xs font-medium text-slate-400 hover:text-rose-600 transition">
                                    Remove
                                </button>
                            </div>

                        </div>
                    @endforeach

                </div>

            </div>

        </div>

        <div class="rounded-xl border bg-white p-4 space-y-4
            {{ ($errors->has('plan_of_actions') || $errors->has('plan_of_actions.*')) 
                ? 'border-rose-500 ring-2 ring-rose-300' 
                : 'border-slate-200' }}">

            <div class="flex items-center gap-2">
                <i data-lucide="calendar-clock" class="w-3.5 h-3.5 text-blue-600"></i>
                <span class="text-[11px] font-semibold text-slate-700 uppercase tracking-wide">
                    Plan of Action
                </span>
            </div>

            <p class="text-[11px] text-blue-600">
                Define the sequence of activities and schedule
            </p>

            <div class="flex items-center justify-between">
                <span class="text-[11px] text-slate-500">
                    Schedule Entries
                </span>

                <button type="button"
                    id="addPlanBtn"
                    class="text-[11px] font-semibold text-blue-600 hover:text-blue-800 transition">
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
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-2 items-center border border-slate-200 rounded-xl p-2 bg-white hover:bg-slate-50 transition plan-row">

                        <input type="date"
                            name="plan_of_actions[{{ $i }}][date]"
                            value="{{ isset($p['date']) ? \Carbon\Carbon::parse($p['date'])->format('Y-m-d') : '' }}"
                            class="md:col-span-2 rounded-lg border border-slate-300 px-2 py-2 text-xs 
                                   focus:ring-2 focus:ring-blue-500">

                        <input type="time"
                            name="plan_of_actions[{{ $i }}][time]"
                            value="{{ optional(\Illuminate\Support\Carbon::make($p['time'] ?? null))->format('H:i') }}"
                            class="md:col-span-2 rounded-lg border border-slate-300 px-2 py-2 text-xs 
                                   focus:ring-2 focus:ring-blue-500">

                        <input type="text"
                            name="plan_of_actions[{{ $i }}][activity]"
                            value="{{ $p['activity'] ?? '' }}"
                            class="md:col-span-4 rounded-lg border border-slate-300 px-3 py-2 text-xs 
                                   focus:ring-2 focus:ring-blue-500"
                            placeholder="Activity">

                        <input type="text"
                            name="plan_of_actions[{{ $i }}][venue]"
                            value="{{ $p['venue'] ?? '' }}"
                            class="md:col-span-3 rounded-lg border border-slate-300 px-3 py-2 text-xs 
                                   focus:ring-2 focus:ring-blue-500"
                            placeholder="Venue">

                        <div class="md:col-span-1 flex justify-end">
                            <button type="button"
                                class="remove-btn text-xs font-medium text-slate-400 hover:text-rose-600 transition">
                                Remove
                            </button>
                        </div>

                    </div>
                @endforeach

            </div>

        </div>

    </div>

</div>