@foreach ($links as $link)
    <a href="{{ $link['href'] }}"
       class="block w-full rounded-xl border px-3 py-2 text-sm transition {{ $link['class'] }}">
        {{ $link['label'] }}
    </a>
@endforeach
