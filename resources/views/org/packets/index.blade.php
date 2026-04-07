<x-app-layout>

<div class="mx-auto max-w-6xl px-4 py-6 space-y-6">

    {{-- ================= HEADER ================= --}}
    <div class="flex items-start justify-between gap-4">

        <div>
            <h1 class="text-lg font-semibold text-slate-900 flex items-center gap-2">
                <i data-lucide="inbox" class="w-5 h-5 text-amber-600"></i>
                Org Packet Submission
            </h1>

            <div class="text-xs text-slate-500 mt-1">
                Project: {{ $project->title }}
            </div>
        </div>

        <div class="flex items-center gap-3">

            <a href="{{ route('org.projects.documents.hub', $project) }}"
               class="text-xs text-slate-600 hover:text-slate-900 transition flex items-center gap-1">
                <i data-lucide="arrow-left" class="w-3 h-3"></i>
                Back to Project Hub
            </a>

            <form method="POST" action="{{ route('org.projects.packets.create', $project) }}">
                @csrf

                <button
                    class="inline-flex items-center gap-1 px-3 py-2 text-xs font-medium rounded-lg bg-amber-600 text-white hover:bg-amber-700 transition shadow-sm">

                    <i data-lucide="plus" class="w-3 h-3"></i>
                    Create Packet
                </button>
            </form>

        </div>

    </div>


    {{-- ================= INFO / INSTRUCTIONS ================= --}}
    <div class="rounded-2xl border border-amber-200 bg-gradient-to-r from-amber-50 to-white p-4 flex gap-3 items-start">

        <div class="mt-0.5">
            <i data-lucide="info" class="w-4 h-4 text-amber-600"></i>
        </div>

        <div class="text-xs text-amber-800 leading-relaxed">

            <div class="font-semibold mb-1">
                About Org Packet Submission
            </div>

            <p>
                This module is used to organize <span class="font-medium">physical document submissions</span>
                to the SACDEV Office. Each packet may include letters, receipts, and other supporting documents
                related to your project.
            </p>

            <p class="mt-1">
                After submission, SACDEV staff will review and mark packets as received for proper tracking.
            </p>

        </div>

    </div>


  
    <div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm overflow-hidden">

        {{-- HEADER --}}
        <div class="px-4 py-3 border-b border-slate-100 flex items-center justify-between">

            <div class="text-xs font-semibold text-slate-700 flex items-center gap-2">
                <i data-lucide="archive" class="w-4 h-4 text-slate-500"></i>
                Packet Records
            </div>

            <div class="text-[11px] text-slate-500">
                {{ $packets->count() }} total
            </div>

        </div>


        {{-- TABLE --}}
        <div class="overflow-x-auto">

            <table class="w-full text-xs">

                <thead class="bg-slate-50 border-b border-slate-200 text-slate-600">
                    <tr>
                        <th class="px-4 py-2 text-left font-medium">Packet Code</th>
                        <th class="px-4 py-2 text-left font-medium">Generated</th>
                        <th class="px-4 py-2 text-left font-medium">Receipts</th>
                        <th class="px-4 py-2 text-left font-medium">Status</th>
                        <th class="px-4 py-2 text-right font-medium">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y">

                @forelse($packets as $packet)

                    @php
                        $status = $packet->status ?? 'generated';

                        $statusStyle = match($status) {
                            'received' => 'bg-emerald-50 text-emerald-700',
                            'submitted' => 'bg-blue-50 text-blue-700',
                            'generated' => 'bg-amber-50 text-amber-700',
                            default => 'bg-slate-100 text-slate-700'
                        };
                    @endphp

                    <tr class="hover:bg-slate-50 transition">

                        {{-- CODE --}}
                        <td class="px-4 py-3 font-medium text-slate-900">
                            {{ $packet->packet_code }}
                        </td>

                        {{-- DATE --}}
                        <td class="px-4 py-3 text-slate-600">
                            {{ $packet->generated_at?->format('M d, Y') ?? '-' }}
                        </td>

                        {{-- RECEIPTS --}}
                        <td class="px-4 py-3 text-slate-600">
                            <div class="flex items-center gap-1">
                                <i data-lucide="file-text" class="w-3 h-3 text-slate-400"></i>
                                {{ $packet->receipts->count() }}
                            </div>
                        </td>

                        {{-- STATUS --}}
                        <td class="px-4 py-3">
                            <span class="text-[11px] px-2 py-1 rounded-full {{ $statusStyle }}">
                                {{ ucfirst($status) }}
                            </span>
                        </td>

                        {{-- ACTIONS --}}
                        <td class="px-4 py-3 text-right">

                            <div class="flex justify-end items-center gap-3">

                                <a href="{{ route('org.projects.packets.show', [$project, $packet]) }}"
                                   class="text-blue-600 hover:text-blue-800 transition flex items-center gap-1">

                                    <i data-lucide="eye" class="w-3 h-3"></i>
                                    Manage
                                </a>

                                <form method="POST"
                                      action="{{ route('org.projects.packets.destroy', [$project, $packet]) }}"
                                      class="inline">

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        class="text-rose-600 hover:text-rose-800 transition flex items-center gap-1">

                                        <i data-lucide="archive-x" class="w-3 h-3"></i>
                                        Archive
                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                @empty

                    {{-- EMPTY STATE --}}
                    <tr>
                        <td colspan="5" class="px-4 py-10 text-center">

                            <div class="flex flex-col items-center gap-3">

                                <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center">
                                    <i data-lucide="inbox" class="w-5 h-5 text-amber-600"></i>
                                </div>

                                <div class="text-sm font-medium text-slate-700">
                                    No packets yet
                                </div>

                                <div class="text-xs text-slate-500 max-w-xs">
                                    Start organizing your physical submissions by creating your first packet.
                                </div>

                                <form method="POST" action="{{ route('org.projects.packets.create', $project) }}">
                                    @csrf

                                    <button
                                        class="mt-2 px-3 py-2 text-xs bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition shadow-sm">

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