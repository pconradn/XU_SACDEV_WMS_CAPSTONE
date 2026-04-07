<x-app-layout>
<div class="max-w-5xl mx-auto px-4 py-6 space-y-6">

    {{-- HEADER --}}
    <div class="rounded-2xl border border-blue-200 bg-gradient-to-r from-blue-50 to-white shadow-sm p-5">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-xs uppercase tracking-wide text-blue-700 font-semibold">
                    External Packet Receiving
                </div>
                <div class="text-lg font-semibold text-slate-900 mt-1">
                    Scan or Enter Reference Number
                </div>
                <div class="text-sm text-slate-600 mt-1">
                    Search for a packet or scan QR to process its contents.
                </div>
            </div>
        </div>
    </div>

    {{-- SEARCH --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-5">
        <form method="POST" action="{{ route('admin.external-packets.lookup') }}" class="flex gap-2">
            @csrf

            <input type="text"
                   name="reference_no"
                   value="{{ $reference ?? '' }}"
                   placeholder="Enter reference number (e.g. EP-2026-0001)"
                   class="flex-1 rounded-xl border border-slate-300 px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">

            <button class="rounded-xl bg-blue-600 text-white px-4 py-2 text-sm font-medium hover:bg-blue-700">
                Search
            </button>
        </form>
    </div>

    {{-- NOT FOUND --}}
    @if($reference && !$packet)
        <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700">
            Packet not found.
        </div>
    @endif

    {{-- PACKET DISPLAY --}}
    @if($packet)

        {{-- PACKET INFO --}}
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-5 space-y-3">

            <div class="flex items-center justify-between">
                <div class="text-sm font-semibold text-slate-900">
                    {{ $packet->reference_no }}
                </div>

                {{-- STATUS --}}
                <div>
                    @if($packet->status === 'approved')
                        <span class="text-xs px-2 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                            Approved
                        </span>
                    @elseif($packet->status === 'returned')
                        <span class="text-xs px-2 py-1 rounded-full bg-rose-50 text-rose-700 border border-rose-200">
                            Returned
                        </span>
                    @elseif($packet->status === 'submitted')
                        <span class="text-xs px-2 py-1 rounded-full bg-blue-50 text-blue-700 border border-blue-200">
                            Submitted
                        </span>
                    @else
                        <span class="text-xs px-2 py-1 rounded-full bg-slate-50 text-slate-700 border border-slate-200">
                            {{ ucfirst($packet->status) }}
                        </span>
                    @endif
                </div>
            </div>

            <div class="text-sm text-slate-600">
                <div><strong>Project:</strong> {{ $packet->project->title }}</div>
                <div><strong>Destination:</strong> {{ $packet->destination }}</div>
            </div>

        </div>

        {{-- PROCESS FORM --}}
        @if($packet->status === 'submitted')

        <form method="POST"
              action="{{ route('admin.external-packets.process', $packet) }}"
              class="space-y-6">
            @csrf

            {{-- ITEMS --}}
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

                <div class="px-5 py-4 border-b bg-slate-50">
                    <div class="text-sm font-semibold text-slate-900">
                        Packet Items
                    </div>
                    <div class="text-xs text-slate-500">
                        All items must be marked before saving.
                    </div>
                </div>

                <div class="divide-y">

                    @foreach($packet->items as $item)
                        <div class="p-4 flex items-center justify-between gap-4">

                            {{-- LABEL --}}
                            <div>
                                <div class="text-sm font-medium text-slate-900">
                                    {{ $item->label }}
                                </div>
                                <div class="text-xs text-slate-500">
                                    {{ ucfirst($item->type) }}
                                </div>
                            </div>

                            {{-- STATUS SELECT --}}
                            <div class="flex gap-2">

                                <label class="flex items-center gap-1 text-xs">
                                    <input type="radio"
                                           name="items[{{ $item->id }}]"
                                           value="approved"
                                           class="text-emerald-600"
                                           required>
                                    <span class="text-emerald-700">Approve</span>
                                </label>

                                <label class="flex items-center gap-1 text-xs">
                                    <input type="radio"
                                           name="items[{{ $item->id }}]"
                                           value="returned"
                                           class="text-rose-600"
                                           required>
                                    <span class="text-rose-700">Return</span>
                                </label>

                            </div>

                        </div>
                    @endforeach

                </div>

            </div>

            {{-- REMARKS --}}
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-5">
                <label class="text-xs font-semibold text-slate-600 uppercase">
                    Remarks (if returned)
                </label>

                <textarea name="remarks"
                          rows="3"
                          class="mt-2 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm"></textarea>
            </div>

            {{-- ACTION --}}
            <div class="flex justify-end">
                <button class="bg-blue-600 text-white px-5 py-2.5 rounded-xl text-sm font-medium hover:bg-blue-700">
                    Save Decision
                </button>
            </div>

        </form>

        @else

        {{-- READ ONLY --}}
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-5">
            <div class="text-sm text-slate-600">
                This packet has already been processed.
            </div>

            <div class="mt-4 space-y-2">
                @foreach($packet->items as $item)
                    <div class="flex justify-between text-sm">
                        <span>{{ $item->label }}</span>
                        <span class="
                            text-xs px-2 py-1 rounded-full
                            {{ $item->status === 'approved'
                                ? 'bg-emerald-50 text-emerald-700 border border-emerald-200'
                                : 'bg-rose-50 text-rose-700 border border-rose-200'
                            }}
                        ">
                            {{ ucfirst($item->status) }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        @endif

    @endif

</div>
</x-app-layout>