<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

@vite(['resources/css/app.css','resources/js/app.js'])

<title>{{ $title ?? 'SAcDev System' }}

</title>
</head>

<body class="bg-slate-100">

<div class="flex h-screen">

    {{-- SIDEBAR --}}
    @include('test.partials.navbar')

    <div class="flex flex-col flex-1">

        {{-- TOPBAR --}}
        @include('test.partials.topbar')

        {{-- PAGE CONTENT --}}
        <main class="p-6 overflow-y-auto">

            {{ $slot ?? '' }}
            @yield('content')

        </main>

    </div>

</div>

</body>
</html>