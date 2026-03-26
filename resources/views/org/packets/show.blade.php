<x-app-layout>

<div class="mx-auto max-w-5xl px-4 py-6">

@php
$locked = $packet->received_at !== null;
@endphp

{{-- HEADER --}}
<div class="flex items-center justify-between mb-6">

<div>
<h1 class="text-lg font-semibold text-slate-900">
Submission Packet
</h1>

<div class="text-xs text-slate-500">
{{ $packet->packet_code }}
</div>
</div>

<div class="flex items-center gap-3">

<a href="{{ route('org.projects.packets.print', [$project,$packet]) }}"
class="px-3 py-2 text-xs bg-slate-800 text-white rounded hover:bg-slate-900">
Print Cover
</a>

<a href="{{ route('org.projects.packets.index', $project) }}"
class="text-xs text-slate-600 hover:underline">
← Back to Packets
</a>

</div>

</div>


@if($locked)

<div class="mb-6 border border-amber-200 bg-amber-50 px-4 py-3 text-xs text-amber-800 rounded">

This packet was already <strong>received by SACDEV</strong> on  
{{ \Carbon\Carbon::parse($packet->received_at)->format('F d, Y h:i A') }}.

Editing is now locked.

</div>

@endif



{{-- PROJECT INFO --}}
<div class="border border-slate-200 bg-white rounded-xl shadow-sm mb-6">

<div class="px-5 py-4 border-b border-slate-200 text-sm font-semibold">
Project Information
</div>

<div class="px-5 py-4 text-xs text-slate-700 space-y-2">

<div><strong>Project:</strong> {{ $project->title }}</div>
<div><strong>Packet Code:</strong> {{ $packet->packet_code }}</div>
<div>
<strong>Generated:</strong>
{{ \Carbon\Carbon::parse($packet->generated_at)->format('F d, Y') }}
</div>

</div>

</div>








@if($packet->return_remarks)

<div class="mb-6 border border-amber-200 bg-amber-50 px-4 py-3 text-xs text-amber-800 rounded">

<div class="font-semibold mb-1">
Packet Returned by SACDEV
</div>

<div class="leading-relaxed">
{{ $packet->return_remarks }}
</div>

@if($packet->returned_at)
<div class="text-[11px] mt-1 italic text-amber-700">
Returned on {{ \Carbon\Carbon::parse($packet->returned_at)->format('F d, Y h:i A') }}
</div>
@endif

</div>

@endif



{{-- DOCUMENT CHECKLIST --}}
<div class="border border-slate-200 bg-white rounded-xl shadow-sm mb-6">

<div class="px-5 py-4 border-b border-slate-200 text-sm font-semibold">
Documents Included in Packet
</div>

<form method="POST" action="{{ route('org.projects.packets.update', [$project,$packet]) }}">
@csrf

<div class="px-5 py-4 text-xs space-y-3">

<label class="flex items-center gap-2">
<input type="checkbox" name="has_solicitation_letter"
value="1"
{{ $packet->has_solicitation_letter ? 'checked' : '' }}
{{ $locked ? 'disabled' : '' }}>
Solicitation / Sponsorship Letters
</label>

<label class="flex items-center gap-2">
<input type="checkbox" name="has_disbursement_voucher"
value="1"
{{ $packet->has_disbursement_voucher ? 'checked' : '' }}
{{ $locked ? 'disabled' : '' }}>
Disbursement Voucher
</label>

<label class="flex items-center gap-2">
<input type="checkbox" name="has_collection_report"
value="1"
{{ $packet->has_collection_report ? 'checked' : '' }}
{{ $locked ? 'disabled' : '' }}>
Collection Report
</label>

<label class="flex items-center gap-2">
<input type="checkbox" name="has_certificates"
value="1"
{{ $packet->has_certificates ? 'checked' : '' }}
{{ $locked ? 'disabled' : '' }}>
Certificates
</label>

<label class="flex items-center gap-2">
<input type="checkbox" name="has_receipts"
value="1"
{{ $packet->has_receipts ? 'checked' : '' }}
{{ $locked ? 'disabled' : '' }}>
Official Receipts
</label>

</div>


{{-- OTHER ITEMS --}}
<div class="px-5 pb-4">

<label class="block text-xs font-medium mb-1">
Other Items Included
</label>

<textarea
name="other_items"
rows="2"
{{ $locked ? 'disabled' : '' }}
class="w-full border border-slate-300 rounded px-2 py-1 text-xs"
placeholder="Example: Attendance Sheet, Signed MOA, Event Photos"
>{{ $packet->other_items }}</textarea>

</div>

@if(!$locked)
<div class="px-5 py-4 border-t border-slate-200 text-right">

<button
class="px-3 py-2 text-xs font-medium rounded-md bg-indigo-600 text-white hover:bg-indigo-700">
Save Changes
</button>

</div>
@endif

</form>

</div>



{{-- SOLICITATION LETTERS --}}
<div class="border border-slate-200 bg-white rounded-xl shadow-sm mb-6">

<div class="px-5 py-4 border-b border-slate-200 text-sm font-semibold">
Solicitation / Sponsorship Letters
</div>

<div class="px-5 py-4 text-xs text-slate-700">

@if($packet->letters->count())

<table class="w-full text-xs mb-4">

<thead class="border-b text-slate-500">
<tr>
<th class="text-left py-1">Control Number</th>
<th class="text-left py-1">Organization</th>
<th class="text-right py-1">Action</th>
</tr>
</thead>

<tbody>

@foreach($packet->letters as $letter)

<tr class="border-b">

<td class="py-1">{{ $letter->control_number }}</td>
<td class="py-1">{{ $letter->organization_name }}</td>

<td class="text-right">

@if(!$locked)
<form method="POST"
action="{{ route('org.projects.packets.letters.destroy', [$project,$packet,$letter]) }}">
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
action="{{ route('org.projects.packets.letters.store', [$project,$packet]) }}">

@csrf

<div class="grid grid-cols-2 gap-3">

<input type="text"
name="control_number"
placeholder="Control Number"
class="border border-slate-300 rounded px-2 py-1 text-xs">

<input type="text"
name="organization_name"
placeholder="Organization"
class="border border-slate-300 rounded px-2 py-1 text-xs">

</div>

<button
class="mt-2 px-3 py-1 text-xs bg-slate-800 text-white rounded hover:bg-slate-900">
Add Letter
</button>

</form>

@endif

</div>

</div>



{{-- RECEIPTS --}}
@include('org.packets.partials.receipts')



{{-- DISBURSEMENT VOUCHERS --}}
@include('org.packets.partials.dvs')

</div>

</x-app-layout>