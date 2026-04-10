@php
    use App\Models\SchoolYear;
    use App\Models\Organization;
    use Illuminate\Support\Str;

    $user = auth()->user();
    $isAdmin = $user && $user->isSacdev();

    $sy = SchoolYear::activeYear();

    $activeSyId = (int) session('encode_sy_id');
    $activeOrgId = (int) session('active_org_id');

    $activeSy = $activeSyId ? SchoolYear::find($activeSyId) : null;
    $activeOrg = $activeOrgId ? Organization::find($activeOrgId) : null;

    $unreadCount = $user ? $user->unreadNotifications()->count() : 0;
    $recentNotifications = $user
        ? $user->notifications()->latest()->take(5)->get()
        : collect();
@endphp

<header class="sticky top-0 z-40 border-b border-slate-800 bg-slate-900/85 backdrop-blur">
    <div class="flex min-h-12 items-center justify-between gap-2 px-3 sm:px-4">

        {{-- LEFT SIDE --}}
        <div class="flex min-w-0 flex-1 items-center gap-2 sm:gap-4">

            {{-- MOBILE SIDEBAR BUTTON --}}
            <button
                @click="sidebarOpen = true"
                class="lg:hidden inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-800 hover:text-white"
                type="button"
            >
                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            {{-- CONTEXT --}}
            <div class="min-w-0 flex-1 overflow-hidden">
                <div class="flex flex-wrap items-center gap-1.5 sm:gap-2">
                    <span
                        class="inline-flex max-w-full items-center rounded-full border px-2 py-1 text-[10px] font-semibold leading-none sm:px-2.5
                        {{ $isAdmin
                            ? 'border-blue-500/30 bg-blue-500/10 text-blue-300'
                            : 'border-emerald-500/30 bg-emerald-500/10 text-emerald-300' }}"
                    >
                        {{ $isAdmin ? 'ADMIN PORTAL' : 'ORG PORTAL' }}
                    </span>

                    @if($isAdmin)
                        <span class="inline-flex max-w-full items-center rounded-full border border-slate-700 bg-slate-800/80 px-2 py-1 text-[10px] font-medium leading-none text-slate-300 sm:px-2.5">
                            <span class="truncate">Active SY {{ $sy->name }}</span>
                        </span>

                    @elseif($activeSyId && !$isAdmin)
                        <span class="inline-flex max-w-full items-center rounded-full border border-slate-700 bg-slate-800/80 px-2 py-1 text-[10px] font-medium leading-none text-slate-300 sm:px-2.5">
                            <span class="truncate">{{ $activeSy->name }}</span>
                        </span>
                    @endif

                    @if($activeOrg && !$isAdmin)
                        <span class="inline-flex max-w-full items-center rounded-full border border-slate-700 bg-slate-800/80 px-2 py-1 text-[10px] font-medium leading-none text-slate-300 sm:px-2.5">
                            <span class="truncate max-w-[160px] sm:max-w-[220px] md:max-w-none">
                                {{ $activeOrg->name }}
                            </span>
                        </span>
                    @endif
                </div>

                {{-- helper text --}}
                <div class="mt-1 hidden text-xs text-slate-400 truncate sm:block">
                    {{ $isAdmin
                        ? 'Manage workflows, organizations, approvals, and system administration'
                        : ($activeOrg ? 'Working inside your organization workspace' : 'Select a school year and organization to continue') }}
                </div>
            </div>
        </div>

        {{-- RIGHT SIDE --}}
        <div class="flex shrink-0 items-center gap-2 sm:gap-3">

            @auth
                @if(!$isAdmin)
                    <div x-data="{ open: false }" class="relative shrink-0">
                        <button
                            @click="open = !open"
                            type="button"
                            class="relative inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-700 bg-slate-800/80 text-slate-300 transition hover:bg-slate-800 hover:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/30"
                        >
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2a2 2 0 01-.6 1.4L4 17h5m6 0a3 3 0 11-6 0m6 0H9"/>
                            </svg>

                            @if($unreadCount > 0)
                                <span class="absolute -top-1 -right-1 flex h-[18px] min-w-[18px] items-center justify-center rounded-full bg-red-500 px-1 text-[10px] font-bold text-white">
                                    {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                                </span>
                            @endif
                        </button>

                {{-- NOTIFICATION DROPDOWN --}}
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

                        <a
                            href="{{ route('notifications.index') }}"
                            class="text-xs font-medium text-blue-300 hover:text-blue-200"
                        >
                            View all
                        </a>
                    </div>

                    <div class="max-h-76 overflow-y-auto">
                        @forelse($recentNotifications as $notification)
                            <a
                                href="{{ route('notifications.show', $notification->id) }}"
                                class="block border-b border-slate-800 px-4 py-3 hover:bg-slate-800/70 transition"
                            >
                                <div class="flex items-start gap-3">
                                    <div class="mt-0.5 h-2.5 w-2.5 rounded-full {{ is_null($notification->read_at) ? 'bg-blue-400' : 'bg-slate-600' }}"></div>

                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs font-medium text-slate-100">
                                            {{ data_get($notification->data, 'title', 'Notification') }}
                                        </p>

                                        @if(data_get($notification->data, 'message'))
                                            <p class="mt-1 text-xs text-slate-400">
                                                {{ Str::limit(data_get($notification->data, 'message'), 90) }}
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
                    </div>
                </div>
            </div>
            @endif
            @endauth

            {{-- USER MENU --}}
            @auth
            <div x-data="{ open: false }" class="relative">
                <button
                    @click="open = !open"
                    class="flex items-center gap-1.5 rounded-xl border border-slate-700 bg-slate-800/80 px-2.5 py-1.5 text-slate-200 hover:bg-slate-800 transition focus:outline-none focus:ring-2 focus:ring-blue-500/30"
                >
                    <div class="flex h-7 w-7 items-center justify-center rounded-full bg-gradient-to-br from-blue-500 to-blue-700 text-xs font-semibold text-white shadow-md shadow-blue-950/30">
                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                    </div>

                    <div class="hidden md:block text-left leading-tight">
                        <div class="max-w-[140px] truncate text-xs font-medium text-slate-100">
                            {{ auth()->user()->name }}
                        </div>
                        <div class="text-[10px] text-slate-400">
                            {{ $isAdmin ? 'System User' : 'Organization User' }}
                        </div>
                    </div>

                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                {{-- USER DROPDOWN --}}
                <div
                    x-show="open"
                    x-transition.origin.top.right
                    @click.outside="open = false"
                    class="absolute right-0 mt-3 w-64 overflow-hidden rounded-2xl border border-slate-700 bg-slate-900 shadow-2xl shadow-slate-950/40 z-50"
                    style="display: none;"
                >
                    <div class="border-b border-slate-800 px-4 py-4">
                        <p class="text-xs font-semibold text-white">{{ auth()->user()->name }}</p>
                        @if(auth()->user()->email)
                            <p class="mt-1 text-xs text-slate-400 truncate">{{ auth()->user()->email }}</p>
                        @endif
                    </div>

                    <div class="px-2 py-2 space-y-1">

                        @if(auth()->user()->system_role !== 'sacdev_admin')
                            <a href="{{ route('org.profile.edit') }}"
                            class="flex items-center gap-1.5 rounded-xl px-3 py-2 text-xs text-slate-200 hover:bg-slate-800 transition">

                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5.121 17.804A9 9 0 1118.88 6.196 9 9 0 015.121 17.804z"/>
                                </svg>

                                <span>Profile</span>
                            </a>
                        @endif

                        <form method="POST" action="/logout">
                            @csrf
                            <button
                                class="flex w-full items-center gap-1.5 rounded-xl px-3 py-2 text-left text-xs text-slate-200 hover:bg-slate-800 transition"
                            >
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H9m4 8H7a2 2 0 01-2-2V6a2 2 0 012-2h6"/>
                                </svg>
                                <span>Logout</span>
                            </button>
                        </form>

                    </div>
                </div>
            </div>
            @endauth

            {{-- LOGIN (guest) --}}
            @guest
                <a
                    href="{{ route('login') }}"
                    class="text-xs font-semibold text-slate-300 hover:text-white"
                >
                    Login
                </a>
            @endguest

        </div>
    </div>
</header>