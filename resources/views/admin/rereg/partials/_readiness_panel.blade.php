<div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

<div class="flex items-center justify-between">

<div>

<div class="font-semibold text-slate-900">

Registration Readiness

</div>

<div class="text-sm text-slate-600">

All forms must be approved.

</div>

</div>


@if($allApproved)

<span class="bg-emerald-100 text-emerald-800 px-3 py-1 rounded-full text-sm font-semibold">

Complete

</span>

@else

<span class="bg-slate-100 text-slate-700 px-3 py-1 rounded-full text-sm font-semibold">

In Progress

</span>

@endif

</div>


@if($allApproved && !$alreadyActivated)

<button onclick="document.getElementById('activateModal').classList.remove('hidden')"
class="mt-4 w-full rounded-lg bg-emerald-600 py-2 text-white font-semibold hover:bg-emerald-700">

Register Organization

</button>

@endif


@if($alreadyActivated)

<div class="mt-4 text-sm text-slate-600">

Organization already registered.

</div>

@endif


</div>