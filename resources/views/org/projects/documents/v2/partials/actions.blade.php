<div class="bg-white border rounded-2xl p-6 shadow-sm space-y-4">

    <h2 class="text-sm font-semibold text-slate-700">
        Action Center
    </h2>

    <div class="space-y-2">

        @forelse($actions as $action)

            <button
                class="w-full text-left px-4 py-3 rounded-lg text-sm font-medium transition

                @if($action['type'] === 'primary')
                    bg-indigo-600 text-white hover:bg-indigo-700

                @elseif($action['type'] === 'warning')
                    bg-yellow-50 text-yellow-800 hover:bg-yellow-100

                @elseif($action['type'] === 'danger')
                    bg-red-50 text-red-700 hover:bg-red-100

                @elseif($action['type'] === 'info')
                    bg-slate-100 text-slate-600 cursor-default

                @else
                    bg-slate-100 text-slate-600
                @endif
                ">

                {{ $action['label'] }}

            </button>

        @empty

            <div class="text-sm text-slate-500">
                No available actions.
            </div>

        @endforelse

    </div>

</div>