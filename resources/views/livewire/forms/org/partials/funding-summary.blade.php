<div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

    <div class="h-1 bg-blue-500"></div>

    <div class="p-5 space-y-6">

        <div>
            <h3 class="text-sm font-semibold text-slate-900">
                Funding & Budget Summary
            </h3>
            <p class="text-xs text-blue-700">
                Unified funding inputs
            </p>
        </div>

        {{-- CORE --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border border-slate-200 rounded-xl p-4">

            {{-- PTA --}}
            <div>
                <label class="block text-xs font-medium text-slate-700 mb-1">
                    PTA Contribution
                </label>

                <input type="text"
                    wire:model.live="budget.pta_amount"
                    class="w-full rounded-lg border px-3 py-2 text-sm text-right">
            </div>

            {{-- COUNTERPART --}}
            <div>

                <label class="block text-xs font-medium text-slate-700 mb-1">
                    Counterpart Funding
                </label>

                <div class="grid grid-cols-2 gap-2">

                    <input type="text"
                        wire:model.live="budget.counterpart_amount_per_pax"
                        placeholder="Amount"
                        class="rounded-lg border px-2 py-2 text-sm text-right">

                    <input type="number"
                        wire:model.live="budget.counterpart_pax"
                        placeholder="Pax"
                        class="rounded-lg border px-2 py-2 text-sm text-center">
                </div>

                <div class="text-xs text-slate-500 mt-2">
                    Total: ₱ {{ number_format($this->counterpartTotal, 2) }}
                </div>

            </div>

        </div>

        {{-- FUND SOURCES --}}
        <div class="border border-slate-200 rounded-xl p-4">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                @foreach($fund_sources as $name => $amount)

                    @if($name === 'Counterpart')
                        @continue
                    @endif

                    <div>
                        <label class="block text-xs text-slate-700 mb-1">
                            {{ $name }}
                        </label>

                        <input type="text"
                            wire:model.live="fund_sources.{{ $name }}"
                            class="w-full rounded-lg border px-3 py-2 text-sm text-right">
                    </div>

                @endforeach

            </div>

        </div>

        {{-- TOTAL --}}
        <div class="border-t pt-4 flex justify-between">

            <div class="text-sm font-medium text-slate-900">
                Total Budget
            </div>

            <div class="text-xl font-bold text-emerald-600">
                ₱ {{ number_format($this->totalBudget, 2) }}
            </div>

        </div>

    </div>

</div>