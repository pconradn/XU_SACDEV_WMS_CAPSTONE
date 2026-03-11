<div class="bg-white border border-slate-200 rounded-xl shadow-sm">

<div class="px-4 py-3 border-b border-slate-200">

<h3 class="text-sm font-semibold text-slate-900">
Activity Notices
</h3>

</div>


<div class="overflow-x-auto">

<table class="min-w-full text-sm">

<thead class="bg-slate-50 border-b">

<tr>

<th class="px-4 py-2 text-left">Type</th>
<th class="px-4 py-2 text-left">Created</th>
<th class="px-4 py-2 text-left">Status</th>
<th class="px-4 py-2 text-right">Actions</th>

</tr>

</thead>


<tbody>

{{-- POSTPONEMENTS --}}
@foreach($postponements as $doc)

<tr class="border-b">

<td class="px-4 py-3 text-slate-700">
Postponement Notice
</td>

<td class="px-4 py-3 text-slate-600">
{{ $doc->created_at->format('M d, Y') }}
</td>

<td class="px-4 py-3">

@if($doc->status === 'submitted')

<span class="text-blue-600 font-medium">
Submitted
</span>

@elseif($doc->status === 'approved_by_sacdev')

<span class="text-emerald-600 font-medium">
Approved
</span>

@elseif($doc->status === 'returned')

<span class="text-rose-600 font-medium">
Returned
</span>

@endif

</td>

<td class="px-4 py-3 text-right">

<a
href="{{ route('admin.projects.documents.open', [$project,'POSTPONEMENT_NOTICE',$doc->id]) }}"
class="text-indigo-600 hover:underline">

Open

</a>

</td>

</tr>

@endforeach



{{-- CANCELLATIONS --}}
@foreach($cancellations as $doc)

<tr class="border-b">

<td class="px-4 py-3 text-slate-700">
Cancellation Notice
</td>

<td class="px-4 py-3 text-slate-600">
{{ $doc->created_at->format('M d, Y') }}
</td>

<td class="px-4 py-3">

@if($doc->status === 'submitted')

<span class="text-blue-600 font-medium">
Submitted
</span>

@elseif($doc->status === 'approved_by_sacdev')

<span class="text-emerald-600 font-medium">
Approved
</span>

@elseif($doc->status === 'returned')

<span class="text-rose-600 font-medium">
Returned
</span>

@endif

</td>

<td class="px-4 py-3 text-right">

<a
href="{{ route('admin.projects.documents.open', [$project,'CANCELLATION_NOTICE',$doc->id]) }}"
class="text-indigo-600 hover:underline">

Open

</a>

</td>

</tr>

@endforeach


@if($postponements->isEmpty() && $cancellations->isEmpty())

<tr>

<td colspan="4" class="px-4 py-6 text-center text-slate-500">
No notices submitted.
</td>

</tr>

@endif

</tbody>

</table>

</div>

</div>
