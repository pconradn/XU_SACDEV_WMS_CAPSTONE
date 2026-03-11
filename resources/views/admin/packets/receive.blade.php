<x-app-layout>

<div class="mx-auto max-w-4xl px-4 py-6">

<h1 class="text-lg font-semibold mb-6">
Packet Receiving
</h1>



{{-- SEARCH FORM --}}
<div class="border rounded-lg p-5 mb-6 bg-white shadow">

<form method="GET" action="{{ route('admin.packets.receive') }}">

<div class="flex gap-3 items-end">

<div>

<label class="text-xs font-medium">
Packet Code
</label>

<input
type="text"
name="packet_code"
value="{{ request('packet_code') }}"
placeholder="PKT-2026-0004"
autofocus
class="border border-slate-300 rounded px-3 py-2 text-sm w-56">

</div>

<button
class="bg-indigo-600 text-white text-xs px-4 py-2 rounded hover:bg-indigo-700">

Lookup Packet

</button>

</div>

</form>

</div>



@if(isset($packet) && $packet)

<div class="border rounded-lg bg-white shadow p-6">

<h2 class="font-semibold text-sm mb-4">
Packet Information
</h2>


<div class="text-sm space-y-2">

<div>
<strong>Packet Code:</strong> {{ $packet->packet_code }}
</div>

<div>
<strong>Project:</strong> {{ $packet->project->title }}
</div>

<div>
<strong>Generated:</strong>
{{ \Carbon\Carbon::parse($packet->generated_at)->format('F d, Y') }}
</div>

<div>
<strong>Receipts:</strong>
{{ $packet->receipts->count() }}
</div>

<div>
<strong>Disbursement Vouchers:</strong>
{{ $packet->dvs->count() }}
</div>

<div>
<strong>Solicitation Letters:</strong>
{{ $packet->letters->count() }}
</div>

</div>


@if($packet->received_at)

<div class="mt-6 border border-green-300 bg-green-50 text-green-700 text-sm p-3 rounded">

Packet already received on  
{{ \Carbon\Carbon::parse($packet->received_at)->format('F d, Y h:i A') }}

</div>

@else

<form
method="POST"
action="{{ route('admin.packets.mark_received', $packet) }}"
class="mt-6">

@csrf

<button
class="bg-green-600 text-white px-4 py-2 text-sm rounded hover:bg-green-700">

Mark Packet as Received

</button>

</form>

@endif

</div>

@endif



</div>

</x-app-layout>