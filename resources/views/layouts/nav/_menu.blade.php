@foreach ($links as $link)
    <a href="{{ $link['href'] }}"
       class="flex items-center w-full rounded-xl border px-3 py-2 text-sm transition {{ $link['class'] }}">
        <span>{{ $link['label'] }}</span>

        @if(!empty($link['badge']))
            <span class="ml-auto inline-flex items-center rounded-full bg-red-600 px-2 py-0.5 text-xs font-semibold text-white">
                {{ $link['badge'] }}
            </span>
        @endif
    </a>


@endforeach