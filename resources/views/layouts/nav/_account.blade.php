@php
    $wrapper = ($mode ?? 'desktop') === 'mobile'
        ? 'mt-3 pt-3 border-t border-slate-200'
        : 'mt-4 pt-4 border-t border-slate-200';
@endphp

<div class="{{ $wrapper }}">
    <div class="px-1 mb-2">
        <div class="text-xs font-semibold text-slate-900">{{ $user->name }}</div>
        <div class="text-xs text-slate-500 break-all">{{ $user->email }}</div>
    </div>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit"
                class="w-full rounded-xl bg-slate-900 px-3 py-2 text-sm font-semibold text-white hover:bg-slate-800">
            Log out
        </button>
    </form>
</div>
