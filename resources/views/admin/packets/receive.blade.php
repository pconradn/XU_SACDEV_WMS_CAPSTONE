<x-app-layout>

<div class="mx-auto max-w-3xl px-4 py-6 space-y-6">

    {{-- ================= HEADER ================= --}}
    <div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm p-5">

        <div>
            <h1 class="text-lg font-semibold text-slate-900">
                Packet Receiving
            </h1>

            <p class="text-xs text-slate-500 mt-1">
                Lookup a packet using its code and confirm physical submission.
            </p>
        </div>

    </div>


    {{-- ================= SEARCH CARD ================= --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-5">

        <form method="GET" action="{{ route('admin.packets.receive') }}">

            <div class="flex flex-col sm:flex-row gap-3 sm:items-end">

                {{-- INPUT --}}
                <div class="flex-1">

                    <label class="text-[11px] font-medium text-slate-600 uppercase tracking-wide">
                        Packet Code
                    </label>

                    <input
                        type="text"
                        name="packet_code"
                        value="{{ request('packet_code') }}"
                        placeholder="PKT-2026-0004"
                        autofocus
                        class="mt-1 w-full border border-slate-300 rounded-lg px-3 py-2 text-sm
                               focus:outline-none focus:ring-2 focus:ring-slate-200 focus:border-slate-400 transition"
                    >

                </div>

                {{-- BUTTON --}}
                <button
                    class="rounded-lg bg-slate-900 text-white text-sm font-semibold px-4 py-2.5 hover:bg-slate-800 transition">

                    Lookup Packet

                </button>

            </div>

        </form>

    </div>


    {{-- ================= PACKET RESULT ================= --}}
    @if(isset($packet) && $packet)

    <div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm">

        {{-- HEADER --}}
        <div class="px-5 py-4 border-b border-slate-200 flex items-center justify-between">

            <div>
                <div class="text-sm font-semibold text-slate-900">
                    Packet Information
                </div>
                <div class="text-xs text-slate-500">
                    Verify details before confirming receipt
                </div>
            </div>

            <span class="text-[11px] font-mono text-slate-500">
                {{ $packet->packet_code }}
            </span>

        </div>


        {{-- BODY --}}
        <div class="p-5 space-y-5">

            {{-- PROJECT --}}
            <div>
                <div class="text-[11px] text-slate-500">Project</div>
                <div class="text-sm font-semibold text-slate-900">
                    {{ $packet->project->title }}
                </div>
            </div>


            {{-- META GRID --}}
            <div class="grid grid-cols-2 gap-4 text-sm">

                <div>
                    <div class="text-[11px] text-slate-500">Generated</div>
                    <div class="text-slate-800">
                        {{ \Carbon\Carbon::parse($packet->generated_at)->format('M d, Y') }}
                    </div>
                </div>

                <div>
                    <div class="text-[11px] text-slate-500">Receipts</div>
                    <div class="text-slate-800">
                        {{ $packet->receipts->count() }}
                    </div>
                </div>

                <div>
                    <div class="text-[11px] text-slate-500">Disbursement Vouchers</div>
                    <div class="text-slate-800">
                        {{ $packet->dvs->count() }}
                    </div>
                </div>

                <div>
                    <div class="text-[11px] text-slate-500">Solicitation Letters</div>
                    <div class="text-slate-800">
                        {{ $packet->letters->count() }}
                    </div>
                </div>

            </div>


            {{-- STATUS --}}
            @if($packet->received_at)

                <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4">

                    <div class="text-xs font-semibold text-emerald-800">
                        Already Received
                    </div>

                    <div class="text-[11px] text-emerald-700 mt-1">
                        {{ \Carbon\Carbon::parse($packet->received_at)->format('F d, Y h:i A') }}
                    </div>

                </div>

            @else

                <div class="rounded-xl border border-amber-200 bg-amber-50 p-4">

                    <div class="text-xs font-semibold text-amber-800">
                        Pending Receipt
                    </div>

                    <div class="text-[11px] text-amber-700 mt-1">
                        This packet has not yet been marked as received.
                    </div>

                </div>

            @endif

        </div>


        {{-- ACTION --}}
        <div class="px-5 py-4 border-t border-slate-200 flex justify-end">

            @if(!$packet->received_at)

                <form
                    method="POST"
                    action="{{ route('admin.packets.mark_received', $packet) }}">

                    @csrf

                    <button
                        class="rounded-lg bg-emerald-600 text-white text-sm font-semibold px-4 py-2.5 hover:bg-emerald-700 transition">

                        Mark as Received

                    </button>

                </form>

            @endif

        </div>

    </div>

    @endif

</div>

</x-app-layout>