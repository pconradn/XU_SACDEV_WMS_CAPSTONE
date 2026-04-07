<div
    x-show="helpOpen"
    x-cloak
    class="fixed inset-0 z-[998] flex items-center justify-center bg-black/60 backdrop-blur-sm px-3"
>

    <div @click.away="helpOpen = false"
         class="bg-gradient-to-b from-slate-50 to-white rounded-2xl shadow-xl max-w-lg w-full max-h-[85vh] flex flex-col overflow-hidden">

        {{-- HEADER --}}
        <div class="px-5 py-4 border-b border-slate-200 flex items-start justify-between gap-3">

            <div class="flex items-start gap-2">
                <i data-lucide="book-open" class="w-5 h-5 text-amber-600 mt-0.5"></i>

                <div>
                    <h2 class="text-sm font-semibold text-slate-800">
                        Submission Guides
                    </h2>
                    <p class="mt-1 text-[11px] text-slate-500">
                        Follow these guides to complete your requirements correctly
                    </p>
                </div>
            </div>

            <button
                type="button"
                @click="helpOpen = false"
                class="text-slate-400 hover:text-slate-600 transition"
            >
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>

        </div>


        {{-- SCROLLABLE CONTENT --}}
        <div class="px-5 py-4 space-y-6 overflow-y-auto text-[11px] text-slate-700">

            {{-- ================= ORG PACKET GUIDE ================= --}}
            <div>

                <div class="flex items-center gap-2 mb-2">
                    <i data-lucide="inbox" class="w-4 h-4 text-amber-600"></i>
                    <div class="font-semibold text-slate-800">
                        Org Packet Submission Guide
                    </div>
                </div>

                <div class="space-y-3">

                    <div class="flex items-start gap-3 p-3 rounded-lg border border-amber-200 bg-amber-50">
                        <i data-lucide="check-square" class="w-4 h-4 text-amber-600 mt-0.5"></i>
                        <div>
                            <div class="font-semibold text-amber-800">Select Included Documents</div>
                            <div class="text-slate-600">
                                Mark all documents included in your physical packet (receipts, vouchers, letters, etc.).
                            </div>
                        </div>
                    </div>

                    <div class="flex items-start gap-3 p-3 rounded-lg border border-slate-200 bg-white">
                        <i data-lucide="plus-circle" class="w-4 h-4 text-slate-600 mt-0.5"></i>
                        <div>
                            <div class="font-semibold text-slate-800">Add Supporting Details</div>
                            <div class="text-slate-600">
                                Enter receipt numbers, disbursement vouchers, and other references for tracking.
                            </div>
                        </div>
                    </div>

                    <div class="flex items-start gap-3 p-3 rounded-lg border border-slate-200 bg-white">
                        <i data-lucide="save" class="w-4 h-4 text-slate-600 mt-0.5"></i>
                        <div>
                            <div class="font-semibold text-slate-800">Save Your Entries</div>
                            <div class="text-slate-600">
                                Make sure to save after adding items to ensure all entries are recorded.
                            </div>
                        </div>
                    </div>

                    <div class="flex items-start gap-3 p-3 rounded-lg border border-emerald-200 bg-emerald-50">
                        <i data-lucide="inbox" class="w-4 h-4 text-emerald-600 mt-0.5"></i>
                        <div>
                            <div class="font-semibold text-emerald-800">Submit Physical Packet</div>
                            <div class="text-slate-600">
                                Submit the compiled documents to SACDEV. Once received, editing will be locked.
                            </div>
                        </div>
                    </div>

                </div>

            </div>


            {{-- ================= OFF-CAMPUS GUIDE ================= --}}
            <div>

                <div class="flex items-center gap-2 mb-2">
                    <i data-lucide="map" class="w-4 h-4 text-purple-600"></i>
                    <div class="font-semibold text-slate-800">
                        Off-Campus Clearance Guide
                    </div>
                </div>

                <div class="space-y-3">

                    <div class="flex items-start gap-3 p-3 rounded-lg border border-purple-200 bg-purple-50">
                        <i data-lucide="file-text" class="w-4 h-4 text-purple-600 mt-0.5"></i>
                        <div>
                            <div class="font-semibold text-purple-800">Generate Clearance</div>
                            <div class="text-slate-600">
                                Generate the clearance form from the system.
                            </div>
                        </div>
                    </div>

                    <div class="flex items-start gap-3 p-3 rounded-lg border border-slate-200 bg-white">
                        <i data-lucide="printer" class="w-4 h-4 text-slate-600 mt-0.5"></i>
                        <div>
                            <div class="font-semibold text-slate-800">Print & Sign</div>
                            <div class="text-slate-600">
                                Print and secure required signatures.
                            </div>
                        </div>
                    </div>

                    <div class="flex items-start gap-3 p-3 rounded-lg border border-slate-200 bg-white">
                        <i data-lucide="upload" class="w-4 h-4 text-slate-600 mt-0.5"></i>
                        <div>
                            <div class="font-semibold text-slate-800">Upload File</div>
                            <div class="text-slate-600">
                                Upload the signed clearance document.
                            </div>
                        </div>
                    </div>

                    <div class="flex items-start gap-3 p-3 rounded-lg border border-amber-200 bg-amber-50">
                        <i data-lucide="refresh-cw" class="w-4 h-4 text-amber-600 mt-0.5"></i>
                        <div>
                            <div class="font-semibold text-amber-800">If Returned</div>
                            <div class="text-slate-600">
                                Review remarks and resubmit the corrected file.
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>


        {{-- FOOTER --}}
        <div class="px-5 py-3 border-t border-slate-200 flex justify-between items-center">

            <div class="text-[10px] text-slate-400">
                Follow the steps carefully to avoid delays.
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