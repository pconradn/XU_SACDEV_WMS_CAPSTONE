@php

$steps = [
    'pre' => 'Pre-Implementation',
    'ready' => 'Ready for Implementation',
    'in_progress' => 'Implementation Ongoing',
    'post_processing' => 'Post Implementation',
    'completed' => 'Project Completed'
];

$keys = array_keys($steps);
$currentIndex = array_search($stage, $keys);

@endphp


<div class="mb-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

<div class="text-sm font-semibold text-slate-900 mb-4">
Project Progress
</div>


<div class="flex items-center w-full">

@foreach($steps as $key => $label)

@php
$index = array_search($key, $keys);
$active = $index <= $currentIndex;
$current = $index === $currentIndex;
@endphp


<div class="flex items-center flex-1">

{{-- STEP --}}
<div class="relative group flex items-center justify-center">

<div class="flex items-center justify-center w-6 h-6 rounded-full text-[11px] font-semibold
{{ $active ? 'bg-emerald-500 text-white' : 'bg-slate-200 text-slate-500' }}
{{ $current ? 'ring-2 ring-emerald-200' : '' }}
">

{{ $loop->iteration }}

</div>


{{-- TOOLTIP LABEL --}}
<div class="absolute bottom-8 hidden group-hover:block whitespace-nowrap
bg-slate-900 text-white text-xs px-2 py-1 rounded shadow">

{{ $label }}

</div>

</div>


{{-- CONNECTOR --}}
@if(!$loop->last)

<div class="flex-1 h-[2px]
{{ $active ? 'bg-emerald-500' : 'bg-slate-200' }}">
</div>

@endif

</div>

@endforeach

</div>

</div>