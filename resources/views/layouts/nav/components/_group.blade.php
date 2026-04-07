@php
    $isActiveGroup = false;

    foreach ($group['links'] as $link) {
        if (str_contains($link['class'], 'bg-blue')) {
            $isActiveGroup = true;
            break;
        }
    }
@endphp

<div
    x-data="{ open: true }"
    class="mb-3"
>

    {{-- GROUP HEADER --}}
    <button
        @click="open = !open"
        class="w-full flex items-center justify-between px-3 py-2
               rounded-lg
               text-[11px] uppercase tracking-wider font-semibold
               text-slate-400 hover:text-white
               hover:bg-slate-800/60
               transition"
    >

        <div class="flex items-center gap-2">

            {{-- ICON --}}
            <span class="w-4 h-4">
                @include('layouts.nav.components._icons', ['name' => $group['icon'] ?? 'menu'])
            </span>

            <span>{{ $group['title'] }}</span>
        </div>

        {{-- CHEVRON --}}
        <svg
            class="w-4 h-4 transform transition duration-200 text-slate-500"
            :class="{ 'rotate-180': open }"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
        >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    {{-- LINKS --}}
    <div
        x-show="open"
        x-transition
        class="mt-1 space-y-1 pl-6 relative"
    >

        {{-- vertical guide line --}}
        <div class="absolute left-2 top-0 bottom-0 w-px bg-slate-700/50"></div>

        @foreach ($group['links'] as $link)

            @php
                $isActive = str_contains($link['class'], 'bg-blue');
            @endphp

            <a
                href="{{ $link['href'] }}"
                class="relative flex items-center gap-3 px-3 py-2 rounded-lg
                       text-sm transition-all duration-150

                       {{ $isActive
                            ? 'bg-blue-600 text-white shadow-md shadow-blue-900/20'
                            : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}"
            >

                {{-- active indicator --}}
                @if($isActive)
                    <span class="absolute left-[-10px] w-1.5 h-6 rounded bg-blue-500"></span>
                @endif

                {{-- bullet --}}
                <span class="w-2 h-2 rounded-full {{ $isActive ? 'bg-white' : 'bg-slate-500' }}"></span>

                {{-- label --}}
                <span class="truncate">
                    {{ $link['label'] }}
                </span>

                {{-- badge --}}
                @if(!empty($link['badge']))
                    <span class="ml-auto text-[10px] px-2 py-0.5 rounded-full bg-blue-500 text-white">
                        {{ $link['badge'] }}
                    </span>
                @endif

            </a>

        @endforeach
    </div>

</div>