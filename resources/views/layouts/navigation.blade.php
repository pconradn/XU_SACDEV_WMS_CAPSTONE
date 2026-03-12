@php
    $user = Auth::user();
    $isAdmin = $user && $user->system_role === 'sacdev_admin';
@endphp

<nav class="w-full">

    {{-- Mobile --}}
    <details class="lg:hidden">
        <summary class="cursor-pointer select-none rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-800 hover:bg-slate-50">
            Menu
        </summary>

        <div class="mt-2 space-y-1">
            @include('layouts.nav._links', ['user' => $user, 'isAdmin' => $isAdmin, 'mode' => 'mobile'])
        </div>
    </details>

    

    {{-- Desktop --}}
    <div class="hidden lg:block space-y-1">
        @include('layouts.nav._links', ['user' => $user, 'isAdmin' => $isAdmin, 'mode' => 'desktop'])
    </div>

</nav>
