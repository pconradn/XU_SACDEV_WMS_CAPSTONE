<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@include('layouts.partials._head')

<body class="font-sans antialiased bg-slate-100 text-slate-900">

@auth
@php
$isAdmin = auth()->user()->system_role === 'sacdev_admin';
$activeSy = \App\Models\SchoolYear::activeYear();
@endphp
@endauth


<div x-data="{ sidebarOpen: false }" class="flex h-screen overflow-hidden">

    {{-- SIDEBAR --}}
    @include('layouts.partials._sidebar')


    <div class="flex flex-col flex-1 min-w-0">

        {{-- TOPBAR --}}
        @include('layouts.partials._topbar')


        {{-- FLASH --}}
        <div class="px-6 pt-4">
            @include('layouts.partials._flash')
        </div>


        {{-- PAGE CONTENT --}}
        <main class="flex-1 overflow-y-auto p-6">

            @include('layouts.partials._content-wrapper')

        </main>

    </div>

</div>

</body>
</html>