<div id="approveModal"
class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">

<div class="bg-white rounded-lg shadow-lg w-full max-w-4xl">

<div class="border-b px-4 py-3 font-semibold text-sm">
Review Goods & Add Remarks
</div>

<form method="POST"
action="{{ route('admin.projects.documents.approve', [$project,$document->formType->code]) }}">

@csrf

<div class="p-4 overflow-y-auto max-h-[60vh]">

<table class="min-w-full text-[12px] border border-slate-300">

<thead class="bg-slate-100">

<tr>
<th class="border px-2 py-1">Quantity</th>
<th class="border px-2 py-1">Particulars</th>
<th class="border px-2 py-1">Selling Price</th>
<th class="border px-2 py-1">Subtotal</th>
<th class="border px-2 py-1">Remarks (SACDEV)</th>
</tr>

</thead>

<tbody>

@foreach($items as $item)

<tr>

<td class="border px-2 py-1 bg-slate-50">
{{ $item->quantity }}
</td>

<td class="border px-2 py-1 bg-slate-50">
{{ $item->particulars }}
</td>

<td class="border px-2 py-1 bg-slate-50">
{{ number_format($item->selling_price,2) }}
</td>

<td class="border px-2 py-1 bg-slate-50">
{{ number_format($item->quantity * $item->selling_price,2) }}
</td>

<td class="border px-2 py-1">

<input
type="text"
name="items[{{ $item->id }}][remarks]"
value="{{ $item->remarks }}"
class="w-full border border-slate-300 px-2 py-1 text-[12px]">

</td>

</tr>

@endforeach

</tbody>

</table>

</div>

<div class="border-t px-4 py-3 flex justify-end gap-2">

<button
type="button"
onclick="closeApproveModal()"
class="border border-slate-300 px-4 py-2 text-[12px]">
Cancel
</button>

<button
type="submit"
class="bg-emerald-600 text-white px-4 py-2 text-[12px] hover:bg-emerald-700">
Confirm Approval
</button>

</div>

</form>

</div>

</div>