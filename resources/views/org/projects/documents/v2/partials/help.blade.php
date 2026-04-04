<div
    x-show="helpOpen"
    x-cloak
    class="fixed inset-0 z-[998] flex items-center justify-center bg-black/60 backdrop-blur-sm px-3"
>

    <div @click.away="helpOpen = false"
         class="bg-gradient-to-b from-slate-50 to-white rounded-2xl shadow-xl max-w-lg w-full p-6 space-y-5">

        {{-- HEADER --}}
        <div class="flex items-start justify-between gap-3">

            <div class="flex items-start gap-2">
                <i data-lucide="book-open" class="w-5 h-5 text-purple-600 mt-0.5"></i>

                <div>
                    <h2 class="text-sm font-semibold text-slate-800">
                        Off-Campus Clearance Guide
                    </h2>
                    <p class="mt-1 text-[11px] text-slate-500">
                        Follow these steps to complete your clearance requirement
                    </p>
                </div>
            </div>

            <button
                type="button"
                @click="helpOpen = false"
                class="text-slate-400 hover:text-slate-600"
            >
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>

        </div>


        {{-- STEPS --}}
        <div class="space-y-3 text-[11px] text-slate-700">

            {{-- STEP --}}
            <div class="flex items-start gap-3 p-3 rounded-lg border border-purple-200 bg-purple-50">
                <i data-lucide="file-text" class="w-4 h-4 text-purple-600 mt-0.5"></i>
                <div>
                    <div class="font-semibold text-purple-800">Generate Clearance</div>
                    <div class="text-slate-600">
                        Generate the clearance form from this page.
                    </div>
                </div>
            </div>

            {{-- STEP --}}
            <div class="flex items-start gap-3 p-3 rounded-lg border border-slate-200 bg-white">
                <i data-lucide="printer" class="w-4 h-4 text-slate-600 mt-0.5"></i>
                <div>
                    <div class="font-semibold text-slate-800">Print & Sign</div>
                    <div class="text-slate-600">
                        Print the clearance and secure required signatures.
                    </div>
                </div>
            </div>

            {{-- STEP --}}
            <div class="flex items-start gap-3 p-3 rounded-lg border border-slate-200 bg-white">
                <i data-lucide="upload" class="w-4 h-4 text-slate-600 mt-0.5"></i>
                <div>
                    <div class="font-semibold text-slate-800">Upload File</div>
                    <div class="text-slate-600">
                        Upload the signed clearance file.
                    </div>
                </div>
            </div>

            {{-- STEP --}}
            <div class="flex items-start gap-3 p-3 rounded-lg border border-amber-200 bg-amber-50">
                <i data-lucide="refresh-cw" class="w-4 h-4 text-amber-600 mt-0.5"></i>
                <div>
                    <div class="font-semibold text-amber-800">If Returned</div>
                    <div class="text-slate-600">
                        Review remarks and upload corrected file.
                    </div>
                </div>
            </div>

        </div>


        {{-- FOOTER --}}
        <div class="flex justify-between items-center">

            <div class="text-[10px] text-slate-400">
                Need help? Follow the steps above carefully.
            </div>

            <button
                type="button"
                @click="helpOpen = false"
                class="px-3 py-1.5 rounded-md bg-slate-800 text-white text-[11px] hover:bg-slate-700 transition"
            >
                Close
            </button>

        </div>

    </div>
</div>