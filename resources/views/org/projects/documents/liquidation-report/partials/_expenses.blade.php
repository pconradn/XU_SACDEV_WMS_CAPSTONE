<div class="border border-slate-300 bg-white mb-6">

<div class="bg-slate-50 border-b border-slate-300 px-4 py-2">
    <div class="text-[12px] font-semibold text-slate-900 tracking-wide">
        Cash Spent For
    </div>
</div>

<div class="px-4 py-4">

<div class="overflow-x-auto">

<table class="w-full border border-slate-300 text-[12px]" id="expensesTable">

<thead class="bg-slate-50">
<tr>

<th class="border border-slate-300 px-2 py-1">Date</th>
<th class="border border-slate-300 px-2 py-1">Particulars</th>
<th class="border border-slate-300 px-2 py-1">Amount</th>
<th class="border border-slate-300 px-2 py-1">Source Type</th>
<th class="border border-slate-300 px-2 py-1">Source Description</th>
<th class="border border-slate-300 px-2 py-1">OR Number</th>
<th class="border border-slate-300 px-2 py-1"></th>

</tr>
</thead>

<tbody id="expenseRows">

@php
$items = $report?->items ?? collect();
$grouped = $items->groupBy('section_label');
$index = 0;
@endphp

@foreach($grouped as $section => $rows)

<tr class="section-row" data-section="{{ $section }}">
<td colspan="7"
class="border border-slate-300 bg-slate-100 px-2 py-1 font-semibold flex justify-between">

<span>{{ $section }}</span>

<button type="button"
class="text-red-600 text-[11px] remove-section-btn">
Remove Section
</button>

</td>
</tr>

@foreach($rows as $row)

<tr data-section="{{ $section }}">

<td class="border border-slate-300">
<input type="hidden"
name="items[{{ $index }}][section_label]"
value="{{ $section }}">

<input type="date"
name="items[{{ $index }}][date]"
value="{{ $row->date }}"
class="w-full px-2 py-1 border-0">
</td>


<td class="border border-slate-300">
<input type="text"
name="items[{{ $index }}][particulars]"
value="{{ $row->particulars }}"
class="w-full px-2 py-1 border-0">
</td>


<td class="border border-slate-300">
<input type="number"
step="0.01"
name="items[{{ $index }}][amount]"
value="{{ $row->amount }}"
class="w-full px-2 py-1 border-0">
</td>


<td class="border border-slate-300">
<select name="items[{{ $index }}][source_document_type]"
class="w-full border-0 px-2 py-1">

<option value=""></option>

@foreach(['OR','SR','CI','SI','AR','PV'] as $type)
<option value="{{ $type }}"
@selected($row->source_document_type === $type)>
{{ $type }}
</option>
@endforeach

</select>
</td>


<td class="border border-slate-300">
<input type="text"
name="items[{{ $index }}][source_document_description]"
value="{{ $row->source_document_description }}"
class="w-full px-2 py-1 border-0">
</td>


<td class="border border-slate-300">
<input type="text"
name="items[{{ $index }}][or_number]"
value="{{ $row->or_number }}"
class="w-full px-2 py-1 border-0">
</td>


<td class="border border-slate-300 text-center">
<button type="button" class="remove-row-btn">✕</button>
</td>

</tr>

@php $index++; @endphp

@endforeach

@endforeach

</tbody>

</table>

</div>


<div class="mt-3 flex gap-3">

<button type="button"
id="addSectionBtn"
class="text-[11px] px-3 py-1 border border-slate-400 hover:bg-slate-100">
+ Add Section
</button>

<button type="button"
id="addExpenseBtn"
class="text-[11px] px-3 py-1 border border-slate-400 hover:bg-slate-100">
+ Add Expense
</button>

</div>

</div>

</div>