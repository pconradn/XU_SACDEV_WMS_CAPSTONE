{{-- ================= PACKETS ================= --}}
<div 
    x-data="{ expanded:false, open:false, packet:null }"
    class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm transition-all"
>

    {{-- ================= HEADER (CLICKABLE) ================= --}}
    <div 
        @click="expanded = !expanded"
        class="flex items-center justify-between p-5 cursor-pointer select-none"
    >
        <div class="flex items-center gap-2">
            <i data-lucide="package" class="w-4 h-4 text-slate-500"></i>
            <h2 class="text-sm font-semibold text-slate-800">
                External Packet Submissions
            </h2>
        </div>

        {{-- placeholder for future status --}}
            @php
                $latestPackets = $externalPackets->sortByDesc('id')->take(3);

                $approvedCount = $latestPackets->sum('approved');
                $returnedCount = $latestPackets->sum('returned');
                $totalCount = $latestPackets->sum('total');
                $pendingCount = max($totalCount - $approvedCount - $returnedCount, 0);
            @endphp

            <div class="relative group flex items-center gap-2" @click.stop>

            {{-- COMPACT COUNTER --}}
            <div class="text-[11px] font-semibold tracking-tight flex items-center gap-[2px]">

                <span class="text-emerald-600">
                    {{ $approvedCount }}
                </span>

                <span class="text-slate-400">/</span>

                <span >
                    {{ $pendingCount }}
                </span>

                <span class="text-slate-400">/</span>

                <span class="text-rose-600">
                    {{ $returnedCount }}
                </span>

            </div>

            {{-- TOOLTIP --}}
            <div class="absolute right-0 top-full mt-1 hidden group-hover:block z-50">
                <div class="rounded-md bg-slate-900 text-white text-[10px] px-2 py-1 shadow-lg whitespace-nowrap">
                    <div>Approved: {{ $approvedCount }}</div>
                    <div>Pending: {{ $pendingCount }}</div>
                    <div>Returned: {{ $returnedCount }}</div>
                </div>
            </div>

        </div>
    </div>


    {{-- ================= BODY ================= --}}
    <div 
        x-show="expanded"
        x-transition
        @click.stop
        class="px-5 pb-5 space-y-4"
    >

        <div class="space-y-3">

            @forelse($externalPackets->sortByDesc('id')->take(3) as $packet)

                @php
                    $color = match($packet->status) {
                        'approved' => 'emerald',
                        'returned' => 'rose',
                        'submitted' => 'blue',
                        default => 'slate'
                    };

                    $bg = match($packet->status) {
                        'approved' => 'bg-emerald-50',
                        'returned' => 'bg-rose-50',
                        'submitted' => 'bg-blue-50',
                        default => 'bg-slate-50'
                    };

                    $border = match($packet->status) {
                        'approved' => 'border-emerald-200',
                        'returned' => 'border-rose-200',
                        'submitted' => 'border-blue-200',
                        default => 'border-slate-200'
                    };
                @endphp

                <div 
                    @click.stop="packet = {{ json_encode($packet) }}; open = true"
                    class="cursor-pointer rounded-xl border {{ $border }} {{ $bg }} p-4 transition hover:shadow-md hover:scale-[1.01]"
                >

                    <div class="flex items-start justify-between gap-3">

                        <div class="min-w-0">

                            <div class="text-sm font-semibold text-slate-800">
                                {{ $packet->destination }}
                            </div>

                            <div class="text-xs text-slate-500 font-mono mt-0.5">
                                {{ $packet->reference_no }}
                            </div>

                            <div class="text-[11px] text-slate-600 mt-1">
                                {{ $packet->approved }} / {{ $packet->total }} approved
                            </div>

                        </div>

                        <span class="text-xs px-2 py-1 rounded-full
                            bg-{{ $color }}-100 text-{{ $color }}-700 border border-{{ $color }}-200">
                            {{ ucfirst($packet->status) }}
                        </span>

                    </div>

                    <div class="mt-2 text-[10px] text-slate-500">
                        Click to view details
                    </div>

                </div>

            @empty

                <div class="text-xs text-slate-400 flex items-center gap-2">
                    <i data-lucide="inbox" class="w-4 h-4"></i>
                    No packets submitted yet.
                </div>

            @endforelse

        </div>


        @if($externalPackets->count() > 3)
            <div class="pt-2 border-t border-slate-100 text-center">
                <span class="text-[11px] text-slate-400">
                    Showing latest 3 packets
                </span>
            </div>
        @endif

    </div>


    {{-- ================= MODAL ================= --}}
    <div 
        x-show="open"
        x-transition
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/40"
    >
        <div 
            @click.away="open = false"
            class="bg-white rounded-2xl shadow-lg w-full max-w-md p-5 space-y-4"
        >

            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm font-semibold text-slate-900">
                        Packet Details
                    </div>
                    <div class="text-xs text-slate-500">
                        Reference Info
                    </div>
                </div>

                <button @click="open = false" class="text-slate-400 hover:text-slate-600">
                    ✕
                </button>
            </div>

            <template x-if="packet">

                <div class="space-y-3 text-sm">

                    <div>
                        <div class="text-xs text-slate-500">Destination</div>
                        <div class="font-medium text-slate-800" x-text="packet.destination"></div>
                    </div>

                    <div>
                        <div class="text-xs text-slate-500">Reference No.</div>
                        <div class="font-mono text-slate-700" x-text="packet.reference_no"></div>
                    </div>

                    <div class="grid grid-cols-3 gap-3 text-center">

                        <div class="rounded-lg bg-slate-50 p-2">
                            <div class="text-xs text-slate-500">Total</div>
                            <div class="font-semibold text-slate-800" x-text="packet.total"></div>
                        </div>

                        <div class="rounded-lg bg-emerald-50 p-2">
                            <div class="text-xs text-emerald-600">Approved</div>
                            <div class="font-semibold text-emerald-700" x-text="packet.approved"></div>
                        </div>

                        <div class="rounded-lg bg-rose-50 p-2">
                            <div class="text-xs text-rose-600">Returned</div>
                            <div class="font-semibold text-rose-700" x-text="packet.returned"></div>
                        </div>

                    </div>

                    <div>
                        <div class="text-xs text-slate-500">Status</div>
                        <div class="font-semibold text-slate-800 capitalize" x-text="packet.status"></div>
                    </div>

                </div>

            </template>

        </div>
    </div>

</div>