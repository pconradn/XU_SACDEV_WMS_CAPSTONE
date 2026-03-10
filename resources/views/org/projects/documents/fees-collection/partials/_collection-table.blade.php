<div class="border border-slate-300 bg-white mb-6">

<div class="bg-slate-50 border-b border-slate-300 px-4 py-2 flex justify-between items-center">

<div class="text-[12px] font-semibold text-slate-900 tracking-wide">
Summary of Cash Collection
</div>

@if(!$isReadOnly)
<button
type="button"
onclick="addCollectionRow()"
class="text-[11px] bg-blue-900 text-white px-3 py-1 rounded hover:bg-blue-800">
Add Row
</button>
@endif

</div>


<div class="px-4 py-4 overflow-x-auto">

<table class="min-w-full text-[12px] border border-slate-300">

<thead class="bg-slate-100">

<tr>

<th class="border border-slate-300 px-2 py-1 w-[150px]">
Number of Payers
</th>

<th class="border border-slate-300 px-2 py-1 w-[180px]">
Amount Paid
</th>

<th class="border border-slate-300 px-2 py-1">
Receipt / Control Number
</th>

<th class="border border-slate-300 px-2 py-1">
Remarks (SACDEV)
</th>

@if(!$isReadOnly)
<th class="border border-slate-300 px-2 py-1 w-[70px]">
Action
</th>
@endif

</tr>

</thead>


<tbody id="collectionTable">

@php
$items = old('items', $items ?? []);
@endphp


@if(count($items))

@foreach($items as $i => $item)

<tr>

<td class="border border-slate-300">
<input
type="number"
name="items[{{ $i }}][number_of_payers]"
value="{{ $item['number_of_payers'] ?? '' }}"
class="w-full px-2 py-1 border-0 text-[12px]"
@if($isReadOnly || $isAdmin) disabled @endif>
</td>

<td class="border border-slate-300">
<input
type="number"
step="0.01"
name="items[{{ $i }}][amount_paid]"
value="{{ $item['amount_paid'] ?? '' }}"
class="w-full px-2 py-1 border-0 text-[12px]"
@if($isReadOnly || $isAdmin) disabled @endif>
</td>

<td class="border border-slate-300">
<input
type="text"
name="items[{{ $i }}][receipt_series]"
value="{{ $item['receipt_series'] ?? '' }}"
class="w-full px-2 py-1 border-0 text-[12px]"
@if($isReadOnly || $isAdmin) disabled @endif>
</td>

<td class="border border-slate-300">

<input
type="text"
name="items[{{ $i }}][remarks]"
value="{{ $item['remarks'] ?? '' }}"
class="w-full px-2 py-1 border-0 text-[12px] bg-amber-50"
@if(!$isAdmin) disabled @endif>

</td>


@if(!$isReadOnly)
<td class="border border-slate-300 text-center">

<button
type="button"
onclick="this.closest('tr').remove()"
class="text-rose-600 hover:text-rose-800">
Remove
</button>

</td>
@endif

</tr>

@endforeach

@else

<tr>

<td class="border border-slate-300">
<input type="number" name="items[0][number_of_payers]" class="w-full px-2 py-1 border-0 text-[12px]" @if($isReadOnly || $isAdmin) disabled @endif>
</td>

<td class="border border-slate-300">
<input type="number" step="0.01" name="items[0][amount_paid]" class="w-full px-2 py-1 border-0 text-[12px]" @if($isReadOnly || $isAdmin) disabled @endif>
</td>

<td class="border border-slate-300">
<input type="text" name="items[0][receipt_series]" class="w-full px-2 py-1 border-0 text-[12px]" @if($isReadOnly || $isAdmin) disabled @endif>
</td>

<td class="border border-slate-300">
<input type="text" name="items[0][remarks]" class="w-full px-2 py-1 border-0 text-[12px] bg-amber-50" @if(!$isAdmin) disabled @endif>
</td>

@if(!$isReadOnly)
<td class="border border-slate-300 text-center">—</td>
@endif
                                                                
</tr>

@endif

</tbody>

</table>

</div>

</div>