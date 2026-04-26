<x-app-layout>

@php
    $statusClasses = [
        'ready_for_claiming' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
        'reviewed' => 'bg-blue-50 text-blue-700 ring-blue-200',
        'under_review' => 'bg-indigo-50 text-indigo-700 ring-indigo-200',
        'generated' => 'bg-amber-50 text-amber-700 ring-amber-200',
        'received' => 'bg-purple-50 text-purple-700 ring-purple-200',
    ];
@endphp

<div class="mx-auto max-w-6xl px-4 py-6 space-y-6">


    <nav class="text-xs text-slate-500">
        <ol class="flex items-center gap-1.5">

      
            <li>
                <a href="{{ route('org.organization-info.show') }}"
                class="font-medium text-slate-600 hover:text-slate-900 transition">
                    Organization
                </a>
            </li>

            <li class="text-slate-300">/</li>

         
            <li>
                <a href="{{ route('org.projects.index') }}"
                class="font-medium text-slate-600 hover:text-slate-900 transition">
                    Projects
                </a>
            </li>

            <li class="text-slate-300">/</li>

      
            <li>
                <a href="{{ route('org.projects.documents.hub', $project) }}"
                class="font-medium text-slate-600 hover:text-slate-900 transition">
                    Document Hub
                </a>
            </li>

            <li class="text-slate-300">/</li>

        
            <li class="text-slate-400">
                Packet Submission
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
                            Org Packet Submission
                        </h1>
                        <p class="text-xs text-slate-500">
                            Manage physical submission packets for this project.
                        </p>
                    </div>
                </div>

                <div class="flex items-start gap-2 text-xs text-slate-600">
                    <i data-lucide="folder-kanban" class="mt-0.5 h-4 w-4 text-slate-400"></i>
                    <div>
                        <div class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">
                            Project
                        </div>
                        <div class="font-medium text-slate-800">
                            {{ $project->title }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2 sm:justify-end">
                <a href="{{ route('org.projects.documents.hub', $project) }}"
                   class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-600 shadow-sm transition hover:bg-slate-50 hover:text-slate-900">
                    <i data-lucide="arrow-left" class="h-3.5 w-3.5"></i>
                    Back to Project Hub
                </a>

                <form method="POST" action="{{ route('org.projects.packets.create', $project) }}">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center gap-2 rounded-xl bg-amber-600 px-3 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-amber-700">
                        <i data-lucide="plus" class="h-3.5 w-3.5"></i>
                        Create Packet
                    </button>
                </form>
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
                        How to Use Packet Submission
                    </h2>

                    <p class="mt-1 text-xs leading-5 text-slate-600">
                        This page is used to create and manage submission packets for physical documents that will be submitted to SACDEV. A packet helps group related documents under one record, so the office can receive, review, and track them properly.
                    </p>
                </div>

                <div class="grid gap-3 sm:grid-cols-3">
                    <div class="rounded-xl border border-blue-100 bg-white/80 p-3">
                        <div class="flex items-center gap-2 text-xs font-semibold text-blue-700">
                            <i data-lucide="package-plus" class="h-3.5 w-3.5"></i>
                            Create packet
                        </div>

                        <p class="mt-1 text-[11px] leading-5 text-slate-500">
                            Click Create Packet to start a new packet for the physical documents you are preparing to submit.
                        </p>
                    </div>

                    <div class="rounded-xl border border-amber-100 bg-white/80 p-3">
                        <div class="flex items-center gap-2 text-xs font-semibold text-amber-700">
                            <i data-lucide="list-checks" class="h-3.5 w-3.5"></i>
                            Add packet items
                        </div>

                        <p class="mt-1 text-[11px] leading-5 text-slate-500">
                            Open the packet, add the required items, and make sure the reference numbers and details match the physical documents.
                        </p>
                    </div>

                    <div class="rounded-xl border border-emerald-100 bg-white/80 p-3">
                        <div class="flex items-center gap-2 text-xs font-semibold text-emerald-700">
                            <i data-lucide="send" class="h-3.5 w-3.5"></i>
                            Submit to SACDEV
                        </div>

                        <p class="mt-1 text-[11px] leading-5 text-slate-500">
                            Print the packet, compile the documents in an envelope together with the printed copy, then submit everything to SACDEV.
                        </p>
                    </div>
                </div>

                <div class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-[11px] leading-5 text-slate-600">
                    <span class="font-semibold text-slate-700">Note:</span>
                    Once SACDEV receives a packet, the packet may become locked. You can still view the record, but editing may no longer be allowed.
                </div>
            </div>
        </div>
    </div>

    {{-- PACKET RECORDS --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        <div class="flex flex-col gap-3 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-2">
                <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-slate-100 text-slate-600">
                    <i data-lucide="archive" class="h-4 w-4"></i>
                </div>

                <div>
                    <div class="text-sm font-semibold text-slate-900">
                        Packet Records
                    </div>
                    <div class="text-[11px] text-slate-500">
                        Previously created submission packets for this project
                    </div>
                </div>
            </div>

            <div class="inline-flex w-fit items-center gap-1 rounded-full bg-slate-100 px-3 py-1 text-[11px] font-semibold text-slate-600">
                <i data-lucide="layers" class="h-3 w-3"></i>
                {{ $packets->count() }} total
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full table-fixed text-xs">

                <thead class="border-b border-slate-200 bg-slate-50 text-[11px] uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="w-48 px-4 py-3 text-left font-semibold">Packet Code</th>
                        <th class="w-40 px-4 py-3 text-left font-semibold">Generated</th>
                        <th class="w-28 px-4 py-3 text-left font-semibold">Items</th>
                        <th class="w-44 px-4 py-3 text-left font-semibold">Status</th>
                        <th class="w-40 px-4 py-3 text-right font-semibold">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">

                    @forelse($packets as $packet)

                        @php
                            $status = $packet->status ?? 'generated';
                            $statusStyle = $statusClasses[$status] ?? 'bg-slate-100 text-slate-700 ring-slate-200';
                        @endphp

                        <tr class="transition hover:bg-slate-50">

                            <td class="px-4 py-3 align-middle">
                                <div class="font-semibold text-slate-900">
                                    {{ $packet->packet_code }}
                                </div>
                            </td>

                            <td class="px-4 py-3 align-middle text-slate-600">
                                <div class="flex items-center gap-1.5">
                                    <i data-lucide="calendar-days" class="h-3.5 w-3.5 text-slate-400"></i>
                                    {{ $packet->generated_at?->format('M d, Y') ?? '-' }}
                                </div>
                            </td>

                            <td class="px-4 py-3 align-middle text-slate-600">
                                <div class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-2 py-1 text-[11px] font-medium text-slate-600">
                                    <i data-lucide="layers" class="h-3 w-3 text-slate-400"></i>
                                    {{ $packet->items->count() }}
                                </div>
                            </td>

                            <td class="px-4 py-3 align-middle">
                                <span class="inline-flex items-center rounded-full px-2 py-1 text-[11px] font-medium capitalize ring-1 {{ $statusStyle }}">
                                    {{ str_replace('_', ' ', $status) }}
                                </span>
                            </td>

                            <td class="px-4 py-3 align-middle text-right">
                                <div class="flex items-center justify-end gap-3">

                                    <a href="{{ route('org.projects.packets.show', [$project, $packet]) }}"
                                       class="inline-flex items-center gap-1 text-xs font-medium text-blue-600 transition hover:text-blue-800">
                                        <i data-lucide="eye" class="h-3.5 w-3.5"></i>
                                        View
                                    </a>

                                    @if($packet->status === 'generated')
                                        <form method="POST"
                                              action="{{ route('org.projects.packets.destroy', [$project, $packet]) }}"
                                              class="inline">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="inline-flex items-center gap-1 text-xs font-medium text-rose-600 transition hover:text-rose-800">
                                                <i data-lucide="archive-x" class="h-3.5 w-3.5"></i>
                                                Delete
                                            </button>
                                        </form>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-[11px] text-slate-400">
                                            <i data-lucide="lock" class="h-3 w-3"></i>
                                            Locked
                                        </span>
                                    @endif

                                </div>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="5" class="px-4 py-12 text-center">

                                <div class="mx-auto flex max-w-md flex-col items-center">

                                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-100 text-amber-700">
                                        <i data-lucide="inbox" class="h-6 w-6"></i>
                                    </div>

                                    <h3 class="mt-3 text-sm font-semibold text-slate-800">
                                        No packets yet
                                    </h3>

                                    <p class="mt-1 text-xs leading-5 text-slate-500">
                                        Create your first packet before submitting physical documents to SACDEV. After creating a packet, open it, add the document items, print the packet, and include the printed copy with your envelope submission.
                                    </p>

                                    <form method="POST" action="{{ route('org.projects.packets.create', $project) }}" class="mt-4">
                                        @csrf
                                        <button type="submit"
                                                class="inline-flex items-center gap-2 rounded-xl bg-amber-600 px-3 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-amber-700">
                                            <i data-lucide="plus" class="h-3.5 w-3.5"></i>
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

</div>

</x-app-layout>