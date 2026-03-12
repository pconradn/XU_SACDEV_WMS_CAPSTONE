<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@include('layouts.partials._head')

<body class="font-sans antialiased text-gray-900">

@auth
    @php
        $isAdmin = auth()->user()->system_role === 'sacdev_admin';
        $activeSy = \App\Models\SchoolYear::activeYear();
    @endphp
@endauth

<div class="min-h-screen bg-slate-50">

    @include('layouts.partials._flash')

    @include('layouts.partials._topbar')

    <div class="mx-auto max-w-screen-2xl px-6 lg:px-8 py-6">
        <div class="grid grid-cols-12 gap-6">

            @include('layouts.partials._sidebar')

            @include('layouts.partials._content-wrapper')

        </div>
    </div>

</div>

</body>
</html>