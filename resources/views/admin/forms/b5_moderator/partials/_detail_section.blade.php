@props([
    'title' => '',
])

<div class="mt-4 rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
    @if($title)
        <h3 class="text-base font-semibold text-slate-900">{{ $title }}</h3>
    @endif

    <div class="{{ $title ? 'mt-4' : '' }}">
        {{ $slot }}
    </div>
</div>
