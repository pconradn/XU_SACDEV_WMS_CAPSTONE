<div class="bg-white shadow-sm rounded-xl border border-slate-200 p-5">
    <h2 class="text-base font-semibold text-slate-900">Sources of Funds</h2>
    <p class="text-sm text-slate-500 mt-1">Enter the breakdown of your funding sources for this plan.</p>

    <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
        <template x-for="src in fixedFundTypes" :key="src.type">
            <div>
                <label class="block text-sm font-medium text-slate-700" x-text="src.label"></label>
                <input type="number" step="0.01" min="0"
                       class="mt-1 w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500"
                       x-model="fixedFundAmounts[src.type]"
                       :name="'fund_sources['+fundSourceIndex(src.type)+'][amount]'">
                <input type="hidden"
                       :name="'fund_sources['+fundSourceIndex(src.type)+'][type]'"
                       :value="src.type">
            </div>
        </template>
    </div>

    <div class="mt-6 flex items-center justify-between">
        <h3 class="text-sm font-semibold text-slate-800">Other Sources (optional)</h3>
        <button type="button"
                class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-sm font-medium text-slate-700 hover:bg-slate-50"
                @click="addOtherSource()">
            + Add Other Source
        </button>
    </div>

    <div class="mt-3 space-y-3">
        <template x-for="o in otherSources" :key="o._idx">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
                <div class="md:col-span-7">
                    <label class="block text-sm font-medium text-slate-700">Label</label>
                    <input type="text"
                           class="mt-1 w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500"
                           x-model="o.label"
                           :name="'fund_sources['+o._idx+'][label]'">
                </div>
                <div class="md:col-span-4">
                    <label class="block text-sm font-medium text-slate-700">Amount</label>
                    <input type="number" step="0.01" min="0"
                           class="mt-1 w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500"
                           x-model="o.amount"
                           :name="'fund_sources['+o._idx+'][amount]'">
                </div>
                <div class="md:col-span-1">
                    <input type="hidden"
                           :name="'fund_sources['+o._idx+'][type]'"
                           value="other">
                    <button type="button"
                            class="w-full rounded-lg border border-rose-200 bg-rose-50 px-3 py-2 text-sm font-medium text-rose-700 hover:bg-rose-100"
                            @click="removeOtherSource(o._idx)">
                        ✕
                    </button>
                </div>
            </div>
        </template>

        <div x-show="otherSources.length === 0" class="text-sm text-slate-500">
            No other sources added.
        </div>
    </div>

    <div class="mt-6 space-y-2">
        <div class="flex items-center justify-between text-sm">
            <span class="text-slate-600">Total Sources of Funds</span>
            <span class="font-semibold text-slate-900" x-text="formatMoney(totalFunds())"></span>
        </div>

        <div class="rounded-lg border px-3 py-2 text-sm"
             :class="(Math.abs(totalFunds() - overallTotal()) < 0.005) ? 'border-emerald-200 bg-emerald-50 text-emerald-700' : 'border-rose-200 bg-rose-50 text-rose-700'">
            Funds should match the overall budget total.
            Difference:
            <span class="font-semibold" x-text="formatMoney(totalFunds() - overallTotal())"></span>
        </div>
    </div>
</div>
