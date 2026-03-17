{{-- MOBILE OVERLAY --}}
<div
x-show="sidebarOpen"
x-transition.opacity
class="fixed inset-0 bg-black/40 z-40 lg:hidden"
@click="sidebarOpen=false">
</div>


{{-- SIDEBAR --}}
<aside
:class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
class="fixed lg:static z-50 h-screen w-72 bg-white border-r border-slate-200 flex flex-col transform transition-transform duration-300 lg:translate-x-0">


{{-- LOGO --}}
<div class="flex items-center gap-3 px-6 py-5 border-b border-slate-200">

<div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white font-bold shadow-sm">
S
</div>

<div>
<p class="text-sm font-semibold text-slate-800">
SACDEV
</p>

<p class="text-xs text-slate-500">
Project Workflow System
</p>
</div>

</div>



@php
$user = Auth::user();
$isAdmin = $user && $user->system_role === 'sacdev_admin';
@endphp



{{-- NAVIGATION --}}
<nav class="flex-1 overflow-y-auto px-3 py-5 text-sm">

@include('layouts.nav._links', [
    'user' => $user,
    'isAdmin' => $isAdmin,
    'mode' => 'desktop'
])

</nav>







{{-- FOOTER --}}
<div class="px-5 py-4 border-t border-slate-200 text-xs text-slate-500">

<div class="flex justify-between">
<span>SACDEV System</span>
<span class="text-yellow-500 font-medium">v1.0</span>
</div>

</div>


</aside>