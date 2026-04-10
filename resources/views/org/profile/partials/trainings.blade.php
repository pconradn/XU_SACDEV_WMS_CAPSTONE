<div x-data="{ editing: {{ $errors->any() ? 'true' : 'false' }},
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
    },
    remove(index) {
        this.items.splice(index, 1);
    }
}" class="card p-4">

    <div class="flex items-center justify-between">
        <div class="card-header">Trainings / Seminars</div>

        @if($isOwner)
            <button type="button"
                    @click="editing = !editing"
                    class="text-xs text-blue-600 hover:underline">
                <span x-show="!editing">Edit</span>
                <span x-show="editing">Cancel</span>
            </button>
        @endif
    </div>

    {{-- VIEW MODE --}}
    <div x-show="!editing" class="mt-4 space-y-2">
        @forelse($profile->trainings as $t)
            <div class="rounded-lg border border-slate-200 px-3 py-2 text-sm">
                <div class="font-medium text-slate-900">{{ $t->seminar_title }}</div>
                <div class="text-xs text-slate-500">
                    {{ $t->organizer ?? '—' }} • {{ $t->venue ?? '—' }}
                </div>
                <div class="text-xs text-slate-400">
                    {{ optional($t->date_from)->format('M d, Y') ?? '—' }}
                    -
                    {{ optional($t->date_to)->format('M d, Y') ?? '—' }}
                </div>
            </div>
        @empty
            <div class="text-xs text-slate-500">No records</div>
        @endforelse
    </div>

    {{-- EDIT MODE --}}
    @if($isOwner)
    <div x-show="editing" class="mt-4 space-y-3">

        <template x-for="(item, index) in items" :key="index">
            <div class="rounded-lg border border-slate-200 p-3 space-y-2">

                <input type="text"
                       :name="`trainings[${index}][seminar_title]`"
                       x-model="item.seminar_title"
                       placeholder="Seminar Title"
                       class="w-full rounded-lg border border-slate-200 text-sm">

                <input type="text"
                       :name="`trainings[${index}][organizer]`"
                       x-model="item.organizer"
                       placeholder="Organizer"
                       class="w-full rounded-lg border border-slate-200 text-sm">

                <input type="text"
                       :name="`trainings[${index}][venue]`"
                       x-model="item.venue"
                       placeholder="Venue"
                       class="w-full rounded-lg border border-slate-200 text-sm">

                <div class="grid grid-cols-2 gap-2">
                    <input type="date"
                           :name="`trainings[${index}][date_from]`"
                           x-model="item.date_from"
                           class="w-full rounded-lg border border-slate-200 text-sm">

                    <input type="date"
                           :name="`trainings[${index}][date_to]`"
                           x-model="item.date_to"
                           class="w-full rounded-lg border border-slate-200 text-sm">
                </div>

                <div class="flex justify-end">
                    <button type="button"
                            @click="remove(index)"
                            class="text-[11px] text-rose-600 hover:underline">
                        Remove
                    </button>
                </div>

            </div>
        </template>

        <button type="button"
                @click="add()"
                class="text-xs text-blue-600 hover:underline">
            + Add Entry
        </button>

    </div>
    @endif

</div>