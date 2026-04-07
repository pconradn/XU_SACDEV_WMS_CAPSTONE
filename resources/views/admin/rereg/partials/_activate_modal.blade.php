<div 
    x-data="{ open: false }"
    x-on:open-activate-modal.window="open = true"
    x-show="open"
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm"
>

    <div @click.away="open = false"
         class="w-full max-w-md rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-xl p-5">

        {{-- HEADER --}}
        <div class="flex items-start gap-3">

            {{-- ICON --}}
            <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-emerald-50 text-emerald-600">
                <i data-lucide="check-circle" class="w-4 h-4"></i>
            </div>

            <div>
                <div class="text-sm font-semibold text-slate-900">
                    Confirm Registration
                </div>

                <div class="text-xs text-slate-500 mt-0.5">
                    Register organization for selected school year
                </div>
            </div>

        </div>


        {{-- BODY --}}
        <div class="mt-4 text-sm text-slate-700">
            Are you sure you want to register
            <span class="font-semibold text-slate-900">
                {{ $organization->name }}
            </span>
            for the selected school year?
        </div>


        {{-- ACTIONS --}}
        <div class="mt-5 flex justify-end gap-2">

            <button type="button"
                @click="open = false"
                class="px-4 py-2 rounded-lg border border-slate-200 text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 transition">
                Cancel
            </button>

            <form method="POST" action="{{ route('admin.rereg.activate', $organization) }}">
                @csrf
                <button type="submit"
                    class="px-4 py-2 rounded-lg text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-700 transition">
                    Confirm
                </button>
            </form>

        </div>

    </div>

</div>