<div>

@if($this->totalBudget > 0 || collect($this->budgetItems)->flatten(1)->count())

<div class="text-sm font-semibold text-slate-900 mb-2">
    Budget Proposal Section
</div>

@foreach($budgetSectionLabels as $code => $label)

<div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden mb-6">

    <div class="h-1 bg-blue-500"></div>

    <div class="px-5 py-3 border-b bg-slate-50">
        <h3 class="text-sm font-semibold text-slate-900">
            {{ $label }}
        </h3>
    </div>

    <div class="px-5 py-5 space-y-3">

        <div class="grid grid-cols-12 gap-2 text-[11px] font-semibold text-slate-500 border-b pb-2">
            <div class="col-span-1 text-center">Qty</div>
            <div class="col-span-2 text-center">Unit</div>
            <div class="col-span-4">Particulars</div>
            <div class="col-span-2 text-right">Price</div>
            <div class="col-span-2 text-right">Amount</div>
            <div class="col-span-1"></div>
        </div>

        @foreach($budgetItems[$code] as $i => $row)

        <div class="grid grid-cols-12 gap-2 items-center">

            <input type="number"
                wire:model.live="budgetItems.{{ $code }}.{{ $i }}.qty"
                class="col-span-1 text-center border rounded-lg text-sm">

            <input type="text"
                wire:model.live="budgetItems.{{ $code }}.{{ $i }}.unit"
                class="col-span-2 border rounded-lg text-sm">

            <input type="text"
                wire:model.live="budgetItems.{{ $code }}.{{ $i }}.particulars"
                class="col-span-4 border rounded-lg text-sm">

            <input type="text"
                wire:model.live="budgetItems.{{ $code }}.{{ $i }}.price_per_unit"
                class="col-span-2 text-right border rounded-lg text-sm">

            <div class="col-span-2 text-right font-semibold">
                ₱ {{ number_format(
                    ($this->cleanNumber($row['qty'] ?? 0) ?? 0) *
                    ($this->cleanNumber($row['price_per_unit'] ?? 0) ?? 0),
                2) }}
            </div>

            <button type="button"
                wire:click="removeBudgetRow('{{ $code }}', {{ $i }})"
                class="col-span-1 text-rose-500 text-xs">
                ✕
            </button>

        </div>

        @endforeach

        <button type="button"
            wire:click="addBudgetRow('{{ $code }}')"
            class="text-xs font-semibold text-blue-600">
            + Add Item
        </button>

        <div class="text-right font-semibold text-sm pt-2 border-t">
            ₱ {{ number_format($this->getSectionTotal($code), 2) }}
        </div>

    </div>

</div>

@endforeach

{{-- TOTAL --}}
<div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

    <div class="h-1 bg-emerald-500"></div>

    <div class="px-5 py-4 flex justify-between">

        <div>
            <div class="text-sm font-medium text-slate-900">
                Total Expenses
            </div>

            <div class="text-xs mt-1 {{ $this->isMatch ? 'text-emerald-600' : 'text-rose-600' }}">
                {{ $this->isMatch ? 'Budget matched' : 'Mismatch with funding' }}
            </div>
        </div>

        <div class="text-xl font-bold text-emerald-600">
            ₱ {{ number_format($this->grandTotal, 2) }}
        </div>

    </div>

</div>

@endif

</div>