{{-- ========================= --}}
{{-- REMARKS MODAL --}}
{{-- ========================= --}}
<div x-show="openRemarks" x-cloak x-transition.opacity.scale
     class="fixed inset-0 z-50 flex items-center justify-center p-4">

    {{-- BACKDROP --}}
    <div class="absolute inset-0 bg-slate-900/50" @click="openRemarks=false"></div>

    {{-- MODAL --}}
    <div class="relative w-full max-w-2xl rounded-2xl bg-white border border-slate-200 shadow-xl overflow-hidden">

        {{-- HEADER --}}
        <div class="px-5 py-4 border-b flex items-center justify-between">
            <h3 class="text-lg font-semibold text-slate-900">
                Review Remarks
            </h3>

            <button @click="openRemarks=false"
                    class="text-slate-400 hover:text-slate-600 text-lg">
                ✕
            </button>
        </div>

        {{-- BODY --}}
        <div class="p-5 space-y-5 max-h-[70vh] overflow-y-auto">

            {{-- MODERATOR --}}
            @if(!empty($submission->moderator_remarks))
                <div>
                    <div class="text-sm font-semibold text-amber-700 mb-1">
                        Moderator Remarks
                    </div>

                    <div class="prose prose-sm max-w-none text-slate-700">
                        {!! $submission->moderator_remarks !!}
                    </div>

                    @if($submission->moderator_reviewed_at)
                        <div class="text-xs text-slate-500 mt-2">
                            Reviewed on {{ $submission->moderator_reviewed_at->format('F j, Y g:i A') }}
                        </div>
                    @endif
                </div>
            @endif

            {{-- SACDEV --}}
            @if(!empty($submission->sacdev_remarks))
                <div>
                    <div class="text-sm font-semibold text-indigo-700 mb-1">
                        SACDEV Remarks
                    </div>

                    <div class="prose prose-sm max-w-none text-slate-700">
                        {!! $submission->sacdev_remarks !!}
                    </div>

                    @if($submission->sacdev_reviewed_at)
                        <div class="text-xs text-slate-500 mt-2">
                            Reviewed on {{ $submission->sacdev_reviewed_at->format('F j, Y g:i A') }}
                        </div>
                    @endif
                </div>
            @endif

        </div>

        {{-- FOOTER --}}
        <div class="px-5 py-3 border-t flex justify-end">
            <button @click="openRemarks=false"
                    class="px-4 py-2 text-sm rounded-lg border border-slate-300 bg-white hover:bg-slate-50">
                Close
            </button>
        </div>

    </div>
</div>