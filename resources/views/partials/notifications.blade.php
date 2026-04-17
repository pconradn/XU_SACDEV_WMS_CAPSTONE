<button
    @click="open = !open"
    type="button"
    class="relative inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-700 bg-slate-800/80 text-slate-300 transition hover:bg-slate-800 hover:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/30"
>
    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2a2 2 0 01-.6 1.4L4 17h5m6 0a3 3 0 11-6 0m6 0H9"/>
    </svg>

    <span id="notif-count"
        class="absolute -top-1 -right-1 flex h-[18px] min-w-[18px] items-center justify-center rounded-full bg-red-500 px-1 text-[10px] font-bold text-white
        {{ $unreadCount > 0 ? '' : 'hidden' }}">
        {{ $unreadCount > 99 ? '99+' : $unreadCount }}
    </span>
</button>

<div
    x-show="open"
    x-transition.origin.top.right
    @click.outside="open = false"
    class="absolute right-0 mt-3 w-[90vw] max-w-sm min-w-[260px] overflow-hidden rounded-2xl border border-slate-700 bg-slate-900 shadow-2xl shadow-slate-950/40 z-50"
    style="display: none;"
>
    <div class="flex items-center justify-between border-b border-slate-800 px-4 py-3">
        <div>
            <p class="text-xs font-semibold text-white">Notifications</p>
            <p class="text-xs text-slate-400">
                {{ $unreadCount }} unread
            </p>
        </div>

        <a href="{{ route('notifications.index') }}"
           class="text-xs font-medium text-blue-300 hover:text-blue-200">
            View all
        </a>
    </div>

    <div id="notif-list" class="max-h-76 overflow-y-auto">
        @forelse($recentNotifications as $notification)
            <a href="{{ route('notifications.go', $notification->id) }}"
               class="block border-b border-slate-800 px-4 py-3 hover:bg-slate-800/70 transition">
                <div class="flex items-start gap-3">
                    <div class="mt-0.5 h-2.5 w-2.5 rounded-full {{ is_null($notification->read_at) ? 'bg-blue-400' : 'bg-slate-600' }}"></div>

                    <div class="min-w-0 flex-1">
                        <p class="text-xs font-medium text-slate-100">
                            {{ data_get($notification->data, 'title', 'Notification') }}
                        </p>

                        @if(data_get($notification->data, 'message'))
                            <p class="mt-1 text-xs text-slate-400">
                                {{ \Illuminate\Support\Str::limit(data_get($notification->data, 'message'), 90) }}
                            </p>
                        @endif

                        <p class="mt-1 text-[10px] text-slate-500">
                            {{ optional($notification->created_at)->diffForHumans() }}
                        </p>
                    </div>
                </div>
            </a>
        @empty
            <div class="px-4 py-8 text-center">
                <p class="text-xs font-medium text-slate-300">No notifications yet</p>
            </div>
        @endforelse
    </div>
</div>