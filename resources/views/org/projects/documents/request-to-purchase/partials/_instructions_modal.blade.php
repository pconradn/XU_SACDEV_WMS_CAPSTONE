@if($isProjectHead)
<div id="instructionModal"
class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">

<div class="bg-white rounded-2xl shadow-xl w-full max-w-xl overflow-hidden">

    {{-- HEADER --}}
    <div class="border-b px-5 py-3 font-semibold text-sm">
        Request to Purchase Instructions
    </div>

    {{-- BODY --}}
    <div class="p-5 text-sm text-slate-700 space-y-3">

        <p>
            Before submitting this request, please review the following guidelines:
        </p>

        <ul class="list-disc ml-5 space-y-2">
            <li>Use this form when requesting purchase of materials or equipment.</li>
            <li>List all items with quantity, estimated price, and supplier.</li>
            <li>Prepare price quotation for submission.</li>
            <li>Form undergoes approval by President, Moderator, and SACDEV.</li>
        </ul>

    </div>

    {{-- FOOTER --}}
    <div class="border-t px-5 py-3 flex justify-end">
        <button onclick="closeModal('instructionModal')"
            class="bg-blue-600 text-white px-4 py-2 text-xs rounded-lg hover:bg-blue-700">
            I Understand
        </button>
    </div>

</div>

</div>
@endif