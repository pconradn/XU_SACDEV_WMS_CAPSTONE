@if($isProjectHead)

<div id="agreementModal"
     class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">

<div class="bg-white w-full max-w-md rounded shadow-lg p-6">

<h2 class="text-[14px] font-semibold mb-3">
Solicitation Agreement
</h2>

<p class="text-[12px] text-slate-600 mb-4 leading-relaxed">

We understand that there are rules and regulations which govern
solicitation activities using the name of the University.

Failure to abide by them and the approved terms and conditions of
this form entails sanctions for the organization and disciplinary
measures for the students involved.

</p>

<div class="flex justify-end gap-3">

<button type="button"
        onclick="closeAgreementModal()"
        class="border border-slate-400 px-4 py-2 text-[12px] text-slate-700 hover:bg-slate-100">
Cancel
</button>

<button type="submit"
        form="solicitationForm"
        name="action"
        value="submit"
        class="bg-blue-900 px-4 py-2 text-[12px] text-white hover:bg-blue-800">
Agree and Submit
</button>

</div>

</div>

</div>

@endif