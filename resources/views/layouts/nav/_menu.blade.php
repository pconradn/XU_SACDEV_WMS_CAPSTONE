@foreach ($links as $link)

<a
    href="{{ $link['href'] }}"
    class="flex items-center gap-3 px-3 py-2 rounded-lg mb-1
           text-sm transition-all duration-150

           {{ str_contains($link['class'], 'bg-blue')
                ? 'bg-gradient-to-r from-blue-600 via-blue-500 to-blue-600 text-white shadow-md shadow-blue-900/30 border border-blue-400/20'
                : 'text-slate-300 hover:bg-slate-800/70 hover:shadow-sm hover:text-white' }}"
>

    {{-- ICON --}}

    @if(!empty($link['icon']))
        <span class="w-5 h-5">
            @include('layouts.nav.components._icons', ['name' => $link['icon']])
        </span>
    @endif

    {{-- LABEL --}}
    <span class="truncate">
        {{ $link['label'] }}
    </span>

    {{-- BADGE --}}
    @if(!empty($link['badge']))
        <span class="ml-auto text-[10px] px-2 py-0.5 rounded-full bg-blue-500 text-white">
            {{ $link['badge'] }}
        </span>
    @endif

</a>

@endforeach