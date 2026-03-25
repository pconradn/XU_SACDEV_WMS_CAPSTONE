<header class="sticky top-0 z-40 bg-white/90 backdrop-blur border-b border-slate-200">

<div class="flex items-center justify-between px-6 h-16">

{{-- LEFT SIDE --}}
<div class="flex items-center gap-1">

{{-- MOBILE SIDEBAR BUTTON --}}



<h1 class="text-lg font-semibold text-slate-800">

{{ $title ?? 'Dashboard' }}

</h1>

</div>



{{-- RIGHT SIDE --}}
<div class="flex items-center gap-4">



{{-- NOTIFICATIONS --}}
<div x-data="{open:false}" class="relative">

<button
@click="open=!open"
class="relative text-slate-600 hover:text-blue-600 transition">

<svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5"
viewBox="0 0 24 24">

<path stroke-linecap="round" stroke-linejoin="round"
d="M15 17h5l-1.405-1.405A2.032
2.032 0 0118 14.158V11a6.002
6.002 0 00-4-5.659V5a2
2 0 10-4 0v.341C7.67
6.165 6 8.388 6
11v3.159c0 .538-.214
1.055-.595
1.436L4 17h5m6 0v1a3
3 0 11-6 0v-1m6 0H9"/>

</svg>

<span class="absolute -top-1 -right-1 w-2 h-2 bg-red-500 rounded-full"></span>

</button>


{{-- NOTIFICATION DROPDOWN --}}
<div
x-show="open"
x-transition
@click.outside="open=false"
class="absolute right-0 mt-3 w-72 bg-white border border-slate-200 rounded-xl shadow-lg overflow-hidden text-sm">

<div class="px-4 py-3 border-b text-xs font-semibold text-slate-500">
Notifications
</div>

<div class="max-h-64 overflow-y-auto">

<div class="px-4 py-3 hover:bg-slate-50">
No new notifications
</div>

</div>

</div>

</div>



{{-- USER MENU --}}
<div x-data="{open:false}" class="relative">

<button
@click="open=!open"
class="flex items-center gap-2 hover:bg-slate-100 px-2 py-1 rounded-lg transition">

<div class="w-9 h-9 rounded-full bg-blue-600 text-white flex items-center justify-center text-sm font-semibold">

{{ substr(auth()->user()->name ?? 'U',0,1) }}

</div>

<svg class="w-4 h-4 text-slate-500"
fill="none"
stroke="currentColor"
stroke-width="2"
viewBox="0 0 24 24">

<path stroke-linecap="round"
stroke-linejoin="round"
d="M19 9l-7 7-7-7"/>

</svg>

</button>



{{-- USER DROPDOWN --}}
<div
x-show="open"
x-transition
@click.outside="open=false"
class="absolute right-0 mt-3 w-48 bg-white border border-slate-200 rounded-xl shadow-lg py-2 text-sm">

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



</div>

</div>

</header>