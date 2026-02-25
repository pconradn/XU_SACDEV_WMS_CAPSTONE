<div id="activateModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40">

<div class="bg-white rounded-2xl p-6 shadow-xl w-full max-w-lg">

<div class="text-lg font-semibold mb-2">

Confirm Registration

</div>

<div class="text-sm text-slate-600 mb-4">

Register {{ $organization->name }} for selected school year?

</div>


<div class="flex justify-end gap-2">

<button onclick="document.getElementById('activateModal').classList.add('hidden')"
class="border px-4 py-2 rounded-lg">

Cancel

</button>


<form method="POST" action="{{ route('admin.rereg.activate', $organization) }}">

@csrf

<button class="bg-emerald-600 text-white px-4 py-2 rounded-lg font-semibold">

Confirm

</button>

</form>

</div>

</div>

</div>