<div x-data="{ 
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
        $dispatch('mark-dirty', 'awards');
    },

    remove(index) {
        this.items.splice(index, 1);
        $dispatch('mark-dirty', 'awards');
    }
}" class="space-y-5">

    <div class="flex items-start justify-between">
        <div>
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 flex items-center justify-center rounded-lg bg-amber-50 text-amber-600">
                    <i data-lucide="award" class="w-4 h-4"></i>
                </div>
                <div class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                    Awards / Recognitions
                </div>
            </div>
            <div class="text-[11px] text-slate-400 mt-1">
                Honors, achievements, and recognitions received
            </div>
        </div>
    </div>

    @if($errors->any())
        <div class="rounded-xl border border-rose-200 bg-rose-50 px-3 py-2 text-[11px] text-rose-700">
            Please review your award entries.
        </div>
    @endif

    <div x-show="!editingAll" class="space-y-3">

        @forelse($profile->awards as $a)
            <div class="p-3 rounded-xl bg-amber-50/40 border border-amber-100 hover:bg-amber-50 transition">

                <div class="flex items-center justify-between gap-2">
                    <div class="text-sm font-semibold text-slate-900">
                        {{ $a->award_name }}
                    </div>
                    <div class="text-[10px] text-slate-400">
                        {{ optional($a->date_received)->format('M d, Y') ?? '—' }}
                    </div>
                </div>

                <div class="text-xs text-slate-600 mt-0.5">
                    {{ $a->conferred_by ?? '—' }}
                </div>

                @if($a->award_description)
                    <div class="text-[11px] text-slate-500 mt-1 leading-relaxed">
                        {{ $a->award_description }}
                    </div>
                @endif

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
                           :name="`awards[${index}][award_name]`"
                           x-model="item.award_name"
                           @input="$dispatch('mark-dirty', 'awards')"
                           placeholder="Award Name"
                           class="w-full rounded-lg border border-slate-200 text-sm px-2 py-1.5 focus:ring-1 focus:ring-amber-500">

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">

                        <input type="text"
                               :name="`awards[${index}][conferred_by]`"
                               x-model="item.conferred_by"
                               @input="$dispatch('mark-dirty', 'awards')"
                               placeholder="Conferred By"
                               class="w-full rounded-lg border border-slate-200 text-sm px-2 py-1.5 focus:ring-1 focus:ring-amber-500">

                        <div class="space-y-1">
                            <div class="text-[10px] text-slate-400">Date Received</div>
                            <input type="date"
                                   :name="`awards[${index}][date_received]`"
                                   x-model="item.date_received"
                                   @input="$dispatch('mark-dirty', 'awards')"
                                   class="w-full rounded-lg border border-slate-200 text-sm px-2 py-1.5 focus:ring-1 focus:ring-amber-500">
                        </div>

                    </div>

                    <textarea
                        :name="`awards[${index}][award_description]`"
                        x-model="item.award_description"
                        @input="$dispatch('mark-dirty', 'awards')"
                        rows="2"
                        placeholder="Description (optional)"
                        class="w-full rounded-lg border border-slate-200 text-sm px-2 py-2 focus:ring-1 focus:ring-amber-500 resize-none"></textarea>

                </div>

            </div>
        </template>

        <button type="button"
                @click="add()"
                class="w-full rounded-xl border border-dashed border-slate-300 py-2 text-xs font-medium text-amber-600 hover:bg-slate-50 transition">
            + Add Award / Recognition
        </button>

    </div>
    @endif

</div>