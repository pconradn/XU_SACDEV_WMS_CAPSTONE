<div x-data="{ sidebarOpen:false }" class="flex">

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
class="fixed lg:static z-50 h-screen w-64 bg-white border-r border-slate-200 flex flex-col transform transition-transform duration-300 lg:translate-x-0">


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



{{-- NAV --}}
<nav class="flex-1 overflow-y-auto px-3 py-5 text-sm space-y-2">



{{-- DASHBOARD --}}
<a href="#"
class="flex items-center gap-3 px-4 py-2 rounded-lg text-slate-700 hover:bg-blue-50 hover:text-blue-700 transition">

<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5"
viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round"
d="M3 12l9-9 9 9M4 10v10h6v-6h4v6h6V10"/>
</svg>

Dashboard
</a>



{{-- ORGANIZATIONS --}}
<div x-data="{open:false}">

<button
@click="open=!open"
class="w-full flex items-center justify-between px-4 py-2 rounded-lg text-slate-700 hover:bg-blue-50 hover:text-blue-700">

<div class="flex items-center gap-3">

<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5"
viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round"
d="M17 20h5V4H2v16h5m10 0v-6H7v6"/>
</svg>

Organizations

</div>

<svg class="w-4 h-4 transition-transform"
:class="open ? 'rotate-180' : ''"
fill="none" stroke="currentColor" stroke-width="2"
viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round"
d="M19 9l-7 7-7-7"/>
</svg>

</button>


<div
x-show="open"
x-transition
class="ml-8 mt-2 space-y-1">

<a href="#" class="block px-3 py-2 rounded-md hover:bg-slate-100 text-slate-600">
Organization Hub
</a>

<a href="#" class="block px-3 py-2 rounded-md hover:bg-slate-100 text-slate-600">
Re-Registration
</a>

<a href="#" class="block px-3 py-2 rounded-md hover:bg-slate-100 text-slate-600">
Officer Lists
</a>

</div>

</div>



{{-- PROJECTS --}}
<div x-data="{open:false}">

<button
@click="open=!open"
class="w-full flex items-center justify-between px-4 py-2 rounded-lg text-slate-700 hover:bg-blue-50 hover:text-blue-700">

<div class="flex items-center gap-3">

<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5"
viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round"
d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5l5 5v11a2 2 0 01-2 2z"/>
</svg>

Projects

</div>

<svg class="w-4 h-4 transition-transform"
:class="open ? 'rotate-180' : ''"
fill="none" stroke="currentColor" stroke-width="2"
viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round"
d="M19 9l-7 7-7-7"/>
</svg>

</button>


<div
x-show="open"
x-transition
class="ml-8 mt-2 space-y-1">

<a href="#" class="block px-3 py-2 rounded-md hover:bg-slate-100 text-slate-600">
Project Proposals
</a>

<a href="#" class="block px-3 py-2 rounded-md hover:bg-slate-100 text-slate-600">
Budget Proposals
</a>

<a href="#" class="block px-3 py-2 rounded-md hover:bg-slate-100 text-slate-600">
Post Implementation
</a>

</div>

</div>



{{-- APPOINTMENTS --}}
<div x-data="{open:false}">

<button
@click="open=!open"
class="w-full flex items-center justify-between px-4 py-2 rounded-lg text-slate-700 hover:bg-blue-50 hover:text-blue-700">

<div class="flex items-center gap-3">

<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5"
viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round"
d="M12 6v6l4 2"/>
</svg>

Appointments

</div>

<svg class="w-4 h-4 transition-transform"
:class="open ? 'rotate-180' : ''"
fill="none" stroke="currentColor" stroke-width="2"
viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round"
d="M19 9l-7 7-7-7"/>
</svg>

</button>


<div
x-show="open"
x-transition
class="ml-8 mt-2 space-y-1">

<a href="#" class="block px-3 py-2 rounded-md hover:bg-slate-100 text-slate-600">
Consultations
</a>

<a href="#" class="block px-3 py-2 rounded-md hover:bg-slate-100 text-slate-600">
Schedules
</a>

</div>

</div>



{{-- ADMIN --}}
<div x-data="{open:false}">

<button
@click="open=!open"
class="w-full flex items-center justify-between px-4 py-2 rounded-lg text-slate-700 hover:bg-blue-50 hover:text-blue-700">

<div class="flex items-center gap-3">

<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5"
viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round"
d="M12 3v18m9-9H3"/>
</svg>

Administration

</div>

<svg class="w-4 h-4 transition-transform"
:class="open ? 'rotate-180' : ''"
fill="none" stroke="currentColor" stroke-width="2"
viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round"
d="M19 9l-7 7-7-7"/>
</svg>

</button>


<div
x-show="open"
x-transition
class="ml-8 mt-2 space-y-1">

<a href="#" class="block px-3 py-2 rounded-md hover:bg-slate-100 text-slate-600">
School Years
</a>

<a href="#" class="block px-3 py-2 rounded-md hover:bg-slate-100 text-slate-600">
Audit Logs
</a>

</div>

</div>



</nav>



{{-- FOOTER --}}
<div class="px-5 py-4 border-t border-slate-200 text-xs text-slate-500">

<div class="flex justify-between">

<span>SACDEV System</span>

<span class="text-yellow-500 font-medium">v1.0</span>

</div>

</div>

</aside>



{{-- MAIN CONTENT WRAPPER --}}
<div class="flex-1 flex flex-col">

{{-- MOBILE TOPBAR --}}
<div class="lg:hidden flex items-center justify-between bg-white border-b px-4 py-3">

<button
@click="sidebarOpen=true"
class="text-slate-600">

<svg class="w-6 h-6" fill="none" stroke="currentColor"
stroke-width="2"
viewBox="0 0 24 24">

<path stroke-linecap="round"
stroke-linejoin="round"
d="M4 6h16M4 12h16M4 18h16"/>

</svg>

</button>

<span class="font-semibold text-slate-700">
SACDEV
</span>

</div>


{{-- PAGE CONTENT --}}
<main class="p-6 bg-slate-100 min-h-screen">

{{ $slot ?? '' }}

</main>

</div>

</div>