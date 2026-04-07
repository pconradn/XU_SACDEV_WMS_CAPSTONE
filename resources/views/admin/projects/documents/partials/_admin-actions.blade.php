<div class="sticky bottom-0 z-20">

    @php
        $isCompleted = $project->workflow_status === 'completed';

        $stateStyle = match(true) {
            $isCompleted => 'border-emerald-300 bg-gradient-to-r from-emerald-50 to-white',
            $actions['can_mark_complete'] => 'border-emerald-200 bg-gradient-to-r from-emerald-50/70 to-white',
            default => 'border-amber-200 bg-gradient-to-r from-amber-50/70 to-white',
        };
    @endphp


    <div class="rounded-2xl border shadow-md {{ $stateStyle }}">

        <div class="px-5 py-4 flex flex-col md:flex-row md:items-center md:justify-between gap-4">

            {{-- LEFT --}}
            <div class="flex items-start gap-3">

                {{-- ICON --}}
                <div class="mt-0.5">
                    @if($isCompleted)
                        <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600"></i>
                    @elseif($actions['can_mark_complete'])
                        <i data-lucide="circle-check" class="w-5 h-5 text-emerald-500"></i>
                    @else
                        <i data-lucide="alert-circle" class="w-5 h-5 text-amber-500"></i>
                    @endif
                </div>

                {{-- TEXT --}}
                <div class="space-y-0.5">

                    @if($isCompleted)

                        <div class="text-sm font-semibold text-emerald-700">
                            Project Completed
                        </div>

                        <div class="text-[11px] text-slate-500">
                            All requirements accomplished
                            @if($project->completed_at)
                                • {{ \Carbon\Carbon::parse($project->completed_at)->format('M d, Y h:i A') }}
                            @endif
                        </div>

                    @elseif($actions['can_mark_complete'])

                        <div class="text-sm font-semibold text-emerald-700">
                            Ready for Completion
                        </div>

                        <div class="text-[11px] text-slate-500">
                            All required documents have been approved
                        </div>

                    @else

                        <div class="text-sm font-semibold text-amber-700">
                            Not Yet Complete
                        </div>

                        <div class="text-[11px] text-slate-500">
                            Some required documents are still pending
                        </div>

                    @endif

                </div>

            </div>


            {{-- RIGHT --}}
            <div class="flex items-center gap-2">

                @if($actions['can_mark_complete'] && $actions['mark_complete_url'])

                    <form method="POST" action="{{ $actions['mark_complete_url'] }}">
                        @csrf
                        <button type="submit"
                                class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-semibold rounded-xl bg-emerald-600 text-white hover:bg-emerald-700 transition shadow-sm">

                            <i data-lucide="check" class="w-3.5 h-3.5"></i>
                            Mark as Completed

                        </button>
                    </form>

                @else

                    <button disabled
                            class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-semibold rounded-xl bg-slate-200 text-slate-500 cursor-not-allowed">

                        <i data-lucide="lock" class="w-3.5 h-3.5"></i>
                        Mark as Completed

                    </button>

                @endif

            </div>

        </div>

    </div>

</div>