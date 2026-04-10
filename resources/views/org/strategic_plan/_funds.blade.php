@php
    $fixedFundTypes = [
        ['type' => 'org_funds', 'label' => 'Student Org Funds'],
        ['type' => 'aeco', 'label' => 'AECO Fund (Finance Office)'],
        ['type' => 'pta', 'label' => 'PTA'],
        ['type' => 'membership_fee', 'label' => 'Membership Fee'],
        ['type' => 'raised_funds', 'label' => 'Raised Funds'],
    ];

    $fixedFundAmounts = [
        'org_funds' => '',
        'aeco' => '',
        'pta' => '',
        'membership_fee' => '',
        'raised_funds' => '',
    ];

    $otherSources = [];
    $nextOtherIdx = 100;

    foreach ($submission->fundSources as $fs) {
        if (array_key_exists($fs->type, $fixedFundAmounts)) {
            $fixedFundAmounts[$fs->type] = (string) $fs->amount;
        } else {
            $otherSources[] = [
                '_idx' => $nextOtherIdx++,
                'label' => $fs->label ?? '',
                'amount' => (string) $fs->amount,
            ];
        }
    }

    $oldFunds = old('fund_sources');
    if (is_array($oldFunds)) {
        $fixedFundAmounts = [
            'org_funds' => '',
            'aeco' => '',
            'pta' => '',
            'membership_fee' => '',
            'raised_funds' => '',
        ];
        $otherSources = [];
        $nextOtherIdx = 100;

        foreach ($oldFunds as $key => $fs) {
            $type = $fs['type'] ?? null;
            $label = $fs['label'] ?? '';
            $amount = $fs['amount'] ?? '';

            if ($type && array_key_exists($type, $fixedFundAmounts)) {
                $fixedFundAmounts[$type] = (string) $amount;
            } elseif ($type === 'other') {
                $otherSources[] = [
                    '_idx' => is_numeric($key) ? (int) $key : $nextOtherIdx++,
                    'label' => $label,
                    'amount' => (string) $amount,
                ];
            }
        }
    }

    $hasFunds = $submission->fundSources->count() > 0;
@endphp

<form method="POST" action="{{ route('org.rereg.b1.funds.save') }}">
    @csrf

    <div
        x-data="fundsManager(
            @js($fixedFundTypes),
            @js($fixedFundAmounts),
            @js($otherSources)
        )"
        class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden"
    >
        <div class="px-6 py-4 border-b border-slate-200 bg-gradient-to-b from-slate-50 to-white">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h2 class="text-sm font-semibold text-slate-900">
                        Sources of Funds
                    </h2>
                    <p class="text-xs text-slate-500 mt-1">
                        Enter the estimated breakdown of funding sources for this strategic plan.
                    </p>
                </div>

                <span class="text-[10px] px-2 py-0.5 rounded-md font-semibold {{ $hasFunds ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                    {{ $hasFunds ? 'Complete' : 'Incomplete' }}
                </span>
            </div>
        </div>

        <div class="px-6 py-6 space-y-6">

            @if ($errors->has('fund_sources') || $errors->has('fund_sources.*.amount') || $errors->has('fund_sources.*.label') || $errors->has('fund_sources.*.type'))
                <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3">
                    <div class="text-xs font-semibold text-rose-700">
                        Please fix the following
                    </div>
                    <ul class="mt-2 space-y-1 text-xs text-rose-600">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                <template x-for="src in fixedFundTypes" :key="src.type">
                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                        <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide" x-text="src.label"></label>

                        <div class="relative mt-2">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 text-sm">₱</span>

                            <input
                                type="text"
                                inputmode="decimal"
                                class="w-full rounded-lg border-slate-200 pl-7 pr-3 text-right text-sm focus:border-blue-500 focus:ring-blue-500"
                                :value="displayValue(fixedFundAmounts[src.type])"
                                @input="updateFixed(src.type, $event)"
                                :name="'fund_sources[' + src.type + '][amount]'"
                            >
                        </div>

                        <input
                            type="hidden"
                            :name="'fund_sources[' + src.type + '][type]'"
                            :value="src.type"
                        >

                        <p class="mt-2 text-[11px] text-slate-400">
                            Leave blank if not applicable
                        </p>
                    </div>
                </template>
            </div>

            <div class="border-t border-slate-200 pt-6">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h3 class="text-sm font-semibold text-slate-800">
                            Other Sources
                        </h3>
                        <p class="text-xs text-slate-500 mt-1">
                            Add custom funding sources if needed
                        </p>
                    </div>

                    <button
                        type="button"
                        class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-50"
                        @click="addOtherSource()"
                    >
                        + Add Other Source
                    </button>
                </div>

                <div class="mt-4 space-y-3">
                    <template x-for="o in otherSources" :key="o._idx">
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end rounded-xl border border-slate-200 bg-slate-50 p-4">
                            <div class="md:col-span-6">
                                <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide">
                                    Label
                                </label>

                                <input
                                    type="text"
                                    class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500"
                                    x-model="o.label"
                                    :name="'fund_sources[' + o._idx + '][label]'"
                                >
                            </div>

                            <div class="md:col-span-5">
                                <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide">
                                    Amount
                                </label>

                                <div class="relative mt-1">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 text-sm">₱</span>

                                    <input
                                        type="text"
                                        inputmode="decimal"
                                        class="w-full rounded-lg border-slate-200 pl-7 pr-3 text-right text-sm focus:border-blue-500 focus:ring-blue-500"
                                        :value="displayValue(o.amount)"
                                        @input="updateOther(o, $event)"
                                        :name="'fund_sources[' + o._idx + '][amount]'"
                                    >
                                </div>
                            </div>

                            <div class="md:col-span-1">
                                <input
                                    type="hidden"
                                    :name="'fund_sources[' + o._idx + '][type]'"
                                    value="other"
                                >

                                <button
                                    type="button"
                                    class="w-full rounded-lg border border-rose-200 bg-rose-50 px-3 py-2 text-sm font-medium text-rose-700 hover:bg-rose-100"
                                    @click="removeOtherSource(o._idx)"
                                >
                                    ✕
                                </button>
                            </div>
                        </div>
                    </template>

                    <div
                        x-show="otherSources.length === 0"
                        class="rounded-xl border border-dashed border-slate-200 bg-slate-50 px-4 py-5 text-center text-xs text-slate-400"
                    >
                        No other sources added
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-4 space-y-2">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-slate-600">Total Sources of Funds</span>
                    <span class="font-semibold text-slate-900" x-text="'₱ ' + formatMoney(totalFunds())"></span>
                </div>

                <div class="text-xs text-slate-500">
                    These amounts are estimates for planning purposes only.
                </div>
            </div>
        </div>

        <div class="border-t border-slate-200 bg-white px-6 py-4 flex justify-end">
            <button
                type="submit"
                class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-xs font-semibold text-white hover:bg-blue-700"
            >
                Save Funds
            </button>
        </div>
    </div>
</form>

<script>
    function fundsManager(fixedFundTypes, initialFixedFundAmounts, initialOtherSources) {
        return {
            fixedFundTypes: fixedFundTypes,
            fixedFundAmounts: { ...initialFixedFundAmounts },
            otherSources: Array.isArray(initialOtherSources) ? initialOtherSources : [],
            nextIdx: (() => {
                const ids = (Array.isArray(initialOtherSources) ? initialOtherSources : []).map(x => Number(x._idx) || 0);
                return Math.max(100, ...ids, 0) + 1;
            })(),

            normalize(raw) {
                if (raw === null || raw === undefined) return '';
                return String(raw).replace(/,/g, '').trim();
            },

            displayValue(value) {
                const raw = this.normalize(value);
                if (raw === '') return '';
                const num = Number(raw);
                return Number.isNaN(num) ? raw : num.toLocaleString();
            },

            updateFixed(type, event) {
                this.fixedFundAmounts[type] = this.normalize(event.target.value);
            },

            updateOther(item, event) {
                item.amount = this.normalize(event.target.value);
            },

            addOtherSource() {
                this.otherSources.push({
                    _idx: this.nextIdx++,
                    label: '',
                    amount: ''
                });
            },

            removeOtherSource(idx) {
                this.otherSources = this.otherSources.filter(o => o._idx !== idx);
            },

            numeric(value) {
                const raw = this.normalize(value);
                if (raw === '') return 0;
                const num = parseFloat(raw);
                return Number.isNaN(num) ? 0 : num;
            },

            formatMoney(value) {
                return this.numeric(value).toLocaleString(undefined, {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            },

            totalFunds() {
                let total = 0;

                Object.values(this.fixedFundAmounts).forEach(v => {
                    total += this.numeric(v);
                });

                this.otherSources.forEach(o => {
                    total += this.numeric(o.amount);
                });

                return total;
            }
        };
    }
</script>