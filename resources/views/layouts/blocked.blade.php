<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'SAcDev Workflow System') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased text-gray-900">
<div class="min-h-screen bg-slate-50">

    {{-- Minimal Top Bar --}}
    <div class="border-b border-slate-200 bg-white">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-14 items-center justify-between">

                <div class="flex items-center gap-3">
                    <div class="h-9 w-9 rounded-xl bg-slate-900 text-white flex items-center justify-center font-bold">
                        S
                    </div>
                    <div class="leading-tight">
                        <div class="text-sm font-semibold tracking-wide">
                            {{ config('app.name', 'SAcDev Workflow System') }}
                        </div>
                        <div class="text-xs text-slate-500">
                            {{ $subtitle ?? 'Access Notice' }}
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    @auth
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                                Log out
                            </button>
                        </form>
                    @endauth

                    @guest
                        @if (Route::has('login'))
                            <a href="{{ route('login') }}"
                               class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                Back to Login
                            </a>
                        @endif
                    @endguest
                </div>

            </div>
        </div>
    </div>

    {{-- Content --}}
    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 py-10">
        {{ $slot }}
    </div>

</div>
</body>
</html>
