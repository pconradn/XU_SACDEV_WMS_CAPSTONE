<div x-data="{ 
    editing: {{ $errors->any() ? 'true' : 'false' }},
    items: {{ json_encode(old('awards', $profile->awards->map(fn($a) => [
        'award_name' => $a->award_name,
        'award_description' => $a->award_description,
        'conferred_by' => $a->conferred_by,
        'date_received' => optional($a->date_received)->format('Y-m-d'),
    ]))) }},
    add() {
        this.items.push({
            award_name: '',
            award_description: '',
            conferred_by: '',
            date_received: ''
        });
    },
    remove(index) {
        this.items.splice(index, 1);
    }
}" class="card p-4">

    <div class="flex items-center justify-between">
        <div class="card-header">Awards / Recognitions</div>

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
        @forelse($profile->awards as $a)
            <div class="rounded-lg border border-slate-200 px-3 py-2 text-sm">
                <div class="font-medium text-slate-900">{{ $a->award_name }}</div>
                <div class="text-xs text-slate-500">
                    {{ $a->conferred_by ?? '—' }}
                </div>
                <div class="text-xs text-slate-400">
                    {{ optional($a->date_received)->format('M d, Y') ?? '—' }}
                </div>
                @if($a->award_description)
                    <div class="text-xs text-slate-500 mt-1">
                        {{ $a->award_description }}
                    </div>
                @endif
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
                       :name="`awards[${index}][award_name]`"
                       x-model="item.award_name"
                       placeholder="Award Name"
                       class="w-full rounded-lg border border-slate-200 text-sm">

                <input type="text"
                       :name="`awards[${index}][conferred_by]`"
                       x-model="item.conferred_by"
                       placeholder="Conferred By"
                       class="w-full rounded-lg border border-slate-200 text-sm">

                <input type="date"
                       :name="`awards[${index}][date_received]`"
                       x-model="item.date_received"
                       class="w-full rounded-lg border border-slate-200 text-sm">

                <textarea
                    :name="`awards[${index}][award_description]`"
                    x-model="item.award_description"
                    rows="2"
                    placeholder="Description"
                    class="w-full rounded-lg border border-slate-200 text-sm"></textarea>

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