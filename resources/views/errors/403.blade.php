<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>403 - Access Denied</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-slate-50">

<div class="min-h-screen flex items-center justify-center px-6">
    <div class="text-center max-w-md">

        <h1 class="text-2xl font-semibold text-slate-900">
            Access Denied — Try logging in to access this page
        </h1>
        <p class="mt-2 text-sm text-slate-500">
            {{ class_basename($exception) }} — 
            {{ $exception->getMessage() ?: 'You are not allowed to perform this action.' }}
        </p>

        <div class="mt-6">

            <a href="{{ auth()->check()
                    ? (auth()->user()->system_role === 'sacdev_admin'
                        ? route('admin.home')
                        : route('org.home'))
                    : route('login') }}"
            class="px-4 py-2 rounded-lg bg-slate-900 text-white text-sm font-medium hover:bg-slate-800">
                Go to Home
            </a>

        </div>
    </div>
</div>

</body>
</html>