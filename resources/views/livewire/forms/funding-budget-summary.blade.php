<div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

    <div class="h-1 bg-blue-500"></div>

    <div class="p-5 space-y-6">

        <div>
            <h3 class="text-sm font-semibold text-slate-900">
                Funding & Budget Summary
            </h3>
            <p class="text-xs text-blue-700">
                Unified funding inputs (auto-syncs proposal & budget)
            </p>
        </div>

        {{-- CORE --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border border-slate-200 rounded-xl p-4">

            {{-- PTA --}}
            <div>
                <label class="block text-xs font-medium text-slate-700 mb-1">
                    PTA Contribution
                </label>

                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">₱</span>

                    <input type="text"
                        wire:model.lazy="pta"
                        class="w-full pl-7 pr-3 rounded-lg border border-slate-300 text-sm text-right"
                        inputmode="decimal"
                        oninput="this.value = this.value.replace(/[^0-9.,]/g, '')"
                        onblur="this.value = Number(this.value.replace(/,/g,'') || 0).toLocaleString('en-PH', {minimumFractionDigits:2, maximumFractionDigits:2})">
                </div>
            </div>

            {{-- COUNTERPART --}}
            <div>
                <label class="block text-xs font-medium text-slate-700 mb-1">
                    Counterpart Funding
                </label>

                <div class="grid grid-cols-2 gap-2">

                    <div>
                        <label class="text-[11px] text-slate-500">Amount (₱)</label>
                        <input type="text"
                            wire:model.lazy="counterpart_amount"
                            class="w-full rounded-lg border border-slate-300 text-sm text-right"
                            inputmode="decimal"
                            oninput="this.value = this.value.replace(/[^0-9.,]/g, '')"
                            onblur="this.value = Number(this.value.replace(/,/g,'') || 0).toLocaleString('en-PH', {minimumFractionDigits:2, maximumFractionDigits:2})">
                    </div>

                    <div>
                        <label class="text-[11px] text-slate-500">No. of Participants</label>
                        <input type="number"
                            wire:model.lazy="counterpart_pax"
                            class="w-full rounded-lg border border-slate-300 text-sm text-center">
                    </div>

                </div>

                <div class="text-xs text-slate-500 mt-2">
                    Total: ₱ {{ number_format($this->counterpartTotal, 2) }}
                </div>
            </div>

        </div>

        {{-- FUND SOURCES --}}
        <div class="border border-slate-200 rounded-xl p-4">

            <div class="mb-3">
                <h4 class="text-xs font-semibold text-slate-900 uppercase">
                    Fund Sources
                </h4>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                @foreach($fund_sources as $name => $amount)

                    @if($name === 'Counterpart')
                        @continue
                    @endif

                    <div>
                        <label class="block text-xs text-slate-700 mb-1">
                            {{ $name }}
                        </label>

                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">₱</span>

                            <input type="text"
                                wire:model.lazy="fund_sources.{{ $name }}"
                                class="w-full pl-7 pr-3 rounded-lg border border-slate-300 text-sm text-right"
                                inputmode="decimal"
                                oninput="this.value = this.value.replace(/[^0-9.,]/g, '')"
                                onblur="this.value = Number(this.value.replace(/,/g,'') || 0).toLocaleString('en-PH', {minimumFractionDigits:2, maximumFractionDigits:2})">
                        </div>
                    </div>

                @endforeach

            </div>

        </div>

        {{-- TOTAL --}}
        <div class="border-t pt-4 flex justify-between items-center">

            <div>
                <div class="text-sm font-medium text-slate-900">
                    Total Budget
                </div>
            </div>

            <div class="text-xl font-bold text-emerald-600">
                ₱ {{ number_format($this->totalBudget, 2) }}
            </div>

        </div>

    </div>

    {{-- HIDDEN FIELDS --}}
    <input type="hidden" name="pta_amount" value="{{ $this->numeric($pta) }}">
    <input type="hidden" name="counterpart_amount_per_pax" value="{{ $this->numeric($counterpart_amount) }}">
    <input type="hidden" name="counterpart_pax" value="{{ (int)$counterpart_pax }}">
    <input type="hidden" name="counterpart_total" value="{{ $this->counterpartTotal }}">
    <input type="hidden" name="raised_funds" value="{{ $this->raisedTotal }}">
    <input type="hidden" name="total_budget" value="{{ $this->totalBudget }}">

    {{-- FUND SOURCES HIDDEN --}}
    @foreach($fund_sources as $name => $amount)
        @if($name === 'Counterpart')
            <input type="hidden" name="fund_sources[Counterpart]" value="{{ $this->counterpartTotal }}">
        @else
            <input type="hidden" name="fund_sources[{{ $name }}]" value="{{ $this->numeric($amount) }}">
        @endif
    @endforeach

</div>