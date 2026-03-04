<div class="border border-slate-300 bg-white mb-6">

<div class="border-b px-4 py-2 text-[12px] font-semibold bg-slate-50">
Voucher Information
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 text-[12px]">

<div>
<label class="block font-medium mb-1">Project</label>
<input type="text"
       value="{{ $project->title }}"
       disabled
       class="w-full border border-slate-300 px-3 py-2 bg-slate-100">
</div>

<div>
<label class="block font-medium mb-1">Project Head</label>
<input type="text"
       value="{{ $projectHead?->name }}"
       disabled
       class="w-full border border-slate-300 px-3 py-2 bg-slate-100">
</div>

<div>
<label class="block font-medium mb-1">Date</label>
<input type="date"
       name="dv_date"
       value="{{ now()->toDateString() }}"
       class="w-full border border-slate-300 px-3 py-2">
</div>

<div>
<label class="block font-medium mb-1">Payment Type</label>

<select name="payment_type"
class="w-full border border-slate-300 px-3 py-2">

<option value="reimbursement">Reimbursement Refund</option>
<option value="goods_services">Payment for Goods / Services</option>
<option value="honoraria">Payroll Item / Honoraria</option>
<option value="advance">Advance for Liquidation</option>
<option value="others">Others</option>

</select>

</div>

<div>
<label class="block font-medium mb-1">Payment Mode</label>

<select name="payment_mode"
class="w-full border border-slate-300 px-3 py-2">

<option value="cash">Cash</option>
<option value="check">Check</option>
<option value="fund_transfer">Fund Transfer</option>
<option value="payroll_credit">Payroll Credit</option>

</select>

</div>

</div>

</div>