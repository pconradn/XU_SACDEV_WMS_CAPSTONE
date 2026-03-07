@if($isProjectHead)

<div id="instructionsModal"
     class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">

<div class="bg-white w-full max-w-lg rounded shadow-lg p-6">

<h2 class="text-[14px] font-semibold mb-3">
Solicitation Application Instructions
</h2>

<div class="text-[12px] text-slate-600 space-y-3">

<p>
Please print and submit this application form together with a draft of the
solicitation letter.
</p>

<p>
Refer to the Student Organization Manual for the correct format of the
solicitation letter.
</p>

<p>
The letter must include the clause:
</p>

<div class="border border-slate-300 bg-slate-50 px-3 py-2 text-[11px] italic">
"This letter is considered invalid unless audited by SACDEV-OSA,
Xavier University – Ateneo de Cagayan."
</div>

<p>
Mass production of solicitation letters may only be done after approval
of this application.
</p>

<p>
Approved letters must be submitted to SACDEV-OSA for assignment of
control numbers before distribution.
</p>

<p>
A solicitation report must later be submitted together with the
liquidation report of the activity.
</p>

</div>

<div class="flex justify-end mt-5">

<button onclick="closeInstructionsModal()"
        class="bg-blue-900 px-4 py-2 text-white text-[12px] hover:bg-blue-800">
I Understand
</button>

</div>

</div>

</div>

@endif