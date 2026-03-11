<x-app-layout>

<div class="mx-auto max-w-6xl px-4 py-6">

<h1 class="text-lg font-semibold mb-6">
Submission Packets
</h1>

<div class="text-xs text-slate-500 mb-6">
Project: {{ $project->title }}
</div>


<div class="border border-slate-200 bg-white rounded-xl shadow-sm overflow-hidden">

<table class="w-full text-xs">

<thead class="bg-slate-50 border-b border-slate-200">

<tr>
<th class="px-4 py-2 text-left">Packet Code</th>
<th class="px-4 py-2 text-left">Received</th>
<th class="px-4 py-2 text-left">Receipts</th>
<th class="px-4 py-2 text-left">DV</th>
<th class="px-4 py-2 text-left">Letters</th>
<th class="px-4 py-2 text-left">Status</th>
<th class="px-4 py-2 text-right">Actions</th>
</tr>

</thead>

<tbody>

@foreach($packets as $packet)

<tr class="border-b">

<td class="px-4 py-3 font-medium">
{{ $packet->packet_code }}
</td>

<td class="px-4 py-3">
@if($packet->received_at)
{{ \Carbon\Carbon::parse($packet->received_at)->format('M d, Y') }}
@else
<span class="text-slate-400">—</span>
@endif
</td>

<td class="px-4 py-3">
{{ $packet->receipts->count() }}
</td>

<td class="px-4 py-3">
{{ $packet->dvs->count() }}
</td>

<td class="px-4 py-3">
{{ $packet->letters->count() }}
</td>

<td class="px-4 py-3">

@php
$status = $packet->status;
@endphp

@if($status === 'received_by_sacdev')
<span class="px-2 py-1 text-xs rounded bg-blue-100 text-blue-700">
Received
</span>

@elseif($status === 'verified_by_sacdev')
<span class="px-2 py-1 text-xs rounded bg-green-100 text-green-700">
Verified
</span>

@elseif($status === 'forwarded_to_finance')
<span class="px-2 py-1 text-xs rounded bg-purple-100 text-purple-700">
Forwarded to Finance
</span>

@elseif($status === 'generated')
<span class="px-2 py-1 text-xs rounded bg-amber-100 text-amber-700">
Generated
</span>

@else
<span class="px-2 py-1 text-xs rounded bg-slate-100 text-slate-700">
{{ $status }}
</span>
@endif

</td>

<td class="px-4 py-3 text-right space-x-2">


{{-- VERIFY --}}
@if($packet->status === 'received_by_sacdev')

<form method="POST"
action="{{ route('admin.packets.verify',$packet) }}"
class="inline">

@csrf

<button class="text-blue-600 hover:underline">
Verify
</button>

</form>

@endif



{{-- REVERT VERIFICATION --}}
@if($packet->status === 'verified_by_sacdev')

<form method="POST"
action="{{ route('admin.packets.revert_received',$packet) }}"
class="inline">

@csrf

<button class="text-amber-600 hover:underline">
Revert
</button>

</form>

@endif



{{-- FORWARD TO FINANCE --}}
@if($packet->status === 'verified_by_sacdev')

<form method="POST"
action="{{ route('admin.packets.forward_finance',$packet) }}"
class="inline">

@csrf

<button class="text-green-600 hover:underline">
Forward to Finance
</button>

</form>

@endif



{{-- REVERT FROM FINANCE --}}
@if($packet->status === 'forwarded_to_finance')

<form method="POST"
action="{{ route('admin.packets.revert_finance',$packet) }}"
class="inline">

@csrf

<button class="text-red-600 hover:underline">
Revert from Finance
</button>

</form>

@endif



{{-- RETURN --}}
@if(in_array($packet->status,['received_by_sacdev','verified_by_sacdev']))

<button
onclick="openReturnModal({{ $packet->id }})"
class="text-red-600 hover:underline">

Return

</button>

@endif


</td>

</tr>

@endforeach

</tbody>

</table>

</div>

</div>


@include('admin.packets.modals')

</x-app-layout>