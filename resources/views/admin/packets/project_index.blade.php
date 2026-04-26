<x-app-layout>

<div class="mx-auto max-w-6xl px-4 py-6 space-y-6">

    <div class="rounded-2xl border border-amber-200 bg-gradient-to-b from-amber-50 to-white shadow-sm px-5 py-4">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">

            <div class="flex items-start gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-100 text-amber-700 border border-amber-200">
                    <i data-lucide="package" class="w-5 h-5"></i>
                </div>

                <div>
                    <h1 class="text-sm font-semibold text-slate-900">
                        Org Packet Submissions
                    </h1>

                    <div class="mt-0.5 text-[11px] text-slate-500">
                        View, inspect, and manage submission packets for this project
                    </div>

                    <div class="mt-2 text-[11px]">
                        <span class="text-slate-400">Project:</span>
                        <span class="font-medium text-slate-700">
                            {{ $project->title }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <div class="text-[10px] px-2 py-1 rounded-full bg-amber-100 text-amber-700 font-semibold">
                    {{ $packets->count() }} Packets
                </div>
            </div>

        </div>
    </div>


    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm px-4 py-3 text-[11px] text-slate-600">
        Tap a row to view packet details. Use actions on the right to process packets. Status reflects the current processing stage.
    </div>


    <div class="rounded-2xl border border-amber-200 bg-gradient-to-b from-amber-50 to-white shadow-sm overflow-hidden">

        <div class="px-4 py-3 border-b border-amber-200 text-sm font-semibold text-amber-800">
            Submission Packets
        </div>

        <div class="hidden md:block overflow-x-auto">

            <table class="w-full text-xs">

                <thead class="bg-white/70 border-b border-slate-200 text-slate-600">
                    <tr>
                        <th class="px-4 py-3 text-left">Packet Code</th>
                        <th class="px-4 py-3 text-left">Received</th>
                        <th class="px-4 py-3 text-left">Items</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>

                <tbody>

                @foreach($packets as $packet)

                @php
                    $statusStyle = match($packet->status) {
                        'ready_for_claiming' => 'bg-emerald-100 text-emerald-700',
                        'reviewed' => 'bg-blue-100 text-blue-700',
                        'under_review' => 'bg-amber-100 text-amber-700',
                        default => 'bg-slate-100 text-slate-700'
                    };
                @endphp

                <tr
                    class="border-b hover:bg-amber-50/50 transition cursor-pointer"
                    onclick="window.location='{{ route('admin.packets.receive', ['packet_code' => $packet->packet_code]) }}'">

                    <td class="px-4 py-3 font-semibold text-slate-800">
                        {{ $packet->packet_code }}
                    </td>

                    <td class="px-4 py-3 text-slate-600">
                        {{ $packet->received_at ? \Carbon\Carbon::parse($packet->received_at)->format('M d, Y') : '—' }}
                    </td>

                    <td class="px-4 py-3 text-slate-600">
                        {{ $packet->items->count() }}
                    </td>

                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded-full text-[10px] font-semibold {{ $statusStyle }}">
                            {{ str_replace('_',' ', $packet->status) }}
                        </span>
                    </td>

                    <td class="px-4 py-3 text-right space-x-2" onclick="event.stopPropagation()">

                        @if($packet->status === 'reviewed')
                            <form method="POST" action="{{ route('admin.packets.mark_ready',$packet) }}" class="inline">
                                @csrf
                                <button class="text-emerald-600 hover:underline text-[11px]">Ready</button>
                            </form>
                        @endif

                        @if(in_array($packet->status,['reviewed','ready_for_claiming']))
                            <form method="POST" action="{{ route('admin.packets.revert',$packet) }}" class="inline">
                                @csrf
                                <button class="text-slate-600 hover:underline text-[11px]">Revert</button>
                            </form>
                        @endif

                    </td>

                </tr>

                @endforeach

                </tbody>

            </table>

        </div>


        <div class="md:hidden divide-y">

            @foreach($packets as $packet)

            @php
                $statusStyle = match($packet->status) {
                    'ready_for_claiming' => 'border-emerald-300 bg-emerald-50/50',
                    'reviewed' => 'border-blue-300 bg-blue-50/40',
                    'under_review' => 'border-amber-300 bg-amber-50/40',
                    default => 'border-slate-200 bg-white'
                };
            @endphp

                <div
                    class="p-4 border-l-4 {{ $statusStyle }} space-y-3 cursor-pointer"
                    onclick="window.location='{{ route('admin.packets.receive', ['packet_code' => $packet->packet_code]) }}'">

                <div class="flex justify-between items-start">
                    <div class="text-sm font-semibold text-slate-900">
                        {{ $packet->packet_code }}
                    </div>

                    <span class="text-[10px] px-2 py-1 rounded-full bg-white/70 border text-slate-600">
                        {{ str_replace('_',' ', $packet->status) }}
                    </span>
                </div>

                <div class="text-xs text-slate-500">
                    {{ $packet->project->title }}
                </div>

                <div class="text-xs text-slate-600 flex justify-between">
                    <span>Received:</span>
                    <span>{{ $packet->received_at ? \Carbon\Carbon::parse($packet->received_at)->format('M d') : '—' }}</span>
                </div>

                <div class="text-xs text-slate-600 flex justify-between">
                    <span>Total Items:</span>
                    <span class="font-semibold">{{ $packet->items->count() }}</span>
                </div>

                <div class="pt-2 flex gap-3 text-[11px]" onclick="event.stopPropagation()">

                    @if($packet->status === 'reviewed')
                        <form method="POST" action="{{ route('admin.packets.mark_ready',$packet) }}">
                            @csrf
                            <button class="text-emerald-600 font-medium">Ready</button>
                        </form>
                    @endif

                    @if(in_array($packet->status,['reviewed','ready_for_claiming']))
                        <form method="POST" action="{{ route('admin.packets.revert',$packet) }}">
                            @csrf
                            <button class="text-slate-600 font-medium">Revert</button>
                        </form>
                    @endif

                </div>

            </div>

            @endforeach

        </div>

    </div>

</div>


<script>
function openPacketModal(id) {
    document.getElementById('packetModal-' + id).classList.remove('hidden');
    document.getElementById('packetModal-' + id).classList.add('flex');
}
function closePacketModal(id) {
    document.getElementById('packetModal-' + id).classList.add('hidden');
}
</script>

@include('admin.packets.modals')

</x-app-layout>