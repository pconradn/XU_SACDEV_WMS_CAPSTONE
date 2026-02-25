<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SAcDev Workflow System') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased text-gray-900">
@auth
    @php
        $isAdmin = auth()->user()->system_role === 'sacdev_admin';
        $activeSy = \App\Models\SchoolYear::activeYear();
    @endphp
@endauth

<div class="min-h-screen bg-slate-50">

   
    <div class="sticky top-0 z-40 border-b border-slate-200 bg-white/90 backdrop-blur">


        
        <div class="mx-auto max-w-screen-2xl px-6 lg:px-8">
            
            <div class="flex h-14 items-center justify-between gap-4">

              
                <div class="flex items-center gap-3">
                    <div class="h-9 w-15 rounded-xl bg-slate-900 text-white flex items-center justify-center font-bold">
                        PWM
                    </div>
                    <div class="leading-tight">
                        <div class="text-sm font-semibold tracking-wide">
                            {{ config('app.name', 'SAcDev Workflow System') }}
                        </div>
                        <div class="text-xs text-slate-500">
                            Project Workflow Management
                        </div>
                    </div>

                    @auth
                        <span class="ml-2 text-[11px] px-2 py-1 rounded-full border
                            {{ $isAdmin ? 'border-blue-200 bg-blue-50 text-blue-700' : 'border-emerald-200 bg-emerald-50 text-emerald-700' }}">
                            {{ $isAdmin ? 'ADMIN PORTAL' : 'ORG PORTAL' }}
                        </span>
                    @endauth
                </div>

             
                <div class="hidden md:flex flex-1 justify-center">
                    <div class="w-full max-w-xl">
                        <div class="flex items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2">
                            <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M21 21l-4.35-4.35m1.85-5.15a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input
                                type="text"
                                placeholder="Search projects, officers, or requests…"
                                class="w-full bg-transparent text-sm text-slate-700 placeholder:text-slate-400 focus:outline-none"
                                disabled
                            />
                        </div>
                        <div class="mt-1 text-[11px] text-slate-400">
                            
                        </div>
                    </div>
                </div>

            
                <div class="flex items-center gap-3">
                    @auth


                        <div class="flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2">
                            <div class="h-7 w-7 rounded-full bg-slate-100 flex items-center justify-center text-xs font-semibold text-slate-700">
                                {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                            </div>
                            <div class="hidden sm:block leading-tight">
                                <div class="text-xs font-semibold text-slate-800">
                                    {{ auth()->user()->name ?? 'User' }}
                                </div>
                                <div class="text-[11px] text-slate-500">
                                    {{ $isAdmin ? 'SAcDev Staff' : 'Organization User' }}
                                </div>
                            </div>
                        </div>
                    @endauth

                    @auth
                        @php $unreadCount = auth()->user()->unreadNotifications()->count(); @endphp

                        <a href="{{ route('notifications.index') }}"
                        class="relative inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-3 py-2 hover:bg-slate-50">
                            
                            <svg class="h-5 w-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2a2 2 0 01-.6 1.4L4 17h5m6 0a3 3 0 11-6 0m6 0H9"/>
                            </svg>

                            @if($unreadCount > 0)
                                <span class="absolute -top-1 -right-1 inline-flex min-w-[18px] h-[18px] items-center justify-center rounded-full bg-red-600 px-1 text-[11px] font-bold text-white">
                                    {{ $unreadCount }}
                                </span>
                            @endif
                        </a>
                    @endauth


                    @guest
                        <a href="{{ route('login') }}"
                           class="text-sm font-semibold text-slate-700 hover:text-slate-900">
                            Login
                        </a>
                    @endguest
                </div>

            </div>
        </div>
    </div>

    {{-- Body --}}
    <div class="mx-auto max-w-screen-2xl px-6 lg:px-8 py-6">
        <div class="grid grid-cols-12 gap-6">

            <aside class="col-span-12 lg:col-span-3 xl:col-span-3">
                <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

                    <div class="px-4 py-3 border-b border-slate-200 bg-slate-50">
                        <div class="text-xs font-semibold tracking-wide text-slate-700">
                            Navigation
                        </div>
                        <div class="text-[11px] text-slate-500">
                            Use the menu to access modules
                        </div>
                    </div>



                    <div class="p-2">
                
                        @include('layouts.navigation')
                    </div>
                </div>

             
                @auth
                    <div class="mt-4 rounded-2xl border border-slate-200 bg-white shadow-sm p-4">
                        <div class="text-xs font-semibold text-slate-700">Quick Info</div>
                        <div class="mt-2 space-y-2 text-sm">
                            <div class="flex items-center justify-between">
                                <span class="text-slate-500 text-xs">Portal</span>
                                <span class="text-xs font-semibold {{ $isAdmin ? 'text-blue-700' : 'text-emerald-700' }}">
                                    {{ $isAdmin ? 'Admin' : 'Organization' }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-slate-500 text-xs">Active SY</span>
                                <span class="text-xs font-semibold text-slate-800">
                                    {{ $activeSy?->name ?? 'None' }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endauth
            </aside>

            {{-- Main Content --}}
            <section class="col-span-12 lg:col-span-9 xl:col-span-9">
                
                @isset($header)
                    <div class="mb-4 rounded-2xl border border-slate-200 bg-white shadow-sm">
                        <div class="px-6 py-5">
                            {{ $header }}
                        </div>
                    </div>
                @endisset

             
                <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="p-6">
                        {{ $slot }}
                    </div>
                </div>
            </section>

        </div>
    </div>

</div>
</body>
</html>
