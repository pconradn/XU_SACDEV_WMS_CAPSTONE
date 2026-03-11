<x-app-layout>

<div class="mx-auto max-w-6xl px-4 py-6">

<div class="flex items-center justify-between mb-6">

    <div>
        <h1 class="text-lg font-semibold text-slate-900">
            Physical Submission Packets
        </h1>

        <div class="text-xs text-slate-500">
            Project: {{ $project->title }}
        </div>
    </div>

    <div class="flex items-center gap-3">

        <a href="{{ route('org.projects.documents.hub', $project) }}"
           class="text-xs text-slate-600 hover:underline">
            ← Back to Project Hub
        </a>

        <form method="POST" action="{{ route('org.projects.packet.create', $project) }}">
            @csrf

            <button
            class="inline-flex items-center px-3 py-2 text-xs font-medium rounded-md bg-indigo-600 text-white hover:bg-indigo-700">
                + Create Packet
            </button>

        </form>

    </div>

</div>


<div class="border border-slate-200 bg-white rounded-xl shadow-sm overflow-hidden">

<table class="w-full text-xs">

<thead class="bg-slate-50 border-b border-slate-200">

<tr>

<th class="px-4 py-2 text-left">Packet Code</th>

<th class="px-4 py-2 text-left">Generated</th>

<th class="px-4 py-2 text-left">Receipts</th>

<th class="px-4 py-2 text-left">Status</th>

<th class="px-4 py-2 text-right">Actions</th>

</tr>

</thead>


<tbody>

@forelse($packets as $packet)

<tr class="border-b">

<td class="px-4 py-3 font-medium text-slate-900">
{{ $packet->packet_code }}
</td>

<td class="px-4 py-3 text-slate-600">
{{ $packet->generated_at?->format('M d, Y') ?? '-' }}
</td>

<td class="px-4 py-3 text-slate-600">
{{ $packet->receipts->count() }}
</td>

<td class="px-4 py-3">

<span class="text-xs px-2 py-1 rounded bg-indigo-50 text-indigo-700">
{{ ucfirst($packet->status ?? 'generated') }}
</span>

</td>

<td class="px-4 py-3 text-right space-x-3">

<a href="{{ route('org.projects.packet.show', [$project, $packet]) }}"
   class="text-blue-600 hover:underline">
Manage
</a>

<form method="POST"
      action="{{ route('org.projects.packet.destroy', [$project, $packet]) }}"
      class="inline">

@csrf
@method('DELETE')

<button class="text-red-600 hover:underline">
Archive
</button>

</form>

</td>

</tr>

@empty

<tr>

<td colspan="5" class="px-4 py-6 text-center text-slate-500">

No submission packets yet.

<div class="mt-3">

<form method="POST" action="{{ route('org.projects.packet.create', $project) }}">
@csrf

<button
class="px-3 py-2 text-xs bg-indigo-600 text-white rounded hover:bg-indigo-700">
Create First Packet
</button>

</form>

</div>

</td>

</tr>

@endforelse

</tbody>

</table>

</div>

</div>

</x-app-layout>