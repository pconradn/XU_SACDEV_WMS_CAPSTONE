        <div
            x-show="helpOpen"
            x-cloak
            class="fixed inset-0 z-[998] flex items-center justify-center bg-black/50 px-3"
        >
            <div @click.away="helpOpen = false" class="bg-white rounded-2xl shadow-xl max-w-lg w-full p-5">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <h2 class="text-sm font-semibold text-slate-800">
                            Off-Campus Clearance Guide
                        </h2>
                        <p class="mt-1 text-xs text-slate-500">
                            Follow these steps to complete your clearance requirement.
                        </p>
                    </div>

                    <button
                        type="button"
                        @click="helpOpen = false"
                        class="text-slate-400 hover:text-slate-600 text-sm"
                    >
                        ✕
                    </button>
                </div>

                <div class="mt-4 space-y-3 text-xs text-slate-700">
                    <div class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2">
                        1. Generate the clearance form from this page.
                    </div>

                    <div class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2">
                        2. Print the clearance and secure the required physical signatures.
                    </div>

                    <div class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2">
                        3. Upload the signed clearance file in the clearance section.
                    </div>

                    <div class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2">
                        4. If SACDEV returns the clearance, check the remarks and upload the corrected file again.
                    </div>
                </div>

                <div class="mt-4 flex justify-end">
                    <button
                        type="button"
                        @click="helpOpen = false"
                        class="px-3 py-1.5 rounded-md bg-slate-800 text-white text-xs"
                    >
                        Close
                    </button>
                </div>
            </div>
        </div>