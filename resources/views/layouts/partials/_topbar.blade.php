<header class="sticky top-0 z-40 border-b border-slate-200 bg-white/90 backdrop-blur">

<div class="flex h-14 items-center justify-between px-6">

{{-- LEFT SIDE --}}
<div class="flex items-center gap-3">

{{-- MOBILE SIDEBAR BUTTON --}}
<button
@click="sidebarOpen = true"
class="lg:hidden text-slate-600 hover:text-slate-900 transition">

<svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
</svg>

</button>


{{-- PAGE TITLE --}}
<h1 class="text-lg font-semibold text-slate-800">

{{ $title ?? 'Dashboard' }}

</h1>


{{-- PORTAL TAG --}}
@auth
<span class="text-[11px] px-2.5 py-1 rounded-full border font-medium
{{ $isAdmin
    ? 'border-blue-200 bg-blue-50 text-blue-700'
    : 'border-emerald-200 bg-emerald-50 text-emerald-700' }}">

{{ $isAdmin ? 'ADMIN PORTAL' : 'ORG PORTAL' }}

</span>
@endauth

</div>



{{-- RIGHT SIDE --}}
<div class="flex items-center gap-3">


{{-- NOTIFICATIONS --}}
@auth
@php
$unreadCount = auth()->user()->unreadNotifications()->count();
@endphp

<a
href="{{ route('notifications.index') }}"
class="relative inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white p-2 hover:bg-slate-50 transition">

<svg class="h-5 w-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2a2 2 0 01-.6 1.4L4 17h5m6 0a3 3 0 11-6 0m6 0H9"/>
</svg>

@if($unreadCount > 0)
<span class="absolute -top-1 -right-1 flex items-center justify-center min-w-[18px] h-[18px] rounded-full bg-red-600 px-1 text-[10px] font-bold text-white">

{{ $unreadCount }}

</span>
@endif

</a>
@endauth



{{-- USER MENU --}}
@auth
<div x-data="{open:false}" class="relative">

<button
@click="open = !open"
class="flex items-center gap-2 rounded-lg hover:bg-slate-100 px-2 py-1 transition">

<div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center text-sm font-semibold">

{{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}

</div>

<svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
</svg>

</button>



{{-- DROPDOWN --}}
<div
x-show="open"
x-transition.origin.top.right
@click.outside="open=false"
class="absolute right-0 mt-3 w-48 bg-white border border-slate-200 rounded-xl shadow-lg py-2 text-sm z-50">

<div class="px-4 py-2 text-xs text-slate-500 border-b">
{{ auth()->user()->name }}
</div>

<a href="#"
class="block px-4 py-2 hover:bg-slate-100">
Profile
</a>

<a href="#"
class="block px-4 py-2 hover:bg-slate-100">
Settings
</a>

<hr class="my-1">

<form method="POST" action="/logout">
@csrf

<button
class="w-full text-left px-4 py-2 hover:bg-slate-100">
Logout
</button>

</form>

</div>

</div>
@endauth



{{-- LOGIN (guest) --}}
@guest
<a href="{{ route('login') }}"
class="text-sm font-semibold text-slate-700 hover:text-slate-900">
Login
</a>
@endguest

</div>

</div>

</header>