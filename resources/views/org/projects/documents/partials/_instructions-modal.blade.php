@if($isProjectHead && ($document->status ?? 'draft') === 'draft')

<div id="instructionModal"
class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">

<div class="bg-white rounded-lg shadow-lg w-full max-w-xl">

<div class="border-b px-4 py-3 font-semibold text-sm">
{{ $title ?? 'Form Instructions' }}
</div>

<div class="p-4 text-[12px] text-slate-700 space-y-3">

@if(isset($instructions))
{!! $instructions !!}
@endif

</div>

<div class="border-t px-4 py-3 flex justify-end">

<button
type="button"
onclick="closeInstructionModal()"
class="bg-blue-900 text-white px-4 py-2 text-[12px] hover:bg-blue-800">
I Understand
</button>

</div>

</div>

</div>


<script>

function closeInstructionModal() {

const modal = document.getElementById('instructionModal');

if (!modal) return;

modal.classList.add('hidden');
modal.classList.remove('flex');

}

</script>

@endif