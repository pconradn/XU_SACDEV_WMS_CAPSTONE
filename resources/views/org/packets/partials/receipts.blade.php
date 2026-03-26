<div class="border border-slate-200 bg-white rounded-xl shadow-sm mb-6">

<div class="px-5 py-4 border-b border-slate-200 text-sm font-semibold">
Official Receipts Included
</div>

<div class="px-5 py-4 text-xs text-slate-700">

@if($packet->receipts->count())

<table class="w-full text-xs mb-4">

<thead class="border-b text-slate-500">
<tr>
<th class="text-left py-1">OR Number</th>
<th class="text-right py-1">Action</th>
</tr>
</thead>

<tbody>

@foreach($packet->receipts as $receipt)

<tr class="border-b">

<td class="py-1">
OR #{{ $receipt->or_number }}
</td>

<td class="text-right">

@if(!$locked)

<form method="POST"
action="{{ route('org.projects.packets.receipts.destroy', [$project,$packet,$receipt]) }}">

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
action="{{ route('org.projects.packets.receipts.store', [$project,$packet]) }}">

@csrf

<div class="flex gap-2">

<input
type="text"
name="or_number"
placeholder="Enter OR Number"
class="border border-slate-300 rounded px-2 py-1 text-xs w-48">

<button
class="px-3 py-1 text-xs bg-slate-800 text-white rounded hover:bg-slate-900">
Add Receipt
</button>

</div>

</form>

@endif

</div>

</div>