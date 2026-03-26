@if($isProjectHead)
<div id="instructionModal"
class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">

<div class="bg-white rounded-2xl shadow-xl w-full max-w-xl">

<div class="border-b px-5 py-3 font-semibold text-sm">
Solicitation Application Instructions
</div>

<div class="p-5 text-sm text-slate-700 space-y-3">

<ul class="list-disc ml-5 space-y-1">
<li>Submit with draft letter</li>
<li>Must contain SACDEV clause</li>
<li>Mass production after approval</li>
<li>Submit for control numbers</li>
<li>Submit report after activity</li>
</ul>

</div>

<div class="border-t px-5 py-3 flex justify-end">
<button onclick="closeInstructionModal()"
class="bg-blue-600 text-white px-4 py-2 text-xs rounded-lg">
I Understand
</button>
</div>

</div>
</div>
@endif