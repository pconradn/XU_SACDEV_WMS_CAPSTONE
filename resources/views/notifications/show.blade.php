<x-app-layout>

@php
    $data = $n->data ?? [];

    $title = $data['title'] ?? 'Notification';
    $message = $data['message'] ?? null;

    $orgName = $data['org_name'] ?? null;
    $syName = $data['sy_name'] ?? null;
    $actionUrl = $data['action_url'] ?? null;

    $isUnread = is_null($n->read_at);

    $metaParts = collect([$orgName, $syName])->filter()->values();
@endphp

<div class="min-h-screen bg-slate-50 py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <nav class="text-xs text-slate-500">
            <ol class="flex flex-wrap items-center gap-1.5">
                <li>
                    <a href="{{ route('notifications.index') }}"
                       class="font-medium text-slate-600 hover:text-slate-900 transition">
                        Notifications
                    </a>
                </li>

                <li class="text-slate-300">/</li>

                <li class="font-medium text-indigo-700">
                    View Notification
                </li>
            </ol>
        </nav>

        <div class="rounded-2xl border border-indigo-200 bg-gradient-to-br from-indigo-50 via-white to-blue-50 shadow-sm overflow-hidden">
            <div class="p-6 flex flex-col gap-5 sm:flex-row sm:items-start sm:justify-between">

                <div class="flex items-start gap-4">
                    <div class="relative shrink-0">
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-indigo-100 text-indigo-700 ring-1 ring-indigo-200 shadow-sm">
                            <i data-lucide="{{ $isUnread ? 'bell-ring' : 'bell' }}" class="w-7 h-7"></i>
                        </div>

                        @if($isUnread)
                            <span class="absolute -right-0.5 -top-0.5 h-4 w-4 rounded-full border-2 border-white bg-blue-500"></span>
                        @endif
                    </div>

                    <div>
                        <div class="flex flex-wrap items-center gap-2 mb-2">
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-indigo-100 px-2.5 py-1 text-[11px] font-semibold text-indigo-700">
                                <i data-lucide="activity" class="w-3 h-3"></i>
                                Notification
                            </span>

                            @if($isUnread)
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-100 px-2.5 py-1 text-[11px] font-semibold text-blue-700">
                                    <i data-lucide="mail" class="w-3 h-3"></i>
                                    Unread
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-2.5 py-1 text-[11px] font-semibold text-emerald-700">
                                    <i data-lucide="check-circle-2" class="w-3 h-3"></i>
                                    Read
                                </span>
                            @endif
                        </div>

                        <h1 class="text-2xl font-semibold text-slate-900 leading-tight">
                            {{ $title }}
                        </h1>

                        <p class="mt-1 text-sm text-slate-500">
                            {{ $n->created_at->diffForHumans() }} · {{ $n->created_at->format('M d, Y h:i A') }}
                        </p>
                    </div>
                </div>

                <a href="{{ route('notifications.index') }}"
                   class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    Back
                </a>

            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
            <div class="border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white px-5 py-4">
                <div class="flex items-center gap-2 text-sm font-semibold text-slate-900">
                    <i data-lucide="message-square-text" class="w-4 h-4 text-indigo-600"></i>
                    Message Details
                </div>

                <div class="mt-1 text-xs text-slate-500">
                    Full notification message and related context.
                </div>
            </div>

            <div class="p-5 space-y-5">

                @if($message)
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <div class="text-sm leading-6 text-slate-700">
                            {{ $message }}
                        </div>
                    </div>
                @else
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <div class="text-sm text-slate-500">
                            No message content was provided for this notification.
                        </div>
                    </div>
                @endif

                @if($metaParts->count())
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @if($orgName)
                            <div class="rounded-2xl border border-blue-200 bg-blue-50 p-4">
                                <div class="flex items-start gap-3">
                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-700">
                                        <i data-lucide="building-2" class="w-4 h-4"></i>
                                    </div>

                                    <div>
                                        <div class="text-xs font-semibold uppercase tracking-wide text-blue-700">
                                            Organization
                                        </div>

                                        <div class="mt-1 text-sm font-semibold text-slate-900">
                                            {{ $orgName }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($syName)
                            <div class="rounded-2xl border border-indigo-200 bg-indigo-50 p-4">
                                <div class="flex items-start gap-3">
                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-indigo-100 text-indigo-700">
                                        <i data-lucide="calendar-range" class="w-4 h-4"></i>
                                    </div>

                                    <div>
                                        <div class="text-xs font-semibold uppercase tracking-wide text-indigo-700">
                                            School Year
                                        </div>

                                        <div class="mt-1 text-sm font-semibold text-slate-900">
                                            {{ $syName }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

                <div class="rounded-2xl border border-slate-200 bg-white p-4">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <div class="text-sm font-semibold text-slate-900">
                                Notification timestamp
                            </div>

                            <div class="mt-1 text-xs text-slate-500">
                                Created on {{ $n->created_at->format('F d, Y h:i A') }}
                            </div>

                            @if($n->read_at)
                                <div class="mt-1 text-xs text-slate-500">
                                    Read on {{ $n->read_at->format('F d, Y h:i A') }}
                                </div>
                            @endif
                        </div>

                        @if($actionUrl)
                            <a href="{{ $actionUrl }}"
                               class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-xs font-semibold text-white shadow-sm transition hover:bg-indigo-700">
                                Open Related Page
                                <i data-lucide="arrow-right" class="w-4 h-4"></i>
                            </a>
                        @else
                            <a href="{{ route('notifications.index') }}"
                               class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                                Return to Notifications
                                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                            </a>
                        @endif
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

</x-app-layout>