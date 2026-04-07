<div class="rounded-2xl border border-slate-200 bg-gradient-to-br from-white to-slate-50 shadow-sm p-5 space-y-4">

    {{-- HEADER --}}
    <div class="flex items-center justify-between">

        <div class="flex items-center gap-2">
            <i data-lucide="package" class="w-4 h-4 text-slate-500"></i>
            <h3 class="text-sm font-semibold text-slate-800">
                External Packets
            </h3>
        </div>

        <a href="{{ route('admin.external-packets.create', $project) }}"
           class="inline-flex items-center gap-1.5 text-xs font-semibold bg-slate-900 text-white px-3 py-1.5 rounded-lg hover:bg-slate-800 transition">
            <i data-lucide="plus" class="w-3.5 h-3.5"></i>
            New
        </a>

    </div>


    {{-- LIST --}}
    <div class="space-y-3">

        @forelse($project->externalPackets as $packet)

        @php
            $total = $packet->items->count();
            $approved = $packet->items->where('status', 'approved')->count();
            $returned = $packet->items->where('status', 'returned')->count();
        @endphp

        <div class="group border border-slate-200 rounded-xl p-4 bg-white hover:shadow-md transition">

            {{-- TOP --}}
            <div class="flex items-start justify-between gap-3">

                <div class="min-w-0">

                    {{-- DESTINATION --}}
                    <div class="flex items-center gap-2">
                        <i data-lucide="send" class="w-3.5 h-3.5 text-slate-400"></i>

                        <span class="text-xs font-semibold text-slate-800 truncate">
                            {{ $packet->destination }}
                        </span>
                    </div>

                    {{-- REFERENCE --}}
                    <div class="text-[10px] text-slate-400 mt-0.5 font-mono">
                        {{ $packet->reference_no }}
                    </div>

                    <div class="text-[10px] text-slate-400">
                        {{ $total }} items
                    </div>

                </div>


                {{-- STATUS --}}
                <div class="text-right">

                    @if($packet->status === 'approved')
                        <span class="text-[10px] px-2 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                            Approved
                        </span>
                    @elseif($packet->status === 'returned')
                        <span class="text-[10px] px-2 py-1 rounded-full bg-rose-50 text-rose-700 border border-rose-200">
                            Returned
                        </span>
                    @elseif($packet->status === 'submitted')
                        <span class="text-[10px] px-2 py-1 rounded-full bg-blue-50 text-blue-700 border border-blue-200">
                            Submitted
                        </span>
                    @else
                        <span class="text-[10px] px-2 py-1 rounded-full bg-slate-50 text-slate-700 border border-slate-200">
                            Prepared
                        </span>
                    @endif

                </div>

            </div>


            {{-- ITEMS PREVIEW --}}
            <div class="mt-2 text-[11px] text-slate-600 space-y-1">

                @foreach($packet->items->take(3) as $item)
                    <div class="flex items-center gap-1.5">

                        <span class="w-1.5 h-1.5 rounded-full
                            @if($item->status === 'approved') bg-emerald-400
                            @elseif($item->status === 'returned') bg-rose-400
                            @else bg-amber-400
                            @endif
                        "></span>

                        <span class="truncate">{{ $item->label }}</span>

                    </div>
                @endforeach

                @if($total > 3)
                    <div class="text-[10px] text-slate-400 ml-2">
                        + {{ $total - 3 }} more
                    </div>
                @endif

            </div>


            {{-- RECEIVING HINT --}}
            @if($packet->status === 'submitted')
                <div class="mt-2 text-[10px] text-blue-600 flex items-center gap-1">
                    <i data-lucide="scan-line" class="w-3 h-3"></i>
                    Ready for receiving (scan QR or search reference)
                </div>
            @endif


            {{-- ACTIONS --}}
            <div class="flex items-center justify-between mt-3 pt-2 border-t border-slate-100">

                <div class="flex items-center gap-2">

                    {{-- VIEW --}}
                <a href="{{ route('admin.external-packets.receive', ['ref' => $packet->reference_no]) }}"
                class="inline-flex items-center gap-1 text-[11px] font-medium text-blue-600 hover:text-blue-800">
                    <i data-lucide="scan-line" class="w-3.5 h-3.5"></i>
                    Open
                </a>

                </div>

                <div class="flex items-center gap-2">

                    {{-- PRINT --}}
                    <a href="{{ route('admin.external-packets.print', [$packet->project, $packet]) }}"
                       class="inline-flex items-center gap-1 text-[11px] font-semibold bg-slate-900 text-white px-2.5 py-1 rounded-md hover:bg-slate-800 transition">
                        <i data-lucide="printer" class="w-3.5 h-3.5"></i>
                        Print
                    </a>

                    {{-- SUBMIT --}}
                    @if($packet->status === 'prepared')
                        <form method="POST"
                              action="{{ route('admin.external-packets.submit', [$packet->project, $packet]) }}">
                            @csrf
                            <button class="inline-flex items-center gap-1 text-[11px] font-medium text-emerald-700 border border-emerald-200 px-2.5 py-1 rounded-md hover:bg-emerald-50">
                                <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                Submit
                            </button>
                        </form>
                    @endif

                </div>

            </div>

        </div>

        @empty

        <div class="text-xs text-slate-400 flex items-center gap-2">
            <i data-lucide="inbox" class="w-4 h-4"></i>
            No packets yet.
        </div>

        @endforelse

    </div>


    {{-- FOOTER --}}
    <div class="pt-2 border-t border-slate-100">

        <a href="{{ route('admin.external-packets.index', $project) }}"
           class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 text-xs font-semibold rounded-xl border border-slate-200 bg-white hover:bg-slate-50">

            <i data-lucide="layers" class="w-4 h-4 text-slate-500"></i>

            View All Packets

        </a>

    </div>

</div>