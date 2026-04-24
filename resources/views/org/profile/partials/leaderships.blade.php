<div x-data="{
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
        $dispatch('mark-dirty', 'leaderships');
    },

    remove(index) {
        this.items.splice(index, 1);
        $dispatch('mark-dirty', 'leaderships');
    }
}" class="space-y-5">

    <div class="flex items-start justify-between">
        <div>
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                    <i data-lucide="users" class="w-4 h-4"></i>
                </div>
                <div class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                    Leadership Experience
                </div>
            </div>
            <div class="text-[11px] text-slate-400 mt-1">
                Organizational roles and leadership involvement
            </div>
        </div>
    </div>

    @if($errors->any())
        <div class="rounded-xl border border-rose-200 bg-rose-50 px-3 py-2 text-[11px] text-rose-700">
            Please review your leadership entries.
        </div>
    @endif

    <div x-show="!editingAll" class="space-y-3">

        @forelse($profile->leaderships as $l)
            <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 hover:bg-slate-100 transition">

                <div class="flex items-center justify-between gap-2">
                    <div class="text-sm font-semibold text-slate-900">
                        {{ $l->organization_name }}
                    </div>
                    <div class="text-[10px] text-slate-400">
                        {{ $l->inclusive_years ?? '—' }}
                    </div>
                </div>

                <div class="text-xs text-slate-600 mt-0.5">
                    {{ $l->position ?? '—' }}
                </div>

                <div class="text-[11px] text-slate-400 mt-1">
                    {{ $l->organization_address ?? '—' }}
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

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">

                    <input type="text"
                           :name="`leaderships[${index}][organization_name]`"
                           x-model="item.organization_name"
                           @input="$dispatch('mark-dirty', 'leaderships')"
                           placeholder="Organization Name"
                           class="w-full rounded-lg border border-slate-200 text-sm px-2 py-1.5 focus:ring-1 focus:ring-blue-500">

                    <input type="text"
                           :name="`leaderships[${index}][position]`"
                           x-model="item.position"
                           @input="$dispatch('mark-dirty', 'leaderships')"
                           placeholder="Position"
                           class="w-full rounded-lg border border-slate-200 text-sm px-2 py-1.5 focus:ring-1 focus:ring-blue-500">

                    <input type="text"
                           :name="`leaderships[${index}][organization_address]`"
                           x-model="item.organization_address"
                           @input="$dispatch('mark-dirty', 'leaderships')"
                           placeholder="Organization Address"
                           class="sm:col-span-2 w-full rounded-lg border border-slate-200 text-sm px-2 py-1.5 focus:ring-1 focus:ring-blue-500">

                    <input type="text"
                           :name="`leaderships[${index}][inclusive_years]`"
                           x-model="item.inclusive_years"
                           @input="$dispatch('mark-dirty', 'leaderships')"
                           placeholder="Inclusive Years (e.g. 2022–2024)"
                           class="w-full rounded-lg border border-slate-200 text-sm px-2 py-1.5 focus:ring-1 focus:ring-blue-500">

                </div>

            </div>
        </template>

        <button type="button"
                @click="add()"
                class="w-full rounded-xl border border-dashed border-slate-300 py-2 text-xs font-medium text-blue-600 hover:bg-slate-50 transition">
            + Add Leadership Entry
        </button>

    </div>
    @endif

</div>