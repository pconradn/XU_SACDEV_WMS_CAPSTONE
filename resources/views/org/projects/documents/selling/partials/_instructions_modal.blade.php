@if($isProjectHead)

<div id="instructionModal"
class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">

<div class="bg-white rounded-2xl shadow-xl w-full max-w-xl">

<div class="border-b px-5 py-3 font-semibold text-sm">
Application for Selling Instructions
</div>

<div class="p-5 text-[13px] text-slate-700 space-y-3">

<p>
Before submitting this application, please review the following:
</p>

<ul class="list-disc ml-5 space-y-1">
<li>This application must be approved by SACDEV before selling.</li>
<li>All goods must be declared in the table.</li>
<li>Projected sales must be reasonable.</li>
<li>Post-activity liquidation must be submitted.</li>
</ul>

</div>

<div class="border-t px-5 py-3 flex justify-end">
<button
onclick="closeInstructionModal()"
class="bg-blue-600 text-white px-4 py-2 text-xs rounded-lg hover:bg-blue-700">
I Understand
</button>
</div>

</div>
</div>

@endif