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
        <p class="mt-1 text-xs text-slate-500">You’re all caught up.</p>
    </div>
@endforelse