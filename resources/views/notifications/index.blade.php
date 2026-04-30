<x-app-layout>

<div class="min-h-screen bg-slate-50 py-6">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <nav class="text-xs text-slate-500">
            <ol class="flex flex-wrap items-center gap-1.5">
                <li>
                    <a href="{{ route('org.home') }}"
                       class="font-medium text-slate-600 hover:text-slate-900 transition">
                        Dashboard
                    </a>
                </li>

                <li class="text-slate-300">/</li>

                <li class="font-medium text-indigo-700">
                    Notifications
                </li>
            </ol>
        </nav>

        <div class="rounded-2xl border border-indigo-200 bg-gradient-to-br from-indigo-50 via-white to-blue-50 shadow-sm overflow-hidden">
            <div class="p-6 flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

                <div class="flex items-start gap-4">
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-indigo-100 text-indigo-700 ring-1 ring-indigo-200 shadow-sm">
                        <i data-lucide="bell" class="w-7 h-7"></i>
                    </div>

                    <div>
                        <div class="flex flex-wrap items-center gap-2 mb-2">
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-indigo-100 px-2.5 py-1 text-[11px] font-semibold text-indigo-700">
                                <i data-lucide="activity" class="w-3 h-3"></i>
                                Notification Center
                            </span>

                            @if($filter === 'unread')
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-100 px-2.5 py-1 text-[11px] font-semibold text-blue-700">
                                    <i data-lucide="mail" class="w-3 h-3"></i>
                                    Showing Unread
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-2.5 py-1 text-[11px] font-semibold text-slate-600">
                                    <i data-lucide="inbox" class="w-3 h-3"></i>
                                    Showing All
                                </span>
                            @endif
                        </div>

                        <h1 class="text-2xl font-semibold text-slate-900 leading-tight">
                            Notifications
                        </h1>

                        <p class="mt-1 text-sm leading-6 text-slate-600 max-w-3xl">
                            Review action-required updates, system activity, and workflow messages from the organization system.
                        </p>
                    </div>
                </div>

                <form method="POST" action="{{ route('notifications.markAllRead') }}">
                    @csrf

                    <button type="submit"
                            class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50 sm:w-auto">
                        <i data-lucide="check-check" class="w-4 h-4"></i>
                        Mark all as read
                    </button>
                </form>

            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

            <div class="border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white px-5 py-4">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

                    <div>
                        <div class="flex items-center gap-2 text-sm font-semibold text-slate-900">
                            <i data-lucide="list-filter" class="w-4 h-4 text-indigo-600"></i>
                            Notification List
                        </div>

                        <div class="mt-1 text-xs text-slate-500">
                            Switch between all notifications and unread notifications.
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        @foreach(['all' => 'All', 'unread' => 'Unread'] as $key => $label)
                            <a href="{{ route('notifications.index', ['filter' => $key]) }}"
                               class="inline-flex items-center gap-1.5 rounded-full border px-4 py-1.5 text-xs font-semibold transition
                               {{ $filter === $key
                                    ? 'border-slate-900 bg-slate-900 text-white shadow-sm'
                                    : 'border-slate-200 bg-white text-slate-600 hover:bg-slate-50' }}">
                                <i data-lucide="{{ $key === 'unread' ? 'mail' : 'inbox' }}" class="w-3 h-3"></i>
                                {{ $label }}
                            </a>
                        @endforeach
                    </div>

                </div>
            </div>

            <div class="divide-y divide-slate-200">
                @forelse($notifications as $n)
                    @php
                        $d = $n->data ?? [];
                        $title = $d['title'] ?? 'Notification';
                        $msg = $d['message'] ?? '';
                        $meta = trim(($d['org_name'] ?? '').' • '.($d['sy_name'] ?? ''));
                        $isUnread = is_null($n->read_at);

                        $rowClass = $isUnread
                            ? 'bg-blue-50/50 hover:bg-blue-50'
                            : 'bg-white hover:bg-slate-50';

                        $iconClass = $isUnread
                            ? 'border-blue-200 bg-blue-100 text-blue-700'
                            : 'border-slate-200 bg-slate-100 text-slate-500';
                    @endphp

                    <a href="{{ route('notifications.go', $n->id) }}"
                       class="group block px-5 py-4 transition {{ $rowClass }}">

                        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">

                            <div class="flex items-start gap-3 min-w-0">

                                <div class="relative shrink-0">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-xl border {{ $iconClass }}">
                                        <i data-lucide="{{ $isUnread ? 'bell-ring' : 'bell' }}" class="w-5 h-5"></i>
                                    </div>

                                    @if($isUnread)
                                        <span class="absolute -right-0.5 -top-0.5 h-3 w-3 rounded-full border-2 border-white bg-blue-500"></span>
                                    @endif
                                </div>

                                <div class="min-w-0">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <div class="text-sm font-semibold text-slate-900 group-hover:text-indigo-700 transition">
                                            {{ $title }}
                                        </div>

                                        @if($isUnread)
                                            <span class="inline-flex items-center rounded-full bg-blue-100 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-blue-700">
                                                New
                                            </span>
                                        @endif
                                    </div>

                                    @if($msg)
                                        <div class="mt-1 text-xs leading-5 text-slate-600 line-clamp-2">
                                            {{ $msg }}
                                        </div>
                                    @endif

                                    @if(trim($meta) !== '•')
                                        <div class="mt-2 text-[11px] text-slate-500">
                                            {{ $meta }}
                                        </div>
                                    @endif
                                </div>

                            </div>

                            <div class="flex shrink-0 items-center justify-between gap-3 sm:block sm:text-right">
                                <div>
                                    <div class="text-[11px] font-medium text-slate-500 whitespace-nowrap">
                                        {{ $n->created_at->diffForHumans() }}
                                    </div>

                                    <div class="mt-0.5 text-[10px] text-slate-400 whitespace-nowrap">
                                        {{ $n->created_at->format('M d, Y') }}
                                    </div>
                                </div>

                                <div class="sm:mt-2">
                                    <span class="inline-flex items-center gap-1 rounded-full border border-slate-200 bg-white px-2 py-0.5 text-[10px] font-semibold text-slate-500 group-hover:text-indigo-700">
                                        Open
                                        <i data-lucide="arrow-right" class="w-3 h-3"></i>
                                    </span>
                                </div>
                            </div>

                        </div>
                    </a>

                @empty
                    <div class="px-5 py-14 text-center">
                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl border border-slate-200 bg-slate-50 text-slate-400">
                            <i data-lucide="bell-off" class="w-6 h-6"></i>
                        </div>

                        <div class="mt-3 text-sm font-semibold text-slate-800">
                            No notifications
                        </div>

                        <div class="mt-1 text-xs text-slate-500">
                            You're all caught up.
                        </div>
                    </div>
                @endforelse
            </div>

        </div>

        <div class="pt-1">
            {{ $notifications->links() }}
        </div>

    </div>
</div>

</x-app-layout>