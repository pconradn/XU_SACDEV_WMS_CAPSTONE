<div class="border border-slate-300 bg-white mb-6">

<div class="bg-slate-50 border-b border-slate-300 px-4 py-2 flex justify-between items-center">

<div class="text-[12px] font-semibold text-slate-900 tracking-wide">
Solicitation / Sponsorship Recipients
</div>

@if(!$isReadOnly)
<button
type="button"
onclick="addSolicitationRow()"
class="text-[11px] bg-blue-900 text-white px-3 py-1 rounded hover:bg-blue-800">
Add Row
</button>
@endif

</div>



<div class="px-4 py-4 overflow-x-auto">

<table class="min-w-full text-[12px] border border-slate-300">

<thead class="bg-slate-100">

<tr>

<th class="border border-slate-300 px-2 py-1 w-[140px]">
Control Number
</th>

<th class="border border-slate-300 px-2 py-1 w-[180px]">
Person-in-Charge
</th>

<th class="border border-slate-300 px-2 py-1">
Recipient of Letter
</th>

<th class="border border-slate-300 px-2 py-1 w-[140px]">
Amount Given
</th>

<th class="border border-slate-300 px-2 py-1">
Remarks
</th>

@if(!$isReadOnly)
<th class="border border-slate-300 px-2 py-1 w-[70px]">
Action
</th>
@endif

</tr>

</thead>



<tbody id="solicitationItemsTable">

@php
$items = old('items', $items ?? []);
@endphp



@if(count($items))

@foreach($items as $i => $item)

<tr>

<td class="border border-slate-300">

<input
type="text"
name="items[{{ $i }}][control_number]"
value="{{ $item['control_number'] ?? '' }}"
class="w-full px-2 py-1 border-0 text-[12px]"
@if($isReadOnly) disabled @endif>

</td>


<td class="border border-slate-300">

<input
type="text"
name="items[{{ $i }}][person_in_charge]"
value="{{ $item['person_in_charge'] ?? '' }}"
class="w-full px-2 py-1 border-0 text-[12px]"
@if($isReadOnly) disabled @endif>

</td>


<td class="border border-slate-300">

<input
type="text"
name="items[{{ $i }}][recipient]"
value="{{ $item['recipient'] ?? '' }}"
class="w-full px-2 py-1 border-0 text-[12px]"
@if($isReadOnly) disabled @endif>

</td>


<td class="border border-slate-300">

<input
type="number"
step="0.01"
name="items[{{ $i }}][amount_given]"
value="{{ $item['amount_given'] ?? '' }}"
oninput="updateTotalRaised()"
class="w-full px-2 py-1 border-0 text-[12px]"
@if($isReadOnly) disabled @endif>

</td>


<td class="border border-slate-300">

<input
type="text"
name="items[{{ $i }}][remarks]"
value="{{ $item['remarks'] ?? '' }}"
class="w-full px-2 py-1 border-0 text-[12px]"
@if($isReadOnly) disabled @endif>

</td>


@if(!$isReadOnly)
<td class="border border-slate-300 text-center">

<button
type="button"
onclick="removeSolicitationRow(this)"
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
<input
type="text"
name="items[0][control_number]"
class="w-full px-2 py-1 border-0 text-[12px]"
@if($isReadOnly) disabled @endif>
</td>


<td class="border border-slate-300">
<input
type="text"
name="items[0][person_in_charge]"
class="w-full px-2 py-1 border-0 text-[12px]"
@if($isReadOnly) disabled @endif>
</td>


<td class="border border-slate-300">
<input
type="text"
name="items[0][recipient]"
class="w-full px-2 py-1 border-0 text-[12px]"
@if($isReadOnly) disabled @endif>
</td>


<td class="border border-slate-300">
<input
type="number"
step="0.01"
name="items[0][amount_given]"
oninput="updateTotalRaised()"
class="w-full px-2 py-1 border-0 text-[12px]"
@if($isReadOnly) disabled @endif>
</td>


<td class="border border-slate-300">
<input
type="text"
name="items[0][remarks]"
class="w-full px-2 py-1 border-0 text-[12px]"
@if($isReadOnly) disabled @endif>
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