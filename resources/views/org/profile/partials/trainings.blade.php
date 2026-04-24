<div x-data="{
    items: {{ json_encode(old('trainings', $profile->trainings->map(fn($t) => [
        'seminar_title' => $t->seminar_title,
        'organizer' => $t->organizer,
        'venue' => $t->venue,
        'date_from' => optional($t->date_from)->format('Y-m-d'),
        'date_to' => optional($t->date_to)->format('Y-m-d'),
    ]))) }},

    add() {
        this.items.push({
            seminar_title: '',
            organizer: '',
            venue: '',
            date_from: '',
            date_to: ''
        });
        $dispatch('mark-dirty', 'trainings');
    },

    remove(index) {
        this.items.splice(index, 1);
        $dispatch('mark-dirty', 'trainings');
    }
}" class="space-y-5">

    <div class="flex items-start justify-between">
        <div>
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 flex items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">
                    <i data-lucide="graduation-cap" class="w-4 h-4"></i>
                </div>
                <div class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                    Trainings / Seminars
                </div>
            </div>
            <div class="text-[11px] text-slate-400 mt-1">
                Professional development and attended programs
            </div>
        </div>
    </div>

    @if($errors->any())
        <div class="rounded-xl border border-rose-200 bg-rose-50 px-3 py-2 text-[11px] text-rose-700">
            Please review your training entries.
        </div>
    @endif

    <div x-show="!editingAll" class="space-y-3">

        @forelse($profile->trainings as $t)
            <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 hover:bg-slate-100 transition">

                <div class="flex items-center justify-between gap-2">
                    <div class="text-sm font-semibold text-slate-900">
                        {{ $t->seminar_title }}
                    </div>
                    <div class="text-[10px] text-slate-400">
                        {{ optional($t->date_from)->format('M d, Y') ?? '—' }} - {{ optional($t->date_to)->format('M d, Y') ?? '—' }}
                    </div>
                </div>

                <div class="text-xs text-slate-600 mt-0.5">
                    {{ $t->organizer ?? '—' }}
                </div>

                <div class="text-[11px] text-slate-400 mt-1">
                    {{ $t->venue ?? '—' }}
                </div>

            </div>
        @empty
            <div class="text-xs text-slate-500">No records</div>
        @endforelse

    </div>

    @if($isOwner)
    <div x-show="editingAll" class="space-y-4">

        <template x-for="(item, index) in items" :key="index">
            <div class="p-4 rounded-xl border border-slate-200 bg-gradient-to-b from-white to-slate-50 space-y-3">

                <div class="flex items-center justify-between">
                    <div class="text-[11px] font-semibold text-slate-500">
                        Entry <span x-text="index + 1"></span>
                    </div>
                    <button type="button"
                            @click="remove(index)"
                            class="text-[11px] text-rose-600 hover:text-rose-700 transition">
                        Remove
                    </button>
                </div>

                <div class="space-y-3">

                    <input type="text"
                           :name="`trainings[${index}][seminar_title]`"
                           x-model="item.seminar_title"
                           @input="$dispatch('mark-dirty', 'trainings')"
                           placeholder="Seminar Title"
                           class="w-full rounded-lg border border-slate-200 text-sm px-2 py-1.5 focus:ring-1 focus:ring-indigo-500">

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">

                        <input type="text"
                               :name="`trainings[${index}][organizer]`"
                               x-model="item.organizer"
                               @input="$dispatch('mark-dirty', 'trainings')"
                               placeholder="Organizer"
                               class="w-full rounded-lg border border-slate-200 text-sm px-2 py-1.5 focus:ring-1 focus:ring-indigo-500">

                        <input type="text"
                               :name="`trainings[${index}][venue]`"
                               x-model="item.venue"
                               @input="$dispatch('mark-dirty', 'trainings')"
                               placeholder="Venue"
                               class="w-full rounded-lg border border-slate-200 text-sm px-2 py-1.5 focus:ring-1 focus:ring-indigo-500">

                    </div>

                    <div class="grid grid-cols-2 gap-3">

                        <div class="space-y-1">
                            <div class="text-[10px] text-slate-400">From</div>
                            <input type="date"
                                   :name="`trainings[${index}][date_from]`"
                                   x-model="item.date_from"
                                   @input="$dispatch('mark-dirty', 'trainings')"
                                   class="w-full rounded-lg border border-slate-200 text-sm px-2 py-1.5 focus:ring-1 focus:ring-indigo-500">
                        </div>

                        <div class="space-y-1">
                            <div class="text-[10px] text-slate-400">To</div>
                            <input type="date"
                                   :name="`trainings[${index}][date_to]`"
                                   x-model="item.date_to"
                                   @input="$dispatch('mark-dirty', 'trainings')"
                                   class="w-full rounded-lg border border-slate-200 text-sm px-2 py-1.5 focus:ring-1 focus:ring-indigo-500">
                        </div>

                    </div>

                </div>

            </div>
        </template>

        <button type="button"
                @click="add()"
                class="w-full rounded-xl border border-dashed border-slate-300 py-2 text-xs font-medium text-indigo-600 hover:bg-slate-50 transition">
            + Add Training / Seminar
        </button>

    </div>
    @endif

</div>