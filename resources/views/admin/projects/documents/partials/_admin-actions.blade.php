<div class="border border-slate-200 bg-white sticky bottom-0 shadow-md rounded-2xl">

    <div class="px-6 py-4 flex flex-col md:flex-row md:items-center md:justify-between gap-4">

        {{-- LEFT: STATUS INFO --}}
        <div>

            @if($actions['can_mark_complete'])

                <div class="text-sm font-semibold text-emerald-700">
                    Project is ready for completion
                </div>

                <div class="text-xs text-slate-500 mt-1">
                    All required documents have been approved.
                </div>

            @else

                <div class="text-sm font-semibold text-amber-700">
                    Project not yet complete
                </div>

                <div class="text-xs text-slate-500 mt-1">
                    Some documents are still pending approval.
                </div>

            @endif

        </div>


        {{-- RIGHT: ACTIONS --}}
        <div class="flex items-center gap-2">

            {{-- VIEW PACKETS --}}
            <a href="{{ route('admin.projects.packets.index', $project) }}"
               class="px-4 py-2 text-xs font-semibold rounded-xl border border-slate-200 bg-white hover:bg-slate-50">
                View Packets
            </a>

            {{-- MARK COMPLETE --}}
            @if($actions['can_mark_complete'] && $actions['mark_complete_url'])
                <form method="POST" action="{{ $actions['mark_complete_url'] }}">
                    @csrf
                    <button type="submit"
                            class="px-4 py-2 text-xs font-semibold rounded-xl bg-emerald-600 text-white hover:bg-emerald-700">
                        Mark as Completed
                    </button>
                </form>
            @else
                <button disabled
                        class="px-4 py-2 text-xs font-semibold rounded-xl bg-slate-200 text-slate-500 cursor-not-allowed">
                    Mark as Completed
                </button>
            @endif

        </div>

    </div>

</div>