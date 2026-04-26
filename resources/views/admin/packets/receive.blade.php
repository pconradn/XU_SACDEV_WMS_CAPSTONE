<x-app-layout>

<div class="mx-auto max-w-4xl px-4 py-6 space-y-6">

    <div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm p-5">
        <div class="flex items-start gap-3">
            <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-100 text-slate-700">
                <i data-lucide="scan-line" class="w-5 h-5"></i>
            </div>

            <div>
                <h1 class="text-lg font-semibold text-slate-900">
                    Packet Receiving & Review
                </h1>
                <p class="text-xs text-slate-500 mt-1">
                    Search a packet, confirm physical receipt, review each item, and mark items that can be claimed.
                </p>
            </div>
        </div>
    </div>

    <div class="rounded-2xl border border-indigo-200 bg-gradient-to-r from-indigo-50 to-white px-4 py-3 shadow-sm">
        <div class="flex items-start gap-3">
            <div class="mt-0.5 flex h-7 w-7 items-center justify-center rounded-lg bg-indigo-100 text-indigo-700">
                <i data-lucide="info" class="w-4 h-4"></i>
            </div>

            <div class="text-xs text-indigo-800 leading-relaxed">
                <div class="font-semibold mb-1">How to process this packet</div>
                <div class="space-y-1">
                    <div>1. Enter or scan the packet code from the printed cover sheet.</div>
                    <div>2. Check the physical documents, then mark the packet as received.</div>
                    <div>3. Review each item and select its correct status.</div>
                    <div>4. Save the review. If changes are needed later, revert the packet back to review mode.</div>
                </div>
            </div>
        </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-5">
        <form method="GET" action="{{ route('admin.packets.receive') }}">
            <div class="flex flex-col sm:flex-row gap-3 sm:items-end">
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
                        class="mt-1 w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 shadow-sm transition focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-200"
                    >
                </div>

                <button
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800 active:scale-[0.99]">
                    <i data-lucide="search" class="w-4 h-4"></i>
                    Lookup Packet
                </button>
            </div>
        </form>
    </div>

    @if(isset($packet) && $packet)

        @php
            $editable = $packet->status === 'under_review';

            $packetStatusStyle = match($packet->status) {
                'ready_for_claiming' => 'border-emerald-200 bg-emerald-50 text-emerald-700',
                'reviewed' => 'border-blue-200 bg-blue-50 text-blue-700',
                'under_review' => 'border-amber-200 bg-amber-50 text-amber-700',
                default => 'border-slate-200 bg-slate-100 text-slate-600'
            };

            $orderedTypes = ['dv', 'receipt', 'solicitation_letter', 'other'];

            $grouped = $packet->items->groupBy('type');

            $typeMeta = [
                'dv' => [
                    'label' => 'Disbursement Vouchers',
                    'color' => 'amber',
                    'icon' => 'file-text'
                ],
                'receipt' => [
                    'label' => 'Official Receipts',
                    'color' => 'emerald',
                    'icon' => 'receipt'
                ],
                'solicitation_letter' => [
                    'label' => 'Solicitation Letters',
                    'color' => 'blue',
                    'icon' => 'mail'
                ],
                'other' => [
                    'label' => 'Other Items',
                    'color' => 'slate',
                    'icon' => 'layers'
                ],
            ];
        @endphp

        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

            <div class="border-b border-slate-200 bg-gradient-to-b from-slate-50 to-white px-5 py-4">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <div class="flex items-center gap-2">
                            <div class="text-sm font-semibold text-slate-900">
                                {{ $packet->packet_code }}
                            </div>
                            <span class="inline-flex items-center rounded-full border px-2 py-1 text-[11px] font-medium {{ $packetStatusStyle }}">
                                {{ str_replace('_', ' ', $packet->status) }}
                            </span>
                        </div>

                        <div class="mt-1 text-xs text-slate-500">
                            {{ $packet->project->title }}
                        </div>

                        <div class="mt-2 grid grid-cols-1 gap-1 text-[11px] text-slate-500 sm:grid-cols-2">
                            <div>
                                Generated:
                                <span class="font-medium text-slate-700">
                                    {{ $packet->generated_at?->format('M d, Y') ?? '—' }}
                                </span>
                            </div>

                            <div>
                                Received:
                                <span class="font-medium text-slate-700">
                                    {{ $packet->received_at?->format('M d, Y h:i A') ?? 'Not yet received' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-5 space-y-6">

                @if(!$packet->received_at)
                    <div class="rounded-2xl border border-amber-200 bg-gradient-to-r from-amber-50 to-white p-4 shadow-sm">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                            <div class="flex items-start gap-3">
                                <div class="mt-0.5 flex h-8 w-8 items-center justify-center rounded-lg bg-amber-100 text-amber-700">
                                    <i data-lucide="package-check" class="w-4 h-4"></i>
                                </div>

                                <div>
                                    <div class="text-sm font-semibold text-amber-900">
                                        Waiting for physical documents
                                    </div>
                                    <div class="mt-1 text-xs text-amber-800">
                                        Mark this packet as received only after the printed packet and supporting documents are physically submitted.
                                    </div>
                                </div>
                            </div>

                            <form method="POST" action="{{ route('admin.packets.receive.store', $packet) }}" class="shrink-0">
                                @csrf
                                <button
                                    class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-xs font-semibold text-white shadow-sm transition hover:bg-emerald-700 active:scale-[0.99] sm:w-auto">
                                    <i data-lucide="check-circle-2" class="w-4 h-4"></i>
                                    Mark as Received
                                </button>
                            </form>
                        </div>
                    </div>
                @endif

                @if($packet->status === 'reviewed')
                    <div class="rounded-2xl border border-blue-200 bg-blue-50 p-4 text-xs text-blue-800">
                        Review has been saved. To change item statuses, revert this packet back to review mode.
                    </div>
                @endif

                @if($packet->status === 'ready_for_claiming')
                    <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-xs text-emerald-800">
                        This packet is ready for claiming. Front desk staff may mark claimable items once released.
                    </div>
                @endif

                @if($packet->received_at)

                    <form method="POST" action="{{ route('admin.packets.review', $packet) }}">
                    @csrf

                    <div class="space-y-4">

                    @foreach($orderedTypes as $type)
                    @if(isset($grouped[$type]))

                    @php
                        $meta = $typeMeta[$type];
                        $color = $meta['color'];
                    @endphp

                    <div class="rounded-2xl border border-{{ $color }}-200 bg-gradient-to-b from-{{ $color }}-50/40 to-white shadow-sm overflow-hidden">

                        <div class="px-4 py-3 border-b border-{{ $color }}-100 text-xs font-semibold text-{{ $color }}-700 flex items-center gap-2">
                            <i data-lucide="{{ $meta['icon'] }}" class="w-4 h-4"></i>
                            {{ $meta['label'] }}
                        </div>

                        <div class="divide-y">

                        @foreach($grouped[$type] as $item)

                        @php
                            $status = $item->review_status;

                            $cardStyle = match($status) {
                                'ready_for_claiming' => 'border-emerald-300 bg-emerald-50/50',
                                'revision_required' => 'border-rose-300 bg-rose-50/50',
                                'reviewed' => 'border-blue-300 bg-blue-50/40',
                                default => 'border-slate-200 bg-white'
                            };
                        @endphp

                        <div class="p-4">
                            <div class="rounded-xl border {{ $cardStyle }} p-4 space-y-3">

                                {{-- TOP ROW --}}
                                <div class="flex justify-between gap-3">

                                    <div class="flex-1 min-w-0">
                                        <div class="text-[10px] uppercase text-slate-400 font-semibold">Reference</div>
                                        <div class="text-sm font-semibold text-slate-900 truncate">
                                            {{ $item->reference_number }}
                                        </div>
                                    </div>

                                    <div class="flex-1 min-w-0">
                                        <div class="text-[10px] uppercase text-slate-400 font-semibold">Description</div>
                                        <div class="text-sm text-slate-700 leading-snug">
                                            {{ $item->label }}
                                        </div>
                                    </div>

                                </div>

                                {{-- STATUS --}}
                                <div>
                                    <div class="text-[10px] uppercase text-slate-400 font-semibold mb-1">
                                        Status
                                    </div>

                                    <select
                                        name="items[{{ $item->id }}][review_status]"
                                        class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-700
                                            focus:ring-2 focus:ring-slate-200 focus:border-slate-400
                                            disabled:bg-slate-100 disabled:text-slate-500"
                                        @if(!$editable) disabled @endif>

                                        <option value="pending" @selected($status==='pending')>Pending</option>
                                        <option value="reviewed" @selected($status==='reviewed')>Reviewed</option>
                                        <option value="revision_required" @selected($status==='revision_required')>Needs Revision</option>
                                        <option value="ready_for_claiming" @selected($status==='ready_for_claiming')>Ready for Claiming</option>

                                    </select>
                                </div>

                            </div>
                        </div>

                        @endforeach

                        </div>

                    </div>

                    @endif
                    @endforeach


                    {{-- REMARKS --}}
                    <div>
                        <label class="text-[11px] uppercase text-slate-500 font-medium">
                            Packet Remarks
                        </label>

                        <textarea
                            name="remarks"
                            rows="3"
                            class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm"
                            @if(!$editable) disabled @endif>{{ $packet->remarks }}</textarea>
                    </div>


                    {{-- ACTIONS --}}
                    @if($editable)
                    <div class="pt-3 border-t flex justify-end">

                        <button
                            type="submit"
                            class="px-5 py-2.5 bg-slate-900 text-white text-sm font-semibold rounded-xl hover:bg-slate-800">
                            Save Review
                        </button>

                    </div>
                    @endif

                    </div>

                    </form>

                    @if($packet->status === 'reviewed')
                        <div class="rounded-2xl border border-emerald-200 bg-gradient-to-r from-emerald-50 to-white p-4">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <div class="text-sm font-semibold text-emerald-900">
                                        Review completed
                                    </div>
                                    <div class="mt-1 text-xs text-emerald-700">
                                        Mark the packet ready for claiming if items need to be released back to the organization.
                                    </div>
                                </div>

                                <form method="POST" action="{{ route('admin.packets.mark_ready', $packet) }}" class="shrink-0">
                                    @csrf
                                    <button
                                        class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-xs font-semibold text-white shadow-sm transition hover:bg-emerald-700 active:scale-[0.99] sm:w-auto">
                                        <i data-lucide="package-check" class="w-4 h-4"></i>
                                        Mark Ready for Claiming
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif

                    @if(in_array($packet->status, ['reviewed', 'ready_for_claiming']))
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <div class="text-sm font-semibold text-slate-900">
                                        Need to change item statuses?
                                    </div>
                                    <div class="mt-1 text-xs text-slate-500">
                                        Revert this packet back to review mode to unlock the status fields and save corrections.
                                    </div>
                                </div>

                                <form method="POST" action="{{ route('admin.packets.revert', $packet) }}" class="shrink-0">
                                    @csrf
                                    <button
                                        class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-xs font-semibold text-slate-700 shadow-sm transition hover:bg-slate-100 active:scale-[0.99] sm:w-auto">
                                        <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                                        Revert to Review
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif

                @endif

            </div>
        </div>

    @endif

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    if (window.innerWidth >= 768) {
        document.querySelectorAll('[data-mobile]').forEach(el => {
            el.disabled = true;
        });
    }
});
</script>

</x-app-layout>