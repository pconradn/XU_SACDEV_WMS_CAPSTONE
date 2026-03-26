<div class="border border-slate-200 bg-white rounded-xl shadow-sm">

<div class="px-5 py-4 border-b border-slate-200 text-sm font-semibold">
Disbursement Vouchers
</div>

<div class="px-5 py-4 text-xs">

@if($packet->dvs->count())

<table class="w-full text-xs mb-4">

<thead class="border-b text-slate-500">
<tr>
<th class="text-left py-1">Reference</th>
<th class="text-left py-1">Description</th>
<th class="text-left py-1">Amount</th>
<th class="text-right py-1">Action</th>
</tr>
</thead>

<tbody>

@foreach($packet->dvs as $dv)

<tr class="border-b">

<td class="py-1">
{{ $dv->dv_reference }}
</td>

<td class="py-1">
{{ $dv->dv_label }}
</td>

<td class="py-1">
{{ $dv->amount }}
</td>

<td class="text-right">

@if(!$locked)

<form method="POST"
action="{{ route('org.projects.packets.dvs.destroy', [$project,$packet,$dv]) }}">

@csrf
@method('DELETE')

<button class="text-red-600 hover:underline text-xs">
Remove
</button>

</form>

@endif

</td>

</tr>

@endforeach

</tbody>

</table>

@endif



@if(!$locked)

<form method="POST"
action="{{ route('org.projects.packets.dvs.store', [$project,$packet]) }}">

@csrf

<div class="grid grid-cols-3 gap-3 mb-3">

<input
type="text"
name="dv_reference"
placeholder="DV Reference"
class="border border-slate-300 rounded px-2 py-1 text-xs">

<input
type="text"
name="dv_label"
placeholder="Description"
class="border border-slate-300 rounded px-2 py-1 text-xs">

<input
type="number"
step="0.01"
name="amount"
placeholder="Amount"
class="border border-slate-300 rounded px-2 py-1 text-xs">

</div>

<button
class="px-3 py-1 text-xs bg-slate-800 text-white rounded hover:bg-slate-900">
Add DV
</button>

</form>

@endif

</div>

</div>