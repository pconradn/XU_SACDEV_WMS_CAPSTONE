<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>
        {{ config('app.name') }}{{ isset($title) ? ' - ' . $title : '' }}
    </title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-100 min-h-screen font-sans antialiased">


    
    <div class="bg-white border-b border-slate-200">
        <div class="max-w-4xl mx-auto px-6 py-4 flex items-center justify-between">

          
            @isset($backRoute)
                <a href="{{ $backRoute }}"
                   class="inline-flex items-center gap-2 text-sm font-medium text-slate-600 hover:text-slate-900 transition">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 19l-7-7 7-7" />
                    </svg>
                    Back
                </a>
            @endisset

           
            <div class="text-sm font-semibold text-slate-700">
                {{ $title ?? '' }}
            </div>

           
            <div></div>

        </div>
    </div>

   
    <main class="max-w-4xl mx-auto px-6 py-10">
        {{ $slot }}
    </main>

</body>
</html>