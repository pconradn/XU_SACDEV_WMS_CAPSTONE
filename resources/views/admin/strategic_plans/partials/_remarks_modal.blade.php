<div x-show="openRemarks" x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center p-4">

    <div class="absolute inset-0 bg-slate-900/50" @click="openRemarks=false"></div>

    <div class="relative w-full max-w-2xl rounded-2xl bg-white border border-slate-200 shadow-xl">

        {{-- HEADER --}}
        <div class="px-5 py-4 border-b">
            <h3 class="text-lg font-semibold text-slate-900">
                Remarks
            </h3>
        </div>

        {{-- CONTENT --}}
        <div class="p-5 space-y-5">

            {{-- MODERATOR --}}
            @if($submission->moderator_remarks)
                <div>
                    <div class="text-sm font-semibold text-amber-700">Moderator</div>
                    <div class="mt-1 text-sm text-slate-800 whitespace-pre-line">
                        {!! $submission->moderator_remarks !!}
                    </div>
                </div>
            @endif

            {{-- SACDEV --}}
            @if($submission->sacdev_remarks)
                <div>
                    <div class="text-sm font-semibold text-blue-700">SACDEV</div>
                    <div class="mt-1 text-sm text-slate-800 whitespace-pre-line">
                        {!! $submission->sacdev_remarks !!}
                    </div>
                </div>
            @endif

        </div>

        {{-- FOOTER --}}
        <div class="px-5 py-4 border-t flex justify-between">

            <button @click="openEditRemarks = true"
                    class="text-sm text-blue-600 hover:underline">
                Edit Remarks
            </button>

            <button @click="openRemarks=false"
                    class="rounded-lg bg-slate-900 px-4 py-2 text-sm text-white">
                Close
            </button>

        </div>

    </div>
</div>