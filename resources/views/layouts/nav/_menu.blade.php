@foreach ($links as $link)

<a
href="{{ $link['href'] }}"
class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm transition
{{ $link['class'] ?? 'text-slate-700 hover:bg-blue-50 hover:text-blue-700' }}">

{{-- ICON --}}
@if(!empty($link['icon']))
<span class="w-5 h-5 text-slate-500">
{!! $link['icon'] !!}
</span>
@endif


{{-- LABEL --}}
<span class="truncate">
{{ $link['label'] }}
</span>


{{-- BADGE --}}
@if(!empty($link['badge']))
<span class="ml-auto inline-flex items-center rounded-full bg-red-600 px-2 py-0.5 text-[10px] font-semibold text-white">
{{ $link['badge'] }}
</span>
@endif

</a>

@endforeach