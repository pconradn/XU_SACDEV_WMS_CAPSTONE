<div id="returnModal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center">

<div class="bg-white rounded-lg p-6 w-96">

<h2 class="text-sm font-semibold mb-4">
Return Packet
</h2>

<form id="returnForm" method="POST">

@csrf

<textarea
name="remarks"
rows="3"
class="w-full border border-slate-300 rounded px-2 py-1 text-xs"
placeholder="Enter remarks"></textarea>

<div class="mt-4 flex justify-end gap-2">

<button
type="button"
onclick="closeReturnModal()"
class="text-xs px-3 py-1 border rounded">

Cancel

</button>

<button
class="text-xs px-3 py-1 bg-red-600 text-white rounded">

Return Packet

</button>

</div>

</form>

</div>

</div>


<script>

function openReturnModal(packetId){

const modal = document.getElementById('returnModal')

modal.classList.remove('hidden')

document.getElementById('returnForm').action =
`/admin/packets/${packetId}/return`

}

function closeReturnModal(){

document.getElementById('returnModal').classList.add('hidden')

}

function openApproveModal(packetId){

if(confirm('Approve this packet?')){

fetch(`/admin/packets/${packetId}/approve`,{
method:'POST',
headers:{
'X-CSRF-TOKEN':'{{ csrf_token() }}'
}
}).then(()=>location.reload())

}

}

</script>