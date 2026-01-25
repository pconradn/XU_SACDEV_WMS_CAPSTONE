@props(['title' => null, 'subtitle' => null])

@include('layouts.blocked', [
    'title' => $title,
    'subtitle' => $subtitle,
    'slot' => $slot
])
