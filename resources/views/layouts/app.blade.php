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

<body class="font-sans antialiased">
<div class="min-h-screen bg-gray-100">

    {{-- Breeze navigation --}}
    @include('layouts.navigation')

    {{-- Top utility bar --}}
    <div class="bg-gray-900 text-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="text-sm font-semibold tracking-wide">
                    {{ config('app.name', 'SAcDev Workflow System') }}
                </span>

                @auth
                    @php
                        $isAdmin = auth()->user()->system_role === 'sacdev_admin';
                        $activeSy = \App\Models\SchoolYear::activeYear();
                    @endphp

                    <span class="text-xs px-2 py-1 rounded {{ $isAdmin ? 'bg-blue-600' : 'bg-emerald-600' }}">
                        {{ $isAdmin ? 'ADMIN PORTAL' : 'ORG PORTAL' }}
                    </span>

                    <span class="text-xs px-2 py-1 rounded bg-gray-700">
                        Active SY:
                        <span class="font-semibold">
                            {{ $activeSy?->name ?? 'None' }}
                        </span>
                    </span>
                @endauth
            </div>


        </div>
    </div>

    {{-- Page Heading --}}
    @isset($header)
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
    @endisset

    {{-- Page Content --}}
    <main>
        {{ $slot }}
    </main>

</div>
</body>
</html>
