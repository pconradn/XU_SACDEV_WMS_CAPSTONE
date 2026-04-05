<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">

            {{-- LEFT --}}
            <div>
                <div class="text-lg font-semibold text-slate-900">
                    Notifications
                </div>
                <div class="text-xs text-slate-500">
                    Action-required updates and system activity
                </div>
            </div>

            {{-- RIGHT --}}
            <form method="POST" action="{{ route('notifications.markAllRead') }}">
                @csrf
                <button
                    class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 shadow-sm hover:bg-slate-50 transition"
                >
                    <i data-lucide="check-check" class="w-4 h-4"></i>
                    Mark all as read
                </button>
            </form>

        </div>
    </x-slot>

    <div class="space-y-4">

        {{-- FILTER TABS --}}
        <div class="flex gap-2">
            @foreach(['all' => 'All', 'unread' => 'Unread'] as $key => $label)
                <a href="{{ route('notifications.index', ['filter' => $key]) }}"
                   class="rounded-full px-4 py-1.5 text-xs font-semibold border transition
                   {{ $filter === $key
                        ? 'bg-slate-900 text-white border-slate-900 shadow-sm'
                        : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        {{-- MAIN CARD --}}
        <div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm overflow-hidden">

            {{-- LIST --}}
            @forelse($notifications as $n)
                @php
                    $d = $n->data ?? [];
                    $title = $d['title'] ?? 'Notification';
                    $msg = $d['message'] ?? '';
                    $meta = trim(($d['org_name'] ?? '').' • '.($d['sy_name'] ?? ''));
                    $isUnread = is_null($n->read_at);
                @endphp

                <a href="{{ route('notifications.show', $n->id) }}"
                   class="group block px-5 py-4 border-b border-slate-200 transition
                          {{ $isUnread ? 'bg-blue-50/40 hover:bg-blue-50' : 'hover:bg-slate-50' }}">

                    <div class="flex items-start gap-3">

                        {{-- STATUS DOT --}}
                        <div class="mt-1.5">
                            <div class="h-2.5 w-2.5 rounded-full
                                {{ $isUnread ? 'bg-blue-500' : 'bg-slate-300' }}">
                            </div>
                        </div>

                        {{-- CONTENT --}}
                        <div class="flex-1 min-w-0">

                            {{-- TITLE --}}
                            <div class="flex items-center gap-2 flex-wrap">

                                <div class="text-sm font-semibold text-slate-900 group-hover:text-slate-800">
                                    {{ $title }}
                                </div>

                                @if($isUnread)
                                    <span class="inline-flex items-center rounded-full bg-blue-100 px-2 py-0.5 text-[10px] font-semibold text-blue-700">
                                        New
                                    </span>
                                @endif

                            </div>

                            {{-- MESSAGE --}}
                            @if($msg)
                                <div class="mt-1 text-xs text-slate-600 leading-relaxed line-clamp-2">
                                    {{ $msg }}
                                </div>
                            @endif

                            {{-- META --}}
                            @if(trim($meta) !== '•')
                                <div class="mt-1 text-[11px] text-slate-500">
                                    {{ $meta }}
                                </div>
                            @endif

                        </div>

                        {{-- RIGHT SIDE --}}
                        <div class="text-right shrink-0">

                            <div class="text-[11px] text-slate-500 whitespace-nowrap">
                                {{ $n->created_at->diffForHumans() }}
                            </div>

                            <div class="mt-1 text-[10px] text-slate-400">
                                {{ $n->created_at->format('M d, Y') }}
                            </div>

                        </div>

                    </div>
                </a>

            @empty
                <div class="px-5 py-12 text-center">
                    <i data-lucide="bell-off" class="w-6 h-6 text-slate-300 mx-auto mb-2"></i>
                    <p class="text-sm font-medium text-slate-600">No notifications</p>
                    <p class="text-xs text-slate-400 mt-1">You're all caught up</p>
                </div>
            @endforelse

        </div>

        {{-- PAGINATION --}}
        <div class="pt-2">
            {{ $notifications->links() }}
        </div>

    </div>
</x-app-layout>