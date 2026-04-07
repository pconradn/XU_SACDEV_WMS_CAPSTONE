<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SACDEV Clearance Verification</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center">

<div class="w-full max-w-md mx-auto space-y-6">

    {{-- HEADER --}}
    <div class="text-center">
        <h1 class="text-lg font-semibold text-slate-900">
            SACDEV Clearance Verification
        </h1>
        <p class="text-sm text-slate-500">
            Enter a student ID to verify clearance status.
        </p>
    </div>

    {{-- CARD --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-6">

        {{-- FORM --}}
        <form method="POST" action="{{ route('clearance.public.verify') }}" class="space-y-3">
            @csrf

            <input
                type="text"
                name="student_id"
                value="{{ old('student_id') }}"
                placeholder="Student ID (e.g. 20201234)"
                class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500"
                required
            >

            <button class="w-full px-4 py-2 rounded-xl bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700">
                Verify
            </button>
        </form>

        {{-- ERROR --}}
        @if(session('error'))
            <div class="mt-4 text-sm text-rose-600 font-medium text-center">
                {{ session('error') }}
            </div>
        @endif

    </div>

    {{-- RESULT --}}
    @isset($user)

        <div class="rounded-2xl border shadow-sm p-6 text-center
            {{ $isCleared
                ? 'border-emerald-200 bg-emerald-50'
                : 'border-rose-200 bg-rose-50'
            }}
        ">

            {{-- STATUS --}}
            <div class="text-xs uppercase tracking-wide
                {{ $isCleared ? 'text-emerald-700' : 'text-rose-700' }}">
                Clearance Status
            </div>

            <div class="mt-1 text-lg font-semibold
                {{ $isCleared ? 'text-emerald-900' : 'text-rose-900' }}">
                {{ $isCleared ? 'CLEARED' : 'NOT CLEARED' }}
            </div>

            {{-- NAME --}}
            <div class="text-sm text-slate-600 mt-2">
                {{ $user->name }}
            </div>

            {{-- PENDING COUNT --}}
            @if(!$isCleared)
                <div class="text-sm font-semibold text-rose-700 mt-2">
                    {{ $pendingCount }} Pending Responsibility{{ $pendingCount > 1 ? 'ies' : '' }}
                </div>
            @endif

        </div>

    @endisset

</div>

</body>
</html>