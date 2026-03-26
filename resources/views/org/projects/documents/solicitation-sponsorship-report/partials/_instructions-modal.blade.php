<div id="instructionsModal"
     class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">

    <div class="bg-white w-full max-w-2xl rounded-2xl shadow-xl">

        {{-- HEADER --}}
        <div class="px-6 py-4 border-b flex justify-between items-center">
            <div>
                <h3 class="text-sm font-semibold text-slate-800">
                    Solicitation Report Instructions
                </h3>
                <p class="text-xs text-slate-500">
                    Please review before submitting
                </p>
            </div>

            <button onclick="closeModal('instructionsModal')"
                class="text-slate-500 text-xs">
                Close
            </button>
        </div>

        {{-- BODY --}}
        <div class="p-6 text-sm text-slate-700 space-y-4 max-h-[70vh] overflow-y-auto">

            <p>
                Before submitting this report, ensure all solicitation documents are properly accounted for.
            </p>

            <ul class="list-disc ml-5 space-y-2">

                <li>
                    If a recipient did not provide any contribution, retrieve the solicitation letter and return it to SACDEV.
                </li>

                <li>
                    If a letter was lost, secure a written waiver confirming no contribution was given.
                </li>

                <li>
                    Attach all waivers and acknowledgement receipts to this report.
                </li>

                <li>
                    Ensure control numbers match the physical documents submitted.
                </li>

            </ul>

            {{-- OPTIONAL TIP BOX --}}
            <div class="rounded-lg bg-blue-50 border border-blue-200 p-3 text-xs text-blue-800">
                Tip: Double-check entries before submission to avoid delays in approval.
            </div>

        </div>

        {{-- FOOTER --}}
        <div class="px-6 py-4 border-t flex justify-end">
            <button onclick="closeModal('instructionsModal')"
                class="px-4 py-2 text-xs bg-blue-600 text-white rounded-lg">
                Got it
            </button>
        </div>

    </div>
</div>