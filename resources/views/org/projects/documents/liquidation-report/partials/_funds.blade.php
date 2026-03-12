<div class="border border-slate-300 bg-white mb-6">

<div class="bg-slate-50 border-b border-slate-300 px-4 py-2">
    <div class="text-[12px] font-semibold text-slate-900 tracking-wide">
        Cash Received (Source of Funds)
    </div>
</div>

<div class="px-4 py-4 text-[12px]">

<table class="w-full border border-slate-300 text-[12px]">

<thead class="bg-slate-50">
<tr>
    <th class="border border-slate-300 px-2 py-1">Cluster A</th>
    <th class="border border-slate-300 px-2 py-1">Cluster B</th>
</tr>
</thead>

<tbody>

<tr>
<td class="border border-slate-300 p-2">

<div class="space-y-2">

<div>
<label class="block text-[11px]">XU Finance</label>
<input type="number"
name="finance_amount"
step="0.01"
value="{{ old('finance_amount', $report->finance_amount ?? '') }}"
class="border border-slate-300 px-2 py-1 w-full">
</div>

<div>
<label class="block text-[11px]">Fund Raising</label>
<input type="number"
name="fund_raising_amount"
step="0.01"
value="{{ old('fund_raising_amount', $report->fund_raising_amount ?? '') }}"
class="border border-slate-300 px-2 py-1 w-full">
</div>

<div>
<label class="block text-[11px]">SACDEV</label>
<input type="number"
name="sacdev_amount"
step="0.01"
value="{{ old('sacdev_amount', $report->sacdev_amount ?? '') }}"
class="border border-slate-300 px-2 py-1 w-full">
</div>

</div>

</td>

<td class="border border-slate-300 p-2">

<label class="block text-[11px]">
PTA / College / Department
</label>

<input type="number"
name="pta_amount"
step="0.01"
value="{{ old('pta_amount', $report->pta_amount ?? '') }}"
class="border border-slate-300 px-2 py-1 w-full">

</td>
</tr>

</tbody>

</table>


<div class="mt-4">

<label class="text-[11px] block mb-1">
If Fund Raising, check among the options:
</label>

@php
$fundraisingTypes = old(
    'fundraising_types',
    $report->fundraising_types ?? []
);
@endphp

<div class="flex flex-wrap gap-4 text-[11px]">

<label class="flex items-center gap-1">
<input type="checkbox"
name="fundraising_types[]"
value="solicitation"
@checked(in_array('solicitation', $fundraisingTypes))>
Solicitation
</label>

<label class="flex items-center gap-1">
<input type="checkbox"
name="fundraising_types[]"
value="counterpart"
@checked(in_array('counterpart', $fundraisingTypes))>
Counterpart
</label>

<label class="flex items-center gap-1">
<input type="checkbox"
name="fundraising_types[]"
value="ticket_selling"
@checked(in_array('ticket_selling', $fundraisingTypes))>
Ticket Selling
</label>

<label class="flex items-center gap-1">
<input type="checkbox"
name="fundraising_types[]"
value="selling"
@checked(in_array('selling', $fundraisingTypes))>
Selling
</label>

</div>

</div>

</div>
</div>