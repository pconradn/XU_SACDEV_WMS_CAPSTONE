<x-app-layout>
<div class="max-w-6xl mx-auto px-4 py-6 space-y-6">

    {{-- HEADER --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-5 flex items-center justify-between">
        <div>
            <div class="text-xs uppercase tracking-wide text-slate-500">
                External Packets
            </div>
            <div class="text-lg font-semibold text-slate-900 mt-1">
                {{ $project->title }}
            </div>
            <div class="text-sm text-slate-500 mt-1">
                Manage and track all external packet submissions for this project.
            </div>
        </div>

        <a href="{{ route('admin.external-packets.create', $project) }}"
           class="inline-flex items-center gap-2 rounded-xl bg-blue-600 text-white px-4 py-2 text-sm font-medium hover:bg-blue-700">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="M12 5v14"/>
                <path d="M5 12h14"/>
            </svg>
            New Packet
        </a>
    </div>

    {{-- EMPTY --}}
    @if($packets->isEmpty())
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-10 text-center">
            <div class="text-sm text-slate-500">
                No external packets created yet.
            </div>
        </div>
    @else

    {{-- TABLE --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">

                {{-- HEAD --}}
                <thead class="bg-slate-50 text-slate-600 text-xs uppercase tracking-wide">
                    <tr>
                        <th class="px-4 py-3 text-left">Reference</th>
                        <th class="px-4 py-3 text-left">Destination</th>
                        <th class="px-4 py-3 text-left">Items</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-left">Created</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>

                {{-- BODY --}}
                <tbody class="divide-y">

                    @foreach($packets as $packet)
                        <tr class="hover:bg-slate-50 transition">

                            {{-- REF --}}
                            <td class="px-4 py-3 font-medium text-slate-900">
                                {{ $packet->reference_no }}
                            </td>

                            {{-- DEST --}}
                            <td class="px-4 py-3 text-slate-600">
                                {{ $packet->destination }}
                            </td>

                            {{-- COUNT --}}
                            <td class="px-4 py-3 text-slate-600">
                                {{ $packet->items->count() }} items
                            </td>

                            {{-- STATUS --}}
                            <td class="px-4 py-3">
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
                                        Prepared
                                    </span>
                                @endif
                            </td>

                            {{-- DATE --}}
                            <td class="px-4 py-3 text-slate-500 text-xs">
                                {{ $packet->created_at->format('M d, Y') }}
                            </td>

                            {{-- ACTIONS --}}
                            <td class="px-4 py-3 text-right">

                                <div class="flex justify-end gap-2">

                                    {{-- VIEW --}}
                                    <button
                                        x-data
                                        @click="$dispatch('open-packet-{{ $packet->id }}')"
                                        class="text-xs px-3 py-1.5 rounded-lg border border-slate-200 hover:bg-slate-50">
                                        View
                                    </button>

                                    {{-- PRINT --}}
                                    <a href="{{ route('admin.external-packets.print', [$project, $packet]) }}"
                                       target="_blank"
                                       class="text-xs px-3 py-1.5 rounded-lg border border-blue-200 text-blue-700 hover:bg-blue-50">
                                        Print
                                    </a>

                                    {{-- SUBMIT --}}
                                    @if($packet->status === 'prepared')
                                        <form method="POST"
                                              action="{{ route('admin.external-packets.submit', [$project, $packet]) }}">
                                            @csrf
                                            <button class="text-xs px-3 py-1.5 rounded-lg border border-emerald-200 text-emerald-700 hover:bg-emerald-50">
                                                Submit
                                            </button>
                                        </form>
                                    @endif

                                    {{-- ARCHIVE --}}
                                    @if($packet->status === 'prepared')
                                        <form method="POST"
                                              action="{{ route('admin.external-packets.archive', [$project, $packet]) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-xs px-3 py-1.5 rounded-lg border border-rose-200 text-rose-700 hover:bg-rose-50">
                                                Archive
                                            </button>
                                        </form>
                                    @endif

                                </div>

                            </td>

                        </tr>

                        {{-- MODAL --}}
                        <div
                            x-data="{ open: false }"
                            x-on:open-packet-{{ $packet->id }}.window="open = true"
                            x-show="open"
                            x-cloak
                            class="fixed inset-0 z-50 flex items-center justify-center bg-black/40"
                        >
                            <div class="bg-white rounded-2xl shadow-lg w-full max-w-lg p-6">

                                <div class="flex justify-between items-center mb-4">
                                    <div class="font-semibold text-slate-900">
                                        {{ $packet->reference_no }}
                                    </div>
                                    <button @click="open = false" class="text-slate-400 hover:text-slate-600">
                                        ✕
                                    </button>
                                </div>

                                <div class="space-y-3 text-sm">
                                    <div><strong>Destination:</strong> {{ $packet->destination }}</div>

                                    <div>
                                        <strong>Items:</strong>
                                        <ul class="mt-2 space-y-1">
                                            @foreach($packet->items as $item)
                                                <li class="flex justify-between text-xs">
                                                    <span>{{ $item->label }}</span>
                                                    <span class="
                                                        {{ $item->status === 'approved'
                                                            ? 'text-emerald-700'
                                                            : ($item->status === 'returned'
                                                                ? 'text-rose-700'
                                                                : 'text-slate-500')
                                                        }}">
                                                        {{ ucfirst($item->status) }}
                                                    </span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>

                            </div>
                        </div>

                    @endforeach

                </tbody>

            </table>
        </div>

    </div>

    @endif

</div>
</x-app-layout>