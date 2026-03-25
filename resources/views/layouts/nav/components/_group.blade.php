@if(!empty($group['single']))
    @include('layouts.nav._menu', ['links' => $group['links']])
@else
@php

$open = false;

foreach ($group['links'] as $l) {
    if (str_contains($l['class'], 'bg-blue-50')) {
        $open = true;
        break;
    }
}
@endphp


<div x-data="{ open: {{ $open ? 'true' : 'false' }} }" class="space-y-1">

{{-- GROUP BUTTON --}}
<button
@click="open = !open"
class="w-full flex items-center justify-between px-4 py-2 rounded-lg text-slate-700 hover:bg-blue-50 hover:text-blue-700 transition">

<div class="flex items-center gap-3">

@include('layouts.nav.components._icons', ['name' => $group['icon'] ?? 'menu'])

<span class="text-sm font-medium">
{{ $group['title'] }}
</span>

</div>

<svg
class="w-4 h-4 transition-transform"
:class="open ? 'rotate-180' : ''"
fill="none"
stroke="currentColor"
stroke-width="2"
viewBox="0 0 24 24">

<path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>

</svg>

</button>


{{-- DROPDOWN LINKS --}}
<div
x-show="open"
x-transition
class="ml-8 mt-1 space-y-1">

@include('layouts.nav._menu', ['links' => $group['links']])

</div>

</div>

@endif