<div class="border border-slate-300 bg-white mb-6">

<div class="border-b px-4 py-2 text-[12px] font-semibold bg-slate-50">
Select Budget Items to Include in DV
</div>

<div class="p-4">

@if($budgetItems->count())

<table class="w-full text-[12px] border">

<thead class="bg-slate-100">

<tr>

<th class="border px-2 py-2 text-left">Select</th>
<th class="border px-2 py-2 text-left">Particulars</th>
<th class="border px-2 py-2 text-left">Section</th>
<th class="border px-2 py-2 text-right">Amount</th>
<th class="border px-2 py-2 text-left">Charge Account</th>

</tr>

</thead>

<tbody>

@foreach($budgetItems as $item)

<tr>

<td class="border px-2 py-2 text-center">
<input type="checkbox"
       class="dv-item"
       data-amount="{{ $item->amount }}"
       name="items[]"
       value="{{ $item->id }}">
</td>

<td class="border px-2 py-2">
{{ $item->particulars }}
</td>

<td class="border px-2 py-2 capitalize">
{{ str_replace('_',' ', $item->section) }}
</td>

<td class="border px-2 py-2 text-right">
₱ {{ number_format($item->amount,2) }}
</td>

<td class="border px-2 py-2">
<input type="text"
       name="charge_account[{{ $item->id }}]"
       class="w-full border border-slate-300 px-2 py-1">
</td>

</tr>

@endforeach

</tbody>

</table>



@else

<div class="text-slate-500 text-sm">
No budget proposal items found.
</div>

@endif

</div>

</div>