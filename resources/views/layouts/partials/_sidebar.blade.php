{{-- MOBILE OVERLAY --}}
<div
    x-show="sidebarOpen"
    x-transition.opacity
    class="fixed inset-0 bg-slate-850/60 backdrop-blur-sm z-40 lg:hidden"
    @click="sidebarOpen = false">
</div>

{{-- SIDEBAR --}}
<aside
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed lg:static inset-y-0 left-0 z-50 h-screen w-72 shrink-0
           bg-slate-800 text-slate-200 border-r border-slate-800/80
           flex flex-col transform transition-transform duration-300 ease-in-out
           lg:translate-x-0 shadow-2xl shadow-slate-950/30">

    @php
        $user = Auth::user();
        $isAdmin = $user && $user->isSacdev();
    @endphp

    {{-- TOP BRAND --}}
    <div class="relative px-5 py-5 border-b border-slate-800">
        <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-blue-500/40 to-transparent"></div>

        <div class="flex items-center gap-3">
            <div class="flex h-11 w-11 items-center justify-center rounded-2xl
                        bg-gradient-to-br from-blue-500 to-blue-700
                        text-white font-semibold shadow-lg shadow-blue-900/30">
                S
            </div>

            <div class="min-w-0">
                <p class="text-sm font-semibold tracking-wide text-white">
                    SACDEV
                </p>
                <p class="text-xs text-slate-400 truncate">
                    Project Workflow System
                </p>
            </div>
        </div>
    </div>

    {{-- NAV --}}
    <nav class="flex-1 overflow-y-auto px-3 py-4 text-sm custom-sidebar-scroll">
        @include('layouts.nav._links', [
            'user' => $user,
            'isAdmin' => $isAdmin,
            'mode' => 'desktop'
        ])
    </nav>

    {{-- FOOTER --}}
    <div class="border-t border-slate-800 px-5 py-4 bg-slate-850/80">
        <div class="flex items-center justify-between text-xs">
            <span class="text-slate-400">SACDEV System</span>
            <span class="rounded-full border border-blue-500/30 bg-blue-500/10 px-2 py-0.5 font-medium text-blue-300">
                v1.0
            </span>
        </div>
    </div>
</aside>