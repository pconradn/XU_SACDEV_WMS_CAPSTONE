<div x-data="{ open: null }" class="bg-white border-b border-gray-200">

<div class="max-w-7xl mx-auto px-6">

<div class="flex items-center h-14 space-x-8">

<!-- Dashboard -->
<a href="#" class="text-sm font-medium text-gray-700 hover:text-blue-700 transition">
Dashboard     
</a>


<!-- Organization Hub -->
<div
x-data="{ menu: false }"
@mouseenter="menu = true"
@mouseleave="menu = false"
class="relative"
>

<button class="text-sm font-medium text-gray-700 hover:text-blue-700 transition flex items-center gap-1">

Organization Hub

<svg class="w-4 h-4 transition-transform"
:class="{ 'rotate-180': menu }"
viewBox="0 0 20 20">
<path fill="currentColor" d="M5 7l5 5 5-5"/>
</svg>

</button>

<div
x-show="menu"
x-transition:enter="transition ease-out duration-150"
x-transition:enter-start="opacity-0 translate-y-1"
x-transition:enter-end="opacity-100 translate-y-0"
x-transition:leave="transition ease-in duration-100"
x-transition:leave-start="opacity-100 translate-y-0"
x-transition:leave-end="opacity-0 translate-y-1"
class="absolute left-0 mt-3 w-[420px] bg-white border rounded-xl shadow-xl p-6"
>

<div class="grid grid-cols-2 gap-6">

<div>
<h3 class="text-xs font-semibold text-gray-500 uppercase mb-3">
Organization Tools
</h3>

<a href="#" class="block text-sm hover:text-blue-700 transition mb-2">
Organization Hub
</a>

<a href="#" class="block text-sm hover:text-blue-700 transition mb-2">
Members
</a>

<a href="#" class="block text-sm hover:text-blue-700 transition">
Projects
</a>
</div>

<div>
<h3 class="text-xs font-semibold text-gray-500 uppercase mb-3">
Management
</h3>

<a href="#" class="block text-sm hover:text-blue-700 transition mb-2">
Appointments
</a>

<a href="#" class="block text-sm hover:text-blue-700 transition">
Notifications
</a>
</div>

</div>

</div>

</div>


<!-- Submissions -->
<div
x-data="{ menu: false }"
@mouseenter="menu = true"
@mouseleave="menu = false"
class="relative"
>

<button class="text-sm font-medium text-gray-700 hover:text-blue-700 transition flex items-center gap-1">

Submissions

<svg class="w-4 h-4 transition-transform"
:class="{ 'rotate-180': menu }"
viewBox="0 0 20 20">
<path fill="currentColor" d="M5 7l5 5 5-5"/>
</svg>

</button>

<div
x-show="menu"
x-transition:enter="transition ease-out duration-150"
x-transition:enter-start="opacity-0 translate-y-1"
x-transition:enter-end="opacity-100 translate-y-0"
x-transition:leave="transition ease-in duration-100"
x-transition:leave-start="opacity-100 translate-y-0"
x-transition:leave-end="opacity-0 translate-y-1"
class="absolute left-0 mt-3 w-[520px] bg-white border rounded-xl shadow-xl p-6"
>

<div class="grid grid-cols-2 gap-8">

<div>

<h3 class="text-xs font-semibold text-gray-500 uppercase mb-3">
Re-Registration
</h3>

<a href="#" class="block text-sm hover:text-blue-700 transition mb-2">
Pending Forms
</a>

<a href="#" class="block text-sm hover:text-blue-700 transition">
Approved Forms
</a>

</div>

<div>

<h3 class="text-xs font-semibold text-gray-500 uppercase mb-3">
Project Submissions
</h3>

<a href="#" class="block text-sm hover:text-blue-700 transition mb-2">
Project Proposals
</a>

<a href="#" class="block text-sm hover:text-blue-700 transition mb-2">
Budget Proposals
</a>

<a href="#" class="block text-sm hover:text-blue-700 transition">
Other Forms
</a>

</div>

</div>

</div>

</div>


<!-- Settings -->
<div
x-data="{ menu: false }"
@mouseenter="menu = true"
@mouseleave="menu = false"
class="relative"
>

<button class="text-sm font-medium text-gray-700 hover:text-blue-700 transition flex items-center gap-1">

Settings

<svg class="w-4 h-4 transition-transform"
:class="{ 'rotate-180': menu }"
viewBox="0 0 20 20">
<path fill="currentColor" d="M5 7l5 5 5-5"/>
</svg>

</button>

<div
x-show="menu"
x-transition
class="absolute left-0 mt-3 w-[360px] bg-white border rounded-xl shadow-xl p-6"
>

<h3 class="text-xs font-semibold text-gray-500 uppercase mb-3">
System Settings
</h3>

<a href="#" class="block text-sm hover:text-blue-700 transition mb-2">
School Years
</a>

<a href="#" class="block text-sm hover:text-blue-700 transition mb-2">
Organizations
</a>

<a href="#" class="block text-sm hover:text-blue-700 transition">
Audit Logs
</a>

</div>

</div>


<!-- Reports -->
<a href="#" class="text-sm font-medium text-gray-700 hover:text-blue-700 transition">
Reports
</a>

</div>

</div>

</div>