<x-app-layout>

<div class="mx-auto max-w-6xl px-4 py-6">

    

        <div class="rounded-2xl border border-amber-200 bg-gradient-to-b from-amber-50 to-white shadow-sm px-5 py-4" style="margin-bottom:20px">

            <div class="flex items-center justify-between gap-4">

                {{-- LEFT --}}
                <div class="flex items-start gap-3">

                    {{-- ICON --}}
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-amber-700 border border-amber-200">
                        <i data-lucide="package" class="w-5 h-5"></i>
                    </div>

                    {{-- TEXT --}}
                    <div>
                        <h1 class="text-sm font-semibold text-slate-900 leading-tight">
                            Org Packet Submissions
                        </h1>

                        <div class="mt-0.5 text-[11px] text-slate-500">
                            Manage and review submission packets for this project
                        </div>

                        <div class="mt-2 text-[11px]">
                            <span class="text-slate-400">Project:</span>
                            <span class="font-medium text-slate-700">
                                {{ $project->title }}
                            </span>
                        </div>
                    </div>

                </div>


                {{-- RIGHT (OPTIONAL FUTURE ACTION SLOT) --}}
                <div class="hidden md:flex items-center gap-2">

                    <div class="text-[10px] px-2 py-1 rounded-full bg-amber-100 text-amber-700 font-semibold">
                        {{ $packets->count() }} Packets
                    </div>

                </div>

            </div>

        </div>

   

    {{-- CARD --}}
    <div class="rounded-2xl border border-amber-200 bg-gradient-to-b from-amber-50 to-white shadow-sm overflow-hidden">

        <div class="px-4 py-3 border-b border-amber-200 text-sm font-semibold text-amber-800">
            Submission Packets
        </div>

        <div class="overflow-x-auto">

            <table class="w-full text-xs">

                <thead class="bg-white/70 border-b border-slate-200 text-slate-600">
                    <tr>
                        <th class="px-4 py-3 text-left">Packet Code</th>
                        <th class="px-4 py-3 text-left">Received</th>
                        <th class="px-4 py-3 text-left">Receipts</th>
                        <th class="px-4 py-3 text-left">DV</th>
                        <th class="px-4 py-3 text-left">Letters</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>

                <tbody>

                @foreach($packets as $packet)

                <tr 
                    class="border-b hover:bg-amber-50/50 transition cursor-pointer"
                    onclick="openPacketModal({{ $packet->id }})"
                >

                    <td class="px-4 py-3 font-semibold text-slate-800">
                        {{ $packet->packet_code }}
                    </td>

                    <td class="px-4 py-3">
                        @if($packet->received_at)
                            {{ \Carbon\Carbon::parse($packet->received_at)->format('M d, Y') }}
                        @else
                            <span class="text-slate-400">—</span>
                        @endif
                    </td>

                    <td class="px-4 py-3">{{ $packet->receipts->count() }}</td>
                    <td class="px-4 py-3">{{ $packet->dvs->count() }}</td>
                    <td class="px-4 py-3">{{ $packet->letters->count() }}</td>

                    <td class="px-4 py-3">

                        @php $status = $packet->status; @endphp

                        @if($status === 'received_by_sacdev')
                            <span class="px-2 py-1 rounded-full bg-blue-100 text-blue-700 text-[10px] font-semibold">Received</span>

                        @elseif($status === 'verified_by_sacdev')
                            <span class="px-2 py-1 rounded-full bg-emerald-100 text-emerald-700 text-[10px] font-semibold">Verified</span>


                        @elseif($status === 'generated')
                            <span class="px-2 py-1 rounded-full bg-amber-100 text-amber-700 text-[10px] font-semibold">Generated</span>

                        @else
                            <span class="px-2 py-1 rounded-full bg-slate-100 text-slate-700 text-[10px] font-semibold">
                                {{ $status }}
                            </span>
                        @endif

                    </td>

                    <td class="px-4 py-3 text-right space-x-2" onclick="event.stopPropagation()">

                        @if($packet->status === 'received_by_sacdev')
                            <form method="POST" action="{{ route('admin.packets.verify',$packet) }}" class="inline">
                                @csrf
                                <button class="text-blue-600 hover:underline">Verify</button>
                            </form>
                        @endif

                        @if($packet->status === 'verified_by_sacdev')
                            <form method="POST" action="{{ route('admin.packets.revert_received',$packet) }}" class="inline">
                                @csrf
                                <button class="text-amber-600 hover:underline">Revert</button>
                            </form>
                        @endif



                        @if(in_array($packet->status,['received_by_sacdev','verified_by_sacdev']))
                            <button onclick="openReturnModal({{ $packet->id }})" class="text-rose-600 hover:underline">
                                Return
                            </button>
                        @endif

                    </td>

                </tr>

                {{-- MODAL --}}
                <div id="packetModal-{{ $packet->id }}" class="hidden fixed inset-0 bg-black/40 z-50 items-center justify-center">

                    <div class="w-full max-w-3xl rounded-2xl border border-amber-200 bg-gradient-to-b from-amber-50 to-white shadow-lg p-6 space-y-5">

                        {{-- HEADER --}}
                        <div class="flex justify-between items-center">
                            <div>
                                <h2 class="text-sm font-semibold text-slate-900">
                                    Packet Details
                                </h2>
                                <p class="text-[11px] text-slate-500">
                                    {{ $packet->packet_code }}
                                </p>
                            </div>

                            <button onclick="closePacketModal({{ $packet->id }})"
                                    class="text-slate-400 hover:text-slate-600 text-sm">
                                ✕
                            </button>
                        </div>

                        {{-- BASIC INFO --}}
                        <div class="grid grid-cols-2 gap-3 text-xs">

                            <div>
                                <div class="text-slate-500">Status</div>
                                <div class="font-semibold text-slate-800">
                                    {{ str_replace('_',' ', $packet->status) }}
                                </div>
                            </div>

                            <div>
                                <div class="text-slate-500">Generated</div>
                                <div class="font-medium">
                                    {{ optional($packet->generated_at)->format('M d, Y') ?? '—' }}
                                </div>
                            </div>

                            <div>

                            </div>

                            <div>
                                <div class="text-slate-500">Received</div>
                                <div class="font-medium">
                                    {{ optional($packet->received_at)->format('M d, Y') ?? '—' }}
                                </div>
                            </div>

                        </div>


                        {{-- CONTENT SUMMARY --}}
                        <div class="border-t border-amber-200 pt-3">

                            <div class="text-xs font-semibold text-amber-700 mb-2">
                                Packet Contents
                            </div>

                            <div class="grid grid-cols-3 gap-2 text-xs text-slate-600">

                                <div>Receipts: <span class="font-semibold">{{ $packet->receipts->count() }}</span></div>
                                <div>DVs: <span class="font-semibold">{{ $packet->dvs->count() }}</span></div>
                                <div>Letters: <span class="font-semibold">{{ $packet->letters->count() }}</span></div>

                                <div>Certificates: <span class="font-semibold">{{ $packet->has_certificates ? 'Yes' : 'No' }}</span></div>
                                <div>Collection Report: <span class="font-semibold">{{ $packet->has_collection_report ? 'Yes' : 'No' }}</span></div>
                                <div>Receipts Flag: <span class="font-semibold">{{ $packet->has_receipts ? 'Yes' : 'No' }}</span></div>

                            </div>

                        </div>


                        {{-- RECEIPTS --}}
                        @if($packet->receipts->count())
                        <div class="border-t border-slate-200 pt-3">

                            <div class="text-xs font-semibold text-slate-700 mb-2">
                                Official Receipts
                            </div>

                            <div class="max-h-24 overflow-y-auto text-[11px] text-slate-600 space-y-1">
                                @foreach($packet->receipts as $r)
                                    <div class="flex justify-between">
                                        <span>{{ $r->or_number }}</span>
                                    </div>
                                @endforeach
                            </div>

                        </div>
                        @endif


                        {{-- DVs --}}
                        @if($packet->dvs->count())
                        <div class="border-t border-slate-200 pt-3">

                            <div class="text-xs font-semibold text-slate-700 mb-2">
                                Disbursement Vouchers
                            </div>

                            <div class="max-h-28 overflow-y-auto text-[11px] space-y-1">
                                @foreach($packet->dvs as $dv)
                                    <div class="flex justify-between text-slate-600">
                                        <span>{{ $dv->dv_reference }}</span>
                                        <span class="font-medium">₱{{ number_format($dv->amount,2) }}</span>
                                    </div>
                                @endforeach
                            </div>

                        </div>
                        @endif


                        {{-- LETTERS --}}
                        @if($packet->letters->count())
                        <div class="border-t border-slate-200 pt-3">

                            <div class="text-xs font-semibold text-slate-700 mb-2">
                                Solicitation Letters
                            </div>

                            <div class="max-h-24 overflow-y-auto text-[11px] text-slate-600 space-y-1">
                                @foreach($packet->letters as $l)
                                    <div class="flex justify-between">
                                        <span>{{ $l->control_number }}</span>
                                        <span class="text-slate-400">{{ $l->organization_name }}</span>
                                    </div>
                                @endforeach
                            </div>

                        </div>
                        @endif


                        {{-- OTHER ITEMS --}}
                        @if($packet->other_items)
                        <div class="border-t border-slate-200 pt-3 text-[11px] text-slate-600">
                            <div class="font-semibold text-slate-700 mb-1">Other Items</div>
                            {{ $packet->other_items }}
                        </div>
                        @endif


                        {{-- RETURN REMARKS --}}
                        @if($packet->return_remarks)
                        <div class="border-t border-rose-200 pt-3 text-[11px] text-rose-600">
                            <div class="font-semibold">Return Remarks</div>
                            {{ $packet->return_remarks }}
                        </div>
                        @endif

                    </div>

                </div>

                @endforeach

                </tbody>

            </table>

        </div>

    </div>

</div>


{{-- SCRIPT --}}
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