<x-app-layout>

<style>
    [x-cloak] { display: none !important; }
</style>

@php
    $locked = $packet->status !== 'generated';
    $grouped = $packet->items->groupBy('type');

    $statusClasses = [
        'ready_for_claiming' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
        'revision_required' => 'bg-rose-50 text-rose-700 ring-rose-200',
        'reviewed' => 'bg-blue-50 text-blue-700 ring-blue-200',
        'pending' => 'bg-amber-50 text-amber-700 ring-amber-200',
        'generated' => 'bg-slate-100 text-slate-700 ring-slate-200',
    ];

    $packetStatusClass = $statusClasses[$packet->status] ?? 'bg-slate-100 text-slate-700 ring-slate-200';
@endphp

<div class="mx-auto max-w-6xl px-4 py-6 space-y-6"
     x-data="{ openModal: false, type: '' }"
     x-init="$nextTick(() => window.lucide && window.lucide.createIcons())">


     <nav class="text-xs text-slate-500">
    <ol class="flex items-center gap-1.5">

        {{-- Organization --}}
        <li>
            <a href="{{ route('org.organization-info.show') }}"
               class="font-medium text-slate-600 hover:text-slate-900 transition">
                Organization
            </a>
        </li>

        <li class="text-slate-300">/</li>

        {{-- Projects --}}
        <li>
            <a href="{{ route('org.projects.index') }}"
               class="font-medium text-slate-600 hover:text-slate-900 transition">
                Projects
            </a>
        </li>

        <li class="text-slate-300">/</li>

        {{-- Document Hub --}}
        <li>
            <a href="{{ route('org.projects.documents.hub', $project) }}"
               class="font-medium text-slate-600 hover:text-slate-900 transition">
                Document Hub
            </a>
        </li>

        <li class="text-slate-300">/</li>

        {{-- Packet Submission --}}
        <li>
            <a href="{{ route('org.projects.packets.index', $project) }}"
               class="font-medium text-slate-600 hover:text-slate-900 transition">
                Packet Submission
            </a>
        </li>

        <li class="text-slate-300">/</li>

        {{-- CURRENT --}}
        <li class="text-slate-400">
            View Packet
        </li>

    </ol>
</nav>

    {{-- HEADER --}}
    <div class="rounded-2xl border border-slate-200 bg-gradient-to-br from-amber-50 via-white to-slate-50 p-5 shadow-sm">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">

            <div class="space-y-3">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-amber-100 text-amber-700">
                        <i data-lucide="package-check" class="h-5 w-5"></i>
                    </div>

                    <div>
                        <h1 class="text-lg font-semibold text-slate-900">
                            Submission Packet
                        </h1>
                        <p class="text-xs text-slate-500">
                            {{ $packet->packet_code }}
                        </p>
                    </div>
                </div>

                <div class="grid gap-2 text-xs text-slate-600 sm:grid-cols-2">
                    <div class="flex items-start gap-2">
                        <i data-lucide="folder-kanban" class="mt-0.5 h-4 w-4 text-slate-400"></i>
                        <div>
                            <div class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Project</div>
                            <div class="font-medium text-slate-800">{{ $project->title }}</div>
                        </div>
                    </div>

                    <div class="flex items-start gap-2">
                        <i data-lucide="calendar-days" class="mt-0.5 h-4 w-4 text-slate-400"></i>
                        <div>
                            <div class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Generated</div>
                            <div class="font-medium text-slate-800">
                                {{ $packet->generated_at?->format('M d, Y') ?? 'Not yet generated' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2 sm:justify-end">
                <span class="inline-flex items-center gap-1 rounded-full px-3 py-1 text-[11px] font-semibold capitalize ring-1 {{ $packetStatusClass }}">
                    <i data-lucide="{{ $locked ? 'lock' : 'circle-dot' }}" class="h-3 w-3"></i>
                    {{ str_replace('_', ' ', $packet->status) }}
                </span>

                <a href="{{ route('org.projects.packets.print', [$project, $packet]) }}"
                   class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-3 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-slate-800">
                    <i data-lucide="printer" class="h-3.5 w-3.5"></i>
                    Print Packet
                </a>
            </div>

        </div>
    </div>

    {{-- INSTRUCTIONS --}}
    <div class="rounded-2xl border border-blue-200 bg-gradient-to-br from-blue-50 via-white to-slate-50 p-5 shadow-sm">
        <div class="flex items-start gap-3">
            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-700">
                <i data-lucide="info" class="h-4 w-4"></i>
            </div>

            <div class="space-y-3">
                <div>
                    <h2 class="text-sm font-semibold text-slate-900">
                        How to Use This Page
                    </h2>
                    <p class="mt-1 text-xs leading-5 text-slate-600">
                        This page shows the physical documents included in this submission packet. Add each item before printing so SACDEV can match the printed packet with the actual documents you will submit.
                    </p>
                </div>

                <div class="grid gap-3 sm:grid-cols-3">
                    <div class="rounded-xl border border-blue-100 bg-white/80 p-3">
                        <div class="flex items-center gap-2 text-xs font-semibold text-blue-700">
                            <i data-lucide="plus-circle" class="h-3.5 w-3.5"></i>
                            Add items
                        </div>
                        <p class="mt-1 text-[11px] leading-5 text-slate-500">
                            Click Add Item, choose the document type, then enter the reference number and details.
                        </p>
                    </div>

                    <div class="rounded-xl border border-amber-100 bg-white/80 p-3">
                        <div class="flex items-center gap-2 text-xs font-semibold text-amber-700">
                            <i data-lucide="badge-check" class="h-3.5 w-3.5"></i>
                            Check details
                        </div>
                        <p class="mt-1 text-[11px] leading-5 text-slate-500">
                            Make sure the reference numbers, organization names, descriptions, and amounts match your physical documents.
                        </p>
                    </div>

                    <div class="rounded-xl border border-emerald-100 bg-white/80 p-3">
                        <div class="flex items-center gap-2 text-xs font-semibold text-emerald-700">
                            <i data-lucide="printer-check" class="h-3.5 w-3.5"></i>
                            Print packet
                        </div>
                        <p class="mt-1 text-[11px] leading-5 text-slate-500">
                            Print the packet after confirming all entries. Compile all required physical documents in an envelope together with the printed packet, then submit everything to SACDEV. Once SACDEV receives it, editing will be locked.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- LOCK NOTICE --}}
    @if($locked)
        <div class="rounded-2xl border border-amber-200 bg-amber-50 px-5 py-4 text-xs text-amber-800 shadow-sm">
            <div class="flex items-start gap-3">
                <i data-lucide="lock" class="mt-0.5 h-4 w-4 shrink-0"></i>
                <div>
                    <div class="font-semibold">Editing is locked</div>
                    <div class="mt-1 text-[11px] leading-5">
                        This packet has already been received by SACDEV. You can still view and print the packet, but you can no longer add or remove items.
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- ITEMS HEADER --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-col gap-3 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-2">
                <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-slate-100 text-slate-600">
                    <i data-lucide="layers" class="h-4 w-4"></i>
                </div>

                <div>
                    <div class="text-sm font-semibold text-slate-900">Packet Items</div>
                    <div class="text-[11px] text-slate-500">Documents currently included in this packet</div>
                </div>
            </div>

            @if(!$locked)
                <button type="button"
                        @click="openModal = true; $nextTick(() => window.lucide && window.lucide.createIcons())"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-amber-600 px-3 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-amber-700">
                    <i data-lucide="plus" class="h-3.5 w-3.5"></i>
                    Add Item
                </button>
            @endif
        </div>

        <div class="p-5 space-y-5">

            {{-- SOLICITATION LETTERS --}}
            @if($grouped->get('solicitation_letter'))
                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
                    <div class="flex items-center gap-2 border-b border-slate-200 bg-gradient-to-r from-blue-50 to-white px-5 py-3 text-xs font-semibold text-slate-800">
                        <i data-lucide="mail" class="h-4 w-4 text-blue-600"></i>
                        Solicitation Letters
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full table-fixed text-xs">
                            <thead class="border-b border-slate-200 bg-slate-50 text-[11px] uppercase tracking-wide text-slate-500">
                                <tr>
                                    <th class="w-40 px-4 py-3 text-left font-semibold">Control No.</th>
                                    <th class="min-w-64 px-4 py-3 text-left font-semibold">Organization</th>
                                    <th class="w-44 px-4 py-3 text-left font-semibold">Status</th>
                                    <th class="w-28 px-4 py-3 text-right font-semibold">Action</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-slate-100">
                                @foreach($grouped['solicitation_letter'] as $item)
                                    @php
                                        $status = $item->review_status;
                                        $style = $statusClasses[$status] ?? 'bg-slate-100 text-slate-600 ring-slate-200';
                                    @endphp

                                    <tr class="transition hover:bg-slate-50">
                                        <td class="px-4 py-3 align-middle font-medium text-slate-900">
                                            {{ $item->reference_number }}
                                        </td>

                                        <td class="px-4 py-3 align-middle text-slate-700">
                                            <div class="max-w-md truncate">
                                                {{ $item->organization_name }}
                                            </div>
                                        </td>

                                        <td class="px-4 py-3 align-middle">
                                            <span class="inline-flex items-center rounded-full px-2 py-1 text-[11px] font-medium capitalize ring-1 {{ $style }}">
                                                {{ str_replace('_', ' ', $status) }}
                                            </span>
                                        </td>

                                        <td class="px-4 py-3 align-middle text-right">
                                            @if(!$locked)
                                                <form method="POST" action="{{ route('org.projects.packets.items.destroy', [$project, $packet, $item]) }}">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit"
                                                            class="inline-flex items-center justify-end gap-1 text-xs font-medium text-rose-600 transition hover:text-rose-700">
                                                        <i data-lucide="trash-2" class="h-3.5 w-3.5"></i>
                                                        Remove
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-[11px] text-slate-400">Locked</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            {{-- DISBURSEMENT VOUCHERS --}}
            @if($grouped->get('dv'))
                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
                    <div class="flex items-center gap-2 border-b border-slate-200 bg-gradient-to-r from-amber-50 to-white px-5 py-3 text-xs font-semibold text-slate-800">
                        <i data-lucide="file-text" class="h-4 w-4 text-amber-600"></i>
                        Disbursement Vouchers
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full table-fixed text-xs">
                            <thead class="border-b border-slate-200 bg-slate-50 text-[11px] uppercase tracking-wide text-slate-500">
                                <tr>
                                    <th class="w-40 px-4 py-3 text-left font-semibold">Reference</th>
                                    <th class="min-w-64 px-4 py-3 text-left font-semibold">Description</th>
                                    <th class="w-36 px-4 py-3 text-right font-semibold">Amount</th>
                                    <th class="w-44 px-4 py-3 text-left font-semibold">Status</th>
                                    <th class="w-28 px-4 py-3 text-right font-semibold">Action</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-slate-100">
                                @foreach($grouped['dv'] as $item)
                                    @php
                                        $status = $item->review_status;
                                        $style = $statusClasses[$status] ?? 'bg-slate-100 text-slate-600 ring-slate-200';
                                    @endphp

                                    <tr class="transition hover:bg-slate-50">
                                        <td class="px-4 py-3 align-middle font-medium text-slate-900">
                                            {{ $item->reference_number }}
                                        </td>

                                        <td class="px-4 py-3 align-middle text-slate-700">
                                            <div class="max-w-md truncate">
                                                {{ $item->label }}
                                            </div>
                                        </td>

                                        <td class="px-4 py-3 align-middle text-right font-medium text-slate-800">
                                            ₱{{ number_format($item->amount, 2) }}
                                        </td>

                                        <td class="px-4 py-3 align-middle">
                                            <span class="inline-flex items-center rounded-full px-2 py-1 text-[11px] font-medium capitalize ring-1 {{ $style }}">
                                                {{ str_replace('_', ' ', $status) }}
                                            </span>
                                        </td>

                                        <td class="px-4 py-3 align-middle text-right">
                                            @if(!$locked)
                                                <form method="POST" action="{{ route('org.projects.packets.items.destroy', [$project, $packet, $item]) }}">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit"
                                                            class="inline-flex items-center justify-end gap-1 text-xs font-medium text-rose-600 transition hover:text-rose-700">
                                                        <i data-lucide="trash-2" class="h-3.5 w-3.5"></i>
                                                        Remove
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-[11px] text-slate-400">Locked</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            {{-- RECEIPTS --}}
            @if($grouped->get('receipt'))
                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
                    <div class="flex items-center gap-2 border-b border-slate-200 bg-gradient-to-r from-emerald-50 to-white px-5 py-3 text-xs font-semibold text-slate-800">
                        <i data-lucide="receipt" class="h-4 w-4 text-emerald-600"></i>
                        Official Receipts
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full table-fixed text-xs">
                            <thead class="border-b border-slate-200 bg-slate-50 text-[11px] uppercase tracking-wide text-slate-500">
                                <tr>
                                    <th class="w-40 px-4 py-3 text-left font-semibold">OR Number</th>
                                    <th class="min-w-64 px-4 py-3 text-left font-semibold">Description</th>
                                    <th class="w-36 px-4 py-3 text-right font-semibold">Amount</th>
                                    <th class="w-44 px-4 py-3 text-left font-semibold">Status</th>
                                    <th class="w-28 px-4 py-3 text-right font-semibold">Action</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-slate-100">
                                @foreach($grouped['receipt'] as $item)
                                    @php
                                        $status = $item->review_status;
                                        $style = $statusClasses[$status] ?? 'bg-slate-100 text-slate-600 ring-slate-200';
                                    @endphp

                                    <tr class="transition hover:bg-slate-50">
                                        <td class="px-4 py-3 align-middle font-medium text-slate-900">
                                            {{ $item->reference_number }}
                                        </td>

                                        <td class="px-4 py-3 align-middle text-slate-700">
                                            <div class="max-w-md truncate">
                                                {{ $item->label }}
                                            </div>
                                        </td>

                                        <td class="px-4 py-3 align-middle text-right font-medium text-slate-800">
                                            ₱{{ number_format($item->amount, 2) }}
                                        </td>

                                        <td class="px-4 py-3 align-middle">
                                            <span class="inline-flex items-center rounded-full px-2 py-1 text-[11px] font-medium capitalize ring-1 {{ $style }}">
                                                {{ str_replace('_', ' ', $status) }}
                                            </span>
                                        </td>

                                        <td class="px-4 py-3 align-middle text-right">
                                            @if(!$locked)
                                                <form method="POST" action="{{ route('org.projects.packets.items.destroy', [$project, $packet, $item]) }}">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit"
                                                            class="inline-flex items-center justify-end gap-1 text-xs font-medium text-rose-600 transition hover:text-rose-700">
                                                        <i data-lucide="trash-2" class="h-3.5 w-3.5"></i>
                                                        Remove
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-[11px] text-slate-400">Locked</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            {{-- OTHER ITEMS --}}
            @if($grouped->get('other'))
                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
                    <div class="flex items-center gap-2 border-b border-slate-200 bg-gradient-to-r from-slate-100 to-white px-5 py-3 text-xs font-semibold text-slate-800">
                        <i data-lucide="layers" class="h-4 w-4 text-slate-600"></i>
                        Other Items
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full table-fixed text-xs">
                            <thead class="border-b border-slate-200 bg-slate-50 text-[11px] uppercase tracking-wide text-slate-500">
                                <tr>
                                    <th class="w-40 px-4 py-3 text-left font-semibold">Reference</th>
                                    <th class="min-w-64 px-4 py-3 text-left font-semibold">Description</th>
                                    <th class="w-44 px-4 py-3 text-left font-semibold">Status</th>
                                    <th class="w-28 px-4 py-3 text-right font-semibold">Action</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-slate-100">
                                @foreach($grouped['other'] as $item)
                                    @php
                                        $status = $item->review_status;
                                        $style = $statusClasses[$status] ?? 'bg-slate-100 text-slate-600 ring-slate-200';
                                    @endphp

                                    <tr class="transition hover:bg-slate-50">
                                        <td class="px-4 py-3 align-middle font-medium text-slate-900">
                                            {{ $item->reference_number }}
                                        </td>

                                        <td class="px-4 py-3 align-middle text-slate-700">
                                            <div class="max-w-md truncate">
                                                {{ $item->label }}
                                            </div>
                                        </td>

                                        <td class="px-4 py-3 align-middle">
                                            <span class="inline-flex items-center rounded-full px-2 py-1 text-[11px] font-medium capitalize ring-1 {{ $style }}">
                                                {{ str_replace('_', ' ', $status) }}
                                            </span>
                                        </td>

                                        <td class="px-4 py-3 align-middle text-right">
                                            @if(!$locked)
                                                <form method="POST" action="{{ route('org.projects.packets.items.destroy', [$project, $packet, $item]) }}">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit"
                                                            class="inline-flex items-center justify-end gap-1 text-xs font-medium text-rose-600 transition hover:text-rose-700">
                                                        <i data-lucide="trash-2" class="h-3.5 w-3.5"></i>
                                                        Remove
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-[11px] text-slate-400">Locked</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            @if($packet->items->isEmpty())
                <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-5 py-10 text-center">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-slate-400 shadow-sm">
                        <i data-lucide="inbox" class="h-6 w-6"></i>
                    </div>

                    <h3 class="mt-3 text-sm font-semibold text-slate-800">
                        No items added yet
                    </h3>

                    <p class="mx-auto mt-1 max-w-md text-xs leading-5 text-slate-500">
                        Add the documents that will be included in this packet before printing. This helps SACDEV verify the physical submission properly.
                    </p>

                    @if(!$locked)
                        <button type="button"
                                @click="openModal = true; $nextTick(() => window.lucide && window.lucide.createIcons())"
                                class="mt-4 inline-flex items-center justify-center gap-2 rounded-xl bg-amber-600 px-3 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-amber-700">
                            <i data-lucide="plus" class="h-3.5 w-3.5"></i>
                            Add First Item
                        </button>
                    @endif
                </div>
            @endif

        </div>
    </div>

    {{-- MODAL --}}
    <div x-show="openModal"
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 px-4"
         x-transition.opacity>

        <div @click.outside="openModal = false"
             class="w-full max-w-lg rounded-2xl bg-white shadow-xl"
             x-transition.scale>

            <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                <div class="flex items-center gap-2">
                    <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-amber-100 text-amber-700">
                        <i data-lucide="plus-circle" class="h-4 w-4"></i>
                    </div>

                    <div>
                        <div class="text-sm font-semibold text-slate-900">Add Packet Item</div>
                        <div class="text-[11px] text-slate-500">Enter the document details below</div>
                    </div>
                </div>

                <button type="button"
                        @click="openModal = false"
                        class="rounded-lg p-1 text-slate-400 transition hover:bg-slate-100 hover:text-slate-600">
                    <i data-lucide="x" class="h-4 w-4"></i>
                </button>
            </div>

            <form method="POST"
                  action="{{ route('org.projects.packets.items.store', [$project, $packet]) }}"
                  class="space-y-4 px-5 py-5">

                @csrf

                <div>
                    <label class="mb-1 block text-[11px] font-semibold uppercase tracking-wide text-slate-500">
                        Item Type
                    </label>

                    <select name="type"
                            x-model="type"
                            required
                            class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs text-slate-700 shadow-sm focus:border-amber-400 focus:outline-none focus:ring-2 focus:ring-amber-100">
                        <option value="">Select Type</option>
                        <option value="solicitation_letter">Solicitation Letter</option>
                        <option value="dv">Disbursement Voucher</option>
                        <option value="receipt">Official Receipt</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div>
                    <label class="mb-1 block text-[11px] font-semibold uppercase tracking-wide text-slate-500">
                        Reference Number
                    </label>

                    <input type="text"
                           name="reference_number"
                           required
                           :placeholder="
                                type === 'dv' ? 'DV Reference Number' :
                                type === 'receipt' ? 'Official Receipt Number' :
                                type === 'solicitation_letter' ? 'Control Number' :
                                'Reference Number'
                           "
                           class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs text-slate-700 shadow-sm focus:border-amber-400 focus:outline-none focus:ring-2 focus:ring-amber-100">
                </div>

                <div>
                    <label class="mb-1 block text-[11px] font-semibold uppercase tracking-wide text-slate-500">
                        Description / Label
                    </label>

                    <input type="text"
                           name="label"
                           required
                           :placeholder="
                                type === 'dv' ? 'Purpose / Description' :
                                type === 'receipt' ? 'Description' :
                                type === 'solicitation_letter' ? 'Organization / Recipient' :
                                'Label'
                           "
                           class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs text-slate-700 shadow-sm focus:border-amber-400 focus:outline-none focus:ring-2 focus:ring-amber-100">
                </div>

                <template x-if="type === 'dv' || type === 'receipt'">
                    <div>
                        <label class="mb-1 block text-[11px] font-semibold uppercase tracking-wide text-slate-500">
                            Amount
                        </label>

                        <input type="number"
                               step="0.01"
                               name="amount"
                               placeholder="0.00"
                               class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs text-slate-700 shadow-sm focus:border-amber-400 focus:outline-none focus:ring-2 focus:ring-amber-100">
                    </div>
                </template>

                <template x-if="type === 'solicitation_letter'">
                    <div>
                        <label class="mb-1 block text-[11px] font-semibold uppercase tracking-wide text-slate-500">
                            Organization Name
                        </label>

                        <input type="text"
                               name="organization_name"
                               placeholder="Organization / Recipient Name"
                               class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs text-slate-700 shadow-sm focus:border-amber-400 focus:outline-none focus:ring-2 focus:ring-amber-100">
                    </div>
                </template>

                <template x-if="type === 'other'">
                    <div class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-[11px] leading-5 text-slate-500">
                        Provide a clear reference number and description so SACDEV can identify this item during review.
                    </div>
                </template>

                <div class="flex items-center justify-end gap-2 border-t border-slate-200 pt-4">
                    <button type="button"
                            @click="openModal = false"
                            class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-600 transition hover:bg-slate-50">
                        Cancel
                    </button>

                    <button type="submit"
                            class="inline-flex items-center gap-2 rounded-xl bg-amber-600 px-3 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-amber-700">
                        <i data-lucide="save" class="h-3.5 w-3.5"></i>
                        Save Item
                    </button>
                </div>

            </form>
        </div>
    </div>

</div>

</x-app-layout>