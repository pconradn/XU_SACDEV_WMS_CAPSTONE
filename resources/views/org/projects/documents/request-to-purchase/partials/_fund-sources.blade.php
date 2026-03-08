<div class="border border-slate-300 bg-white mb-6">

<div class="bg-slate-50 border-b border-slate-300 px-4 py-2">
<div class="text-[12px] font-semibold text-slate-900 tracking-wide">
Source of Funds
</div>
</div>


<div class="px-4 py-4 overflow-x-auto">

<table class="min-w-full text-[12px] border border-slate-300">

<thead class="bg-slate-100">

<tr>
<th class="border border-slate-300 px-2 py-1 text-left">
Fund Source
</th>

<th class="border border-slate-300 px-2 py-1 w-[200px]">
Amount
</th>

</tr>

</thead>

<tbody>


{{-- XU Finance --}}
<tr>

<td class="border border-slate-300 px-2 py-1">
XU Finance
</td>

<td class="border border-slate-300">

<input
type="number"
step="0.01"
name="xu_finance_amount"
value="{{ old('xu_finance_amount', $data->xu_finance_amount ?? '') }}"
class="w-full px-2 py-1 border-0 text-[12px]"
placeholder="0.00">

</td>

</tr>


{{-- Membership Fee --}}
<tr>

<td class="border border-slate-300 px-2 py-1">
Membership Fee
</td>

<td class="border border-slate-300">

<input
type="number"
step="0.01"
name="membership_fee_amount"
value="{{ old('membership_fee_amount', $data->membership_fee_amount ?? '') }}"
class="w-full px-2 py-1 border-0 text-[12px]"
placeholder="0.00">

</td>

</tr>


{{-- PTA --}}
<tr>

<td class="border border-slate-300 px-2 py-1">
PTA
</td>

<td class="border border-slate-300">

<input
type="number"
step="0.01"
name="pta_amount"
value="{{ old('pta_amount', $data->pta_amount ?? '') }}"
class="w-full px-2 py-1 border-0 text-[12px]"
placeholder="0.00">

</td>

</tr>


{{-- Solicitations --}}
<tr>

<td class="border border-slate-300 px-2 py-1">
Solicitations
</td>

<td class="border border-slate-300">

<input
type="number"
step="0.01"
name="solicitations_amount"
value="{{ old('solicitations_amount', $data->solicitations_amount ?? '') }}"
class="w-full px-2 py-1 border-0 text-[12px]"
placeholder="0.00">

</td>

</tr>


{{-- Others --}}
<tr>

<td class="border border-slate-300 px-2 py-1">

<div class="flex items-center gap-2">

<span>Others</span>

<input
type="text"
name="others_label"
placeholder="Specify source"
value="{{ old('others_label', $data->others_label ?? '') }}"
class="border border-slate-300 px-2 py-1 text-[12px] w-48">

</div>

</td>

<td class="border border-slate-300">

<input
type="number"
step="0.01"
name="others_amount"
value="{{ old('others_amount', $data->others_amount ?? '') }}"
class="w-full px-2 py-1 border-0 text-[12px]"
placeholder="0.00">

</td>

</tr>

</tbody>

</table>

</div>

</div>