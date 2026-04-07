<div id="returnModal" class="hidden fixed inset-0 bg-black/40 z-50 flex items-center justify-center">

    <div class="w-full max-w-md rounded-2xl border border-amber-200 bg-gradient-to-b from-amber-50 to-white shadow-lg p-5 space-y-4">

        {{-- HEADER --}}
        <div class="flex items-center justify-between">
            <h2 class="text-sm font-semibold text-slate-900">
                Return Packet
            </h2>
            <button onclick="closeReturnModal()" class="text-slate-400 hover:text-slate-600 text-sm">
                ✕
            </button>
        </div>

        {{-- PACKET INFO --}}
        <div id="returnPacketInfo" class="text-xs space-y-2">

            <div class="flex justify-between">
                <span class="text-slate-500">Packet Code</span>
                <span id="modalPacketCode" class="font-semibold text-slate-800"></span>
            </div>

            <div class="flex justify-between">
                <span class="text-slate-500">Status</span>
                <span id="modalPacketStatus" class="font-semibold text-slate-700"></span>
            </div>

            {{-- CONTENT SUMMARY --}}
            <div class="border-t border-amber-200 pt-2">
                <div class="text-[11px] font-semibold text-amber-700 mb-1">
                    Contents
                </div>

                <div class="grid grid-cols-2 gap-1 text-[11px] text-slate-600">
                    <div>Receipts: <span id="modalReceipts"></span></div>
                    <div>DVs: <span id="modalDvs"></span></div>
                    <div>Letters: <span id="modalLetters"></span></div>
                    <div>Certificates: <span id="modalCertificates"></span></div>
                </div>
            </div>

        </div>

        {{-- FORM --}}
        <form id="returnForm" method="POST" class="space-y-3">
            @csrf

            <div>
                <label class="block text-[11px] font-medium text-slate-600 mb-1">
                    Return Remarks
                </label>
                <textarea
                    name="remarks"
                    rows="3"
                    class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500"
                    placeholder="Explain why this packet is being returned..."
                ></textarea>
            </div>

            <div class="flex justify-end gap-2 pt-2">

                <button
                    type="button"
                    onclick="closeReturnModal()"
                    class="text-xs px-3 py-1.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition">
                    Cancel
                </button>

                <button
                    class="text-xs px-3 py-1.5 rounded-xl 
                           bg-gradient-to-r from-rose-600 to-rose-500 text-white 
                           hover:from-rose-700 hover:to-rose-600 transition shadow-sm">
                    Return Packet
                </button>

            </div>
        </form>

    </div>

</div>
