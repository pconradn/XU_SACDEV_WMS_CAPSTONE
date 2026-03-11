<div class="border border-slate-300 bg-white mb-6">

<div class="bg-slate-50 border-b border-slate-300 px-4 py-2">
    <div class="text-[12px] font-semibold text-slate-900 tracking-wide">
        Financial Summary
    </div>
</div>

<div class="px-4 py-4 grid grid-cols-1 md:grid-cols-2 gap-6 text-[12px]">

<div>
<label class="block text-[11px] mb-1">
Total Expenses
</label>

<input type="number"
name="total_expenses"
step="0.01"
value="{{ old('total_expenses', $report->total_expenses ?? '') }}"
class="border border-slate-300 px-2 py-1 w-full">
</div>


<div>
<label class="block text-[11px] mb-1">
Total Amount Advanced
</label>

<input type="number"
name="total_advanced"
step="0.01"
value="{{ old('total_advanced', $report->total_advanced ?? '') }}"
class="border border-slate-300 px-2 py-1 w-full">
</div>


<div>
<label class="block text-[11px] mb-1">
Balance
</label>

<input type="number"
name="balance"
step="0.01"
value="{{ old('balance', $report->balance ?? '') }}"
class="border border-slate-300 px-2 py-1 w-full">
</div>


<div>
<label class="block text-[11px] mb-1">
Amount to be Returned (Cluster A)
</label>

<input type="number"
name="cluster_a_return"
step="0.01"
value="{{ old('cluster_a_return', $report->cluster_a_return ?? '') }}"
class="border border-slate-300 px-2 py-1 w-full">
</div>


<div>
<label class="block text-[11px] mb-1">
Amount to be Returned (Cluster B)
</label>

<input type="number"
name="cluster_b_return"
step="0.01"
value="{{ old('cluster_b_return', $report->cluster_b_return ?? '') }}"
class="border border-slate-300 px-2 py-1 w-full">
</div>

</div>

</div>