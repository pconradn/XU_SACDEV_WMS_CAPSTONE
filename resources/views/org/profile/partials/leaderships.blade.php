<div x-data="{
    editing: {{ $errors->any() ? 'true' : 'false' }},
    items: {{ json_encode(old('leaderships', $profile->leaderships->map(fn($l) => [
        'organization_name' => $l->organization_name,
        'position' => $l->position,
        'organization_address' => $l->organization_address,
        'inclusive_years' => $l->inclusive_years,
    ]))) }},
    add() {
        this.items.push({
            organization_name: '',
            position: '',
            organization_address: '',
            inclusive_years: ''
        });
    },
    remove(index) {
        this.items.splice(index, 1);
    }
}" class="card p-4">

    <div class="flex items-center justify-between">
        <div class="card-header">Leadership Experience</div>

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
        @forelse($profile->leaderships as $l)
            <div class="rounded-lg border border-slate-200 px-3 py-2 text-sm">
                <div class="font-medium text-slate-900">{{ $l->organization_name }}</div>
                <div class="text-xs text-slate-500">
                    {{ $l->position ?? '—' }} • {{ $l->inclusive_years ?? '—' }}
                </div>
                <div class="text-xs text-slate-400">
                    {{ $l->organization_address ?? '—' }}
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
                       :name="`leaderships[${index}][organization_name]`"
                       x-model="item.organization_name"
                       placeholder="Organization Name"
                       class="w-full rounded-lg border border-slate-200 text-sm">

                <input type="text"
                       :name="`leaderships[${index}][position]`"
                       x-model="item.position"
                       placeholder="Position"
                       class="w-full rounded-lg border border-slate-200 text-sm">

                <input type="text"
                       :name="`leaderships[${index}][organization_address]`"
                       x-model="item.organization_address"
                       placeholder="Organization Address"
                       class="w-full rounded-lg border border-slate-200 text-sm">

                <input type="text"
                       :name="`leaderships[${index}][inclusive_years]`"
                       x-model="item.inclusive_years"
                       placeholder="Inclusive Years"
                       class="w-full rounded-lg border border-slate-200 text-sm">

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