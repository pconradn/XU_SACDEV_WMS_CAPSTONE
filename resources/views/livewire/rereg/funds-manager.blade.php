
<div x-data="{ editing: @entangle('editing').live }">

<div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-white to-slate-50 shadow-sm overflow-hidden">
@php
    $isApproved = $submission->status === 'approved_by_sacdev';
@endphp
    {{-- HEADER --}}
    <div class="px-5 py-5 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white">
        <div class="flex items-start justify-between gap-4">

            <div class="space-y-2">
                <div class="flex items-center gap-2 text-[11px] font-semibold uppercase tracking-wide text-slate-500">
                    <i data-lucide="wallet" class="w-3.5 h-3.5"></i>
                    Financial Planning
                </div>

                <div>
                    <h2 class="text-base font-semibold text-slate-900">
                        Sources of Funds
                    </h2>
                    <p class="mt-1 text-xs text-slate-500">
                        Provide a clear breakdown of how the projects will be funded.
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2 flex-wrap">

                <span class="text-[10px] px-2.5 py-1 rounded-md font-semibold
                    {{ count($this->otherSources) || collect($fixedFundAmounts)->filter()->count()
                        ? 'bg-emerald-100 text-emerald-700 ring-1 ring-emerald-200'
                        : 'bg-amber-100 text-amber-700 ring-1 ring-amber-200' }}">
                    {{ count($this->otherSources) || collect($fixedFundAmounts)->filter()->count()
                        ? 'Complete'
                        : 'Incomplete' }}
                </span>
                
                @if($canEdit && in_array($status, ['draft','returned_by_moderator','returned_by_sacdev']))

                    <button
                        type="button"
                        x-show="!editing"
                        @click="editing = true"
                        class="inline-flex items-center gap-1 text-[11px] px-3 py-1.5 rounded-lg font-semibold
                            bg-blue-100 text-blue-700 hover:bg-blue-200 transition">
                        <i data-lucide="pencil" class="w-3 h-3"></i>
                        Enable Editing
                    </button>

                    <button
                        type="button"
                        x-show="editing"
                        @click="editing = false"
                        class="inline-flex items-center gap-1 text-[11px] px-3 py-1.5 rounded-lg font-semibold
                            bg-slate-100 text-slate-700 hover:bg-slate-200 transition">
                        <i data-lucide="x" class="w-3 h-3"></i>
                        Cancel
                    </button>

                @endif

                @if($isApproved)
                    <span class="text-[10px] px-2.5 py-1 rounded-md bg-emerald-100 text-emerald-700 font-semibold ring-1 ring-emerald-200">
                        <i data-lucide="lock" class="w-3 h-3"></i>
                        Locked
                    </span>
                @endif

            </div>

        </div>
    </div>

    <div class="px-5 py-6 space-y-6">

        @if (session('status'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-xs text-emerald-700 flex items-start gap-2">
                <i data-lucide="check-circle" class="w-4 h-4 mt-0.5"></i>
                {{ session('status') }}
            </div>
        @endif

        {{-- FIXED SOURCES --}}
        <div class="space-y-3">
            <div>
                <h3 class="text-sm font-semibold text-slate-800">
                    Fixed Sources
                </h3>
                <p class="text-[11px] text-slate-500">
                    These are standard funding categories. Enter estimated amounts.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                @foreach ($fixedFundTypes as $src)
                    <div class="rounded-2xl border border-slate-200 bg-white p-4 space-y-3 shadow-sm">

                        <label class="block text-[11px] font-semibold text-slate-600 uppercase tracking-wide">
                            {{ $src['label'] }}
                        </label>

                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">₱</span>

                            <input
                                type="text"
                                inputmode="decimal"
                                wire:model.live="fixedFundAmounts.{{ $src['type'] }}"

                                @input="$el.value = $el.value.replace(/[^0-9.]/g, '')"
                                @blur="
                                    let n = parseFloat($el.value);
                                    if (!isNaN(n)) {
                                        $el.value = n.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                                    }
                                "
                                class="w-full rounded-lg border-slate-200 pl-7 pr-3 text-right text-sm focus:border-blue-500 focus:ring-blue-500"
                                @disabled(!$canEdit || !$editing)
                            >
                        </div>

                        <p class="text-[10px] text-slate-400">
                            Enter amount in PHP
                        </p>

                        @error('fixedFundAmounts.' . $src['type'])
                            <p class="text-[11px] text-rose-600">{{ $message }}</p>
                        @enderror

                    </div>
                @endforeach
            </div>
        </div>

        <div class="border-t border-slate-200 pt-6">

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

                {{-- LEFT: OTHER SOURCES --}}
                <div class="lg:col-span-2 space-y-4">

                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-semibold text-slate-800">
                                Other Sources
                            </h3>
                            <p class="text-[11px] text-slate-500">
                                Add custom funding sources not listed above.
                            </p>
                        </div>

                        @if($canEdit && $editing)
                            <button
                                type="button"
                                wire:click="addOtherSource"
                                class="inline-flex items-center gap-1 text-[11px] px-3 py-1.5 rounded-lg font-semibold
                                    bg-blue-100 text-blue-700 hover:bg-blue-200 transition">
                                + Add
                            </button>
                        @endif
                    </div>

                    <div class="space-y-3">

                        @foreach ($otherSources as $index => $source)

                            <div class="flex items-center gap-3">

                                {{-- LABEL --}}
                                <div class="flex-1">
                                    <input
                                        type="text"
                                        wire:model.live="otherSources.{{ $index }}.label"
                                        placeholder="Source name (e.g. Sponsorship)"
                                        class="w-full rounded-lg border-slate-200 text-sm px-3 py-2 focus:border-blue-500 focus:ring-blue-500"
                                        @disabled(!$canEdit || !$editing)
                                    >

                                    @error('otherSources.' . $index . '.label')
                                        <p class="mt-1 text-[11px] text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- AMOUNT --}}
                                <div class="w-40 relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">₱</span>

                                    <input
                                        type="text"
                                        inputmode="decimal"
                                        wire:model.live="otherSources.{{ $index }}.amount"
                                 
                                        @input="$el.value = $el.value.replace(/[^0-9.]/g, '')"
                                        @blur="
                                            let n = parseFloat($el.value);
                                            if (!isNaN(n)) {
                                                $el.value = n.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                                            }
                                        "
                                        class="w-full rounded-lg border-slate-200 pl-7 pr-3 text-sm text-right focus:border-blue-500 focus:ring-blue-500"
                                        @disabled(!$canEdit || !$editing)
                                    >

                                    @error('otherSources.' . $index . '.amount')
                                        <p class="mt-1 text-[11px] text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- REMOVE --}}
                                <div class="w-8 flex justify-center">
                                    @if($canEdit && $editing)
                                        <button
                                            type="button"
                                            wire:click="removeOtherSource({{ $index }})"
                                            class="text-rose-500 hover:text-rose-700 text-sm transition">
                                            ✕
                                        </button>
                                    @endif
                                </div>

                            </div>

                        @endforeach

                    </div>

                </div>

                {{-- RIGHT: TOTALS --}}
                <div class="lg:col-span-2">

                    <div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white px-4 py-4 space-y-3 h-fit">

                        <div class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                            Totals
                        </div>

                        <div class="flex justify-between text-sm">
                            <span class="text-slate-600">Project Budget</span>
                            <span class="font-semibold text-slate-900">
                                ₱ {{ number_format($projectTotal, 2) }}
                            </span>
                        </div>

                        <div class="flex justify-between text-sm">
                            <span class="text-slate-600">Total Funds</span>
                            <span class="font-semibold text-slate-900">
                                ₱ {{ number_format($this->totalFunds, 2) }}
                            </span>
                        </div>

                        <div class="flex justify-between text-sm border-t pt-2">
                            <span class="text-slate-600">Difference</span>
                            <span class="font-semibold {{ $this->difference == 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                ₱ {{ number_format($this->difference, 2) }}
                            </span>
                        </div>

                        <p class="text-[11px] text-slate-400">
                           
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- SAVE --}}
    <div class="border-t border-slate-200 px-5 py-4 flex justify-between items-center bg-white">

        <div class="text-[11px] text-slate-400">
            Changes must be saved before submission
        </div>

        @if($canEdit && $editing)
            <button
                wire:click="confirmSave"
                class="inline-flex items-center gap-2 bg-blue-600 text-white text-xs px-4 py-2 rounded-lg hover:bg-blue-700 transition shadow-sm">
                <i data-lucide="save" class="w-3.5 h-3.5"></i>
                Save Funds
            </button>
        @endif

    </div>

</div>

@if($confirmingSave)
<div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">

    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 space-y-4">

        <div class="text-sm font-semibold text-amber-600">
            Warning
        </div>

        <div class="text-xs text-slate-600">
            Saving changes may reset this submission to draft and require resubmission.
        </div>

        <div class="flex justify-end gap-2 pt-2">
            <button
                wire:click="$set('confirmingSave', false)"
                class="px-3 py-1.5 text-xs rounded-lg border border-slate-200">
                Cancel
            </button>

            <button
                wire:click="saveConfirmed"
                class="px-3 py-1.5 text-xs rounded-lg bg-amber-500 text-white">
                Yes, Save
            </button>
        </div>

    </div>

</div>
@endif

</div>

