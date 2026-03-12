<div class="border border-slate-300 bg-white mb-6">

<div class="bg-slate-50 border-b border-slate-300 px-4 py-2 flex justify-between items-center">

<div class="text-[12px] font-semibold text-slate-900 tracking-wide">
Actual Number of Items Sold
</div>

@if(!$isReadOnly)
<button
type="button"
onclick="addSellingItemRow()"
class="text-[11px] bg-blue-900 text-white px-3 py-1 rounded hover:bg-blue-800">
Add Row
</button>
@endif

</div>


<div class="px-4 py-4 overflow-x-auto">

<table class="min-w-full text-[12px] border border-slate-300">

<thead class="bg-slate-100">

<tr>

<th class="border border-slate-300 px-2 py-1 w-[90px]">
Quantity
</th>

<th class="border border-slate-300 px-2 py-1">
Particulars
</th>

<th class="border border-slate-300 px-2 py-1 w-[140px]">
Price
</th>



<th class="border border-slate-300 px-2 py-1 w-[140px]">
Subtotal
</th>

<th class="border border-slate-300 px-2 py-1 w-[180px]">
Acknowledgement Receipt #
</th>

@if(!$isReadOnly)
<th class="border border-slate-300 px-2 py-1 w-[70px]">
Action
</th>
@endif

</tr>

</thead>


<tbody id="sellingItemsTable">

@php
$items = old('items', $items ?? []);
@endphp


@if(count($items))

@foreach($items as $i => $item)

<tr>

<td class="border border-slate-300">
<input
type="number"
name="items[{{ $i }}][quantity]"
value="{{ $item['quantity'] ?? '' }}"
oninput="updateSubtotal(this)"
class="w-full px-2 py-1 border-0 text-[12px]"
@if($isReadOnly) disabled @endif>
</td>

<td class="border border-slate-300">
<input
type="text"
name="items[{{ $i }}][particulars]"
value="{{ $item['particulars'] ?? '' }}"
class="w-full px-2 py-1 border-0 text-[12px]"
@if($isReadOnly) disabled @endif>
</td>

<td class="border border-slate-300">
<input
type="number"
step="0.01"
name="items[{{ $i }}][price]"
value="{{ $item['price'] ?? '' }}"
oninput="updateSubtotal(this)"
class="w-full px-2 py-1 border-0 text-[12px]"
@if($isReadOnly) disabled @endif>
</td>


<td class="border border-slate-300 bg-slate-50">

<input
type="text"
readonly
class="w-full px-2 py-1 border-0 text-[12px] subtotal-field"
value="{{ ($item['quantity'] ?? 0) * ($item['price'] ?? 0) }}">

</td>

<td class="border border-slate-300">
<input
type="text"
name="items[{{ $i }}][acknowledgement_receipt_number]"
value="{{ $item['acknowledgement_receipt_number'] ?? '' }}"
class="w-full px-2 py-1 border-0 text-[12px]"
@if($isReadOnly) disabled @endif>
</td>

@if(!$isReadOnly)
<td class="border border-slate-300 text-center">

<button
type="button"
onclick="this.closest('tr').remove(); updateTotalSales();"
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
<input type="number" name="items[0][quantity]" oninput="updateSubtotal(this)" class="w-full px-2 py-1 border-0 text-[12px]" @if($isReadOnly) disabled @endif>
</td>

<td class="border border-slate-300">
<input type="text" name="items[0][particulars]" class="w-full px-2 py-1 border-0 text-[12px]" @if($isReadOnly) disabled @endif>
</td>

<td class="border border-slate-300">
<input type="number" step="0.01" name="items[0][price]" oninput="updateSubtotal(this)" class="w-full px-2 py-1 border-0 text-[12px]" @if($isReadOnly) disabled @endif>
</td>



<td class="border border-slate-300 bg-slate-50">
<input type="text" readonly class="w-full px-2 py-1 border-0 text-[12px] subtotal-field">
</td>

<td class="border border-slate-300">
<input type="text" name="items[0][acknowledgement_receipt_number]" class="w-full px-2 py-1 border-0 text-[12px]" @if($isReadOnly) disabled @endif>
</td>

@if(!$isReadOnly)
<td class="border border-slate-300 text-center">—</td>
@endif

</tr>

@endif

</tbody>

</table>


{{-- TOTAL SALES --}}
<div class="flex justify-end mt-4">

<div class="text-[12px] font-semibold">



</div>

</div>

</div>

</div>