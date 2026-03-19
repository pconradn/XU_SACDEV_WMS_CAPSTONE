<div class="fixed bottom-0 left-0 right-0 z-40">

    <div class="max-w-6xl mx-auto px-4 pb-4">

        <div class="flex items-center justify-between rounded-2xl border border-slate-200 bg-white/95 backdrop-blur shadow-xl px-4 py-3">

            {{-- LEFT INFO --}}
            <div class="text-sm text-slate-500">
                Review complete? Choose an action.
            </div>

         
            <div class="flex items-center gap-2">

                <button type="button"
                        @click="openRevert = true"
                        class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                    Revert
                </button>

                <button type="button"
                        @click="openReturn = true"
                        class="rounded-lg bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-700">
                    Return
                </button>

                <button type="button"
                        @click="openApprove = true"
                        class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
                    Approve
                </button>

            </div>

        </div>

    </div>

</div>