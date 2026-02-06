<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-xl font-semibold text-slate-900">Notifications</div>
                <div class="text-sm text-slate-600">Action-required updates.</div>
            </div>

            <form method="POST" action="{{ route('notifications.markAllRead') }}">
                @csrf
                <button class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-800 hover:bg-slate-50">
                    Mark all as read
                </button>
            </form>
        </div>
    </x-slot>

    <div class="space-y-4">
        <div class="flex gap-2">
            <a href="{{ route('notifications.index', ['filter' => 'all']) }}"
               class="rounded-full px-4 py-2 text-sm font-semibold border {{ $filter === 'all' ? 'bg-slate-900 text-white border-slate-900' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50' }}">
                All
            </a>

            <a href="{{ route('notifications.index', ['filter' => 'unread']) }}"
               class="rounded-full px-4 py-2 text-sm font-semibold border {{ $filter === 'unread' ? 'bg-slate-900 text-white border-slate-900' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50' }}">
                Unread
            </a>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
            @forelse($notifications as $n)
                @php
                    $d = $n->data ?? [];
                    $title = $d['title'] ?? 'Notification';
                    $msg = $d['message'] ?? '';
                    $meta = trim(($d['org_name'] ?? '').' • '.($d['sy_name'] ?? ''));
                @endphp

                <a href="{{ route('notifications.show', $n->id) }}"
                   class="block px-5 py-4 border-b border-slate-200 hover:bg-slate-50">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <div class="flex items-center gap-2">
                                <div class="text-sm font-semibold text-slate-900">{{ $title }}</div>

                                @if(is_null($n->read_at))
                                    <span class="inline-flex items-center rounded-full bg-blue-100 px-2 py-0.5 text-xs font-semibold text-blue-800">
                                        New
                                    </span>
                                @endif
                            </div>

                            @if($msg)
                                <div class="mt-1 text-sm text-slate-700">{{ $msg }}</div>
                            @endif

                            @if(trim($meta) !== '•')
                                <div class="mt-1 text-xs text-slate-500">{{ $meta }}</div>
                            @endif
                        </div>

                        <div class="text-xs text-slate-500 whitespace-nowrap">
                            {{ $n->created_at->format('M d, Y h:i A') }}
                        </div>
                    </div>
                </a>
            @empty
                <div class="px-5 py-10 text-center text-slate-600">No notifications.</div>
            @endforelse
        </div>

        {{ $notifications->links() }}
    </div>
</x-app-layout>
