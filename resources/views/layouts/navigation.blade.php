@php
    $user = Auth::user();
    $isAdmin = $user && $user->system_role === 'sacdev_admin';
@endphp

<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            {{-- Left --}}
            <div class="flex items-center gap-4">
                {{-- Logo --}}
                <a href="{{ Route::has('dashboard') ? route('dashboard') : url('/') }}"
                   class="shrink-0 flex items-center">
                    <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                </a>

                {{-- Primary links (desktop) --}}
                <div class="hidden sm:flex items-center gap-2">

                    {{-- Dashboard (optional) --}}
                    @if (Route::has('dashboard'))
                        <a href="{{ route('dashboard') }}"
                           class="px-3 py-2 text-sm rounded-md text-gray-700 hover:bg-gray-100">
                            Dashboard
                        </a>
                    @endif

                    @auth
                        {{-- Admin links --}}
                        @if ($isAdmin)
                            @if (Route::has('admin.home'))
                                <a href="{{ route('admin.home') }}"
                                   class="px-3 py-2 text-sm rounded-md text-gray-700 hover:bg-gray-100">
                                    Admin Home
                                </a>
                            @endif

                            @if (Route::has('admin.school-years.index'))
                                <a href="{{ route('admin.school-years.index') }}"
                                   class="px-3 py-2 text-sm rounded-md text-gray-700 hover:bg-gray-100">
                                    School Years
                                </a>
                            @endif

                            @if (Route::has('admin.organizations.index'))
                                <a href="{{ route('admin.organizations.index') }}"
                                   class="px-3 py-2 text-sm rounded-md text-gray-700 hover:bg-gray-100">
                                    Organizations
                                </a>
                            @endif

                            @if (Route::has('admin.review.index'))
                                <a href="{{ route('admin.review.index') }}"
                                class="px-3 py-2 text-sm rounded-md text-gray-700 hover:bg-gray-100">
                                    Review Submissions
                                </a>
                            @endif
                            
                        @else
                            {{-- Org link --}}
                            @if (Route::has('org.home'))
                                <a href="{{ route('org.home') }}"
                                   class="px-3 py-2 text-sm rounded-md text-gray-700 hover:bg-gray-100">
                                    Org Home
                                </a>
                            @endif
                        @endif
                    @endauth
                </div>
            </div>

            {{-- Right (desktop) --}}
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <div class="flex items-center gap-3">
                    <span class="text-sm text-gray-700">
                        {{ $user->name ?? 'User' }}
                    </span>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="px-3 py-2 text-sm rounded-md text-white bg-gray-800 hover:bg-gray-700">
                            Log out
                        </button>
                    </form>
                </div>
            </div>

            {{-- Hamburger --}}
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

        </div>
    </div>

    {{-- Responsive Menu (mobile) --}}
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">

        <div class="pt-2 pb-3 space-y-1">
            {{-- Dashboard (optional) --}}
            @if (Route::has('dashboard'))
                <a href="{{ route('dashboard') }}"
                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    Dashboard
                </a>
            @endif

            @auth
                @if ($isAdmin)
                    @if (Route::has('admin.home'))
                        <a href="{{ route('admin.home') }}"
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            Admin Home
                        </a>
                    @endif

                    @if (Route::has('admin.school-years.index'))
                        <a href="{{ route('admin.school-years.index') }}"
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            School Years
                        </a>
                    @endif

                    @if (Route::has('admin.organizations.index'))
                        <a href="{{ route('admin.organizations.index') }}"
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            Organizations
                        </a>
                    @endif
                @else
                    @if (Route::has('org.home'))
                        <a href="{{ route('org.home') }}"
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            Org Home
                        </a>
                    @endif
                @endif
            @endauth
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ $user->name ?? 'User' }}</div>
                <div class="font-medium text-sm text-gray-500">{{ $user->email ?? '' }}</div>
            </div>

            <div class="mt-3 space-y-1 px-4">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="w-full text-left px-3 py-2 text-sm rounded-md text-white bg-gray-800 hover:bg-gray-700">
                        Log out
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
