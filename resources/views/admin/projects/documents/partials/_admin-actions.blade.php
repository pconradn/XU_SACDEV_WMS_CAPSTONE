<div 
    x-data="{
        showCompleteModal: false,
        showRetractModal: false
    }"
    class="sticky bottom-0 z-20"
>

@php
    $isCoa = auth()->user()?->is_coa_officer ?? false;
    $isCompleted = $project->workflow_status === 'completed';

    $stateStyle = match(true) {
        $isCompleted => 'border-emerald-300 bg-gradient-to-r from-emerald-50 to-white',

        $actions['is_ready'] && !$isCoa
            => 'border-emerald-200 bg-gradient-to-r from-emerald-50/70 to-white',

        $actions['is_ready'] && $isCoa
            => 'border-blue-200 bg-gradient-to-r from-blue-50/70 to-white',

        default => 'border-amber-200 bg-gradient-to-r from-amber-50/70 to-white',
    };

    $retractUrl = route('admin.projects.retract-complete', $project);
@endphp


<div class="rounded-2xl border shadow-md {{ $stateStyle }}">

    <div class="px-5 py-4 flex flex-col md:flex-row md:items-center md:justify-between gap-4">

        {{-- LEFT --}}
        <div class="flex items-start gap-3">

            {{-- ICON --}}
            <div class="mt-0.5">
                @if($isCompleted)
                    <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600"></i>

                @elseif($actions['is_ready'] && !$isCoa)
                    <i data-lucide="circle-check" class="w-5 h-5 text-emerald-500"></i>

                @elseif($actions['is_ready'] && $isCoa)
                    <i data-lucide="shield-check" class="w-5 h-5 text-blue-500"></i>

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

                @elseif($actions['is_ready'] && !$isCoa)

                    <div class="text-sm font-semibold text-emerald-700">
                        Ready for Completion
                    </div>

                    <div class="text-[11px] text-slate-500">
                        All required documents have been approved
                    </div>

                @elseif($actions['is_ready'] && $isCoa)

                    <div class="text-sm font-semibold text-blue-700">
                        Ready for Finalization
                    </div>

                    <div class="text-[11px] text-slate-500">
                        All documents approved. Awaiting SACDEV completion.
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

            {{-- ✅ MARK COMPLETE --}}
            @if(!$isCompleted && $actions['can_mark_complete'] && $actions['mark_complete_url'])

                <button
                    type="button"
                    @click="showCompleteModal = true"
                    class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-semibold rounded-xl bg-emerald-600 text-white hover:bg-emerald-700 transition shadow-sm"
                >
                    <i data-lucide="check" class="w-3.5 h-3.5"></i>
                    Mark as Completed
                </button>

            {{-- 🔁 RETRACT --}}
            @elseif($isCompleted && !$isCoa)

                <button
                    type="button"
                    @click="showRetractModal = true"
                    class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-semibold rounded-xl bg-rose-600 text-white hover:bg-rose-700 transition shadow-sm"
                >
                    <i data-lucide="undo-2" class="w-3.5 h-3.5"></i>
                    Undo Completion
                </button>

            {{-- 🔒 DISABLED --}}
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


{{-- ========================= --}}
{{-- ✅ COMPLETE MODAL --}}
{{-- ========================= --}}
<div x-show="showCompleteModal"
     x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">

    <div class="w-full max-w-md rounded-2xl bg-white shadow-xl border border-slate-200 p-6 space-y-4">

        <div class="flex items-start gap-3">
            <div class="mt-1">
                <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600"></i>
            </div>

            <div>
                <div class="text-sm font-semibold text-slate-900">
                    Mark Project as Completed
                </div>
                <p class="text-xs text-slate-500 mt-1">
                    This will mark the project workflow as completed. 
                    Ensure all documents and requirements are finalized before proceeding.
                </p>
            </div>
        </div>

        <div class="flex justify-end gap-2 pt-2">

            <button @click="showCompleteModal = false"
                    class="px-3 py-1.5 text-xs rounded-lg border border-slate-300 text-slate-600 hover:bg-slate-50">
                Cancel
            </button>

            <form method="POST" action="{{ $actions['mark_complete_url'] }}">
                @csrf
                <button type="submit"
                        class="px-3 py-1.5 text-xs rounded-lg bg-emerald-600 text-white hover:bg-emerald-700">
                    Confirm
                </button>
            </form>

        </div>

    </div>
</div>


{{-- ========================= --}}
{{-- 🔁 RETRACT MODAL --}}
{{-- ========================= --}}
<div x-show="showRetractModal"
     x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">

    <div class="w-full max-w-md rounded-2xl bg-white shadow-xl border border-slate-200 p-6 space-y-4">

        <div class="flex items-start gap-3">
            <div class="mt-1">
                <i data-lucide="undo-2" class="w-5 h-5 text-rose-600"></i>
            </div>

            <div>
                <div class="text-sm font-semibold text-slate-900">
                    Undo Project Completion
                </div>
                <p class="text-xs text-slate-500 mt-1">
                    This will revert the project workflow back to post-implementation. 
                    You can continue editing or reviewing documents after this.
                </p>
            </div>
        </div>

        <div class="flex justify-end gap-2 pt-2">

            <button @click="showRetractModal = false"
                    class="px-3 py-1.5 text-xs rounded-lg border border-slate-300 text-slate-600 hover:bg-slate-50">
                Cancel
            </button>

            <form method="POST" action="{{ $retractUrl }}">
                @csrf
                <button type="submit"
                        class="px-3 py-1.5 text-xs rounded-lg bg-rose-600 text-white hover:bg-rose-700">
                    Confirm
                </button>
            </form>

        </div>

    </div>
</div>

</div>