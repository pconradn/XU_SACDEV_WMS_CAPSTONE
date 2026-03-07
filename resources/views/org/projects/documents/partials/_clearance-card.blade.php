@if($project->requires_clearance)

<div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">

<div class="flex items-start justify-between">

<div>

<div class="text-xs uppercase tracking-wide text-slate-500">
Off-Campus Clearance
</div>

<div class="mt-1 font-semibold text-slate-900">

Reference:
<span class="font-mono text-blue-700">
{{ $project->clearance_reference }}
</span>

</div>

</div>

</div>


<div class="mt-4 text-sm">

Status:

@switch($project->clearance_status)

@case('required')

<span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded text-xs">
Clearance Required
</span>

@break


@case('uploaded')

<span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs">
Uploaded — Awaiting Verification
</span>

@break


@case('verified')

<span class="px-2 py-1 bg-emerald-100 text-emerald-700 rounded text-xs">
Verified
</span>

@break


@case('rejected')

<span class="px-2 py-1 bg-rose-100 text-rose-700 rounded text-xs">
Returned for Revision
</span>

@break

@endswitch

</div>


@if($isProjectHead)

<div class="mt-4 flex flex-wrap gap-3">

<a href="{{ route('org.projects.clearance.print', $project) }}"
   target="_blank"
   class="inline-flex items-center rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-blue-700">
Generate Clearance Form
</a>


@if(in_array($project->clearance_status,['required','rejected']))

<button
class="inline-flex items-center rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-slate-800">

Upload Signed Clearance

</button>

@endif


@if($project->clearance_status === 'uploaded')

<div class="text-xs text-slate-500 italic">
Clearance uploaded. Waiting for SACDEV verification.
</div>

@endif


@if($project->clearance_status === 'verified')

<div class="text-xs text-emerald-600 font-medium">
Clearance verified by SACDEV.
</div>

@endif

</div>

@endif

</div>

@endif