<div x-data="{ openGuide: false }" class="border border-slate-300 bg-slate-50 px-4 py-3 mb-6 text-sm">

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">

        <div>
            <div class="font-semibold text-slate-800">
                DISBURSEMENT VOUCHER GENERATOR
            </div>

            <div class="text-[12px] text-slate-600">
                Generate a printable DV based on selected budget items.
            </div>
        </div>

        <button
            @click="openGuide = true"
            type="button"
            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg border border-slate-300 bg-white text-slate-700 hover:bg-slate-100 transition">
            <i data-lucide="info" class="w-3.5 h-3.5"></i>
            Guide
        </button>

    </div>

    <div
        x-show="openGuide"
        x-transition.opacity
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">

        <div
            @click.outside="openGuide = false"
            x-transition
            class="w-full max-w-lg bg-white rounded-2xl shadow-lg border border-slate-200 p-5 space-y-4">

            <div class="flex items-start justify-between gap-3">

                <div>
                    <h2 class="text-sm font-semibold text-slate-900">
                        Disbursement Voucher Guide
                    </h2>
                    <p class="text-[11px] text-slate-500">
                        Important instructions before generating a DV
                    </p>
                </div>

                <button
                    @click="openGuide = false"
                    class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>

            </div>

            <div class="text-xs text-slate-700 space-y-2 leading-relaxed">

                <p>
                    Generate a Disbursement Voucher (DV) using the selected data from your approved budget proposal.
                </p>

                <p>
                    The system will <span class="font-semibold text-rose-600">not store</span> any information related to the generated DV. This is for printing purposes only.
                </p>

                <p>
                    All disbursement vouchers must go through <span class="font-semibold">physical review and approval</span> following SACDEV procedures.
                </p>

                <p>
                    After generating your DV, proceed to your project hub and create a packet under the <span class="font-semibold">Actions Panel</span>, then submit the required documents to the SACDEV office.
                </p>

                <p>
                    You may also create your own DV manually using the official template provided by the SACDEV office if needed.
                </p>

            </div>

            <div class="flex justify-end pt-2">
                <button
                    @click="openGuide = false"
                    class="px-3 py-1.5 text-xs font-semibold rounded-lg bg-slate-900 text-white hover:bg-slate-700 transition">
                    Close
                </button>
            </div>

        </div>

    </div>

</div>